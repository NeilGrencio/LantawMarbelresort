<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmenityTable;

class AmenityController extends Controller
{

    public function index()
    {
        $amenities = AmenityTable::all()->map(function ($amenity) {
            $amenity->image = url('uploads/' . $amenity->image);
            return $amenity;
        });
        return response()->json($amenities);
    }
}
