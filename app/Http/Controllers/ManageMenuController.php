<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MenuTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\SessionLogTable;
use Termwind\Components\Raw;

class ManageMenuController extends Controller
{
    // List menus (for web)
    public function menuList(Request $request)
    {
        $menu = MenuTable::where('itemtype', '!=', 'Services')->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();

        // Prepare image URLs using a secure route
        foreach ($menu as $item) {
            $item->image_url = $item->image
                ? route('menu.image', ['filename' => basename($item->image)])
                : null;
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Menu List',
                'date'     => now(),
            ]);
        }

        return view('manager/menu_list', compact('menu', 'uniqueMenuTypes'));
    }

<<<<<<< HEAD
    public function serviceList(Request $request){
        $service = MenuTable::where('itemtype', 'Services')->get();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Service List',
                'date'     => now(),
            ]);
        }

=======
    public function serviceList(){
        $service = MenuTable::where('itemtype', 'Services')->get();

>>>>>>> d927b3a3dbe225427cfaf6d569765ffb9f95c0be
        return view('manager.service_list', compact('service'));
    }

    public function addService(Request $request){
        if ($request->isMethod('get')){
            return view('manager.add_service');
        }

        $validatedData = $request->validate([
            'menuname' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);
        DB::beginTransaction();
        try {
            $menu = new MenuTable();
            $menu->menuname = $validatedData['menuname'];
            $menu->itemtype = 'Services';
            $menu->price = $validatedData['price'];
            $menu->status = $validatedData['status'];

            // Save image securely
            if ($request->hasFile('image')) {
                $menu->image = $request->file('image')->store('menu_images', 'public');
                Log::info('Menu image uploaded', ['image' => $menu->image]);
            }

            $menu->save();
<<<<<<< HEAD

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Service: ' . $menu->menuname,
                    'date'     => now(),
                ]);
            }

=======
>>>>>>> d927b3a3dbe225427cfaf6d569765ffb9f95c0be
            DB::commit();

            return redirect('manager/service_list')->with('success', 'The menu has been successfully added');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Failed to add menu', ['error' => $ex->getMessage()]);
            return redirect('manager/add_service')->withInput()->with('error', 'Failed! The menu failed to be added');
        }
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

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Menu Item: ' . $menu->menuname,
                    'date'     => now(),
                ]);
            }
            
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

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Menu Item: ' . $menu->menuname,
                    'date'     => now(),
                ]);
            }

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

    public function editService(Request $request, $menuID)
    {
        $menu = MenuTable::findOrFail($menuID);

        if ($request->isMethod('get')) {
            return view('manager/edit_service', compact('menu'));
        }

        $validatedData = $request->validate([
            'menuname' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);

        // Check for changes
        $hasChanges = $menu->menuname !== $validatedData['menuname'] ||
                      $menu->status !== $validatedData['status'] ||
                      $menu->price !== $validatedData['price'] ||
                      $request->hasFile('image');

        if (!$hasChanges) {
            return redirect()->route('manager.edit_service', ['menuID' => $menuID])
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
                'itemtype' => 'Services',
                'price' => $validatedData['price'],
                'status' => $validatedData['status']
            ]);

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Service: ' . $menu->menuname,
                    'date'     => now(),
                ]);
            }

            DB::commit();
            return redirect('manager/services_list')->with('success', 'The service was successfully updated!');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Failed to update menu', ['menuID' => $menuID, 'error' => $ex->getMessage()]);
            return redirect()->route('manager.edit_service', ['menuID' => $menuID])
                             ->withInput()
                             ->with('error', 'Failed! The menu failed to be updated');
        }
    }

    // Activate menu
    public function activateMenu($menuID, Request $request)
    {
        $menu = MenuTable::find($menuID);
        if ($menu) {
            $menu->status = 'Available';
            $menu->save();
            return back()->with('success', 'The item has been activated');
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Activated a Menu Item: ' . $menu->menuname,
                'date'     => now(),
            ]);
        }
        return back()->with('error', 'Failed! The item was not activated');
    }

    // Deactivate menu
    public function deactivateMenu($menuID, Request $request)
    {
        $menu = MenuTable::find($menuID);
        if ($menu) {
            $menu->status = 'Unavailable';
            $menu->save();
            return back()->with('success', 'The item has been deactivated');
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Deactivated a Menu Item: ' . $menu->menuname,
                'date'     => now(),
            ]);
        }

        return back()->with('error', 'Failed! The menu was not deactivated');
    }
}
