<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\RoomTable;

class ManageRoomController extends Controller
{
    // Display all rooms (for web)
    public function roomList()
    {
        $rooms = RoomTable::all();
        Log::info('Fetched room list', ['count' => $rooms->count()]);

        // Prepare image URLs using the route you defined
        foreach ($rooms as $room) {
            $room->image_url = $room->image
                ? route('room.image', ['filename' => basename($room->image)])
                : null;
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
            $room = new RoomTable($validatedData);

            // Save image to storage/app/public/room_images
            if ($request->hasFile('image')) {
                $room->image = $request->file('image')->store('room_images', 'public');
                Log::info('Room image uploaded', ['image' => $room->image]);
            }

            $room->save();
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
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    Storage::disk('public')->delete($room->image);
                    Log::info('Old image deleted', ['roomID' => $roomID, 'image' => $room->image]);
                }
                $room->image = $request->file('image')->store('room_images', 'public');
                Log::info('New image stored', ['roomID' => $roomID, 'image' => $room->image]);
            }

            $room->update($validatedData);
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
