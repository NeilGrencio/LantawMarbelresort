<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\CottageTable;

class ManageCottageController extends Controller
{
    public function cottageList(){
        $cottage = CottageTable::all();

        return view('manager/cottage_list', compact('cottage'));
    }

    //show form
    public function addCottage(){
        return view('manager/add_cottages');
    }
    
    public function submitCottage(Request $request){
        $validatedData = $request->validate([
            'cottagename' => 'required',
            'capacity' => 'required',
            'price' => 'required|decimal:0,2',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg:max:2048',
        ]);

        $filePath = $request->file('image')->store('cottage_image', 'public');

        DB::beginTransaction();

        try{
            $cottage = new CottageTable();
            $cottage->cottagename = $validatedData['cottagename'];
            $cottage->capacity = $validatedData['capacity'];
            $cottage->price = $validatedData['price'];
            $cottage->image = $filePath;
            $cottage->status = 'Available';
            $cottage->amenityID = '1';
            $cottage->save();

            DB::commit();
            return redirect('manager/cottage_list')->with('success', 'The cottage has been successfully added');
        } catch (\Exception $ex){
            DB::rollback();
            return redirect('manager/add_cottages')->withInput()->with('error', 'Failed to add cottage' . $ex->getMessage());
        }
    }

    public function editcottage(Request $request, $cottageID){
        $cottage = CottageTable::where('cottageID', $cottageID)->first();

        if($request->isMethod('get')){
                return view('manager/edit_cottage', compact('cottage'));
            }

            if($request->isMethod('post')){

            $validatedData = $request->validate([
                'cottagename' => 'required',
                'capacity' => 'required',
                'price' => 'required|decimal:0,2',
                'image' => 'required|image|mimes:webp,png,jpg,jpeg:max:2048',
                'status' => 'required',
            ]);

            $hasChanges = false;

            if (
                $cottage->cottagename !== $validatedData['cottagename'] ||
                $cottage->capacity != $validatedData['capacity'] ||
                $cottage->price != $validatedData['price'] ||
                $cottage->status !== $validatedData['status'] ||
                $request->hasFile('image') // new image uploaded
            ) {
                $hasChanges = true;
            }

            if (!$hasChanges) {
                return redirect('manager/add_cottages')->withInput()->with('error', 'No changes were found.');
            }

            DB::beginTransaction();

            try{
                $cottage->cottagename = $validatedData['cottagename'];
                $cottage->capacity = $validatedData['capacity'];
                $cottage->price = $validatedData['price'];
                $cottage->status = $validatedData['status'];

                if ($request->hasFile('image')) {
                    $filePath = $request->file('image')->store('cottage_image', 'public');
                    $cottage->image = $filePath;
                }

                $cottage->save();

                DB::commit();
                return redirect('manager/cottage_list')->with('success', 'The cottage has been successfully added');
            } catch (\Exception $ex){
                DB::rollback();
                return redirect('manager/add_cottages')->withInput()->with('error', 'Failed to add cottage' . $ex->getMessage());
            }
        }


    }

    public function deactivateCottage($cottageID){
        $cottage = CottageTable::find($cottageID);
        if($cottage){
            $cottage->status = 'Unavailable';
            $cottage->save();
            return redirect('manager/cottage_list')->with('success', 'Cottage status updated successfully.');
        } else {
            return redirect('manager/cottage_list')->with('error', 'Cottage status update failed.'); 
        }

    }

    public function activateCottage($cottageID){
        $cottage = CottageTable::find($cottageID);
        if($cottage){
            $cottage->status = 'Available';
            $cottage->save();
            return redirect('manager/cottage_list')->with('success', 'Cottage status updated successfully.');
        } else {
            return redirect('manager/cottage_list')->with('error', 'Cottage status update failed.'); 
        }

    }

    public function maintenanceCottage($cottageID){
        $cottage = CottageTable::find($cottageID);
        if($cottage){
            $cottage->status = 'Maintenance';
            $cottage->save();
            return redirect('manager/cottage_list')->with('success', 'Cottage status updated successfully.');
        } else {
            return redirect('manager/cottage_list')->with('error', 'Cottage status update failed.'); 
        }

    }
}
