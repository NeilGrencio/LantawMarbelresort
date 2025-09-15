<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CottageTable;
use Illuminate\Http\Request;

class CottageController extends Controller
{
     public function index()
    {
        $cottages = CottageTable::where('status', ['Available','Booked'])
            ->get()
            ->map(function ($cottage) {
                $cottage->image = url('uploads/' . $cottage->image);
                return $cottage;
            });

        return response()->json($cottages);
    }
}
