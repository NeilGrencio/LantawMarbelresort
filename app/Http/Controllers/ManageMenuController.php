<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MenuTable;
use Illuminate\Http\Request;

class ManageMenuController extends Controller
{
    public function menuList(){
        $menu = MenuTable::all();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();

        return view('manager/menu_list', compact('menu', 'uniqueMenuTypes'));
    }

    public function addMenu(Request $request){
        return view('manager/add_menu');
    }

    public function submitMenu(Request $request){
        $validatedData = $request->validate([
            'menuname' => 'required|string',
            'itemtype' => 'required|string',
            'price' => 'required|decimal:0,2',
            'status' => 'required|string',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);

        $imagePath = $request->file('image')->store('menu_images', 'public');

        DB::beginTransaction();

        try{
            $menu = new MenuTable();
            $menu->menuname = $validatedData['menuname'];
            $menu->itemtype = $validatedData['itemtype'];
            $menu->price = $validatedData['price'];
            $menu->status = $validatedData['status'];
            $menu->image = $imagePath;

            $menu->save();

            DB::commit();
            return redirect('manager/menu_list')->with('success', 'The menu has been successfully added');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect('manager/add_menu')->withInput()->with('error', 'Failed! The menu failed to be added');
        }
    } 

    public function editMenu(Request $request, $menuID){
        $menu = MenuTable::where( 'menuID', $menuID)->first();

        if($request->isMethod('get')){
            return view('manager/edit_menu', compact('menu'));
        }
        if($request->isMethod('post')){
            $validatedData = $request->validate([
            'menuname' => 'required|string',
            'itemtype' => 'required|string',
            'price' => 'required|decimal:0,2',
            'status' => 'required|string',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg|max:20248',
        ]);

        // Check if any field has changed   
            $hasChanges = false;
            if (
                $menu->menuname != $validatedData['menuname'] || 
                $menu->itemtype != $validatedData['itemtype'] ||
                $menu->status != $validatedData['status'] ||
                $menu->price != $validatedData['price'] ||
                $request->hasFile('image')
            ) {
                $hasChanges = true;
            }

            if (!$hasChanges) {
                return redirect()->route('manager.edit_menu', ['menuID' => $menuID])->with('error', 'No changes detected.');
            }

            DB::beginTransaction();

            try {
                 // Prepare update data
                $updateData = [
                    'menuname' => $validatedData['menuname'],
                    'itemtype' => $validatedData['itemtype'],
                    'price' => $validatedData['price'],
                    'status' => $validatedData['status'],
                ];

                // Add image only if a file is uploaded
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('menu_images', 'public');
                    $updateData['image'] = $imagePath;
                }

                // Perform update
                DB::table('menu')
                    ->where('menuID', $menuID)
                    ->update($updateData);

                DB::commit();

                return redirect('manager/menu_list')->with('success', 'The menu was successfully updated!');
            } catch (\Exception $ex) {
                return redirect()->route('manager.add_menu', ['menuID' => $menuID])->withInput()->with('error', 'Failed! The menu failed to be added');
            }

        }
    }

    public function activateMenu($menuID) {
        $menu = MenuTable::find( $menuID);

        if($menu){
            $menu->status = 'Available';
            $menu->save();
            return redirect('manager/menu_list')->with('success', 'The menu has been activated');
        } else {
            return redirect('manager/menu_list')->with('error', 'Failed! The menu was not activated');
        }
    }

    public function deactivateMenu($menuID) {
        $menu = MenuTable::find( $menuID);

        if($menu){
            $menu->status = 'Unavailable';
            $menu->save();
            return redirect('manager/menu_list')->with('success', 'The menu has been deactivated');
        } else {
            return redirect('manager/menu_list')->with('error', 'Failed! The menu was not deactivated');
        }
    }
}
