<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\BookingTable;
use App\Models\RoomTable;
use App\Models\CottageTable;
use App\Models\AmenityTable;
use App\Models\GuestTable;
use App\Models\UserTable;
use App\Models\DiscountTable;
use App\Models\BillingTable;
use App\Models\PaymentTable;
use App\Models\CheckTable;
use App\Services\OCRService;
use App\Models\User; // Make sure to import your User model
use App\Notifications\OrderUpdateNotification;
use App\Models\RoomBookTable;
use App\Models\CottageBookTable;
use App\Models\ChargeTable;
use Illuminate\Types\Relations\Car;

class BookingController extends Controller
{
    public function bookingList()
    {

        $bookingtoday = BookingTable::where('status', 'Booked')
            ->orwhereDate('bookingstart', DB::raw('CURDATE()'))
            ->orWhereDate('bookingend', DB::raw('CURDATE()'))
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname")
            )
            ->paginate(10);

        $bookingpending = BookingTable::where('booking.status', 'Pending')
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname")
            )
            ->get();

        $bookingconfirmed = BookingTable::where('status', 'Booked')
            ->where('booking.bookingstart', '>=', \Carbon\Carbon::today())
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname")
            )
            ->get();

        //$statuses = DB::table('cottages')->pluck('status');
        //dd($statuses);

        $rooms = RoomTable::whereIn('status', ['Available', 'Booked'])->get();
        $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])->get();
        $amenities = AmenityTable::whereIn('amenityname', ['Kiddy Pool'])->get();

        return view('receptionist/booking', compact('bookingtoday', 'bookingpending', 'bookingconfirmed', 'rooms', 'cottages', 'amenities'));
    }
    public function events()
    {
        $today = \Carbon\Carbon::today();
        $booking = BookingTable::join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->select(
                'booking.bookingID',
                DB::raw("MAX(CONCAT(guest.firstname, ' ', guest.lastname)) as guestname"), // Aggregate the guest name
                DB::raw("COUNT(roombook.roomID) as rooms_count"),
                DB::raw("COUNT(cottagebook.cottageID) as cottages_count"),
                DB::raw("MAX(booking.guestamount) as guestamount"), // Aggregate guestamount
                DB::raw("MAX(booking.bookingstart) as bookingstart"),
                DB::raw("MAX(booking.bookingend) as bookingend"),
                DB::raw("MAX(booking.status) as status")
            )
            ->groupBy('booking.bookingID')
            ->get();

        $events = [];
        $count = 1;
        foreach ($booking as $bookings) {
            $start = Carbon::parse($bookings->bookingstart);
            $end = Carbon::parse($bookings->bookingend)->addDay();
            $status = $bookings->status;
            $today = Carbon::today();
            $colorIndex = $count;

            if ($status === 'Booked' && $today->between($start, $end->copy()->subDay())) {
                $hue = 210;
                $saturation = 80;
                $lightness = 40 + ($colorIndex * 5);
                $color = "hsl($hue, $saturation%, $lightness%)";
            } else if ($status === 'Booked') {
                $hue = 120;
                $saturation = 90;
                $lightness = 20 + ($colorIndex * 5);
                $color = "hsl($hue, $saturation%, $lightness%)";
            } else if ($status === 'Pending') {
                $hue = 180;
                $saturation = 90;
                $lightness = 50 + ($colorIndex * 5);
                $color = "hsl($hue, $saturation%, $lightness%)";
            } else if ($status === 'Cancelled' || $status === 'Finished') {
                $hue = 10;
                $saturation = 10;
                $lightness = 20 + ($colorIndex * 5);
                $color = "hsl($hue, $saturation%, $lightness%)";
            }


            $hasMessage = '';
            $hasRooms = $bookings->rooms_count > 0;
            $hasCottages = $bookings->cottages_count > 0;
            $hasAmenity = $bookings->amenityID;

            if ($hasRooms && $hasCottages && $hasAmenity) {
                $hasMessage = 'is booking multiple resort services';
            } elseif ($hasRooms) {
                $hasMessage = 'is booking ' . $bookings->rooms_count . ' room' . ($bookings->rooms_count > 1 ? 's' : '');
            } elseif ($hasCottages) {
                $hasMessage = 'is booking ' . $bookings->cottages_count . ' cottage' . ($bookings->cottages_count > 1 ? 's' : '');
            } elseif ($hasAmenity) {
                $hasMessage = 'is booking an amenity';
            }

            $events[] = [
                'id' => $bookings->bookingID,
                'title' => $bookings->guestname . ' ' . $hasMessage,
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'color' => $color,
            ];
            $count++;
        }
        return response()->json($events);
    }

    public function bookingListView()
    {
        $bookings = BookingTable::with([
            'guest',
            'amenity',
            'roomBookings.room',
            'cottageBookings.cottage',
        ])
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->select(
                'booking.*',
                DB::raw('COALESCE(amenities.amenityname, "N/A") as amenityname'),
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname")
            )
            ->orderBy('bookingID', 'desc')
            ->get();

        return view('receptionist.booking_list', compact('bookings'));
    }

    public function createBooking()
    {
        $rooms = RoomTable::where('status', 'Available')->get();
        $cottages = CottageTable::where('status', 'Available')->get();
        $amenities = AmenityTable::where('amenityname', 'Kiddy Pool')->get();

        // Get all bookings with their dates
        $roomBookings = RoomBookTable::select('roomID', 'bookingDate')->get();
        $cottageBookings = CottageBookTable::select('cottageID', 'bookingDate')->get();
        $amenityBookings = BookingTable::whereNotNull('amenityID')
            ->select('amenityID', 'bookingstart', 'bookingend')
            ->get();

        $bookedItems = [
            'rooms'     => $roomBookings,
            'cottages'  => $cottageBookings,
            'amenities' => $amenityBookings,
        ];

        return view('receptionist.create_booking', compact('rooms', 'cottages', 'amenities', 'bookedItems'));
    }

    public function submitBooking(Request $request, OCRService $ocrService)
    {
        $validated = $request->validate([
            'room' => 'nullable|array',
            'room.*' => 'integer|exists:rooms,roomID',
            'cottage' => 'nullable|array',
            'cottage.*' => 'integer|exists:cottages,cottageID',
            'amenity' => 'nullable|array',
            'amenity.*' => 'integer|exists:amenities,amenityID',
            'guestamount' => 'required|integer|min:1',
            'amenity_adult_guest' => 'required|integer|min:0',
            'amenity_child_guest' => 'required|integer|min:0',
            'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'lastname'  => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'contactnum' => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'gender'    => 'nullable|string',
            'birthday'  => ['nullable', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'validID'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'username' => 'nullable|min:5|max:20|unique:users,username',
            'password' => [
                'nullable',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
            ],
            'password_confirmation' => 'nullable|string|same:password',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
        ]);

        if (empty($validated['room']) && empty($validated['cottage']) && empty($validated['amenity'])) {
            return redirect()->back()
                ->withErrors(['selection' => 'Please select at least a room, a cottage, or an amenity.'])
                ->withInput();
        }

        $availabilityError = $this->checkAvailability($validated);
        if ($availabilityError) {
            return redirect()->back()
                ->withErrors(['availability' => $availabilityError])
                ->withInput();
        }

        $path = null; // initialize variable

        if ($request->hasFile('validID')) {
            $file = $request->file('validID');

            // Store the file
            $path = $file->storeAs(
                'temp_valid_ids',
                uniqid() . '.' . $file->getClientOriginalExtension(),
                'public'
            );

            $absolutePath = storage_path('app/public/' . $path);

            // Call OCR service
            $result = $ocrService->verify($absolutePath);

            if (!$result['isValid']) {
                return back()
                    ->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.'])
                    ->with('ocrtext', $result['ocrText']);
            }
            $validated['validID'] = $path;

            // Dump the OCR result
            //dd([
            //    'isValid' => $result['isValid'],
            //    'ocrText' => $result['ocrText'],
            //    'filePath' => $absolutePath,
            // ]);
        } else {
            $validated['validID'] = null;
        }
        $prices = $this->calculatePrices($validated);

        $bookingSessionID = (string) Str::uuid();
        session([
            'booking_data_' . $bookingSessionID => $validated,
            'booking_prices_' . $bookingSessionID => $prices,
        ]);

        return redirect()->route('receptionist.booking_receipt', ['sessionID' => $bookingSessionID]);
    }



    public function receiptBooking($sessionID)
    {
        $data = session('booking_data_' . $sessionID);
        $prices = session('booking_prices_' . $sessionID);

        if (!$data || !$prices) {
            return redirect()->route('receptionist.create_booking')
                ->with('error', 'Booking session expired.');
        }

        $selectedRooms = RoomTable::whereIn('roomID', data_get($data, 'room', []))->get();
        $selectedCottages = CottageTable::whereIn('cottageID', data_get($data, 'cottage', []))->get();
        $selectedAmenities = AmenityTable::whereIn('amenityID', data_get($data, 'amenity', []))->get();
        $discounts = DiscountTable::where('status', 'Available')->get();

        return view('receptionist/receipt_booking', [
            'booking' => $data,
            'sessionID' => $sessionID,
            'room' => $selectedRooms,
            'cottage' => $selectedCottages,
            'amenity' => $selectedAmenities,
            'discount' => $discounts,
            'roomprice' => $prices['roomprice'],
            'cottageprice' => $prices['cottageprice'],
            'adultprice' => $prices['adultprice'],
            'childprice' => $prices['childprice'],
            'amenityprice' => $prices['amenityprice'],
            'totalprice' => $prices['totalprice'],
        ]);
    }

    private function parseDate($dateString)
    {
        try {
            $formats = ['m/d/Y', 'Y-m-d', 'd/m/Y', 'm-d-Y'];
            foreach ($formats as $format) {
                $date = Carbon::createFromFormat($format, $dateString);
                if ($date) {
                    return $date; // Return Carbon object instead of formatted string
                }
            }
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }
    public function confirmBooking(Request $request, $sessionID)
    {
        $data = session('booking_data_' . $sessionID);
        $prices = session('booking_prices_' . $sessionID);

        if (!$data || !$prices) {
            return redirect()->route('receptionist.create_booking')
                ->with('error', 'Booking session has expired. Please start again.');
        }

        $birthday = null;
        if (!empty($data['birthday'])) {
            try {
                $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $birthday = Carbon::createFromFormat('Y-m-d', $data['birthday'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $birthday = null;
                }
            }
        }

        $validated = $request->validate([
            'cashamount'   => 'required_if:payment,cash|nullable|numeric|min:0',
            'discount'     => 'nullable',
            'payment_type' => 'required|in:full,downpayment',
            'payment'      => 'required|in:cash,gcash',
        ]);

        try {
            $validIDPath = $data['validID'] ?? null;
            $defaultAvatar = 'images/profile.jpg';

            $userID = null;

            if (!empty($data['username']) && !empty($data['password'])) {
                $user = UserTable::firstOrCreate(
                    ['username' => $data['username']],
                    ['password' => bcrypt($data['password'])]
                );
                $userID = $user->userID;
            }
            $avatarPath = !empty($data['avatar']) ? $data['avatar'] : $defaultAvatar;

            $guest = GuestTable::firstOrCreate(
                [
                    'firstname' => $data['firstname'],
                    'lastname'  => $data['lastname'],
                ],
                [
                    'mobilenum' => $data['contactnum'] ?? null,
                    'email'     => $data['email'] ?? null,
                    'gender'    => $data['gender'] ?? null,
                    'birthday'  => $birthday ?? null,
                    'validID'   => $validIDPath,
                    'role'      => 'guest',
                    'avatar'    => $avatarPath,
                    'userID'    => $userID,
                ]
            );

            if ($guest && $userID && $guest->userID === null) {
                $guest->update(['userID' => $userID]);
            }

            if (!$guest) {
                return redirect()->back()->with('error', 'Unable to process guest information.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing guest information: ' . $e->getMessage());
        }

        $originalAmount = (float) str_replace(',', '', $prices['totalprice']);
        $discountAmount = 0;

        if (!empty($validated['discount'])) {
            $discount = DB::table('discount')->where('discountID', $validated['discount'])->first();
            if ($discount && isset($discount->amount)) {
                $discountAmount = $originalAmount * (float) $discount->amount;
            }
        }

        $discountedAmount = $originalAmount - $discountAmount;
        $requiredAmount   = $validated['payment_type'] === 'downpayment'
            ? $discountedAmount * 0.5
            : $discountedAmount;

        $amountPaid = $validated['payment'] === 'cash'
            ? ($validated['cashamount'] ?? 0)
            : $requiredAmount;

        $change = ($validated['payment'] === 'cash' && $amountPaid > $requiredAmount)
            ? $amountPaid - $requiredAmount
            : 0;

        if ($validated['payment'] === 'cash' && $amountPaid < $requiredAmount) {
            return redirect()->back()->with('error', 'Insufficient payment. Required: ₱' . number_format($requiredAmount, 2));
        }

        $remainingBalance = $discountedAmount - $amountPaid;

        try {
            DB::beginTransaction();

            $checkinDate  = $this->parseDate($data['checkin']);
            $checkoutDate = $this->parseDate($data['checkout']);

            if (!$checkinDate || !$checkoutDate) {
                throw new \Exception('Invalid date format');
            }

            $status = 'Booked';
            if ($checkinDate->lessThanOrEqualTo(Carbon::now())) {
                $status = 'Ongoing';
            }

            $booking = BookingTable::create([
                'bookingcreated' => Carbon::now(),
                'bookingstart'   => $checkinDate,
                'bookingend'     => $checkoutDate,
                'guestamount'    => (int) $data['guestamount'],
                'childguest'     => (int) ($data['amenity_child_guest'] ?? 0),
                'adultguest'     => (int) ($data['amenity_adult_guest'] ?? 0),
                'totalprice'     => $originalAmount,
                'amenityID'      => !empty($data['amenity']) ? (int) $data['amenity'][0] : null,
                'status'         => $status,
                'guestID'        => $guest->guestID,
            ]);

            if ($status === 'Ongoing') {
                CheckTable::create([
                    'date'      => Carbon::now(),
                    'status'    => 'Checked In',
                    'guestID'   => $guest->guestID,
                    'bookingID' => $booking->bookingID,
                ]);
            }

            if (!empty($data['room'])) {
                foreach ($data['room'] as $roomID) {
                    RoomBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'roomID'    => (int) $roomID,
                        'bookingDate' => Carbon::now(),
                    ]);
                }
            }

            if (!empty($data['cottage'])) {
                foreach ($data['cottage'] as $cottageID) {
                    CottageBookTable::create([
                        'bookingID'  => $booking->bookingID,
                        'cottageID'  => (int) $cottageID,
                        'bookingDate' => Carbon::now(),
                    ]);
                }
            }

            $billing = BillingTable::create([
                'totalamount' => $remainingBalance,
                'datebilled'  => Carbon::now(),
                'status'      => $remainingBalance <= 0 ? 'Paid' : ($amountPaid > 0 ? 'Partial' : 'Unpaid'),
                'bookingID'   => $booking->bookingID,
                'orderID'     => null,
                'amenityID'   => null,
                'chargeID'    => null,
                'discountID'  => !empty($validated['discount']) && $validated['discount'] != '0'
                    ? (int) $validated['discount']
                    : null,
                'guestID'     => $guest->guestID,
            ]);

            PaymentTable::create([
                'billingID'   => $billing->billingID,
                'guestID'     => $guest->guestID,
                'totaltender' => $amountPaid,
                'totalchange' => $change,
                'datepayment' => Carbon::now(),
            ]);

            DB::commit();

            session()->forget(['booking_data_' . $sessionID, 'booking_prices_' . $sessionID]);

            return redirect()->route('receptionist.booking')
                ->with('success', 'Booking confirmed successfully!' . ($change > 0 ? ' Change: ₱' . number_format($change, 2) : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    // Helper methods to make code more readable and maintainable

    private function checkAvailability($validated)
    {
        $dateRange = [$validated['checkin'], $validated['checkout']];

        // Check room availability
        if (!empty($validated['room'])) {
            $roomConflict = RoomBookTable::whereIn('roombook.roomID', $validated['room'])
                ->join('booking', 'roombook.bookingID', '=', 'booking.bookingID')
                ->where(function ($query) use ($dateRange, $validated) {
                    $query->whereBetween('booking.bookingstart', $dateRange)
                        ->orWhereBetween('booking.bookingend', $dateRange)
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('booking.bookingstart', '<=', $validated['checkin'])
                                ->where('booking.bookingend', '>=', $validated['checkout']);
                        });
                })
                ->exists();

            if ($roomConflict) {
                return 'One or more rooms are already booked for the selected dates.';
            }
        }

        // Check cottage availability
        if (!empty($validated['cottage'])) {
            $cottageConflict = CottageBookTable::whereIn('cottagebook.cottageID', $validated['cottage'])
                ->join('booking', 'cottagebook.bookingID', '=', 'booking.bookingID')
                ->where(function ($query) use ($dateRange, $validated) {
                    $query->whereBetween('booking.bookingstart', $dateRange)
                        ->orWhereBetween('booking.bookingend', $dateRange)
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('booking.bookingstart', '<=', $validated['checkin'])
                                ->where('booking.bookingend', '>=', $validated['checkout']);
                        });
                })
                ->exists();

            if ($cottageConflict) {
                return 'One or more cottages are already booked for the selected dates.';
            }
        }

        // Check amenity availability
        if (!empty($validated['amenity'])) {
            $amenityConflict = BookingTable::whereIn('amenityID', $validated['amenity'])
                ->where(function ($query) use ($dateRange, $validated) {
                    $query->whereBetween('bookingstart', $dateRange)
                        ->orWhereBetween('bookingend', $dateRange)
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('bookingstart', '<=', $validated['checkin'])
                                ->where('bookingend', '>=', $validated['checkout']);
                        });
                })
                ->exists();

            if ($amenityConflict) {
                return 'One or more amenities are already booked for the selected dates.';
            }
        }

        return null; // No conflicts
    }

    private function calculatePrices($validated)
    {
        $roomprice = !empty($validated['room']) ?
            RoomTable::whereIn('roomID', $validated['room'])->sum('price') : 0;

        $cottageprice = !empty($validated['cottage']) ?
            CottageTable::whereIn('cottageID', $validated['cottage'])->sum('price') : 0;

        $amenityprice = 0;
        $adultCost = 0;
        $childCost = 0;

        if (!empty($validated['amenity'])) {
            $amenityID = $validated['amenity'][0];
            $amenity = AmenityTable::where('amenityID', $amenityID)->first();
            if ($amenity) {
                $adultCount = $validated['amenity_adult_guest'] ?? 0;
                $childCount = $validated['amenity_child_guest'] ?? 0;
                $adultCost = $adultCount * $amenity->adultprice;
                $childCost = $childCount * $amenity->childprice;
                $amenityprice = $adultCost + $childCost;
            }
        }

        $totalprice = $roomprice + $cottageprice + $amenityprice;

        return [
            'roomprice' => $roomprice,
            'cottageprice' => $cottageprice,
            'adultprice' => $adultCost,
            'childprice' => $childCost,
            'amenityprice' => $amenityprice,
            'totalprice' => number_format($totalprice, 2, '.', ''),
        ];
    }

    private function findOrCreateGuest($firstname, $lastname)
    {
        return GuestTable::firstOrCreate(
            ['firstname' => $firstname, 'lastname' => $lastname],
            ['firstname' => $firstname, 'lastname' => $lastname]
        );
    }


    private function calculateDiscountAmount($discountId, $originalAmount)
    {
        if (empty($discountId) || $discountId === '0') {
            return 0;
        }

        try {
            $discountRecord = DiscountTable::find($discountId);
            if (!$discountRecord) {
                return 0;
            }

            return ($originalAmount * $discountRecord->amount) / 100;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function approveBooking($bookingID)
    {
        try {
            $booking = BookingTable::where('bookingID', $bookingID)->firstOrFail();
            $booking->status = 'Booked';
            $booking->save();

            // Fetch the user who made the booking
            $user = User::find($booking->guest->userID); // adjust field if different

            if ($user) {
                // Send FCM + database notification
                $user->notify(new OrderUpdateNotification($booking));
            } else {
                Log::warning("Booking {$bookingID} has no associated user to notify");
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Error approving booking: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking'
            ], 500);
        }
    }
    public function declineBooking($bookingID)
    {
        try {
            $booking = BookingTable::where('bookingID', $bookingID)->firstOrFail();
            $booking->status = 'Declined';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking declined successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Error declining booking: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking'
            ], 500);
        }
    }

    public function cancelBooking($bookingID)
    {
        try {
            $booking = BookingTable::where('bookingID', $bookingID)->firstOrFail();
            $booking->status = 'Cancelled';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Error cancelling booking: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking'
            ], 500);
        }
    }


    protected function autoCancelExpiredBookings()
    {
        $today = Carbon::today();

        $expiredBookings = BookingTable::where('status', 'Pending')
            ->whereDate('bookingstart', '<', $today)
            ->get();

        $cancelledBookings = [];

        foreach ($expiredBookings as $booking) {
            $booking->status = 'Cancelled';
            $booking->save();
            $cancelledBookings[] = $booking;
        }

        return $cancelledBookings; // Return an array of automatically cancelled booking
    }

    protected function notifyDueBooking()
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        // Get all bookings where the check-in is yesterday or today, and still pending
        $dueBookings = BookingTable::where('status', 'Pending')
            ->whereDate('bookingstart', '>=', $yesterday)
            ->whereDate('bookingstart', '<=', $today)
            ->get();

        $count = $dueBookings->count();

        return [
            'bookings' => $dueBookings,
            'count' => $count,
        ];
    }

    public function bookingDashboard()
    {
        $cancelledBookings = $this->autoCancelExpiredBookings();
        $countCancelledBookings = count($cancelledBookings);
        $dueBookingData = $this->notifyDueBooking();
        $PendingBookings = BookingTable::where('status', 'Pending')->count();

        return view('receptionist/dashboard', [
            'cancelledBookings' => $cancelledBookings,
            'cancelledBookingsCount' => $countCancelledBookings,
            'dueBookings' => $dueBookingData['bookings'],
            'dueBookingCount' => $dueBookingData['count'],
            'pendingBookingCount' => $PendingBookings,
        ]);
    }

    // public function viewBooking($bookingID){
    //     $booking = BookingTable::where('bookingID', $bookingID)
    //         ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
    //         ->select('booking.*', 'guest.firstname', 'guest.lastname')
    //         ->first();

    //     if (!$booking) {
    //         return redirect()->back()->with('error', 'Booking not found');
    //     }

    //     $rooms = RoomTable::whereIn('status', ['Available', 'Booked'])->get();
    //     $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])->get();
    //     $amenities = AmenityTable::where('amenityname', 'Kiddy Pool')->get();

    //     $bookingData = (object) [
    //         'bookingID' => $booking->bookingID,
    //         'firstname' => $booking->firstname,
    //         'lastname' => $booking->lastname,
    //         'guestamount' => $booking->guestamount,
    //         'checkin' => $booking->bookingstart,
    //         'checkout' => $booking->bookingend,
    //         'room' => $this->getSelectedRooms($bookingID),
    //         'cottage' => $this->getSelectedCottages($bookingID),
    //         'amenity' => $booking->amenityID,
    //     ];

    //     return view('receptionist.view_booking', compact('rooms', 'cottages', 'amenities', 'bookingData'));
    // }
    public function viewBooking($bookingID)
    {
        // Load booking with relationships
        $booking = BookingTable::with([
            'Guest',
            'roomBookings.Room',
            'cottageBookings.Cottage',
            'Amenity',
            'billing.Payments',
            'menuBookings.Menu'
        ])->find($bookingID);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Fetch all rooms, cottages, amenities
        $rooms = RoomTable::whereIn('status', ['Available', 'Booked'])->get();
        $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])->get();
        $amenities = AmenityTable::all();

        // Prepare structured booking data for view
        $bookingData = (object) [
            'bookingID' => $booking->bookingID,
            'firstname' => $booking->Guest->firstname ?? '',
            'lastname' => $booking->Guest->lastname ?? '',
            'guestamount' => $booking->guestamount,
            'adultguest' => $booking->adultguest,
            'childguest' => $booking->childguest,
            'checkin' => $booking->bookingstart,
            'checkout' => $booking->bookingend,
            'rooms' => $booking->roomBookings->pluck('roomID')->toArray(),
            'cottages' => $booking->cottageBookings->pluck('cottageID')->toArray(),
            'amenities' => $booking->Amenity ? [$booking->Amenity->amenityID] : [],
            'billing' => $booking->billing,
            'payments' => $booking->billing ? $booking->billing->payments : [],
            'menuOrders' => $booking->menuBookings ?? []
        ];

        // Log the booking data for debugging
        Log::info('Booking Data for viewBooking:', (array) $bookingData);

        return view('receptionist.view_booking', compact('rooms', 'cottages', 'amenities', 'bookingData'));
    }

    private function getSelectedRooms($bookingID)
    {
        return DB::table('roombook')
            ->where('bookingID', $bookingID)
            ->pluck('roomID')
            ->toArray();
    }

    private function getSelectedCottages($bookingID)
    {
        return DB::table('cottagebook')
            ->where('bookingID', $bookingID)
            ->pluck('cottageID')
            ->toArray();
    }
    public function check(Request $request)
    {
        $checkin = $request->query('checkin');
        $checkout = $request->query('checkout');

        // Rooms with overlapping bookings
        $bookedRoomIDs = RoomBookTable::where(function ($q) use ($checkin, $checkout) {
            $q->where('checkin', '<=', $checkout)
                ->where('checkout', '>=', $checkin);
        })
            ->pluck('roomID')
            ->toArray();

        // Cottages with overlapping bookings
        $bookedCottageIDs = CottageBookTable::where(function ($q) use ($checkin, $checkout) {
            $q->where('checkin', '<=', $checkout)
                ->where('checkout', '>=', $checkin);
        })
            ->pluck('cottageID')
            ->toArray();

        return response()->json([
            'bookedRooms' => $bookedRoomIDs,
            'bookedCottages' => $bookedCottageIDs,
        ]);
    }

    public function updateBooking(Request $request, $bookingID)
    {
        try {
            // Validate input
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'guestamount' => 'required|int|min:1',
                'amenity_adult_guest' => 'required|int|min:1',
                'amenity_child_guest' => 'required|int|min:0',
                'checkin' => 'required|date',
                'checkout' => 'required|date|after:checkin',
                'room' => 'nullable|array',
                'room.*' => 'exists:rooms,roomID',
                'cottage' => 'nullable|array',
                'cottage.*' => 'exists:cottages,cottageID',
                'amenity' => 'nullable|exists:amenities,amenityID',
            ]);

            $rooms = $request->input('room', []);
            $cottages = $request->input('cottage', []);
            $amenity = $request->input('amenity');

            // Ensure at least one of the three is selected
            if (
                empty($request->input('room')) &&
                empty($request->input('cottage')) &&
                empty($request->input('amenity'))
            ) {
                return redirect()->back()->withInput()->with('error', 'Please select at least one: Room, Cottage, or Amenity.');
            }

            $booking = BookingTable::find($bookingID);
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found.');
            }

            $checkinFormatted = Carbon::createFromFormat('m/d/Y', $request->input('checkin'))->format('Y-m-d');
            $checkoutFormatted = Carbon::createFromFormat('m/d/Y', $request->input('checkout'))->format('Y-m-d');

            $original = $booking->getOriginal();
            $originalGuest = GuestTable::find($booking->guestID);
            $isChanged = (
                !$originalGuest ||
                $originalGuest->firstname !== $request->firstname ||
                $originalGuest->lastname !== $request->lastname ||
                $original['bookingstart'] !== $checkinFormatted ||
                $original['bookingend'] !== $checkoutFormatted ||
                $original['guestamount'] != $request->guestamount ||
                $original['adultguest'] != $request->amenity_adult_guest ||
                $original['childguest'] != $request->amenity_child_guest ||
                $original['amenityID'] != $amenity
            );

            // Compare room and cottage assignments
            $currentRooms = DB::table('roombook')->where('bookingID', $bookingID)->pluck('roomID')->toArray();
            $currentCottages = DB::table('cottagebook')->where('bookingID', $bookingID)->pluck('cottageID')->toArray();

            if (
                array_diff($currentRooms, $rooms) ||
                array_diff($rooms, $currentRooms) ||
                array_diff($currentCottages, $cottages) ||
                array_diff($cottages, $currentCottages)
            ) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return redirect()->route('view.booking', $bookingID)->with('info', 'No changes detected.');
            }

            $guestID = GuestTable::where('firstname', $request->firstname)
                ->where('lastname', $request->lastname)
                ->first();

            // Update main booking data
            $booking->update([
                'guestID' => $guestID->guestID,
                'guestamount' => $request->guestamount,
                'adultguest' => $request->amenity_adult_guest,
                'childguest' => $request->amenity_child_guest,
                'bookingstart' => $checkinFormatted,
                'bookingend' => $checkoutFormatted,
                'amenityID' => $amenity,
            ]);

            // Sync Rooms
            DB::table('roombook')->where('bookingID', $bookingID)->delete();
            foreach ($rooms as $roomID) {
                DB::table('roombook')->insert(['bookingID' => $bookingID, 'roomID' => $roomID]);
            }

            // Sync Cottages
            DB::table('cottagebook')->where('bookingID', $bookingID)->delete();
            foreach ($cottages as $cottageID) {
                DB::table('cottagebook')->insert(['bookingID' => $bookingID, 'cottageID' => $cottageID]);
            }

            // Billing Calculation
            $roomTotal = DB::table('rooms')->whereIn('roomID', $rooms)->sum('price');
            $cottageTotal = DB::table('cottages')->whereIn('cottageID', $cottages)->sum('price');
            $amenityTotal = $amenity ? DB::table('amenities')->where('amenityID', $amenity)->value('price') : 0;
            $totalAmount = $roomTotal + $cottageTotal + $amenityTotal;

            DB::table('billing')->updateOrInsert(
                ['bookingID' => $bookingID],
                [
                    'totalamount'  => $totalAmount,
                    'datebilled'   => now(),
                    'status'       => 'Partial',
                    'bookingID'    => $bookingID,
                    'amenityID'    => $amenity,
                    'discountID'   => null,       // Set this if applying a discount reference
                    'guestID'      => $guestID->guestID,
                ]
            );

            return redirect()->route('receptionist.booking')->with('success', 'Booking and billing updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Book update failed' . $e->getMessage());
        }
    }

    public function viewCheckIn()
    {
        $today = Carbon::today()->toDateString();

        $checkin = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Booked')
            ->whereDate('booking.bookingstart', '<=', $today)
            ->get();

        $checkout = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Ongoing')
            ->get();

        return view('receptionist.check-in-out', compact('checkin', 'checkout', 'today'));
    }

    public function checkInBooking(Request $request, $bookingID)
    {
        $today = Carbon::now()->format('m/d/Y');
        $todayDB = Carbon::now()->format('Y-m-d');
        $booking = BookingTable::where('bookingID', $bookingID)
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"),
                'amenities.amenityname',
                'amenities.adultprice',
                'amenities.childprice'
            )
            ->first();

        $billing = BillingTable::where('bookingID', $bookingID)
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select('billing.*', 'discount.amount')
            ->first();

        $payment = PaymentTable::where('billingID', $billing->billingID)->first();

        $room = RoomBookTable::where('bookingID', $bookingID)
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->select('roombook.*', 'rooms.roomnum', 'rooms.price')
            ->get();

        $cottage = CottageBookTable::where('bookingID', $bookingID)
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->select('cottagebook.*', 'cottages.cottagename', 'cottages.price')
            ->get();

        if ($request->isMethod('get')) {
            return view('receptionist.checkInBooking', compact('booking', 'today', 'room', 'cottage', 'billing', 'payment'));
        }

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'payment' => 'required|in:cash,gcash',
                'amount_paid' => 'required_if:payment,cash|numeric|min:0',
            ]);
            try {
                // Calculate remaining balance
                $totalPaid = $payment->totaltender;
                $remainingBalance = $billing->total_amount;

                // Validate payment amount for cash
                if ($validated['payment'] === 'cash' && $validated['amount_paid'] < $remainingBalance) {
                    return redirect()->back()->with('error', 'Insufficient payment amount. Remaining balance: ₱' . number_format($remainingBalance, 2));
                }

                $change = $validated['payment'] === 'cash' ?
                    max(0, $validated['amount_paid'] - $remainingBalance) : 0;

                $today = Carbon::now();

                // Create payment record for this check-in payment
                $payment = PaymentTable::create([
                    'totaltender' => $validated['amount_paid'],
                    'totalchange' => $change,
                    'datepayment' => $todayDB,
                    'guestID' => $booking->guestID,
                    'billingID' => $billing->billingID,
                ]);

                // Check if billing is fully paid
                $newTotalPaid = PaymentTable::where('billingID', $billing->billingID)->first();
                $newTotalPaidAmount = $newTotalPaid ? $newTotalPaid->totaltender : 0;

                $billingtotal = (int) $billing->totalamount - (int) $validated['payment'];
                $billing->totalamount = $billingtotal;

                if ($billing->totalamount < 0) {
                    $billing->totalamount = 0;
                }

                $billing->save();

                if ((int) $newTotalPaidAmount + (int) $validated['payment'] >= $billing->totalamount) {
                    $billing->status = 'Paid';
                    $billing->save();
                }

                // Update booking status
                $booking->status = 'Ongoing';
                $booking->save();

                // Create check record
                CheckTable::create([
                    'date' => $todayDB,
                    'status' => 'Checked In',
                    'guestID' => $booking->guestID,
                    'bookingID' => $bookingID,
                ]);

                // Update room status
                foreach ($booking->roomBookings as $roomBook) {
                    RoomTable::where('roomID', $roomBook->roomID, $roomBook->bookingDate)->update(['status' => 'Booked']);
                }

                // Update cottage status
                foreach ($booking->cottageBookings as $cottageBook) {
                    CottageTable::where('cottageID', $cottageBook->cottageID, $cottageBook->bookingDate)->update(['status' => 'Booked']);
                }

                if ($booking->amenityID) {
                    AmenityTable::where('amenityID', $booking->amenityID)
                        ->update(['status' => 'Booked']);
                }

                return redirect()->route('receptionist.view_check')->with('success', 'Booking successfully checked in.');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Failed to check-in booking' . $e->getMessage());
            }
        }
    }

    public function checkOutBooking(Request $request, $bookingID)
    {
        $today = Carbon::now()->format('m/d/Y');

        $booking = BookingTable::where('bookingID', $bookingID)
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"),
                'amenities.amenityname',
                'amenities.adultprice',
                'amenities.childprice'
            )
            ->first();

        $billing = BillingTable::where('bookingID', $bookingID)
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select('billing.*', 'discount.amount')
            ->first();

        $payment = PaymentTable::where('billingID', $billing->billingID)->first();

        $room = RoomBookTable::where('bookingID', $bookingID)
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->select('roombook.*', 'rooms.roomnum', 'rooms.price')
            ->get();

        $cottage = CottageBookTable::where('bookingID', $bookingID)
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->select('cottagebook.*', 'cottages.cottagename', 'cottages.price')
            ->get();

        if ($request->isMethod('get')) {
            return view('receptionist.checkOutBooking', compact('booking', 'today', 'room', 'cottage', 'billing', 'payment'));
        }
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'addcharge' => 'sometimes|numeric',
                'chargedesc' => 'sometimes|string',
                'payment' => 'sometimes|in:cash,gcash',
                'amount_paid' => 'required_if:payment,cash|numeric|min:0',
            ]);
            try {
                // Calculate remaining balance
                $totalPaid = $payment->totaltender;
                $remainingBalance = $billing->total_amount;

                // Validate payment amount for cash
                if ($validated['payment'] === 'cash' && $validated['amount_paid'] < $remainingBalance) {
                    return redirect()->back()->with('error', 'Insufficient payment amount. Remaining balance: ₱' . number_format($remainingBalance, 2));
                }

                $change = $validated['payment'] === 'cash' ?
                    max(0, $validated['amount_paid'] - $remainingBalance) : 0;

                $today = Carbon::now();

                // Create payment record for this check-in payment
                $payment = PaymentTable::create([
                    'totaltender' => $validated['amount_paid'],
                    'totalchange' => $change,
                    'datepayment' => $today,
                    'guestID' => $booking->guestID,
                    'billingID' => $billing->billingID,
                ]);

                $charge = ChargeTable::create([
                    'amount' => $validated['addcharge'],
                    'chargedescription' => $validated['chargedesc'],
                ]);

                // Check if billing is fully paid
                $newTotalPaid = PaymentTable::where('billingID', $billing->billingID)->first();
                $newTotalPaidAmount = $newTotalPaid ? $newTotalPaid->totaltender : 0;
                if ($newTotalPaidAmount >= $billing->totalamount) {
                    $billing->status = 'Paid';
                    $billing->update(['chargeID' => $charge->chargeID]);
                    $billing->save();
                }

                // Update booking status
                $booking->status = 'Finished';
                $booking->save();

                // Create check record
                CheckTable::create([
                    'date' => $today,
                    'status' => 'Checked Out',
                    'guestID' => $booking->guestID,
                    'bookingID' => $bookingID,
                ]);

                // Update room status
                foreach ($booking->roomBookings as $roomBook) {
                    RoomTable::where('roomID', $roomBook->roomID, $roomBook->bookingDate)->update(['status' => 'Available']);
                }

                // Update cottage status
                foreach ($booking->cottageBookings as $cottageBook) {
                    CottageTable::where('cottageID', $cottageBook->cottageI, $cottageBook->bookingDate)->update(['status' => 'Available']);
                }

                if ($booking->amenityID) {
                    AmenityTable::where('amenityID', $booking->amenityID)
                        ->update(['status' => 'Available']);
                }

                return redirect()->route('receptionist.view_check')->with('success', 'Booking successfully checked out.');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Failed to check-out booking' . $e->getMessage());
            }
        }
    }

    public function edit($bookingID)
    {
        // Load booking with nested relationships
        $booking = BookingTable::with([
            'Guest',
            'roomBookings.Room',
            'cottageBookings.Cottage',
            'Amenity',
            'menuBookings.Menu',
            'billing.Payments'
        ])->findOrFail($bookingID);

        // Fetch all rooms, cottages, amenities for selection
        $rooms = RoomTable::whereIn('status', ['Available', 'Booked'])->get();
        $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])->get();
        $amenities = AmenityTable::all();

        // Prepare data for form pre-filling
        $bookingData = (object) [
            'bookingID' => $booking->bookingID,
            'firstname' => $booking->Guest->firstname ?? '',
            'lastname' => $booking->Guest->lastname ?? '',
            'guestamount' => $booking->guestamount,
            'adultguest' => $booking->adultguest,
            'childguest' => $booking->childguest,
            'checkin' => $booking->bookingstart,
            'checkout' => $booking->bookingend,
            'rooms' => $booking->roomBookings->pluck('roomID')->toArray(),
            'cottages' => $booking->cottageBookings->pluck('cottageID')->toArray(),
            'amenities' => $booking->Amenity ? [$booking->Amenity->amenityID] : [],
        ];

        return view('receptionist.edit_booking', compact('rooms', 'cottages', 'amenities', 'bookingData'));
    }

    public function update(Request $request, $bookingID)
    {
        $booking = BookingTable::with(['Guest'])->findOrFail($bookingID);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'guestamount' => 'required|integer|min:1',
            'adultguest' => 'required|integer|min:0',
            'childguest' => 'required|integer|min:0',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
            'room' => 'nullable|array',
            'room.*' => 'integer|exists:rooms,roomID',
            'cottage' => 'nullable|array',
            'cottage.*' => 'integer|exists:cottages,cottageID',
            'amenity' => 'nullable|integer|exists:amenities,amenityID',
        ]);

        DB::transaction(function () use ($request, $booking) {
            // Update guest info
            if ($booking->Guest) {
                $booking->Guest->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                ]);
            }

            // Update booking info
            $booking->update([
                'guestamount' => $request->guestamount,
                'adultguest' => $request->adultguest,
                'childguest' => $request->childguest,
                'bookingstart' => $request->checkin,
                'bookingend' => $request->checkout,
                'amenityID' => $request->amenity ?? null,
            ]);

            // Sync room bookings
            $booking->roomBookings()->delete(); // remove old
            if ($request->room) {
                foreach ($request->room as $roomID) {
                    $booking->roomBookings()->create(['roomID' => $roomID]);
                }
            }

            // Sync cottage bookings
            $booking->cottageBookings()->delete(); // remove old
            if ($request->cottage) {
                foreach ($request->cottage as $cottageID) {
                    $booking->cottageBookings()->create(['cottageID' => $cottageID]);
                }
            }
        });

        return redirect()->route('receptionist.booking.edit', $bookingID)
            ->with('success', 'Booking updated successfully.');
    }


    public function checkEvents()
    {
        $today = \Carbon\Carbon::today();

        $booking = BookingTable::join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->select(
                'booking.bookingID',
                DB::raw("MAX(CONCAT(guest.firstname, ' ', guest.lastname)) as guestname"),
                DB::raw("COUNT(roombook.roomID) as rooms_count"),
                DB::raw("COUNT(cottagebook.cottageID) as cottages_count"),
                DB::raw("MAX(booking.guestamount) as guestamount"),
                DB::raw("MAX(booking.bookingstart) as bookingstart"),
                DB::raw("MAX(booking.bookingend) as bookingend"),
                DB::raw("MAX(booking.status) as status")
            )
            ->groupBy('booking.bookingID')
            ->get();

        $events = [];

        foreach ($booking as $book) {
            $start = \Carbon\Carbon::parse($book->bookingstart);
            $end = \Carbon\Carbon::parse($book->bookingend)->addDay();
            $status = $book->status;

            // Color logic based on today's date
            if ($start->isToday()) {
                $color = '#FFA500';
            } elseif ($end->isToday()) {
                $color = '#FF6347';
            } elseif ($today->between($start, $end)) {
                $color = '#4CAF50';
            } elseif ($start->isFuture()) {
                $color = '#1E90FF';
            } else {
                $color = '#D3D3D3';
            }

            $events[] = [
                'id'    => $book->bookingID,
                'title' => $book->guestname,
                'start' => $start->format('Y-m-d'),
                'end'   => $end->format('Y-m-d'),
                'color' => $color,
            ];
        }

        return response()->json($events);
    }

    public function walkinBooking(Request $request, OCRService $ocrService)
    {
        // Fetch available items
        $rooms = RoomTable::where('status', 'Available')->get();
        $cottages = CottageTable::where('status', 'Available')->get();
        $amenities = AmenityTable::where('amenityname', 'Kiddy Pool')->get();

        // Get all bookings with their dates
        $roomBookings = RoomBookTable::select('roomID', 'bookingDate')->get();
        $cottageBookings = CottageBookTable::select('cottageID', 'bookingDate')->get();
        $amenityBookings = BookingTable::whereNotNull('amenityID')
            ->select('amenityID', 'bookingstart', 'bookingend')
            ->get();

        $bookedItems = [
            'rooms'     => $roomBookings,
            'cottages'  => $cottageBookings,
            'amenities' => $amenityBookings,
        ];

        // GET request → Show booking form
        if ($request->isMethod('get')) {
            return view('receptionist.walk-booking', compact('rooms', 'cottages', 'amenities', 'bookedItems'));
        }

        // POST request → Process booking
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'room' => 'nullable|array',
                'room.*' => 'integer|exists:rooms,roomID',

                'cottage' => 'nullable|array',
                'cottage.*' => 'integer|exists:cottages,cottageID',

                'amenity' => 'nullable|array',
                'amenity.*' => 'integer|exists:amenities,amenityID',

                'guestamount' => 'required|integer|min:1',
                'amenity_adult_guest' => 'required|integer|min:0',
                'amenity_child_guest' => 'required|integer|min:0',

                'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
                'lastname'  => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
                'contactnum' => 'nullable|string|max:20',
                'email'     => 'nullable|email|max:255',
                'gender'    => 'nullable|string|in:Male,Female,Other',
                'birthday'  => 'nullable|date',
                'validID'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

                'username' => 'nullable|string|min:5|max:20|unique:users,username',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ],
                'password_confirmation' => 'nullable|string|same:password',

                'checkin' => 'required|date',
                'checkout' => 'required|date|after_or_equal:checkin',
            ]);

            // -------------------------------
            // Handle Valid ID + OCR checking
            // -------------------------------
            $path = null; // initialize
            if ($request->hasFile('validID')) {
                $file = $request->file('validID');

                // Store temporarily
                $path = $file->storeAs(
                    'temp_valid_ids',
                    uniqid() . '.' . $file->getClientOriginalExtension(),
                    'public'
                );

                $absolutePath = storage_path('app/public/' . $path);

                // Run OCR service
                $result = $ocrService->verify($absolutePath);

                // If OCR fails validation → reject booking
                if (!$result['isValid']) {
                    return back()
                        ->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.'])
                        ->with('ocrtext', $result['ocrText']);
                }

                // Passed OCR validation
                $validated['validID'] = $path;
            } else {
                $validated['validID'] = null;
            }

            // Require at least 1 booking item
            if (empty($validated['room']) && empty($validated['cottage']) && empty($validated['amenity'])) {
                return redirect()->back()
                    ->with('error', 'Please select at least a room, a cottage, or an amenity.')
                    ->withInput();
            }

            // Check availability
            $availabilityError = $this->checkAvailability($validated);
            if ($availabilityError) {
                return redirect()->back()->with('error', $availabilityError)->withInput();
            }

            // Calculate prices
            $prices = $this->calculatePrices($validated);

            // Add extra fields
            $validated['password'] = bcrypt($validated['password']);
            $validated['booking_type'] = 'Walk-In';

            // Generate unique session ID
            $bookingSessionID = (string) Str::uuid();

            // Store in session
            session([
                'booking_data_' . $bookingSessionID => $validated,
                'booking_prices_' . $bookingSessionID => $prices,
            ]);

            // Redirect to receipt page
            return redirect()->route('receptionist.booking_receipt', ['sessionID' => $bookingSessionID]);
        }
    }


    public function walkInList()
    {
        $bookings = BookingTable::with([
            'guest',
            'amenity',
            'roomBookings.room',
            'cottageBookings.cottage',
        ])
            ->where('booking.status', 'Ongoing')
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->select(
                'booking.*',
                DB::raw('COALESCE(amenities.amenityname, "N/A") as amenityname'),
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname")
            )
            ->orderBy('bookingID', 'desc')
            ->paginate(10);

        return view('receptionist.walkin_guest_list', compact('bookings'));
    }
}
