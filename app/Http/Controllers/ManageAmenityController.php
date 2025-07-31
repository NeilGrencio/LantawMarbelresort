<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\AmenityTable;

class ManageAmenityController extends Controller
{
    public function amenityList(Request $request){
        $amenities = AmenityTable::all();

        return view('manager/amenity_list', compact('amenities'));
    }

    public function saveAmenity(Request $request){
        $validateData = $request->validate([
            'amenityname' => 'required',
            'description' => 'required',
            'amenityimage' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'childprice' => 'required|decimal:0,2',
            'adultprice' => 'required|decimal:0,2'
        ]);

        $imagePath = $request->file('amenityimage')->store('amenity_images', 'public');

        DB::beginTransaction();

        try {
            $amenity = new AmenityTable();
            $amenity->amenityname = $validateData['amenityname'];
            $amenity->description = $validateData['description'];
            $amenity->image = $imagePath;
            $amenity->childprice = $validateData['childprice'];
            $amenity->adultprice = $validateData['adultprice'];
            $amenity->save();

            DB::commit();

            return redirect('manager/amenity_list')->with('success', 'The Amenity was successfully added!');

        } catch (\Exception $ex){
            DB::rollback();

            return redirect('manager/add_amenity')->with('error', 'The Amenity failed to be added!');
        }
    }

    public function addAmenity(){
        return view('manager/add_amenity');
    }

    public function editAmenity(Request $request, $amenityID){
        $amenity = AmenityTable::where('amenityID', $amenityID)->first();

        if ($request->isMethod('get')){
            return view('manager.edit_amenity', compact('amenity'));
        }

        if($request->isMethod('post')){
            $validateData = $request->validate([
                'amenityname' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'childprice' => 'required|decimal:0,2',
                'adultprice' => 'required|decimal:0,2',
                'status' => 'required',
            ]);
            // Check if any field has changed
            $hasChanges = false;
            if (
                $amenity->amenityname != $validateData['amenityname'] || 
                $amenity->description != $validateData['description'] ||
                $amenity->status != $validateData['status'] ||
                $amenity->childprice != $validateData['childprice'] ||
                $amenity->adultprice != $validateData['adultprice'] ||
                $request->hasFile('image')
            ) {
                $hasChanges = true;
            }

            if (!$hasChanges) {
                return redirect()->route('manager.edit_amenity', ['amenityID' => $amenityID])->with('error', 'No changes detected.');
            }

            DB::beginTransaction();

            try {

                // Prepare update data
                $updateData = [
                    'amenityname' => $validateData['amenityname'],
                    'description' => $validateData['description'],
                    'childprice' => $validateData['childprice'],
                    'adultprice' => $validateData['adultprice'],
                    'status' => $validateData['status'],
                ];

                // Add image only if a file is uploaded
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('amenity_images', 'public');
                    $updateData['image'] = $imagePath;
                }

                // Perform update
                DB::table('amenities')
                    ->where('amenityID', $amenityID)
                    ->update($updateData);

                DB::commit();

                return redirect('manager/amenity_list')->with('success', 'The Amenity was successfully updated!');
            } catch (\Exception $ex) {
                DB::rollback();

                return redirect()->route('manager.edit_amenity', ['amenityID' => $amenityID])
                    ->withInput()
                    ->with('error', 'The Amenity failed to be updated! ' . $ex->getMessage());
            }

        }
    }
}
