<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CottageTable;
use Illuminate\Http\Request;

class CottageController extends Controller
{
    public function index()
    {
        $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])
            ->get()
            ->map(function ($cottage) {
                // Generate URL using the named route
                $cottage->image_url = $cottage->image
                    ? route('cottage.image', basename($cottage->image))
                    : null;
                return $cottage;
            });

        return response()->json($cottages);
    }
}
