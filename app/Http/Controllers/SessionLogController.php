<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SessionLogTable;
use Illuminate\Http\Request;

class SessionLogController extends Controller
{
    public function viewSessions(){
       $session = SessionLogTable::query()
            ->join('users', 'usersessionlog.userID', '=', 'users.userID')
            ->select('usersessionlog.*', 'users.username')
            ->paginate(10);

        return view('manager/session_logs', compact('session'));
    }
}
