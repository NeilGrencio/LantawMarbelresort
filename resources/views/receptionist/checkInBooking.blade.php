<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort</title>
<link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

@include('components.receptionist_sidebar')

<!-- MAIN LAYOUT -->
<div id="main-layout" class="min-h-screen ml-[15rem] p-8 transition-all" style="width: calc(100vw - 15rem);">

    <!-- HEADER -->
    <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-wide text-gray-800 uppercase">Check-in {{ $booking->guestname }}</h1>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- LEFT: Booking + Receipt -->
        <div class="flex-1 bg-white border border-gray-200 rounded-2xl shadow-lg p-6 overflow-y-auto max-h-[80vh]">
            
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-14 mb-4">

            <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Booking Summary</h2>

            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-semibold">Guest:</span> {{ $booking->guestname }}</p>
                <p><span class="font-semibold">Guest Count:</span> {{ $booking->guestamount }}</p>
                <p><span class="font-semibold">Adult:</span> {{ $booking->adultguest }}</p>
                <p><span class="font-semibold">Children:</span> {{ $booking->childguest }}</p>
            </div>

            <hr class="my-4 border-gray-300"/>

            @if($room->isNotEmpty())
            <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Rooms</h3>
            <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                @foreach($room as $r)
                <li class="flex justify-between">
                    <span>{{ $r->roomtype }}: ₱ {{ number_format($r->price,2) }}</span>
                </li>
                @endforeach
            </ul>
            @endif

            @if($cottage->isNotEmpty())
            <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Cottages</h3>
            <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                @foreach($cottage as $c)
                <li>{{ $c->cottagename }}: ₱ {{ number_format($c->price,2) }}</li>
                @endforeach
            </ul>
            @endif

            @if($booking->amenityID)
            <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Amenity</h3>
            <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                <li>{{ $booking->amenityname }} - Adult: ₱ {{ $booking->adultprice }}, Children: ₱ {{ $booking->childprice }}</li>
            </ul>
            @endif

            <hr class="my-4 border-gray-300"/>

            @php
use Carbon\Carbon;

$now = Carbon::now();
$bookingStart = Carbon::parse($booking->bookingstart);
$bookingEnd = Carbon::parse($booking->bookingend);

// Calculate nights
$nights = $bookingStart->diffInDays($bookingEnd);

// Room total
$roomTotal = $room->sum(function($r) use ($nights) {
    return ($r->price ?? 0) * ($r->quantity ?? 1) * $nights;
});

// Cottage total
$cottageTotal = $cottage->sum(function($c) {
    return $c->price ?? 0;
});

// Amenity total
$amenityTotal = ($booking->adultprice ?? 0) * ($booking->adultguest ?? 0) +
                ($booking->childprice ?? 0) * ($booking->childguest ?? 0);

// Early check-in breakdown
$earlyCheckInHours = 0;
$earlyCheckInRate = 200;
$earlyCheckInFee = 0;

if ($now->lt($bookingStart)) {
    // Minutes difference: booking start minus now
    $minutesEarly = $bookingStart->diffInMinutes($now);
    $earlyCheckInHours = ceil($minutesEarly / 60); // round up to nearest hour
    $earlyCheckInFee = $earlyCheckInHours * $earlyCheckInRate;
}
if($earlyCheckInFee < 0 ){
    $earlyCheckInFee = $earlyCheckInFee * -1;
} else {
    $earlyCheckInFee = $earlyCheckInFee * 1;
}

// Subtotal including early check-in
$subtotal = $roomTotal + $cottageTotal + $amenityTotal + $earlyCheckInFee;

// Apply discount
$discountPercent = $discount->percentamount ?? null;
$discountFlat = $discount->flatamount ?? null;
$discountAmount = 0;

if ($discountFlat) {
    $discountAmount = $discountFlat;
} elseif ($discountPercent) {
    $discountAmount = $subtotal * ($discountPercent / 100);
}

// Total after discount
$totalAfterDiscount = $subtotal - $discountAmount;

// Amount paid
$amountPaid = $payment->totaltender ?? 0;

// Remaining balance
$remainingBalance = max(0, $totalAfterDiscount - $amountPaid);
@endphp

<div class="space-y-2 text-sm text-gray-700">
    <p><span class="font-semibold">Check-in:</span> {{ $booking->bookingstart }}</p>
    <p><span class="font-semibold">Check-out:</span> {{ $booking->bookingend }}</p>


    <hr class="border-gray-300 my-2">

    <p><span class="font-semibold">Room Total:</span> ₱ {{ number_format($roomTotal, 2) }}</p>
    <p><span class="font-semibold">Cottage Total:</span> ₱ {{ number_format($cottageTotal, 2) }}</p>
    <p><span class="font-semibold">Amenity Total:</span> ₱ {{ number_format($amenityTotal, 2) }}</p>

    @if($earlyCheckInFee > 0)
        <p class="font-semibold">Early Check-in Fee Breakdown:</p>
        <ul class="list-disc list-inside text-gray-600 mb-2">
            <li>{{ $earlyCheckInHours }} hour{{ $earlyCheckInHours > 1 ? 's' : '' }} × ₱{{ number_format($earlyCheckInRate,2) }} = ₱{{ number_format($earlyCheckInFee,2) }}</li>
        </ul>
    @endif

    <hr class="border-gray-300 my-2">

    <p><span class="font-semibold">Subtotal:</span> ₱ {{ number_format($subtotal, 2) }}</p>
    <p><span class="font-semibold">Discount:</span> 
        @if($discountFlat)
            ₱ {{ number_format($discountFlat, 2) }}
        @elseif($discountPercent)
            {{ $discountPercent }}%
        @else
            0%
        @endif
    </p>

    <hr class="border-gray-300 my-2">

    <p class="font-semibold text-lg">Total Amount: ₱ {{ number_format($totalAfterDiscount, 2) }}</p>
    <p class="font-semibold text-lg">Amount Paid: ₱ {{ number_format($amountPaid, 2) }}</p>
    <p class="font-semibold text-lg text-red-600">Remaining Balance: ₱ {{ number_format($remainingBalance, 2) }}</p>
</div>


        </div>

        <!-- RIGHT: Payment Options -->
        <form 
            method="POST" 
            action="{{ url('receptionist/checkin/' . $booking->bookingID) }}" 
            class="bg-white border border-gray-200 shadow-lg rounded-2xl p-6 w-full lg:w-[28rem] flex flex-col justify-between"
        >
            @csrf

            <div class="flex flex-col space-y-6">

                <div>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Payment Method</h2>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" id="cash" name="payment" value="cash" class="text-black focus:ring-gray-600" required>
                            <span>Cash</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" id="gcash" name="payment" value="gcash" class="text-black focus:ring-gray-600" required>
                            <span>GCash</span>
                        </label>
                    </div>
                </div>

                <div id="cash-amount-wrapper" class="hidden">
                    <label for="cash-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                    <input 
                        type="number" 
                        id="cash-amount" 
                        name="cashamount" 
                        class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-gray-600 focus:border-gray-600" 
                        min="0" step="0.01" placeholder="Enter amount">
                </div>

            </div>

            <div class="flex justify-between items-center mt-8">
                <button type="button" id="cancel-button" class="border border-gray-700 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-800 hover:text-white transition">Cancel</button>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition">Submit</button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="fixed bottom-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">
            <p>{{ session('error') }}</p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cashRadio = document.getElementById('cash');
    const gcashRadio = document.getElementById('gcash');
    const cashWrapper = document.getElementById('cash-amount-wrapper');

    function toggleCashInput() {
        cashWrapper.classList.toggle('hidden', !cashRadio.checked);
    }

    cashRadio.addEventListener('change', toggleCashInput);
    gcashRadio.addEventListener('change', toggleCashInput);

    document.getElementById('cancel-button').addEventListener('click', () => {
        window.history.back();
    });
});
</script>

</body>
</html>
