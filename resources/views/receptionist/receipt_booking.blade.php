<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">
    @include('components.receptionist_sidebar')

    <!-- MAIN LAYOUT -->
    <div 
        id="main-layout" 
        class="min-h-screen bg-gray-100 ml-[15rem] p-8 overflow-y-auto transition-all"
        style="width: calc(100vw - 15rem);"
    >
        <!-- HEADER -->
        <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-wide text-gray-800 uppercase">Booking Payment</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ==========================
                 LEFT SIDE — BOOKING + PAYMENT SUMMARY
            =========================== -->
            <div class="flex-1 bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold uppercase tracking-wide text-gray-800 mb-3">Booking Information</h2>

                <div class="space-y-2 mb-4 text-sm">
                    <p><span class="font-semibold">Guest:</span> {{ $booking['firstname'] . ' ' . $booking['lastname'] }}</p>
                    <p><span class="font-semibold">Guest Amount:</span> {{ $booking['guestamount'] }}</p>
                </div>

                <hr class="my-4 border-gray-300"/>

                @if(!$rooms->isEmpty())
                    <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Selected Rooms</h3>
                    <ul class="list-disc list-inside text-sm mb-4 text-gray-600">
                        @foreach($rooms as $r)
                            <li class="flex justify-between">
                                <div>
                                    <span class="font-semibold">{{ $r->roomtype }}</span>
                                    <span class="text-gray-500">× {{ $r->quantity }}</span><br>
                                    <span class="text-xs text-gray-500">
                                        {{ $r->nights }} night(s) @ ₱{{ number_format($r->price_per_night, 2) }} per night
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    ₱{{ number_format($r->total_price, 2) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h3 class="text-sm text-gray-600 italic">No room selected</h3>
                @endif



                @if(!$cottage->isEmpty())
                    <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Selected Cottages</h3>
                    <ul class="list-disc list-inside text-sm mb-4 text-gray-600">
                        @foreach($cottage as $c)
                            <li>{{ $c->cottagename }}</li>
                        @endforeach
                    </ul>
                @endif

                @if(!$amenity->isEmpty())
                    <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Selected Amenities</h3>
                    <ul class="list-disc list-inside text-sm mb-4 text-gray-600">
                        @foreach($amenity as $a)
                            <li>{{ $a->amenityname }}</li>
                        @endforeach
                    </ul>
                @endif


                @if(!empty($inclusionsByRoom))
                    <hr class="my-4 border-gray-300"/>
                    <h3 class="font-semibold text-sm uppercase text-gray-700 mb-1">Room Inclusions</h3>

                    @foreach($inclusionsByRoom as $roomNum => $items)
                        <p class="font-semibold text-gray-800 mt-2">Room {{ $roomNum }}</p>
                        <ul class="list-disc list-inside text-sm text-gray-600 mb-3">
                            @foreach($items as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif

                <hr class="my-4 border-gray-300"/>

                <h2 class="text-lg font-semibold mb-2 text-gray-800 uppercase tracking-wide">Selected Dates</h2>
                <div class="space-y-1 text-sm text-gray-700 mb-4">
                    <p><span class="font-semibold">Check-in:</span> {{ $booking['checkin'] }}</p>
                    <p><span class="font-semibold">Check-out:</span> {{ $booking['checkout'] }}</p>
                </div>

                <hr class="my-4 border-gray-300"/>

                <!-- ==========================
    PAYMENT SUMMARY (CLEAN VERSION)
=========================== -->
<section class="mt-8">
    <h2 class="text-lg font-semibold uppercase tracking-wide text-gray-800 mb-3">Payment Summary</h2>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mt-4 space-y-4">

        <!-- ✅ ROOM PRICE BREAKDOWN TABLE -->
        @if(!$rooms->isEmpty())
            <h3 class="font-semibold text-sm uppercase text-gray-700 mb-2">Room Breakdown</h3>
            <table class="w-full text-sm text-gray-700 border-collapse mb-4">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-2">Room Type</th>
                        <th class="text-center py-2">Qty</th>
                        <th class="text-center py-2">Price/Night</th>
                        <th class="text-center py-2">Nights</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $r)
                        <tr class="border-b">
                            <td class="py-2">{{ $r->roomtype }}</td>
                            <td class="text-center py-2">{{ $r->quantity }}</td>
                            <td class="text-center py-2">₱{{ number_format($r->price_per_night, 2) }}</td>
                            <td class="text-center py-2">{{ $r->nights }}</td>
                            <td class="text-right py-2 font-semibold">₱{{ number_format($r->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t">
                        <td colspan="4" class="text-right font-bold py-2">Room Total:</td>
                        <td class="text-right font-bold py-2">₱{{ number_format($roomprice, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <div class="border-t border-gray-300 my-4"></div>

        <!-- RECEIPT BREAKDOWN -->
        <div class="text-sm space-y-2 text-gray-700">
            <p class="flex justify-between">
                <span class="font-semibold">Subtotal:</span>
                <span id="subtotal-receipt">₱{{ number_format($totalprice, 2) }}</span>
            </p>
            <p class="flex justify-between">
                <span class="font-semibold">Discount:</span>
                <span id="discount-receipt">0%</span>
            </p>
            <p class="flex justify-between">
                <span class="font-semibold">Total After Discount:</span>
                <span id="total-receipt">₱{{ number_format($totalprice, 2) }}</span>
            </p>
            <p class="flex justify-between">
                <span class="font-semibold">Amount Paid:</span>
                <span id="amount-tendered">₱0.00</span>
            </p>
            <p class="flex justify-between">
                <span class="font-semibold">Change:</span>
                <span id="change-receipt">₱0.00</span>
            </p>
        </div>

        <div class="border-t border-gray-300 my-4"></div>

        <!-- INDIVIDUAL CATEGORY TOTALS -->
        <div class="space-y-2 text-gray-700 text-sm sm:text-base">
            <div class="flex justify-between">
                <span>Room Total:</span>
                <span class="font-semibold">₱{{ number_format($roomprice, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Cottage Total:</span>
                <span class="font-semibold">₱{{ number_format($cottageprice, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Amenity Total:</span>
                <span class="font-semibold">₱{{ number_format($amenityprice, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Adult Guests:</span>
                <span class="font-semibold">₱{{ number_format($adultprice, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Child Guests:</span>
                <span class="font-semibold">₱{{ number_format($childprice, 2) }}</span>
            </div>
        </div>

        <div class="border-t border-gray-300 my-4"></div>

        <!-- TOTAL AMOUNT + AMOUNT DUE (BOTTOM) -->
        <div class="flex justify-between items-center">
            <span class="text-sm tracking-wide uppercase font-semibold text-gray-600">Total Amount</span>
            <span class="text-2xl sm:text-3xl font-bold text-gray-900">
                ₱{{ number_format($totalprice, 2) }}
            </span>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-sm tracking-wide uppercase font-bold text-gray-800">Amount Due</span>
            <span id="amount-due-receipt" class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                ₱{{ number_format($totalprice, 2) }}
            </span>
        </div>
    </div>
</section>


            </div>

            <!-- ==========================
                 RIGHT SIDE — PAYMENT OPTIONS
            =========================== -->
            <form 
                id="booking-form" 
                method="POST" 
                action="{{ url('receptionist/confirm_booking/' . $sessionID) }}" 
                class="bg-white border border-gray-200 shadow-lg rounded-2xl p-6 w-full lg:w-[28rem] flex flex-col justify-between"
            >
                @csrf

                <div class="flex flex-col space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Payment Type</h2>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_type" value="full" checked class="text-black focus:ring-gray-600">
                                <span>Full Payment</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_type" value="downpayment" class="text-black focus:ring-gray-600">
                                <span>50% Downpayment</span>
                            </label>
                        </div>
                    </div>

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

                    <div>
                        <h2 class="text-lg font-semibold mb-3 text-gray-800 uppercase tracking-wide">Discount</h2>
                        <select 
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-gray-600 focus:border-gray-600" 
                            name="discount" 
                            id="discount"
                        >
                            <option value="0" data-amount="0">No Discount</option>
                            @foreach($discount as $d)
                                <option value="{{ $d->discountID }}" data-amount="{{ $d->percentamount }}">
                                    {{ $d->name }}: {{ $d->amount }}%
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <button 
                        type="button" 
                        id="cancel-button" 
                        class="border border-gray-700 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-800 hover:text-white transition"
                    >
                        Cancel
                    </button>
                    <button type="submit" id="submit-button" class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition">Submit</button>
                </div>
            </form>
        </div>

        @if (session('error'))
            <div class="fixed bottom-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // === ELEMENTS ===
    const amountPaidInput = document.getElementById('cash-amount');
    const amountTenderedField = document.getElementById('amount-tendered');
    const discountSelect = document.getElementById('discount');
    const discountField = document.getElementById('discount-receipt');
    const subtotalField = document.getElementById('subtotal-receipt');
    const totalPriceField = document.getElementById('total-receipt');
    const amountDueField = document.getElementById('amount-due-receipt');
    const changeField = document.getElementById('change-receipt');
    const cashAmountWrapper = document.getElementById('cash-amount-wrapper');
    const cashRadio = document.getElementById('cash');
    const gcashRadio = document.getElementById('gcash');
    const fullPaymentRadio = document.querySelector('input[name="payment_type"][value="full"]');
    const downpaymentRadio = document.querySelector('input[name="payment_type"][value="downpayment"]');

    // === BASE PRICES ===
    const roomPrice = parseFloat("{{ $roomprice }}") || 0;
    const cottagePrice = parseFloat("{{ $cottageprice }}") || 0;
    const amenityPrice = parseFloat("{{ $amenityprice }}") || 0;
    const adultPrice = parseFloat("{{ $adultprice }}") || 0;
    const childPrice = parseFloat("{{ $childprice }}") || 0;

    let subtotal = roomPrice + cottagePrice + amenityPrice + adultPrice + childPrice;
    let discountPercent = 0;
    let discountAmount = 0;
    let totalAfterDiscount = subtotal;
    let amountDue = subtotal;

    // === Convert fractional or numeric discounts ===
    function normalizeDiscount(val) {
        const v = parseFloat(val);
        if (!Number.isFinite(v)) return 0;
        return v <= 1 ? v * 100 : v; // treat 0.2 as 20%
    }

    // === Update displayed values ===
    function updateReceipt() {
        // Base subtotal
        subtotal = roomPrice + cottagePrice + amenityPrice + adultPrice + childPrice;

        // Get discount %
        const selectedOption = discountSelect.options[discountSelect.selectedIndex];
        discountPercent = normalizeDiscount(selectedOption?.getAttribute('data-amount'));
        discountAmount = subtotal * (discountPercent / 100);

        // Compute total after discount
        totalAfterDiscount = subtotal - discountAmount;

        // Compute actual amount due
        amountDue = downpaymentRadio.checked ? totalAfterDiscount * 0.5 : totalAfterDiscount;

        // Update DOM
        subtotalField.textContent = '₱ ' + subtotal.toFixed(2);
        discountField.textContent = discountPercent + '%';
        totalPriceField.textContent = '₱ ' + totalAfterDiscount.toFixed(2);
        amountDueField.textContent = '₱ ' + amountDue.toFixed(2);

        // Animate Amount Due for clarity
        amountDueField.classList.add('scale-110');
        setTimeout(() => amountDueField.classList.remove('scale-110'), 200);

        // Update payment display
        if (cashRadio.checked) {
            updateAmountTendered();
        } else {
            amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
            changeField.textContent = '₱ 0.00';
        }
    }

    // === Update Amount Paid + Change ===
    function updateAmountTendered() {
        const amountPaid = parseFloat(amountPaidInput.value) || 0;
        amountTenderedField.textContent = '₱ ' + amountPaid.toFixed(2);
        const change = amountPaid - amountDue;
        changeField.textContent = '₱ ' + (change >= 0 ? change.toFixed(2) : '0.00');
        amountPaidInput.style.borderColor = amountPaid < amountDue ? 'red' : '';
    }

    // === Show/Hide Cash Input ===
    function toggleCashAmount() {
        if (cashRadio.checked) {
            cashAmountWrapper.classList.remove('hidden');
            updateAmountTendered();
        } else {
            cashAmountWrapper.classList.add('hidden');
            amountPaidInput.value = '';
            amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
            changeField.textContent = '₱ 0.00';
        }
    }

    // === EVENT LISTENERS ===
    discountSelect.addEventListener('change', updateReceipt);
    amountPaidInput.addEventListener('input', updateAmountTendered);
    cashRadio.addEventListener('change', () => { toggleCashAmount(); updateReceipt(); });
    gcashRadio.addEventListener('change', () => { toggleCashAmount(); updateReceipt(); });
    fullPaymentRadio.addEventListener('change', updateReceipt);
    downpaymentRadio.addEventListener('change', updateReceipt);

    // === INIT ===
    updateReceipt();
    toggleCashAmount();

    const cancelBtn = document.getElementById('cancel-button');
    cancelBtn.addEventListener('click', () => {
        window.history.back();
    });
});
</script>