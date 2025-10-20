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

<div id="main-layout" class="min-h-screen ml-[15rem] p-8 transition-all" style="width: calc(100vw - 15rem);">

    <!-- HEADER -->
    <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-wide text-gray-800 uppercase">Check-Out {{ $booking->guestname }}</h1>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- LEFT: Receipt -->
        <div class="flex-1 bg-white border border-gray-200 rounded-2xl shadow-lg p-6 overflow-y-auto max-h-[80vh]">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-14 mb-4">

            <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Booking Summary</h2>

            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-semibold">Date:</span> {{ $today }}</p>
                <p><span class="font-semibold">Guest:</span> {{ $booking->guestname }}</p>
                <p><span class="font-semibold">Guest Count:</span> {{ $booking->guestamount }}</p>
                <p><span class="font-semibold">Adult:</span> {{ $booking->adultguest }}</p>
                <p><span class="font-semibold">Children:</span> {{ $booking->childguest }}</p>
            </div>

            <hr class="my-4 border-gray-300"/>

            <!-- Rooms -->
            @if($room->isNotEmpty())
                <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Rooms ({{ $room->count() }})</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                    @foreach($room as $r)
                        <li>{{ $r->roomnum }} - {{ $r->roomtype }}: ₱ {{ number_format($r->price, 2) }}</li>
                    @endforeach
                </ul>
            @else
                <h3 class="font-semibold text-sm text-gray-700">No rooms</h3>
            @endif

            <!-- Cottages -->
            @if($cottage->isNotEmpty())
                <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Cottages ({{ $cottage->count() }})</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                    @foreach($cottage as $c)
                        <li>{{ $c->cottagename }}: ₱ {{ number_format($c->price, 2) }}</li>
                    @endforeach
                </ul>
            @else
                <h3 class="font-semibold text-sm text-gray-700">No cottages</h3>
            @endif

            <!-- Amenities -->
            @if($booking->amenityID)
                <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Amenity</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                    <li>{{ $booking->amenityname }} - Adult: ₱ {{ $booking->adultprice }}, Children: ₱ {{ $booking->childprice }}</li>
                </ul>
            @else
                <h3 class="font-semibold text-sm text-gray-700">No amenities</h3>
            @endif

            <hr class="my-4 border-gray-300"/>

            @php
                $now = \Carbon\Carbon::now();
                $bookingStart = \Carbon\Carbon::parse($booking->bookingstart);
                $bookingEnd = \Carbon\Carbon::parse($booking->bookingend);

                $nights = $bookingStart->diffInDays($bookingEnd);

                $roomTotal = $room->sum(fn($r) => ($r->price ?? 0) * ($r->quantity ?? 1));
                $cottageTotal = $cottage->sum(fn($c) => $c->price ?? 0);
                $amenityTotal = ($booking->adultprice ?? 0) * ($booking->adultguest ?? 0)
                              + ($booking->childprice ?? 0) * ($booking->childguest ?? 0);

                $subtotal = $roomTotal + $cottageTotal + $amenityTotal;

                $discountAmount = $billing->flatamount ?? 0;
                if(!$discountAmount && $billing->percentamount) {
                    $discountAmount = $subtotal * ($billing->percentamount / 100);
                }

                $totalAfterDiscount = $subtotal - $discountAmount;
                $amountPaid = $payment->totaltender ?? 0;
                $remainingBalance = $billing->totalamount ?? max(0, $totalAfterDiscount - $amountPaid);
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

                <p><span class="font-semibold">Subtotal:</span> ₱ <span id="subtotal">{{ number_format($subtotal, 2) }}</span></p>
                <p><span class="font-semibold">Discount:</span> ₱ {{ number_format($discountAmount, 2) }}</p>
                <p><span class="font-semibold">Additional Charge:</span> ₱ <span id="add-charge">0.00</span></p>

                <hr class="border-gray-300 my-2">

                <p class="font-semibold text-lg">Total Amount: ₱ <span id="total-after-charge">{{ number_format($totalAfterDiscount, 2) }}</span></p>
                <p class="font-semibold text-lg">Amount Paid: ₱ {{ number_format($amountPaid, 2) }}</p>
                <p class="font-semibold text-lg text-red-600">Remaining Balance: ₱ <span id="remaining-balance">{{ number_format($remainingBalance, 2) }}</span></p>
            </div>
        </div>

        <!-- RIGHT: Payment -->
        <form method="POST" action="{{ url('receptionist/checkout/' . $booking->bookingID) }}" class="bg-white border border-gray-200 shadow-lg rounded-2xl p-6 w-full lg:w-[28rem] flex flex-col justify-between">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Additional Charges</h2>
                    <label class="block mb-2">Charge Amount:
                        <input type="number" id="addcharge-input" name="addcharge" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-gray-600 focus:border-gray-600" step="0.01">
                    </label>
                    <label class="block mb-2">Description:
                        <input type="text" name="chargedesc" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-gray-600 focus:border-gray-600">
                    </label>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Payment Method</h2>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment" value="cash" class="text-black focus:ring-gray-600" required>
                            <span>Cash</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment" value="gcash" class="text-black focus:ring-gray-600" required>
                            <span>GCash</span>
                        </label>
                    </div>
                </div>

                <div id="cash-amount-wrapper" class="hidden">
                    <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                    <input type="number" name="amount_paid" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-gray-600 focus:border-gray-600" min="0" step="0.01" placeholder="Enter amount">
                </div>
            </div>

            <div class="flex justify-between items-center mt-8">
                <button type="button" id="cancel-button" class="border border-gray-700 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-800 hover:text-white transition">Cancel</button>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition">Submit</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="fixed bottom-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">{{ session('error') }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cashRadio = document.querySelector('input[value="cash"]');
    const gcashRadio = document.querySelector('input[value="gcash"]');
    const cashWrapper = document.getElementById('cash-amount-wrapper');
    const cancelButton = document.getElementById('cancel-button');

    const addChargeInput = document.getElementById('addcharge-input');
    const addChargeDisplay = document.getElementById('add-charge');
    const totalAfterCharge = document.getElementById('total-after-charge');
    const remainingBalanceDisplay = document.getElementById('remaining-balance');
    const subtotalDisplay = parseFloat(document.getElementById('subtotal').textContent.replace(/,/g, ''));
    const baseRemainingBalance = parseFloat(remainingBalanceDisplay.textContent.replace(/,/g, ''));

    function toggleCashInput() {
        cashWrapper.classList.toggle('hidden', !cashRadio.checked);
    }

    cashRadio.addEventListener('change', toggleCashInput);
    gcashRadio.addEventListener('change', toggleCashInput);

    cancelButton.addEventListener('click', () => window.history.back());

    // Update charge dynamically
    addChargeInput.addEventListener('input', function(){
        const charge = parseFloat(this.value) || 0;

        // Update additional charge display
        addChargeDisplay.textContent = charge.toFixed(2);

        // Update total amount
        totalAfterCharge.textContent = (subtotalDisplay + charge).toFixed(2);

        // Update remaining balance
        remainingBalanceDisplay.textContent = (baseRemainingBalance + charge).toFixed(2);
    });
});

</script>

</body>
</html>
