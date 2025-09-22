<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MenuTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManageMenuController extends Controller
{
    // List menus (for web)
    public function menuList()
    {
        $menu = MenuTable::all();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();

        // Prepare image URLs using a secure route
        foreach ($menu as $item) {
            $item->image_url = $item->image
                ? route('menu.image', ['filename' => basename($item->image)])
                : null;
        }

        return view('manager/menu_list', compact('menu', 'uniqueMenuTypes'));
    }

    // Show add menu form
    public function addMenu()
    {
        return view('manager/add_menu');
    }

    // Save new menu
    public function submitMenu(Request $request)
    {
        $validatedData = $request->validate([
            'menuname' => 'required|string',
            'itemtype' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);

        DB::beginTransaction();
        try {
            $menu = new MenuTable();
            $menu->menuname = $validatedData['menuname'];
            $menu->itemtype = $validatedData['itemtype'];
            $menu->price = $validatedData['price'];
            $menu->status = $validatedData['status'];

            // Save image securely
            if ($request->hasFile('image')) {
                $menu->image = $request->file('image')->store('menu_images', 'public');
                Log::info('Menu image uploaded', ['image' => $menu->image]);
            }

            $menu->save();
            DB::commit();

            return redirect('manager/menu_list')->with('success', 'The menu has been successfully added');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Failed to add menu', ['error' => $ex->getMessage()]);
            return redirect('manager/add_menu')->withInput()->with('error', 'Failed! The menu failed to be added');
        }
    }

    // Edit menu
    public function editMenu(Request $request, $menuID)
    {
        $menu = MenuTable::findOrFail($menuID);

        if ($request->isMethod('get')) {
            return view('manager/edit_menu', compact('menu'));
        }

        $validatedData = $request->validate([
            'menuname' => 'required|string',
            'itemtype' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);

        // Check for changes
        $hasChanges = $menu->menuname !== $validatedData['menuname'] ||
                      $menu->itemtype !== $validatedData['itemtype'] ||
                      $menu->status !== $validatedData['status'] ||
                      $menu->price !== $validatedData['price'] ||
                      $request->hasFile('image');

        if (!$hasChanges) {
            return redirect()->route('manager.edit_menu', ['menuID' => $menuID])
                             ->with('error', 'No changes detected.');
        }

        DB::beginTransaction();
        try {
            // Handle image replacement
            if ($request->hasFile('image')) {
                if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                    Storage::disk('public')->delete($menu->image);
                    Log::info('Old menu image deleted', ['menuID' => $menuID, 'image' => $menu->image]);
                }

                $menu->image = $request->file('image')->store('menu_images', 'public');
                Log::info('New menu image stored', ['menuID' => $menuID, 'image' => $menu->image]);
            }

            // Update other fields
            $menu->update([
                'menuname' => $validatedData['menuname'],
                'itemtype' => $validatedData['itemtype'],
                'price' => $validatedData['price'],
                'status' => $validatedData['status']
            ]);

            DB::commit();
            return redirect('manager/menu_list')->with('success', 'The menu was successfully updated!');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Failed to update menu', ['menuID' => $menuID, 'error' => $ex->getMessage()]);
            return redirect()->route('manager.edit_menu', ['menuID' => $menuID])
                             ->withInput()
                             ->with('error', 'Failed! The menu failed to be updated');
        }
    }

    // Activate menu
    public function activateMenu($menuID)
    {
        $menu = MenuTable::find($menuID);
        if ($menu) {
            $menu->status = 'Available';
            $menu->save();
            return redirect('manager/menu_list')->with('success', 'The menu has been activated');
        }
        return redirect('manager/menu_list')->with('error', 'Failed! The menu was not activated');
    }

    // Deactivate menu
    public function deactivateMenu($menuID)
    {
        $menu = MenuTable::find($menuID);
        if ($menu) {
            $menu->status = 'Unavailable';
            $menu->save();
            return redirect('manager/menu_list')->with('success', 'The menu has been deactivated');
        }
        return redirect('manager/menu_list')->with('error', 'Failed! The menu was not deactivated');
    }
}
