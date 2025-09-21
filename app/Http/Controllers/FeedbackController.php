<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeedbackTable;

class FeedbackController extends Controller
{
    public function viewFeedback()
    {
        $feedbacks = FeedbackTable::leftJoin('guest', 'feedback.guestID', '=', 'guest.guestID')
            ->select(
                'feedback.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname")
            )
            ->paginate(10);

        return view('manager.feedback', compact('feedbacks'));
    }
}
