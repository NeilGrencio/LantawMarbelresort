<?php

namespace App\Http\Controllers;

use App\Models\RoomTypeTable;
use Illuminate\Http\Request;

class RoomMobile extends Controller
{
    public function roomList()
    {
        $rooms = RoomTypeTable::
            get()
            ->map(function ($rooms) {
                $rooms->image_url = $rooms->image
                    ? route('room.image', basename($rooms->image))
                    : null;
                return $rooms;
            });

        return response()->json($rooms);
    }
}

