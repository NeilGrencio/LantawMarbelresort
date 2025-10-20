<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SessionLogTable;

use App\Models\AmenityTable;
use App\Models\DisableReasonTable;
use App\Models\StaffTable;

class ManageAmenityController extends Controller
{
    // List all amenities
    public function amenityList(Request $request)
    {
        $amenities = AmenityTable::all();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Amenity List',
                'date'     => now(),
            ]);
        }

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
            'amenitycapacity' => 'required|int',
            'description' => 'required|string',
            'amenityimage' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'childprice' => 'required|numeric|min:0',
            'adultprice' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $request->file('amenityimage')->store('amenity_images', 'public');

            $amenity = new AmenityTable();
            $amenity->amenityname = $validatedData['amenityname'];
            $amenity->description = $validatedData['description'];
            $amenity->image = $imagePath;
            $amenity->capacity = $validatedData['amenitycapacity'];
            $amenity->childprice = $validatedData['childprice'];
            $amenity->adultprice = $validatedData['adultprice'];
            $amenity->status = 'Available';
            $amenity->type = $validatedData['type'];
            $amenity->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created an Amenity: ' . $amenity->amenityname,
                    'date'     => now(),
                ]);
            }

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
            'amenitycapacity' => 'required|int',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'childprice' => 'required|numeric|min:0',
            'adultprice' => 'required|numeric|min:0',
            'status' => 'required|string',
            'type' => 'required|string',
        ]);

        $hasChanges = (
            $amenity->amenityname != $validatedData['amenityname'] ||
            $amenity->description != $validatedData['description'] ||
            $amenity->capacity = $validatedData['amenitycapacity'] ||
            $amenity->childprice != $validatedData['childprice'] ||
            $amenity->adultprice != $validatedData['adultprice'] ||
            $amenity->status != $validatedData['status'] ||
            $amenity->type != $validatedData['type'] ||
            $request->hasFile('image')
        );

        if (!$hasChanges) {
            return redirect()->back()->withInput()->with('error', 'No changes detected.');
        }

        DB::beginTransaction();
        try {
            $amenity->amenityname = $validatedData['amenityname'];
            $amenity->description = $validatedData['description'];
            $amenity->capacity = $validatedData['amenitycapacity'];
            $amenity->childprice = $validatedData['childprice'];
            $amenity->adultprice = $validatedData['adultprice'];
            $amenity->status = $validatedData['status'];
            $amenity->type = $validatedData['type'];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($amenity->image && Storage::disk('public')->exists($amenity->image)) {
                    Storage::disk('public')->delete($amenity->image);
                }
                $amenity->image = $request->file('image')->store('amenity_images', 'public');
            }

            $amenity->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated an Amenity: ' . $amenity->amenityname,
                    'date'     => now(),
                ]);
            }

            DB::commit();
            Log::info('Amenity updated', ['amenityID' => $amenity->amenityID]);

            return redirect('manager/amenity_list')->with('success', 'Amenity updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update amenity', ['amenityID' => $amenityID, 'error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update amenity: ' . $e->getMessage());
        }
    }

    public function activateAmenity($amenityID, Request $request){
        $amenity = AmenityTable::find($amenityID);

        $amenity->status = 'Available';

        // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Activated an Amenity: ' . $amenity->amenityname,
                    'date'     => now(),
                ]);
            }

        $amenity->save();

        return back()->with('success', 'Amenity status changed!');
    }

    public function deactivateAmenity($amenityID, Request $request){
        $amenity = AmenityTable::find($amenityID);

        $amenity->status = 'Unavailable';
        $amenity->save();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');
        $staff = StaffTable::Where('userID', $userID)->first();

        DisableReasonTable::create([
            'amenityID' => $amenityID,
            'reason' => '',
            'reported_by' => $staff->staffID,
        ]);

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Deactivated an Amenity: ' . $amenity->amenityname,
                'date'     => now(),
            ]);
        }

        return back()->with('success', 'Amenity status changed!');
    }

    public function maintenanceAmenity($amenityID, Request $request){
        $amenity = AmenityTable::find($amenityID);

        $amenity->status = 'Maintenance';
        $amenity->save();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User set an Amenity to Maintenance: ' . $amenity->amenityname,
                'date'     => now(),
            ]);
        }

        return back()->with('success', 'Amenity status changed!');
    }
}
