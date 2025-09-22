<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\RoomTable;
use Illuminate\Support\Facades\Log;

class ManageRoomController extends Controller
{
    // Display the list of rooms
    public function roomList()
    {
        // Fetch all rooms from the database
        $rooms = RoomTable::all();
        // Pass the rooms to the view

        return view('manager.room_list', compact('rooms'));
    }

    public function editRoom(Request $request, $roomID)
    {
        // Fetch the room or fail
        $room = RoomTable::findOrFail($roomID);
        Log::info("Editing room fetched", ['roomID' => $roomID, 'room' => $room->toArray()]);

        // Show the edit form
        if ($request->isMethod('get')) {
            return view('manager.edit_room', compact('room'));
        }

        // Validate input for POST request
        $validatedData = $request->validate([
            'roomnum'     => 'required|numeric',
            'description' => 'required|string',
            'roomtype'    => 'required|string',
            'status'      => 'required|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ]);
        Log::info("Validation passed for room edit", ['roomID' => $roomID, 'validatedData' => $validatedData]);

        // Check if any field has changed
        $hasChanges = $room->roomnum != $validatedData['roomnum'] ||
            $room->description != $validatedData['description'] ||
            $room->roomtype != $validatedData['roomtype'] ||
            $room->status != $validatedData['status'] ||
            $room->price != $validatedData['price'] ||
            $request->hasFile('image');

        if (!$hasChanges) {
            Log::info("No changes detected for room", ['roomID' => $roomID]);
            return redirect()->route('manager.edit_room', ['roomID' => $roomID])
                ->with('error', 'No changes detected.');
        }

        DB::beginTransaction();
        try {
            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    Storage::disk('public')->delete($room->image);
                    Log::info("Old image deleted", ['roomID' => $roomID, 'image' => $room->image]);
                }
                // Store new image
                $room->image = $request->file('image')->store('room_images', 'public');
                Log::info("New image stored", ['roomID' => $roomID, 'image' => $room->image]);
            }

            // Update room fields
            $room->roomnum     = $validatedData['roomnum'];
            $room->description = $validatedData['description'];
            $room->roomtype    = $validatedData['roomtype'];
            $room->status      = $validatedData['status'];
            $room->price       = $validatedData['price'];

            $room->save();
            Log::info("Room updated successfully", ['roomID' => $roomID, 'room' => $room->toArray()]);

            DB::commit();

            return redirect()->route('manager.room_list')
                ->with('success', 'Room was updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update room", ['roomID' => $roomID, 'error' => $e->getMessage()]);
            return redirect()->route('manager.edit_room', ['roomID' => $roomID])
                ->withInput()
                ->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }


    public function deactivateRoom($roomID)
    {
        $room = RoomTable::find($roomID);
        if ($room) {
            $room->status = 'Unavailable';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.');
        }
    }

    public function activateRoom($roomID)
    {
        $room = RoomTable::find($roomID);
        if ($room) {
            $room->status = 'Available';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.');
        }
    }

    public function maintenanceRoom($roomID)
    {
        $room = RoomTable::find($roomID);
        if ($room) {
            $room->status = 'Maintenance';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.');
        }
    }

    public function bookRoom($roomID)
    {
        $room = RoomTable::find($roomID);
        if ($room) {
            $room->status = 'Booked';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.');
        }
    }

    public function saveRoom(Request $request)
    {
        $validatedData = $request->validate([
            'roomnum' => 'required|numeric',
            'description' => 'required',
            'roomcapacity' => 'required|numeric|min:1',
            'roomtype' => 'required',
            'price' => 'required|numeric|decimal:0,2',
            'image' => 'required|image|mimes:jpg,jpeg,webp,png',
            'status' => 'required'
        ]);

        $roomImagePath = $request->file('image')->store('room_images', 'public');

        DB::beginTransaction();

        try {
            $room = new RoomTable();
            $room->roomnum = $validatedData['roomnum'];
            $room->description = $validatedData['description'];
            $room->price = $validatedData['price'];
            $room->roomcapacity = $validatedData['roomcapacity'];
            $room->roomtype = $validatedData['roomtype'];
            $room->status = $validatedData['status'];
            $room->image = $roomImagePath;
            $room->save();

            DB::commit();

            return redirect('manager/room_list')->with('success', 'Room was successfully added!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', 'Failed to create room!' . $e->getMessage());
        }
    }

    public function addRoom()
    {
        return view('manager/add_room');
    }
}
