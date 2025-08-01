<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\StaffTable;
use App\Models\ChatTable;

class ChatController extends Controller
{
    public function viewChats(){
        $chat = ChatTable::query()
            ->leftJoin('guest', 'chat.guestID', '=', 'guest.guestID')
            ->leftJoin('staff', 'chat.staffID', '=', 'staff.staffID')
            ->select(
                'chat.*',
                DB::raw("DATE_FORMAT(chat.datesent, '%d/%m/%Y %h:%i %p') as datesent"),
                DB::raw("DATE_FORMAT(chat.datereplied, '%d/%m/%Y %h:%i %p') as datereplied"),

                DB::raw("COALESCE(CONCAT(guest.firstname, ' ', guest.lastname), 'Unknown Guest') as g_fullname"),
                'guest.guestID as gID',
                'guest.role as g_role',
                DB::raw("COALESCE(guest.avatar, '') as g_avatar"), 

                DB::raw("COALESCE(CONCAT(staff.firstname, ' ', staff.lastname), 'Staff') as s_fullname"),
                'staff.staffID as sID',
                'staff.role as s_role',
                DB::raw("COALESCE(staff.avatar, '') as s_avatar") 
            )
            ->orderBy('chat.chatID', 'desc')
            ->get();

        return view('manager/chat', compact('chat'));
    }


    public function sendChat(Request $request, $chatID){
         $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        // Retrieve the chat record
        $chat = ChatTable::where('chatID', $chatID)->first();

        if (!$chat) {
            return redirect()->route('manager.chat_logs')->with('error', 'Chat not found.');
        }

        // Get staff ID from session (should match staff.staffID)
        $userID = (int) $request->session()->get('user_id');
        $senderID = StaffTable::where('userID', $userID)->first(); 

        // Transaction for safety
        DB::beginTransaction();

        try {
            $chat->staffID = $senderID->staffID;
            $chat->reply = $validated['reply'];
            $chat->datereplied = now();
            $chat->status = 'Read';
            $chat->save();

            DB::commit();
            return redirect()->route('manager.chat_logs')->with('success', 'Reply sent!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Show exact error for debugging
            return redirect()->route('manager.chat_logs')
                ->withInput()
                ->with('error', 'Reply failed: ' . $e->getMessage());
        }
    }
}
