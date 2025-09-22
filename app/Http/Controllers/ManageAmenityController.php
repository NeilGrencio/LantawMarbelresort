<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\AmenityTable;

class ManageAmenityController extends Controller
{
    // List all amenities
    public function amenityList()
    {
        $amenities = AmenityTable::all();
        return view('manager.amenity_list', compact('amenities'));
    }

    // Show add form
    public function addAmenity()
    {
        return view('manager.add_amenity');
    }

    // Save new amenity
    public function saveAmenity(Request $request)
    {
        $validatedData = $request->validate([
            'amenityname' => 'required|string|max:255',
            'description' => 'required|string',
            'amenityimage' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'childprice' => 'required|numeric|min:0',
            'adultprice' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $request->file('amenityimage')->store('amenity_images', 'public');

            $amenity = new AmenityTable();
            $amenity->amenityname = $validatedData['amenityname'];
            $amenity->description = $validatedData['description'];
            $amenity->image = $imagePath;
            $amenity->childprice = $validatedData['childprice'];
            $amenity->adultprice = $validatedData['adultprice'];
            $amenity->status = 'Available';
            $amenity->save();

            DB::commit();
            Log::info('Amenity created', ['amenityID' => $amenity->amenityID]);

            return redirect('manager/amenity_list')->with('success', 'The amenity was successfully added!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create amenity', ['error' => $e->getMessage()]);
            return redirect('manager/add_amenity')->withInput()->with('error', 'Failed to add amenity: ' . $e->getMessage());
        }
    }

    // Edit an amenity
    public function editAmenity(Request $request, $amenityID)
    {
        $amenity = AmenityTable::findOrFail($amenityID);

        if ($request->isMethod('get')) {
            return view('manager.edit_amenity', compact('amenity'));
        }

        $validatedData = $request->validate([
            'amenityname' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'childprice' => 'required|numeric|min:0',
            'adultprice' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $hasChanges = (
            $amenity->amenityname != $validatedData['amenityname'] ||
            $amenity->description != $validatedData['description'] ||
            $amenity->childprice != $validatedData['childprice'] ||
            $amenity->adultprice != $validatedData['adultprice'] ||
            $amenity->status != $validatedData['status'] ||
            $request->hasFile('image')
        );

        if (!$hasChanges) {
            return redirect()->back()->withInput()->with('error', 'No changes detected.');
        }

        DB::beginTransaction();
        try {
            $amenity->amenityname = $validatedData['amenityname'];
            $amenity->description = $validatedData['description'];
            $amenity->childprice = $validatedData['childprice'];
            $amenity->adultprice = $validatedData['adultprice'];
            $amenity->status = $validatedData['status'];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($amenity->image && Storage::disk('public')->exists($amenity->image)) {
                    Storage::disk('public')->delete($amenity->image);
                }
                $amenity->image = $request->file('image')->store('amenity_images', 'public');
            }

            $amenity->save();

            DB::commit();
            Log::info('Amenity updated', ['amenityID' => $amenity->amenityID]);

            return redirect('manager/amenity_list')->with('success', 'Amenity updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update amenity', ['amenityID' => $amenityID, 'error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update amenity: ' . $e->getMessage());
        }
    }
}
