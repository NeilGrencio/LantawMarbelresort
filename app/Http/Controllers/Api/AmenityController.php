<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmenityTable;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = AmenityTable::where('status', 'available')
            ->get()
            ->map(function ($amenity) {
                $amenity->image_url = $amenity->image
                    ? route('amenity.image', basename($amenity->image))
                    : null;
                return $amenity;
            });

        return response()->json($amenities);
    }
}
