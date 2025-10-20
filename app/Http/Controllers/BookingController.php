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
use App\Models\SessionLogTable;
use App\Models\User;
use App\Notifications\BookingUpdateNotification;
use App\Models\RoomBookTable;
use App\Models\CottageBookTable;
use App\Models\ChargeTable;
use App\Models\InclusionTable;
use App\Models\RoomTypeTable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Types\Relations\Car;

class BookingController extends Controller
{
    public function bookingList()
    {
        $booked = BookingTable::from('booking')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->where('booking.status', 'Booked')
            ->select(
                'booking.bookingID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname"),
                DB::raw("DATE_FORMAT(booking.bookingstart, '%M %d, %Y') as bookingstart"),
                DB::raw("DATE_FORMAT(booking.bookingend, '%M %d, %Y') as bookingend"),
                'booking.status'
            )
            ->get();

        $pending = BookingTable::from('booking')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->where('booking.status', 'Pending')
            ->select(
                'booking.bookingID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname"),
                DB::raw("DATE_FORMAT(booking.bookingstart, '%M %d, %Y') as bookingstart"),
                DB::raw("DATE_FORMAT(booking.bookingend, '%M %d, %Y') as bookingend"),
                'booking.status'
            )
            ->get();

        $cancelled = BookingTable::from('booking')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->where('booking.status', 'Cancelled')
            ->select(
                'booking.bookingID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname"),
                DB::raw("DATE_FORMAT(booking.bookingstart, '%M %d, %Y') as bookingstart"),
                DB::raw("DATE_FORMAT(booking.bookingend, '%M %d, %Y') as bookingend"),
                'booking.status'
            )
            ->get();

        $finished = BookingTable::from('booking')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->where('booking.status', 'Finished')
            ->select(
                'booking.bookingID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname"),
                DB::raw("DATE_FORMAT(booking.bookingstart, '%M %d, %Y') as bookingstart"),
                DB::raw("DATE_FORMAT(booking.bookingend, '%M %d, %Y') as bookingend"),
                'booking.status'
            )
            ->get();

        $ongoing = BookingTable::from('booking')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->where('booking.status', 'Ongoing')
            ->select(
                'booking.bookingID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname"),
                DB::raw("DATE_FORMAT(booking.bookingstart, '%M %d, %Y') as bookingstart"),
                DB::raw("DATE_FORMAT(booking.bookingend, '%M %d, %Y') as bookingend"),
                'booking.status'
            )
            ->get();
            //$statuses = DB::table('cottages')->pluck('status');
            //dd($statuses);

        $rooms = RoomTable::whereIn('status', ['Available', 'Booked'])->get();
        $cottages = CottageTable::whereIn('status', ['Available', 'Booked'])->get();
        $amenities = AmenityTable::whereIn('amenityname', ['Kiddy Pool'])->get();

        return view('receptionist/booking', compact('booked', 'pending', 'cancelled', 'finished', 'ongoing', 'rooms', 'cottages', 'amenities'));
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

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
            ->where('booking.bookingstart', '<=', $end)
            ->where('booking.bookingend', '>=', $start)
            ->groupBy('booking.bookingID')
            ->get();

        $statusColors = [
            'Booked'     => '#1E90FF',
            'Pending'    => '#FFD700',
            'Cancelled'  => '#A9A9A9',
            'Finished'   => '#32CD32',
            'Ongoing'    => '#FF6347',
        ];

        $events = [];
        foreach ($booking as $bookings) {
            $startDate = \Carbon\Carbon::parse($bookings->bookingstart);
            $endDate = \Carbon\Carbon::parse($bookings->bookingend)->addDay();
            $status = $bookings->status;
            $color = $statusColors[$status] ?? '#808080';

            $hasMessage = '';
            $hasRooms = $bookings->rooms_count > 0;
            $hasCottages = $bookings->cottages_count > 0;

            if ($hasRooms && $hasCottages) $hasMessage = 'is booking multiple resort services';
            elseif ($hasRooms) $hasMessage = 'is booking ' . $bookings->rooms_count . ' room' . ($bookings->rooms_count > 1 ? 's' : '');
            elseif ($hasCottages) $hasMessage = 'is booking ' . $bookings->cottages_count . ' cottage' . ($bookings->cottages_count > 1 ? 's' : '');

            $events[] = [
                'id'    => $bookings->bookingID,
                'title' => $bookings->guestname . ' ' . $hasMessage,
                'start' => $startDate->format('Y-m-d'),
                'end'   => $endDate->format('Y-m-d'),
                'color' => $color,
                'status'=> $status
            ];
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
        // Fetch only room types that have at least one room
        $rooms = RoomTypeTable::join('rooms', 'room_type.roomtypeID', '=', 'rooms.roomtypeID')
            ->leftJoin('discount', 'room_type.discountID', '=', 'discount.discountID')
            ->select(
                'room_type.roomtypeID',
                'room_type.roomtype',
                'room_type.basecapacity',
                'room_type.maxcapacity',
                'room_type.price',
                'room_type.extra',
                'room_type.description',
                'room_type.image',
                'discount.name as discount_name',
                'discount.flatamount as discount_amount'
            )
            ->distinct() // make sure each room type appears only once
            ->get();

        // Add image URL
        foreach ($rooms as $room) {
            $room->image_url = $room->image
                ? route('room.image', ['filename' => basename($room->image)])
                : asset('images/default-room.jpg');
        }

        $cottages = CottageTable::where('status', 'Available')->get();
        $amenities = AmenityTable::where('amenityname', 'Kiddy Pool')->get();
        $inclusions = InclusionTable::with(['roomtype', 'amenity', 'menu'])->get();
        $extras = AmenityTable::where('type', 'Items')->get();

        // Fetch booked items
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

        return view('receptionist.create_booking', compact('rooms', 'cottages', 'amenities', 'bookedItems', 'inclusions', 'extras'));
    }

    public function getRoomInclusions(Request $request)
    {
        $roomIDs = $request->input('roomIDs', []);

        if (empty($roomIDs)) {
            return response()->json([]);
        }

        $inclusions = InclusionTable::leftJoin('amenity_table', 'inclusions.amenityID', '=', 'amenity_table.amenityID')
            ->leftJoin('menu_table', 'inclusions.menuID', '=', 'menu_table.menuID')
            ->select(
                'inclusions.inclusionID',
                'inclusions.roomID',
                'amenity_table.amenityname',
                'menu_table.menuname'
            )
            ->whereIn('inclusions.roomID', $roomIDs)
            ->get();

        // Deduplicate inclusions (same amenity/menu across multiple rooms)
        $unique = $inclusions->unique(function ($item) {
            return ($item->amenityname ?? '') . '|' . ($item->menuname ?? '');
        })->values();

        return response()->json($unique);
    }

    public function submitBooking(Request $request, OCRService $ocrService)
{
    $validated = $request->validate([
        'room' => 'required|array',
        'room.*' => 'integer|min:0',
        'cottage' => 'nullable|array',
        'cottage.*' => 'integer|exists:cottages,cottageID',
        'amenity' => 'nullable|array',
        'amenity.*' => 'integer|exists:amenities,amenityID',
        'extra' => 'nullable|array',
        'extra.*' => 'integer|exists:amenities,amenityID',
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

    // ✅ FIX: ensure at least one room has quantity > 0
    $hasSelectedRoom = collect($validated['room'] ?? [])
        ->some(fn($qty) => (int)$qty > 0);

    if (!$hasSelectedRoom) {
        return back()->withErrors(['room' => 'Please select at least one room.'])->withInput();
    }

    // ===== Availability Check =====
    $availabilityError = $this->checkAvailability($validated);
    if ($availabilityError) {
        return back()->withErrors(['availability' => $availabilityError])->withInput();
    }

    // ===== OCR ID Verification =====
    $validated['validID'] = null;
    if ($request->hasFile('validID')) {
        $file = $request->file('validID');
        $path = $file->storeAs('temp_valid_ids', uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
        $absolutePath = storage_path('app/public/' . $path);
        $result = $ocrService->verify($absolutePath);
        if (!$result['isValid']) {
            return back()->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.']);
        }
        $validated['validID'] = $path;
    }

    // ===== Duration Calculation =====
    $checkin = \Carbon\Carbon::parse($validated['checkin']);
    $checkout = \Carbon\Carbon::parse($validated['checkout']);
    $days = max(1, $checkin->diffInDays($checkout));

    // ===== Pricing Calculations =====
    $totalGuests = (int) $validated['guestamount'];
    $roomprice = 0;
    $totalBaseCapacity = 0;
    $roomDetails = [];

    if (!empty($validated['room'])) {
        foreach ($validated['room'] as $roomID => $qty) {
            if ((int)$qty <= 0) continue; // ✅ skip unselected rooms

            $room = RoomTable::find($roomID);
            if (!$room) continue;

            $roomprice += $room->price * $qty;
            $totalBaseCapacity += $room->basecapacity * $qty;

            $roomDetails[] = [
                'roomID' => $roomID,
                'roomtype' => $room->roomtype,
                'quantity' => $qty,
                'price' => $room->price,
                'basecapacity' => $room->basecapacity,
                'extra' => $room->extra,
            ];
        }
    }

    // ===== Extra Guests Beyond Base Capacity =====
    if ($totalGuests > $totalBaseCapacity) {
        $excess = $totalGuests - $totalBaseCapacity;
        $extraSelected = AmenityTable::whereIn('amenityID', $validated['extra'] ?? [])
            ->where('amenityname', 'like', '%breakfast%')
            ->exists();
        $extraRate = $extraSelected ? 900 : 700;
        $roomprice += $excess * $extraRate;
    }

    // ===== Cottage Pricing =====
    $cottageprice = 0;
    if (!empty($validated['cottage'])) {
        $cottages = CottageTable::whereIn('cottageID', $validated['cottage'])->get();
        foreach ($cottages as $cottage) {
            $cottageprice += $cottage->price;
        }
    }

    // ===== Amenity Pricing =====
    $adultprice = 0;
    $childprice = 0;
    if (!empty($validated['amenity'])) {
        $amenities = AmenityTable::whereIn('amenityID', $validated['amenity'])->get();
        foreach ($amenities as $amenity) {
            $adultprice += $amenity->adultprice * $validated['amenity_adult_guest'];
            $childprice += $amenity->childprice * $validated['amenity_child_guest'];
        }
    }

    // ===== Extra Amenities =====
    $extraPrice = 0;
    if (!empty($validated['extra'])) {
        $extras = AmenityTable::whereIn('amenityID', $validated['extra'])->get();
        foreach ($extras as $extra) {
            $extraPrice += $extra->adultprice * $validated['amenity_adult_guest'];
            $extraPrice += $extra->childprice * $validated['amenity_child_guest'];
        }
    }

    // ===== Multiply room & cottage prices by # of days =====
    $roomprice *= $days;
    $cottageprice *= $days;
    $totalprice = $roomprice + $cottageprice + $adultprice + $childprice + $extraPrice;

    // ===== Store Prices in Session =====
    $prices = [
        'roomprice' => $roomprice,
        'cottageprice' => $cottageprice,
        'adultprice' => $adultprice,
        'childprice' => $childprice,
        'amenityprice' => $adultprice + $childprice,
        'extraPrice' => $extraPrice,
        'totalprice' => $totalprice,
        'rooms' => $roomDetails,
    ];

    $bookingSessionID = (string) Str::uuid();
    session([
        'booking_data_' . $bookingSessionID => $validated,
        'booking_prices_' . $bookingSessionID => $prices,
    ]);

    return redirect()->route('receptionist.booking_receipt', ['sessionID' => $bookingSessionID]);
}

    public function receiptBooking($sessionID)
{
    // Retrieve booking data and prices from session
    $data = session('booking_data_' . $sessionID);
    $prices = session('booking_prices_' . $sessionID);

    if (!$data || !$prices) {
        return redirect()->route('receptionist.create_booking')
            ->with('error', 'Booking session expired.');
    }

    // Calculate nights between check-in and check-out
    $checkin = \Carbon\Carbon::parse($data['checkin']);
    $checkout = \Carbon\Carbon::parse($data['checkout']);
    $nights = $checkin->diffInDays($checkout);
    if ($nights <= 0) $nights = 1; // Default to 1 night if same-day booking

    // Retrieve selected rooms with quantities and compute per-night cost
    $selectedRooms = [];
    $roomTotal = 0;
    if (!empty($prices['rooms'])) {
        foreach ($prices['rooms'] as $roomData) {
            $room = RoomTypeTable::find($roomData['roomID']);
            if ($room) {
                $room->quantity = $roomData['quantity'];
                $room->nights = $nights;
                $room->price_per_night = $room->price;
                $room->subtotal = $room->price_per_night * $room->quantity * $nights;
                $selectedRooms[] = $room;
                $roomTotal += $room->subtotal;
            }
        }
    }

    // Retrieve selected cottages
    $selectedCottages = [];
    if (!empty($data['cottage'])) {
        $selectedCottages = CottageTable::whereIn('cottageID', $data['cottage'])->get();
    }

    // Retrieve selected amenities
    $selectedAmenities = [];
    if (!empty($data['amenity'])) {
        $selectedAmenities = AmenityTable::whereIn('amenityID', $data['amenity'])->get();
    }

    // Retrieve discounts
    $discounts = DiscountTable::where('status', 'Available')->where('type', 'Discount')->get();

    // Get room inclusions
    $inclusionsByRoom = [];
    foreach ($selectedRooms as $room) {
        $roomInclusions = InclusionTable::leftJoin('amenities', 'inclusions.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('menu', 'inclusions.menuID', '=', 'menu.menuID')
            ->select('amenities.amenityname', 'menu.menuname')
            ->where('inclusions.roomtypeID', $room->roomtypeID)
            ->get()
            ->map(fn($item) => $item->amenityname ?? $item->menuname)
            ->filter()
            ->unique()
            ->values();

        $inclusionsByRoom[$room->roomtype] = $roomInclusions;
    }

    // Update roomprice total
    $prices['roomprice'] = $roomTotal;

    //  Compute new total price based on updated room totals
    $roomTotal = $roomTotal ?? 0;
    $cottageTotal = $prices['cottageprice'] ?? 0;
    $amenityTotal = $prices['amenityprice'] ?? 0;
    $adultTotal = $prices['adultprice'] ?? 0;
    $childTotal = $prices['childprice'] ?? 0;
    $extraTotal = $prices['extraPrice'] ?? 0;

    // Recalculate total properly
    $totalprice = $roomTotal + $cottageTotal + $amenityTotal + $adultTotal + $childTotal + $extraTotal;


    // Pass data to view
    return view('receptionist.receipt_booking', [
        'booking'           => $data,
        'sessionID'         => $sessionID,
        'rooms'             => collect($selectedRooms),
        'cottage'           => collect($selectedCottages),
        'amenity'           => collect($selectedAmenities),
        'discount'          => $discounts,
        'roomprice'         => $roomTotal,
        'cottageprice'      => $prices['cottageprice'] ?? 0,
        'adultprice'        => $prices['adultprice'] ?? 0,
        'childprice'        => $prices['childprice'] ?? 0,
        'amenityprice'      => $prices['amenityprice'] ?? 0,
        'extraPrice'        => $prices['extraPrice'] ?? 0,
        'totalprice'        => $totalprice ?? 0,
        'inclusionsByRoom'  => $inclusionsByRoom,
        'nights'            => $nights,
    ]);
}


    private function parseDate($dateString)
    {
        try {
            $formats = ['m/d/Y', 'Y-m-d', 'Y/m/d', 'd/m/Y', 'm-d-Y'];
            foreach ($formats as $format) {
                try {
                    $date = Carbon::createFromFormat($format, $dateString);
                    if ($date) {
                        return $date; // Return Carbon object (DB will store as Y-m-d)
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            // Fallback to Carbon parse
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

        // Validate payment inputs
        $validated = $request->validate([
            'cashamount'   => 'required_if:payment,cash|nullable|numeric|min:0',
            'discount'     => 'nullable',
            'payment_type' => 'required|in:full,downpayment',
            'payment'      => 'required|in:cash,gcash',
        ]);

        // Parse birthday
        $birthday = null;
        if (!empty($data['birthday'])) {
            try {
                $birthday = Carbon::parse($data['birthday'])->format('Y-m-d');
            } catch (\Exception $e) {
                $birthday = null;
            }
        }

        // ====== Guest + User Handling ======
        $userID = null;
        if (!empty($data['username']) && !empty($data['password'])) {
            $user = UserTable::firstOrCreate(
                ['username' => $data['username']],
                ['password' => bcrypt($data['password'])]
            );
            $userID = $user->userID;
        }

        $avatarPath = !empty($data['avatar']) ? $data['avatar'] : 'images/profile.jpg';

        $guest = GuestTable::firstOrCreate(
            ['firstname' => $data['firstname'], 'lastname' => $data['lastname']],
            [
                'mobilenum' => $data['contactnum'] ?? null,
                'email'     => $data['email'] ?? null,
                'gender'    => $data['gender'] ?? null,
                'birthday'  => $birthday,
                'validID'   => $data['validID'] ?? null,
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

        // ====== Discount & Payment Calculations ======
        $originalAmount = (float) $prices['totalprice'];
        $discountAmount = 0;

        if (!empty($validated['discount'])) {
            $discount = DiscountTable::find($validated['discount']);
            if ($discount && isset($discount->amount)) {
                $discountAmount = $originalAmount * ((float) $discount->amount / 100);
            }
        }

        $discountedAmount = $originalAmount - $discountAmount;

        $requiredAmount = $validated['payment_type'] === 'downpayment'
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

        // ====== Save Booking & Billing ======
        try {
            DB::beginTransaction();

            $checkinDate  = Carbon::parse($data['checkin']);
            $checkoutDate = Carbon::parse($data['checkout']);
            $status = $checkinDate->lessThanOrEqualTo(Carbon::now()) ? 'Ongoing' : 'Booked';

            // Create Booking
            $booking = BookingTable::create([
                'bookingcreated' => Carbon::now(),
                'bookingstart'   => $checkinDate->format('Y-m-d'),
                'bookingend'     => $checkoutDate->format('Y-m-d'),
                'guestamount'    => (int) $data['guestamount'],
                'childguest'     => (int) ($data['amenity_child_guest'] ?? 0),
                'adultguest'     => (int) ($data['amenity_adult_guest'] ?? 0),
                'totalprice'     => $originalAmount,
                'amenityID'      => !empty($data['amenity']) ? (int) $data['amenity'][0] : null,
                'booking_type'   => $data['booking_type'] ?? 'Booking',
                'status'         => $status,
                'guestID'        => $guest->guestID,
            ]);

            // Auto check-in if today
            if ($status === 'Ongoing') {
                CheckTable::create([
                    'date'      => Carbon::now()->format('Y-m-d'),
                    'status'    => 'Checked In',
                    'guestID'   => $guest->guestID,
                    'bookingID' => $booking->bookingID,
                ]);
            }

            // ====== Rooms ======
            if (!empty($prices['rooms'])) {
                foreach ($prices['rooms'] as $roomData) {
                    for ($i = 0; $i < $roomData['quantity']; $i++) {
                        RoomBookTable::create([
                            'bookingID'   => $booking->bookingID,
                            'roomID'      => $roomData['roomID'],
                            'bookingDate' => Carbon::now()->format('Y-m-d'),
                        ]);
                    }
                }
            }

            // ====== Cottages ======
            if (!empty($data['cottage'])) {
                foreach ($data['cottage'] as $cottageID) {
                    CottageBookTable::create([
                        'bookingID'   => $booking->bookingID,
                        'cottageID'   => (int) $cottageID,
                        'bookingDate' => Carbon::now()->format('Y-m-d'),
                    ]);
                }
            }

            // ====== Billing ======
            $billing = BillingTable::create([
                'totalamount' => $remainingBalance,
                'datebilled'  => Carbon::now()->format('Y-m-d'),
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

            // ====== Payment ======
            PaymentTable::create([
                'billingID'   => $billing->billingID,
                'guestID'     => $guest->guestID,
                'totaltender' => $amountPaid,
                'totalchange' => $change,
                'datepayment' => Carbon::now()->format('Y-m-d'),
            ]);

            // ====== Log Activity ======
            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Booking',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            // Clear session
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
                    $query->whereBetween('booking.bookingstart', $dateRange) // check for dates existing in the range
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
            Log::info("Attempting to approve booking ID: {$bookingID}");

            $booking = BookingTable::where('bookingID', $bookingID)->firstOrFail();

            // Update booking status
            $booking->status = 'Booked';
            $booking->save();

            Log::info("Booking {$bookingID} status updated to 'Booked'");

            // Fetch the user who made the booking
            $user = User::find($booking->guest->userID ?? null); // safe lookup

            if ($user) {
                Log::info("Found user [ID: {$user->id}, Name: {$user->username}] for booking {$bookingID}");

                // Prepare dynamic notification data
                $messageData = [
                    'title' => "Booking #{$booking->bookingID} Updated",
                    'body'  => "Hello {$user->username}, your booking on {$booking->bookingcreated} has been approved.",
                    'extra' => [
                        'amount' => $booking->totalprice,
                        'status' => $booking->status,
                    ],
                ];

                // Send notification
                $user->notify(new BookingUpdateNotification($messageData));

                Log::info("Notification sent to user ID {$user->id} for booking {$bookingID}", $messageData);
            } else {
                Log::warning("Booking {$bookingID} has no associated user to notify");
            }

            Log::info("Booking {$bookingID} approved successfully");

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Error approving booking [ID: {$bookingID}]: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

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
            'roomBookings.Room.RoomType', // include RoomType relationship
            'cottageBookings.Cottage',
            'Amenity',
            'billing.Payments',
            'menuBookings.Menu'
        ])->find($bookingID);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Gather room and cottage IDs from booking
        $roomIDs = $booking->roomBookings->pluck('roomID')->toArray();
        $cottageIDs = $booking->cottageBookings->pluck('cottageID')->toArray();

        // Fetch all booked rooms and cottages
        $rooms = RoomTable::whereIn('roomID', $roomIDs)->with('RoomType')->get();
        $cottages = CottageTable::whereIn('cottageID', $cottageIDs)->get();
        $amenities = AmenityTable::all();

        // Calculate room total
        $roomTotal = $rooms->sum(function($room) use ($booking) {
            return $room->price ?? 0; // assuming `price` column exists in RoomTable
        });

        // Calculate cottage total
        $cottageTotal = $cottages->sum(function($cottage) use ($booking) {
            return $cottage->price ?? 0; // assuming `price` column exists in CottageTable
        });

        // Add any additional totals, e.g., menuOrders
        $menuTotal = $booking->menuBookings->sum(function($menu) {
            return ($menu->price ?? 0) * ($menu->pivot->quantity ?? 1); // adjust if using pivot
        });

        // Total
        $totalBooking = $roomTotal + $cottageTotal + $menuTotal;

        // Prepare structured booking data
        $bookingData = (object) [
            'bookingID' => $booking->bookingID,
            'status' => $booking->status ?? 'Pending',
            'firstname' => $booking->Guest->firstname ?? '',
            'lastname' => $booking->Guest->lastname ?? '',
            'guestamount' => $booking->guestamount,
            'adultguest' => $booking->adultguest,
            'childguest' => $booking->childguest,
            'checkin' => $booking->bookingstart,
            'checkout' => $booking->bookingend,
            'rooms' => $roomIDs,
            'cottages' => $cottageIDs,
            'amenities' => $booking->Amenity ? [$booking->Amenity->amenityID] : [],
            'billing' => $booking->billing,
            'payments' => $booking->billing ? $booking->billing->payments : [],
            'menuOrders' => $booking->menuBookings ?? [],
            'total' => $totalBooking,
            'pricePerNight' => $rooms->first()->price ?? 0 // if extending, use first room price or define logic
        ];


        // Gather inclusions per booked room type
        $inclusionsByRoom = [];

        foreach ($rooms as $room) {
            if (!$room->RoomType) continue;

            $roomTypeID = $room->RoomType->roomtypeID;

            $roomInclusions = InclusionTable::leftJoin('amenities', 'inclusions.amenityID', '=', 'amenities.amenityID')
                ->leftJoin('menu', 'inclusions.menuID', '=', 'menu.menuID')
                ->select('amenities.amenityname', 'menu.menuname')
                ->where('inclusions.roomtypeID', $roomTypeID)
                ->get()
                ->map(fn($item) => $item->amenityname ?? $item->menuname)
                ->filter()
                ->unique()
                ->values();

            // Key by room number for easy display
            $inclusionsByRoom[$room->roomnum] = $roomInclusions;
        }

        // Log for debugging
        Log::info('Booking Data for viewBooking:', (array) $bookingData);
        Log::info('Inclusions By Room:', $inclusionsByRoom);

        // Pass data to view
        return view('receptionist.view_booking', compact(
            'rooms',
            'cottages',
            'amenities',
            'bookingData',
            'inclusionsByRoom'
        ));
    }

    public function extendBooking(Request $request, $bookingID)
    {
        $booking = BookingTable::find($bookingID);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        $extraNights = (int) $request->input('extra_nights', 0);

        if ($extraNights < 1) {
            return redirect()->back()->with('error', 'Invalid number of nights');
        }

        $newCheckout = \Carbon\Carbon::parse($booking->bookingend)->addDays($extraNights);
        $newTotal = $booking->price * $extraNights + $booking->total; // add to existing total

        $booking->bookingend = $newCheckout;
        $booking->totalprice = $newTotal;
        $booking->save();

        return view('receptionist/booking_list')->with('success', 'Booking extended successfully!');
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
        try {
            $checkin = $request->query('checkin');
            $checkout = $request->query('checkout');

            if (!$checkin || !$checkout) {
                return response()->json([
                    'bookedRooms' => [],
                    'bookedCottages' => [],
                ]);
            }

            $checkin = date('Y-m-d', strtotime($checkin));
            $checkout = date('Y-m-d', strtotime($checkout));

            $bookedRoomIDs = DB::table('roombook')
                ->join('booking', 'roombook.bookingID', '=', 'booking.bookingID')
                ->where('booking.bookingstart', '<=', $checkout)
                ->where('booking.bookingend', '>=', $checkin)
                ->pluck('roombook.roomID') // take all the rooms that are in the range  count if any exist for the selected roomtype
                ->unique()                 // if no rooms left -> return there are no more rooms left for this type [roomtype]
                ->values()
                ->toArray();

            $bookedCottageIDs = DB::table('cottagebook')
                ->join('booking', 'cottagebook.bookingID', '=', 'booking.bookingID')
                ->where('booking.bookingstart', '<=', $checkout)
                ->where('booking.bookingend', '>=', $checkin)
                ->pluck('cottagebook.cottageID')
                ->unique()
                ->values()
                ->toArray();

            return response()->json([
                'bookedRooms' => $bookedRoomIDs,
                'bookedCottages' => $bookedCottageIDs,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'bookedRooms' => [],
                'bookedCottages' => [],
            ], 500);
        }
    }

    public function updateBooking(Request $request, $bookingID)
    {
        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'guestamount' => 'required|integer|min:1',
                'adultguest' => 'required|integer|min:1',
                'childguest' => 'required|integer|min:0',
                'checkin' => 'required|date',
                'checkout' => 'required|date|after:checkin',
                'room' => 'nullable|array',
                'room.*' => 'exists:rooms,roomID',
                'cottage' => 'nullable|array',
                'cottage.*' => 'exists:cottages,cottageID',
                'amenity' => 'nullable|array',
                'amenity.*' => 'exists:amenities,amenityID',
            ]);

            $rooms = $request->input('room', []);
            $cottages = $request->input('cottage', []);
            $amenities = $request->input('amenity', []);

            if (empty($rooms) && empty($cottages) && empty($amenities)) {
                return redirect()->back()->withInput()->with('error', 'Please select at least one: Room, Cottage, or Amenity.');
            }

            $booking = BookingTable::find($bookingID);
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found.');
            }

            $checkinFormatted = Carbon::parse($request->input('checkin'))->format('Y-m-d');
            $checkoutFormatted = Carbon::parse($request->input('checkout'))->format('Y-m-d');

            $original = $booking->getOriginal();
            $originalGuest = GuestTable::find($booking->guestID);
            $isChanged = (
                !$originalGuest ||
                $originalGuest->firstname !== $request->firstname ||
                $originalGuest->lastname !== $request->lastname ||
                $original['bookingstart'] !== $checkinFormatted ||
                $original['bookingend'] !== $checkoutFormatted ||
                $original['guestamount'] != $request->guestamount ||
                $original['adultguest'] != $request->adultguest ||
                $original['childguest'] != $request->childguest
            );

            $currentRooms = DB::table('roombook')->where('bookingID', $bookingID)->pluck('roomID')->toArray();
            $currentCottages = DB::table('cottagebook')->where('bookingID', $bookingID)->pluck('cottageID')->toArray();
            $currentAmenities = DB::table('amenitybook')->where('bookingID', $bookingID)->pluck('amenityID')->toArray();

            if (
                array_diff($currentRooms, $rooms) ||
                array_diff($rooms, $currentRooms) ||
                array_diff($currentCottages, $cottages) ||
                array_diff($cottages, $currentCottages) ||
                array_diff($currentAmenities, $amenities) ||
                array_diff($amenities, $currentAmenities)
            ) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return redirect()->route('view.booking', $bookingID)->with('info', 'No changes detected.');
            }

            $guestID = GuestTable::where('firstname', $request->firstname)
                ->where('lastname', $request->lastname)
                ->first();

            if (!$guestID) {
                return redirect()->back()->with('error', 'Guest not found in records.');
            }

            $booking->update([
                'guestID' => $guestID->guestID,
                'guestamount' => $request->guestamount,
                'adultguest' => $request->adultguest,
                'childguest' => $request->childguest,
                'bookingstart' => $checkinFormatted,
                'bookingend' => $checkoutFormatted,
            ]);

            DB::table('roombook')->where('bookingID', $bookingID)->delete();
            foreach ($rooms as $roomID) {
                DB::table('roombook')->insert(['bookingID' => $bookingID, 'roomID' => $roomID]);
            }

            DB::table('cottagebook')->where('bookingID', $bookingID)->delete();
            foreach ($cottages as $cottageID) {
                DB::table('cottagebook')->insert(['bookingID' => $bookingID, 'cottageID' => $cottageID]);
            }

            DB::table('amenitybook')->where('bookingID', $bookingID)->delete();
            foreach ($amenities as $amenityID) {
                DB::table('amenitybook')->insert(['bookingID' => $bookingID, 'amenityID' => $amenityID]);
            }

            $roomTotal = DB::table('rooms')->whereIn('roomID', $rooms)->sum('price');
            $cottageTotal = DB::table('cottages')->whereIn('cottageID', $cottages)->sum('price');
            $amenityTotal = DB::table('amenities')->whereIn('amenityID', $amenities)->sum('price');
            $totalAmount = $roomTotal + $cottageTotal + $amenityTotal;

            DB::table('billing')->updateOrInsert(
                ['bookingID' => $bookingID],
                [
                    'totalamount'  => $totalAmount,
                    'datebilled'   => now(),
                    'status'       => 'Partial',
                    'bookingID'    => $bookingID,
                    'discountID'   => null,
                    'guestID'      => $guestID->guestID,
                ]
            );

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Booking',
                    'date'     => Carbon::now(),
                ]);
            }

            return redirect()->route('receptionist.booking')->with('success', 'Booking and billing updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Booking update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Booking update failed: ' . $e->getMessage());
        }
    }


    public function viewCheckIn()
    {
        $today = Carbon::today()->toDateString();

        $checkin = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Booked')
            ->get();

        $checkout = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Ongoing')
            ->get();

        return view('receptionist.check-in-out', compact('checkin', 'checkout', 'today'));
    }

    public function checkinList(){
        $today = Carbon::today()->toDateString();

        $checkin = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Booked')
            ->orderBy('booking.bookingstart', 'desc')
            ->paginate(10);
        

        return view('receptionist.checkin', compact('checkin', 'today'));
    }

    public function checkoutList(){
        $today = Carbon::today()->toDateString();

        $checkout = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Ongoing')
            ->orderBy('booking.bookingend', 'desc')
            ->paginate(10);

        return view('receptionist.checkout', compact('checkout', 'today'));
    }

    public function checkInBooking(Request $request, $bookingID)
{
    $now = Carbon::now();
    $today = $now->format('m/d/Y');
    $todayDB = $now->format('Y-m-d');

    // Fetch booking with guest and amenities
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

    // Fetch billing with discount safely
    $billingQuery = BillingTable::where('bookingID', $bookingID)
        ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
        ->select('billing.*');

    if (Schema::hasColumn('discount', 'amount')) {
        $billingQuery->addSelect('discount.amount as discount_amount');
    }

    $billing = $billingQuery->first();

    $payment = $billing ? PaymentTable::where('billingID', $billing->billingID)->first() : null;

    $room = RoomBookTable::where('bookingID', $bookingID)
        ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
        ->leftJoin('room_type', 'rooms.roomtypeID', '=', 'room_type.roomtypeID')
        ->select(
            'roombook.*',
            'rooms.roomnum',
            'room_type.price',
            'room_type.extra',
            'room_type.roomtype',
            'room_type.description'
        )
        ->get();

    $cottage = CottageBookTable::where('bookingID', $bookingID)
        ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
        ->select('cottagebook.*', 'cottages.cottagename', 'cottages.price')
        ->get();

    // Calculate late check-in fee
    $lateFee = 0;
    $bookingStart = Carbon::parse($booking->bookingstart);
    if ($bookingStart->lt($now)) {
        // Booking is in the past? No late fee
        $lateFee = 0;
    } elseif ($bookingStart->gt($now)) {
        // Booking is in the future, calculate hours difference
        $hoursLate = ceil($bookingStart->diffInMinutes($now) / 60); // round up to next hour
        $lateFee = $hoursLate * 200;
    }

    // Adjust total amount due
    $totalAmountDue = ($billing->totalamount ?? 0) + $lateFee;

    if ($request->isMethod('get')) {
        return view('receptionist.checkInBooking', compact(
            'booking', 'today', 'room', 'cottage', 'billing', 'payment', 'lateFee', 'totalAmountDue'
        ));
    }

    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'payment' => 'required|in:cash,gcash',
            'cashamount' => 'nullable|numeric|min:0',
        ]);

        try {
            $remainingBalance = $totalAmountDue;

            if ($validated['payment'] === 'gcash') {
                $validated['amount_paid'] = $remainingBalance;
            }

            if ($validated['payment'] === 'cash' && $validated['cashamount'] < $remainingBalance) {
                return redirect()->back()->with('error', 'Insufficient payment amount. Remaining balance: ₱' . number_format($remainingBalance, 2));
            }

            $change = $validated['payment'] === 'cash'
                ? max(0, $validated['cashamount'] - $remainingBalance)
                : 0;

            PaymentTable::create([
                'totaltender' => $validated['cashamount'],
                'totalchange' => $change,
                'datepayment' => $todayDB,
                'guestID' => $booking->guestID,
                'billingID' => $billing->billingID,
            ]);

            // Update billing
            $billing->totalamount = 0;
            $billing->status = 'Paid';
            $billing->save();

            // Update booking
            $booking->status = 'Ongoing';
            $booking->save();

            // Add check-in log
            CheckTable::create([
                'date' => $todayDB,
                'status' => 'Checked In',
                'guestID' => $booking->guestID,
                'bookingID' => $bookingID,
            ]);

            // Log user activity
            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Checked-In a Guest',
                    'date'     => now(),
                ]);
            }

            return redirect()->route('receptionist.view_check')->with('success', 'Booking successfully checked in.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to check-in booking: ' . $e->getMessage());
        }
    }
}

    public function checkOutBooking(Request $request, $bookingID)
{
    $today = Carbon::now()->format('m/d/Y');
    $todayDB = Carbon::now()->format('Y-m-d');

    // Fetch booking with guest and amenities
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

    if (!$booking) {
        return back()->with('error', 'Booking not found.');
    }

    // Fetch billing with discount columns
    $billing = BillingTable::where('bookingID', $bookingID)
        ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
        ->select('billing.*', 'discount.flatamount', 'discount.percentamount')
        ->first();

    if (!$billing) {
        return back()->with('error', 'Billing not found.');
    }

    $payment = PaymentTable::where('billingID', $billing->billingID)->first();

    // Rooms
    $room = RoomBookTable::where('bookingID', $bookingID)
        ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
        ->leftJoin('room_type', 'rooms.roomtypeID', '=', 'room_type.roomtypeID')
        ->select('roombook.*', 'rooms.roomnum', 'room_type.price', 'room_type.roomtype')
        ->get();

    // Cottages
    $cottage = CottageBookTable::where('bookingID', $bookingID)
        ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
        ->select('cottagebook.*', 'cottages.cottagename', 'cottages.price')
        ->get();

    // Handle GET request: show receipt
    if ($request->isMethod('get')) {

        // Calculate nights
        $bookingStart = Carbon::parse($booking->bookingstart);
        $bookingEnd = Carbon::parse($booking->bookingend);
        $now = Carbon::now();
        $nights = $bookingStart->diffInDays($bookingEnd);

        // Room total
        $roomTotal = $room->sum(function($r) use ($nights) {
            return ($r->price ?? 0) * ($r->quantity ?? 1) * $nights;
        });

        // Cottage total
        $cottageTotal = $cottage->sum(fn($c) => $c->price ?? 0);

        // Amenity total
        $amenityTotal = ($booking->adultprice ?? 0) * ($booking->adultguest ?? 0)
                      + ($booking->childprice ?? 0) * ($booking->childguest ?? 0);

        // Early check-in fee (if checking out before check-in date)
        $earlyCheckInRate = 200;
        $earlyCheckInHours = 0;
        $earlyCheckInFee = 0;
        if ($now->lt($bookingStart)) {
            $earlyCheckInHours = ceil($bookingStart->diffInMinutes($now) / 60);
            $earlyCheckInFee = $earlyCheckInHours * $earlyCheckInRate;
        }

        // Subtotal
        $subtotal = $roomTotal + $cottageTotal + $amenityTotal + $earlyCheckInFee;

        // Discount
        $discountAmount = $billing->flatamount ?? 0;
        if (!$discountAmount && $billing->percentamount) {
            $discountAmount = $subtotal * ($billing->percentamount / 100);
        }

        $totalAfterDiscount = $subtotal - $discountAmount;

        // Amount paid
        $amountPaid = $payment->totaltender ?? 0;

        // Remaining balance
        $remainingBalance = max(0, $totalAfterDiscount - $amountPaid);

        return view('receptionist.checkOutBooking', compact(
            'booking', 'today', 'room', 'cottage', 'billing', 'payment',
            'roomTotal', 'cottageTotal', 'amenityTotal', 'earlyCheckInFee', 
            'earlyCheckInHours', 'subtotal', 'discountAmount', 'totalAfterDiscount', 
            'amountPaid', 'remainingBalance', 'nights'
        ));
    }

    // Handle POST request: submit checkout
    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'addcharge' => 'sometimes|numeric',
            'chargedesc' => 'sometimes|string',
            'payment' => 'required|in:cash,gcash',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        try {
            // Include any additional charge
            $additionalCharge = $validated['addcharge'] ?? 0;
            $newTotal = ($billing->totalamount ?? 0) + $additionalCharge;

            // GCash pays exact
            if ($validated['payment'] === 'gcash') {
                $validated['amount_paid'] = $newTotal;
            }

            if ($validated['payment'] === 'cash' && $validated['amount_paid'] < $newTotal) {
                return redirect()->back()
                    ->with('error', 'Insufficient payment amount. Remaining balance: ₱' . number_format($newTotal, 2));
            }

            $change = $validated['payment'] === 'cash'
                ? max(0, $validated['amount_paid'] - $newTotal)
                : 0;

            // Record payment
            $payment = PaymentTable::create([
                'totaltender' => $validated['amount_paid'],
                'totalchange' => $change,
                'datepayment' => $todayDB,
                'guestID' => $booking->guestID,
                'billingID' => $billing->billingID,
            ]);

            // Record additional charge if exists
            if ($additionalCharge > 0) {
                ChargeTable::create([
                    'amount' => $additionalCharge,
                    'chargedescription' => $validated['chargedesc'] ?? 'N/A',
                ]);
            }

            // Update billing status
            $billing->update([
                'status' => ($validated['amount_paid'] >= $newTotal) ? 'Paid' : 'Unpaid'
            ]);

            // Update booking status
            $booking->update(['status' => 'Finished']);

            // Record check-out
            CheckTable::create([
                'date' => $todayDB,
                'status' => 'Checked Out',
                'guestID' => $booking->guestID,
                'bookingID' => $bookingID,
            ]);

            // Update rooms to Available
            foreach ($room as $r) {
                RoomTable::where('roomID', $r->roomID)->update(['status' => 'Available']);
            }

            // Update cottages to Available
            foreach ($cottage as $c) {
                CottageTable::where('cottageID', $c->cottageID)->update(['status' => 'Available']);
            }

            // Update amenities to Available
            if ($booking->amenityID) {
                AmenityTable::where('amenityID', $booking->amenityID)->update(['status' => 'Available']);
            }

            // Log session
            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID' => $userID,
                    'activity' => 'User Checked-Out a Guest',
                    'date' => now(),
                ]);
            }

            return redirect()->route('receptionist.view_check')->with('success', 'Booking successfully checked out.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to check-out booking. ' . $e->getMessage());
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
        $amenities = AmenityTable::where('type', 'Items')->get();

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
            'amenity' => 'nullable|array',
            'amenity.*' => 'integer|exists:amenities,amenityID',
        ]);

        try {
            DB::transaction(function () use ($request, $booking) {
                if ($booking->Guest) {
                    $booking->Guest->update([
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                    ]);
                }

                $booking->update([
                    'guestamount' => $request->guestamount,
                    'adultguest' => $request->adultguest,
                    'childguest' => $request->childguest,
                    'bookingstart' => $request->checkin,
                    'bookingend' => $request->checkout,
                ]);

                $booking->roomBookings()->delete();
                if ($request->room) {
                    foreach ($request->room as $roomID) {
                        $booking->roomBookings()->create([
                            'roomID' => $roomID,
                            'bookingDate' => now(),
                        ]);
                    }
                }

                $booking->cottageBookings()->delete();
                if ($request->cottage) {
                    foreach ($request->cottage as $cottageID) {
                        $booking->cottageBookings()->create([
                            'cottageID' => $cottageID,
                            'bookingDate' => now(),
                        ]);
                    }
                }

            });
        } catch (\Throwable $e) {
            dd('Update failed:', $e->getMessage(), $e->getTraceAsString());
        }

        $userID = $request->session()->get('user_id');
        if ($userID) {
            SessionLogTable::create([
                'userID' => $userID,
                'activity' => 'User Updated a Booking',
                'date' => now(),
            ]);
        }

        return redirect()->route('booking.edit', $bookingID)
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
        $rooms = RoomTypeTable::join('rooms', 'room_type.roomtypeID', '=', 'rooms.roomtypeID')
            ->leftJoin('discount', 'room_type.discountID', '=', 'discount.discountID')
            ->select(
                'room_type.roomtypeID',
                'room_type.roomtype',
                'room_type.basecapacity',
                'room_type.maxcapacity',
                'room_type.price',
                'room_type.extra',
                'room_type.description',
                'room_type.image',
                'discount.name as discount_name',
                'discount.flatamount as discount_amount'
            )
            ->distinct() // make sure each room type appears only once
            ->get();

        $cottages = CottageTable::where('status', 'Available')->get();
        $amenities = AmenityTable::where('amenityname', 'Kiddy Pool')->get();

        $roomBookings = RoomBookTable::select('roomID', 'bookingDate')->get();
        $cottageBookings = CottageBookTable::select('cottageID', 'bookingDate')->get();
        $amenityBookings = BookingTable::whereNotNull('amenityID')
            ->select('amenityID', 'bookingstart', 'bookingend')
            ->get();

        $bookedItems = [
            'rooms' => $roomBookings,
            'cottages' => $cottageBookings,
            'amenities' => $amenityBookings,
        ];

        // GET request → show booking form
        if ($request->isMethod('get')) {
            return view('receptionist.walk-booking', compact('rooms', 'cottages', 'amenities', 'bookedItems'));
        }

        // POST request → validate & calculate, store in session
        $validated = $request->validate([
            'room' => 'nullable|array',
            'room.*' => 'integer|min:0',

            'cottage' => 'nullable|array',
            'cottage.*' => 'integer|exists:cottages,cottageID',

            'amenity' => 'nullable|array',
            'amenity.*' => 'integer|exists:amenities,amenityID',

            'extra' => 'nullable|array',
            'extra.*' => 'integer|exists:amenities,amenityID',

            'guestamount' => 'required|integer|min:1',
            'amenity_adult_guest' => 'required|integer|min:0',
            'amenity_child_guest' => 'required|integer|min:0',

            'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'lastname'  => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'contactnum' => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'gender'    => 'nullable|string|in:Male,Female,Other',
            'birthday'  => ['nullable','date','before_or_equal:' . now()->subYears(18)->toDateString()],
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

        // Ensure at least one room has quantity > 0
        $hasSelectedRoom = collect($validated['room'] ?? [])->some(fn($qty) => (int)$qty > 0);
        if (!$hasSelectedRoom) {
            return back()->withErrors(['room' => 'Please select at least one room.'])->withInput();
        }

        // OCR ID verification
        $validated['validID'] = null;
        if ($request->hasFile('validID')) {
            $file = $request->file('validID');
            $path = $file->storeAs('temp_valid_ids', uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            $absolutePath = storage_path('app/public/' . $path);
            $result = $ocrService->verify($absolutePath);
            if (!$result['isValid']) {
                return back()->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.']);
            }
            $validated['validID'] = $path;
        }

        // Duration in days
        $checkin = \Carbon\Carbon::parse($validated['checkin']);
        $checkout = \Carbon\Carbon::parse($validated['checkout']);
        $days = max(1, $checkin->diffInDays($checkout));

        // ===== Pricing Calculations =====
        $totalGuests = (int) $validated['guestamount'];
        $roomprice = 0;
        $totalBaseCapacity = 0;
        $roomDetails = [];

        if (!empty($validated['room'])) {
            foreach ($validated['room'] as $roomID => $qty) {
                if ((int)$qty <= 0) continue;
                $room = RoomTypeTable::find($roomID);
                if (!$room) continue;

                $roomprice += $room->price * $qty;
                $totalBaseCapacity += $room->basecapacity * $qty;

                $roomDetails[] = [
                    'roomID' => $roomID,
                    'roomtype' => $room->roomtype,
                    'quantity' => $qty,
                    'price' => $room->price,
                    'basecapacity' => $room->basecapacity,
                    'extra' => $room->extra,
                ];
            }
        }

        // Extra guests beyond base capacity
        if ($totalGuests > $totalBaseCapacity) {
            $excess = $totalGuests - $totalBaseCapacity;
            $extraSelected = AmenityTable::whereIn('amenityID', $validated['extra'] ?? [])
                ->where('amenityname','like','%breakfast%')->exists();
            $extraRate = $extraSelected ? 900 : 700;
            $roomprice += $excess * $extraRate;
        }

        // Cottage pricing
        $cottageprice = 0;
        if (!empty($validated['cottage'])) {
            $cottages = CottageTable::whereIn('cottageID', $validated['cottage'])->get();
            foreach ($cottages as $cottage) {
                $cottageprice += $cottage->price;
            }
        }

        // Amenity pricing
        $adultprice = 0;
        $childprice = 0;
        if (!empty($validated['amenity'])) {
            $amenities = AmenityTable::whereIn('amenityID', $validated['amenity'])->get();
            foreach ($amenities as $amenity) {
                $adultprice += $amenity->adultprice * $validated['amenity_adult_guest'];
                $childprice += $amenity->childprice * $validated['amenity_child_guest'];
            }
        }

        // Extra amenities pricing
        $extraPrice = 0;
        if (!empty($validated['extra'])) {
            $extras = AmenityTable::whereIn('amenityID', $validated['extra'])->get();
            foreach ($extras as $extra) {
                $extraPrice += $extra->adultprice * $validated['amenity_adult_guest'];
                $extraPrice += $extra->childprice * $validated['amenity_child_guest'];
            }
        }

        // Multiply room & cottage prices by number of days
        $roomprice *= $days;
        $cottageprice *= $days;
        $totalprice = $roomprice + $cottageprice + $adultprice + $childprice + $extraPrice;

        // Store in session
        $prices = [
            'roomprice' => $roomprice,
            'cottageprice' => $cottageprice,
            'adultprice' => $adultprice,
            'childprice' => $childprice,
            'amenityprice' => $adultprice + $childprice,
            'extraPrice' => $extraPrice,
            'totalprice' => $totalprice,
            'rooms' => $roomDetails,
        ];

        $bookingSessionID = (string) Str::uuid();
        session([
            'booking_data_' . $bookingSessionID => $validated,
            'booking_prices_' . $bookingSessionID => $prices,
        ]);

        return redirect()->route('receptionist.booking_receipt', ['sessionID' => $bookingSessionID]);
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
