<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SessionLogTable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SessionLogController extends Controller
{
    public function viewSessions(Request $request)
    {
        $query = SessionLogTable::query()
            ->join('users', 'usersessionlog.userID', '=', 'users.userID')
            ->select('usersessionlog.*', 'users.username');

        if ($filter = $request->input('filter')) {
            $now = Carbon::now();

            switch ($filter) {
                case 'today':
                    $query->whereDate('usersessionlog.date', $now->toDateString());
                    break;

                case 'week':
                    $query->whereBetween('usersessionlog.date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                    break;

                case 'month':
                    $query->whereMonth('usersessionlog.date', $now->month)
                        ->whereYear('usersessionlog.date', $now->year);
                    break;

                case 'year':
                    $query->whereYear('usersessionlog.date', $now->year);
                    break;
            }
        }

        $session = $query->orderBy('usersessionlog.date', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('manager/session_logs', compact('session'));
    }

    public function exportPDF(Request $request){
        $query = SessionLogTable::query()
            ->join('users', 'usersessionlog.userID', '=', 'users.userID')
            ->select('usersessionlog.*', 'users.username');

        if ($request->has('filter')) {
            $filter = $request->input('filter');
            switch ($filter) {
                case 'today':
                    $query->whereDate('usersessionlog.date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('usersessionlog.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('usersessionlog.date', Carbon::now()->month)
                        ->whereYear('usersessionlog.date', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('usersessionlog.date', Carbon::now()->year);
                    break;
            }
        }

        $session = $query->get();

        $pdf = Pdf::loadView('manager.session_logs_pdf', compact('session'));
        return $pdf->download('session_logs.pdf');
    }
}
