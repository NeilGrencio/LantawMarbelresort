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
}
