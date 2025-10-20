<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            Log::warning('availableRoomsByDate called without date parameter.');
            return response()->json(['error' => 'Date parameter is required.'], 400);
        }

        Log::info('Checking available rooms for date: ' . $date);

        try {
            // Get all room IDs that are already booked for the given date
            $bookedRoomIds = \App\Models\RoomBookTable::whereDate('bookingDate', $date)
                ->pluck('roomID')
                ->toArray();

            Log::info('Booked Room IDs for ' . $date . ':', $bookedRoomIds);

            // Get all rooms that are marked as available and not booked
            $availableRooms = \App\Models\RoomTable::where('status', 'available')
                ->whereNotIn('roomID', $bookedRoomIds)
                ->get();

            Log::info('Found ' . $availableRooms->count() . ' available rooms for ' . $date);

            // Attach image URLs (same logic as roomList)
            foreach ($availableRooms as $room) {
                $room->image_url = route('room.image', ['filename' => basename($room->image)]);
            }

            // ✅ Return JSON same as roomList
            return response()->json($availableRooms);
        } catch (\Exception $e) {
            Log::error('Error in availableRoomsByDate: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
