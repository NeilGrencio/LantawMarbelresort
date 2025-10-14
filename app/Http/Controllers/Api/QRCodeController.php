<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\QRTable;

class QRCodeController extends Controller
{
    public function index($guestID)
    {
        $qrcodes = QRTable::with(['Amenity', 'Guest'])
            ->where('guestID', $guestID)
            ->orderBy('accessdate', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $qrcodes
        ]);
    }

    /**
     * Show a specific QR record
     */
    public function show($id)
    {
        $qr = QRTable::with(['Amenity', 'Guest'])->find($id);

        if (!$qr) {
            return response()->json(['success' => false, 'message' => 'QR code not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $qr]);
    }
public function showbyGuest($guestID)
{
    $qr = QRTable::with(['Amenity', 'Guest'])
        ->where('guestID', $guestID)
        ->latest('accessdate') // optional: get latest by date
        ->first();

    if (!$qr) {
        return response()->json([
            'success' => false,
            'message' => 'No QR record found for this guest.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $qr
    ]);
}

    /**
     * Store a new QR code record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'qrcode' => 'required|string',
            'accessdate' => 'nullable|date',
            'amenityID' => 'required|integer',
            'guestID' => 'required|integer'
        ]);

        $qr = QRTable::create([
            'qrcode' => $validated['qrcode'],
            'accessdate' => $validated['accessdate'] ?? now(),
            'amenityID' => $validated['amenityID'],
            'guestID' => $validated['guestID']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR code record created successfully',
            'data' => $qr
        ]);
    }

    /**
     * Delete a QR record (optional)
     */
    public function destroy($id)
    {
        $qr = QRTable::find($id);

        if (!$qr) {
            return response()->json(['success' => false, 'message' => 'QR code not found'], 404);
        }

        $qr->delete();

        return response()->json(['success' => true, 'message' => 'QR code deleted successfully']);
    }
}
