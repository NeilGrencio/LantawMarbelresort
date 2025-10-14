<?php

namespace App\Http\Controllers\Api;
use App\Models\FeedbackTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
     public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'guestID' => 'required|integer|exists:guest,guestID', // ensure guest exists
        ]);

        $feedback = FeedbackTable::create([
            'message' => $validated['message'],
            'rating' => $validated['rating'],
            'guestID' => $validated['guestID'],
            'date' => now(),
            'status' => 'Unread', // or 'approved' depending on logic
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully.',
            'data' => $feedback
        ], 201);
    }

    /**
     * Optional: Get all feedback for a guest
     */
    public function index($guestID)
    {
        $feedbacks = FeedbackTable::where('guestID', $guestID)->get();

        return response()->json([
            'success' => true,
            'data' => $feedbacks
        ]);
    }
}
