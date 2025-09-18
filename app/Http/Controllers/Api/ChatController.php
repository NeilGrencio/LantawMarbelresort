<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatTable;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    // List all chats
    public function index()
    {
        $chats = ChatTable::with(['guest', 'staff'])->get();
        return response()->json($chats);
    }

    // Get all chats for a specific guest
    public function getByGuest($guestID)
    {
        $chats = ChatTable::with(['guest', 'staff'])
            ->where('guestID', $guestID)
            ->orderBy('datesent', 'asc')
            ->get();

        if ($chats->isEmpty()) {
            return response()->json(['message' => 'No chats found for this guest'], 404);
        }

        return response()->json($chats);
    }

    // Show a single chat
    public function show($id)
    {
        $chat = ChatTable::with(['guest', 'staff'])->find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }
        return response()->json($chat);
    }

    // Create a new chat
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'chat' => 'required|string',
                'datesent' => 'required|date',
                'reply' => 'nullable|string',
                'datereplied' => 'nullable|date',
                'status' => 'required|string',
                'guestID' => 'required|integer|exists:guest,guestID',
                'staffID' => 'nullable|integer|exists:staff,staffID'
            ]);

            $chat = ChatTable::create($validated);
            return response()->json($chat, 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Update a chat
    public function update(Request $request, $id)
    {
        $chat = ChatTable::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        try {
            $validated = $request->validate([
                'chat' => 'sometimes|string',
                'datesent' => 'sometimes|date',
                'reply' => 'nullable|string',
                'datereplied' => 'nullable|date',
                'status' => 'sometimes|string',
                'guestID' => 'sometimes|integer|exists:guest,guestID',
                'staffID' => 'nullable|integer|exists:staff,staffID'
            ]);

            $chat->update($validated);
            return response()->json($chat);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Delete a chat
    public function destroy($id)
    {
        $chat = ChatTable::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        $chat->delete();
        return response()->json(['message' => 'Chat deleted successfully']);
    }
}
