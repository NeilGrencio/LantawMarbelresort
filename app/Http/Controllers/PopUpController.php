<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingTable;
use App\Models\QRTable;
use App\Models\PaymentTable;
use App\Models\ChatTable;
use App\Models\SessionLogTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PopUpController extends Controller
{
    public function popUp()
    {
        try {
            $today = Carbon::today();

            $notifications = collect();

            // === BOOKINGS DUE FOR CHECK-IN ===
            $dueCheckIns = BookingTable::whereDate('bookingstart', '<=', $today)
                ->where('status', 'Booked')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->bookingID,
                        'type' => 'Booking',
                        'message' => "Booking (#{$booking->bookingID}) is almost due for check-in.",
                        'timestamp' => $booking->bookingstart ?? now(),
                    ];
                });

            // === BOOKINGS DUE FOR CHECK-OUT ===
            $dueCheckOuts = BookingTable::whereDate('bookingend', '<=', $today)
                ->where('status', 'Ongoing')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->bookingID,
                        'type' => 'Booking',
                        'message' => "Booking (#{$booking->bookingID}) is due for check-out today.",
                        'timestamp' => $booking->bookingend ?? now(),
                    ];
                });

            // === DAYTOUR NOTIFICATIONS ===
            $dayTours = QRTable::whereDate('accessdate', $today)
                ->get()
                ->map(function ($tour) {
                    return [
                        'id' => $tour->qrID,
                        'type' => 'Daytour',
                        'message' => "A daytour has been made today.",
                        'timestamp' => $tour->created_at ?? now(),
                    ];
                });

            // === PAYMENTS ===
            $payments = PaymentTable::whereDate('datepayment', $today)->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->paymentID,
                        'type' => 'Payment',
                        'message' => "Payment of ₱" . number_format($payment->totaltender ?? 0, 2) . " has been received.",
                        'timestamp' => $payment->created_at ?? now(),
                    ];
                });

            // === INQUIRIES ===
            $inquiries = ChatTable::whereDate('datesent', $today)
                ->get()
                ->map(function ($inquiry) {
                    return [
                        'id' => $inquiry->chatID ?? $inquiry->id ?? null,
                        'type' => 'Inquiry',
                        'message' => "A new message was received today.",
                        'timestamp' => $inquiry->datesent ?? now(),
                    ];
                });

            // === MERGE ALL ===
            $notifications = $notifications
                ->merge($dueCheckIns)
                ->merge($dueCheckOuts)
                ->merge($dayTours)
                ->merge($payments)
                ->merge($inquiries)
                ->sortByDesc('timestamp')
                ->values();

            return response()->json($notifications);

        } catch (\Throwable $e) {
            Log::error('PopUpController Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'An error occurred while fetching notifications.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function notifications(Request $request){
        $role = session()->get('role');
        if($role === 'Manager'){
            return view('manager/notifications');
        } else if ($role === 'Receptionist'){
            return view('receptionist/notifications');
        }
    }

    public function popUpManager()
    {
        try {
            $today = Carbon::today();

            $notifications = collect();

            // USER LOGINS
            $logins = SessionLogTable::where('activity', 'like', '%login%')
                ->whereDate('date', $today)
                ->join('users', 'usersessionlog.userID', '=', 'users.userID')
                ->select('usersessionlog.sessionID', 'users.username', 'usersessionlog.date')
                ->get()
                ->map(fn($log) => [
                    'id' => $log->sessionID,
                    'type' => 'Activity',
                    'message' => "User ({$log->username}) logged in.",
                    'timestamp' => $log->date ?? now(),
                ]);

            // USER LOGOUTS
            $logouts = SessionLogTable::where('activity', 'like', '%logout%')
                ->whereDate('date', $today)
                ->join('users', 'usersessionlog.userID', '=', 'users.userID')
                ->select('usersessionlog.sessionID', 'users.username', 'usersessionlog.date')
                ->get()
                ->map(fn($log) => [
                    'id' => $log->sessionID,
                    'type' => 'Activity',
                    'message' => "User ({$log->username}) logged out.",
                    'timestamp' => $log->date ?? now(),
                ]);

            // INQUIRIES
            $inquiries = ChatTable::whereDate('datesent', $today)
                ->get()
                ->map(fn($inq) => [
                    'id' => $inq->chatID ?? $inq->id ?? null,
                    'type' => 'Inquiry',
                    'message' => "A new message was received today.",
                    'timestamp' => $inq->datesent ?? now(),
                ]);

            // MERGE
            $notifications = $notifications
                ->merge($logins)
                ->merge($logouts)
                ->merge($inquiries)
                ->sortByDesc('timestamp')
                ->values();

            return response()->json($notifications);

        } catch (\Throwable $e) {
            Log::error('PopUpController Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Error fetching notifications',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
