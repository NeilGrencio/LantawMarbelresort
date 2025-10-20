<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\RoomTable;

class RoomMobile extends Controller
{
    public function roomList()
    {
        $rooms = RoomTable::where('status', 'available')->get();

        foreach ($rooms as $room) {
            $room->image_url = route('room.image', ['filename' => basename($room->image)]);
        }

        return response()->json($rooms);
    }

    public function availableRoomsByDate(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date parameter is required.'], 400);
        }

        // Get all room IDs that are already booked for the given date
        $bookedRoomIds = \App\Models\RoomBookTable::whereDate('bookingDate', $date)
            ->pluck('roomID')
            ->toArray();

        // Get all available rooms that are NOT in the booked list
        $availableRooms = \App\Models\RoomTable::whereNotIn('roomID', $bookedRoomIds)
            ->where('status', 'available')
            ->get();

        // Attach image URLs like in your original function
        foreach ($availableRooms as $room) {
            $room->image_url = route('room.image', ['filename' => basename($room->image)]);
        }

        return response()->json($availableRooms);
    }
}
