<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\RoomTable;
use App\Models\SessionLogTable;

class ManageRoomController extends Controller
{
    // Display all rooms (for web)
    public function roomList(Request $request)
    {
        $rooms = RoomTable::all();
        Log::info('Fetched room list', ['count' => $rooms->count()]);

        // Prepare image URLs via route
        foreach ($rooms as $room) {
            $room->image_url = $room->image
                ? route('room.image', ['filename' => basename($room->image)])
                : null;
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Room List',
                'date'     => now(),
            ]);
        }

        return view('manager.room_list', compact('rooms'));
    }

    // Show add room form
    public function addRoom()
    {
        return view('manager.add_room');
    }

    // Save new room
    public function saveRoom(Request $request)
    {
        $validatedData = $request->validate([
            'roomnum' => 'required|numeric',
            'description' => 'required|string',
            'roomcapacity' => 'required|numeric|min:1',
            'roomtype' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,webp,png',
            'status' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $room = new RoomTable();
            $room->roomnum = $validatedData['roomnum'];
            $room->description = $validatedData['description'];
            $room->roomcapacity = $validatedData['roomcapacity'];
            $room->roomtype = $validatedData['roomtype'];
            $room->price = $validatedData['price'];
            $room->status = $validatedData['status'];

            // Save image properly to storage/public/room_images
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('room_images', 'public');
                $room->image = $path;
                Log::info('Room image uploaded', ['image' => $room->image]);
            }

            $room->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Room: ' . $room->roomnum,
                    'date'     => now(),
                ]);
            }

            DB::commit();

            Log::info('Room created successfully', ['roomID' => $room->roomID]);
            return redirect()->route('manager.room_list')->with('success', 'Room successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create room', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    // Edit room
    public function editRoom(Request $request, $roomID)
    {
        $room = RoomTable::findOrFail($roomID);
        Log::info('Editing room fetched', ['roomID' => $roomID]);

        if ($request->isMethod('get')) {
            return view('manager.edit_room', compact('room'));
        }

        $validatedData = $request->validate([
            'roomnum' => 'required|numeric',
            'description' => 'required|string',
            'roomtype' => 'required|string',
            'status' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ]);

        DB::beginTransaction();
        try {
            // Handle image replacement
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    Storage::disk('public')->delete($room->image);
                    Log::info('Old image deleted', ['roomID' => $roomID, 'image' => $room->image]);
                }

                // Store new image properly
                $path = $request->file('image')->store('room_images', 'public');
                $room->image = $path;
                Log::info('New image stored', ['roomID' => $roomID, 'image' => $room->image]);
            }

            // Update other fields
            $room->update([
                'roomnum' => $validatedData['roomnum'],
                'description' => $validatedData['description'],
                'roomtype' => $validatedData['roomtype'],
                'status' => $validatedData['status'],
                'price' => $validatedData['price']
            ]);

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Room: ' . $room->roomnum,
                    'date'     => now(),
                ]);
            }

            DB::commit();
            Log::info('Room updated successfully', ['roomID' => $roomID]);
            return redirect()->route('manager.room_list')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update room', ['roomID' => $roomID, 'error' => $e->getMessage()]);
            return redirect()->route('manager.edit_room', ['roomID' => $roomID])
                             ->withInput()
                             ->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }
    
    public function deactivateRoom($roomID, Request $request){
        $room = RoomTable::find($roomID);
        $room->status = 'Unavailable';
        $room->save();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Deactivated a Room: ' . $room->roomnum,
                'date'     => now(),
            ]);
        }

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
    
    public function activateRoom($roomID, Request $request){
        $room = RoomTable::find($roomID);
        $room->status = 'Available';
        $room->save();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Activated a Room: ' . $room->roomnum,
                'date'     => now(),
            ]);
        }

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
    public function maintenanceRoom($roomID, Request $request){
        $room = RoomTable::find($roomID);
        $room->status = 'Maintenance';
        $room->save();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Set a Room to Maintenance: ' . $room->roomnum,
                'date'     => now(),
            ]);
        }

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
    // Update room status
    public function updateRoomStatus($roomID, $status)
    {
        $room = RoomTable::find($roomID);
        if (!$room) {
            Log::warning('Room not found for status update', ['roomID' => $roomID]);
            return redirect()->route('manager.room_list')->with('error', 'Room not found.');
        }

        $room->status = $status;
        $room->save();

        Log::info('Room status updated', ['roomID' => $roomID, 'status' => $status]);
        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
}
