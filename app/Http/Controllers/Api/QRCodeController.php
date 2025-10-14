<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Imagick;
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
    $qr = QRTable::with(['Amenity', 'Guest'])
        ->where('qrID', $id)
        ->get()
        ->map(function ($item) {
            $item->qr_url = $this->getQrUrl($item->qrcode);
            return $item;
        });

    if ($qr->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'QR code not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $qr->first()
    ]);
}

public function showByGuest($guestID)
{
    $qrs = QRTable::with(['Amenity', 'Guest'])
        ->where('guestID', $guestID)
        ->orderByDesc('accessdate')
        ->get()
        ->map(function ($item) {
            $item->qr_url = $this->getQrUrl($item->qrcode);
            return $item;
        });

    if ($qrs->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No QR records found for this guest.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $qrs
    ]);
}

/**
 * Convert .svg to .png (if needed) and return accessible URL
 */
private function getQrUrl($qrcodePath)
{
    if (!$qrcodePath) {
        return null;
    }

    $filename = basename($qrcodePath);
    $storagePath = storage_path('app/public/qrcodes/' . $filename);

    if (!file_exists($storagePath)) {
        return null;
    }

    // If it's an SVG file, convert to PNG if not already existing
    if (Str::endsWith($filename, '.svg')) {
        $pngName = str_replace('.svg', '.png', $filename);
        $pngPath = storage_path('app/public/qrcodes/' . $pngName);

        if (!file_exists($pngPath)) {
            try {
                $image = new Imagick($storagePath);
                $image->setImageFormat('png');
                $image->writeImage($pngPath);
            } catch (\Exception $e) {
                return null;
            }
        }

        return route('qr.code', $pngName);
    }

    // Otherwise, return as-is
    return route('qr.code', $filename);
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
