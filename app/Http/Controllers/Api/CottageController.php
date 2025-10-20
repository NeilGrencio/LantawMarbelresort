<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CottageTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function availableCottagesByDate(Request $request)
{
    $date = $request->input('date');

    if (!$date) {
        Log::warning('availableCottagesByDate called without date parameter.');
        return response()->json(['error' => 'Date parameter is required.'], 400);
    }

    Log::info('Checking available cottages for date: ' . $date);

    try {
        // Get all cottage IDs already booked on the given date
        $bookedCottageIds = \App\Models\CottageBookTable::whereDate('bookingDate', $date)
            ->pluck('cottageID')
            ->toArray();

        Log::info('Booked Cottage IDs for ' . $date . ':', $bookedCottageIds);

        // Get all available cottages not in the booked list
        $availableCottages = \App\Models\CottageTable::whereNotIn('cottageID', $bookedCottageIds)
            ->whereIn('status', ['Available', 'Booked']) // same as your index()
            ->get()
            ->map(function ($cottage) {
                // Attach image URL like in index()
                $cottage->image_url = $cottage->image
                    ? route('cottage.image', ['filename' => basename($cottage->image)])
                    : asset('images/default-cottage.jpg');
                return $cottage;
            });

        Log::info('Found ' . $availableCottages->count() . ' available cottages for ' . $date);

        return response()->json($availableCottages);

    } catch (\Exception $e) {
        Log::error('Error in availableCottagesByDate: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Internal server error'], 500);
    }
}

}
