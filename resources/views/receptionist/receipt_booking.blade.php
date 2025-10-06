<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <div id="layout-header">
                <h1>Booking Payment</h1>
            </div>

            <div class="receipt-wrapper">
                <div class="receipt">
                    <h2>Booking Information</h2>
                    <p><span>Guest: </span>{{ $booking['firstname'] . ' ' . $booking['lastname'] }}</p>
                    <p><span>Guest Amount: </span>{{ $booking['guestamount'] }}</p>
                    <hr/>

                    @if($room->isEmpty())
                        <p class="no-selection">No rooms selected</p>
                    @else
                        <p>Selected Rooms:</p>
                        <ul>
                            @foreach($room as $r)
                                <li><span>Room:</span> {{ $r->roomnum }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($cottage->isEmpty())
                        <p class="no-selection">No cottage selected</p>
                    @else
                        <p>Selected Cottages:</p>
                        <ul>
                            @foreach($cottage as $c)
                                <li>{{ $c->cottagename }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($amenity->isEmpty())
                        <p class="no-selection">No amenity selected</p>
                    @else
                        <p>Selected Amenity:</p>
                        <ul>
                            @foreach($amenity as $a)
                                <li>{{ $a->amenityname }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <hr/>

                    <h2>Selected Dates</h2>
                    <p><span>Check-in: </span>{{ $booking['checkin'] }}</p>
                    <p><span>Check-out: </span>{{ $booking['checkout'] }}</p>
                    <hr/>

                    <h2>Total</h2>
                    @if(!$room->isEmpty())
                        <p><span>Room Total: ₱</span> {{ number_format($roomprice, 2) }}</p>
                    @endif
                    @if(!$cottage->isEmpty())
                        <p><span>Cottage Total: ₱</span> {{ number_format($cottageprice, 2) }}</p>
                    @endif
                    @if(!$amenity->isEmpty())
                        <p><span>Adult Total: ₱</span> {{ number_format($adultprice, 2) }}</p>
                        <p><span>Child Total: ₱</span> {{ number_format($childprice, 2) }}</p>
                        <p><span>Amenity Total: ₱</span> {{ number_format($amenityprice, 2) }}</p>
                    @endif

                    <p><span>SubTotal: ₱</span> <span id="subtotal-receipt">{{ $totalprice }}</span></p>
                    <p><span>Discount: </span><span id="discount-receipt">0%</span></p>
                    <p><span>Total: </span><span id="total-receipt">₱ {{ $totalprice }}</span></p>
                    <p><span>Amount Due: </span><span id="amount-due-receipt">₱ {{ $totalprice }}</span></p>

                    <h2>Payment</h2>
                    <p><span>Amount Tendered: </span><span id="amount-tendered">₱ 0.00</span></p>
                    <p><span>Total Change:</span> <span id="change-receipt">₱ 0.00</span></p>
                </div>

                <form id="booking-form" method="POST" action="{{ url('receptionist/confirm_booking/' . $sessionID) }}">
                    @csrf

                    <div class="payment">
                        <div class="label-container"><h2>Payment Information</h2></div>

                        <div class="payment-type-wrapper">
                            <label for="full-payment">
                                Full Payment:
                                <div class="payment-type-selection"><i class="fas fa-credit-card fa-2x"></i></div>
                                <input class="radio" type="radio" id="full-payment" name="payment_type" value="full" checked>
                            </label>

                            <label for="downpayment">
                                50% Downpayment:
                                <div class="payment-type-selection"><i class="fas fa-percentage fa-2x"></i></div>
                                <input class="radio" type="radio" id="downpayment" name="payment_type" value="downpayment">
                            </label>
                            @error('payment_type')
                                <small style="color: red; font-style: italic;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="payment-selection-wrapper">
                            <label for="cash">
                                Cash:
                                <div class="payment-selection"><i class="fas fa-money-bill-wave fa-2x"></i></div>
                                <input class="radio" type="radio" id="cash" name="payment" value="cash" required>
                            </label>

                            <label for="gcash">
                                Gcash:
                                <div class="payment-selection"><i class="fas fa-mobile-alt fa-2x"></i></div>
                                <input class="radio" type="radio" id="gcash" name="payment" value="gcash" required>
                            </label>
                            @error('payment')
                                <small style="color: red; font-style: italic;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div id="cash-amount-wrapper" style="display: none; margin-top: 10px;">
                            <label for="cash-amount">Amount Paid:</label>
                            <input class="input" type="number" id="cash-amount" name="cashamount" min="0" step="0.01" placeholder="Enter amount paid" value="{{ old('cashamount') }}">
                            @error('cashamount')
                                <small style="color: red; font-style: italic;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="label-container"><h2>Discount Information</h2></div>
                        <label for="discount">Discount
                            <select class="input" name="discount" id="discount">
                                <option value="0" data-amount="0">No Discount</option>
                                @foreach($discount as $d)
                                    <option value="{{ $d->discountID }}" data-amount="{{ $d->amount }}">
                                        {{ $d->name }}: {{ $d->amount }}%
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        @error('discount')
                            <small style="color: red; font-style: italic;">{{ $message }}</small>
                        @enderror

                        <div class="button-container">
                            <button type="button" id="cancel-button" class="form-button">Cancel</button>
                            <button type="submit" id="submit-button" class="form-button">Submit Booking</button>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('error'))
                <div class="alert-message">
                    <h2>{{ session('error') }}</h2>
                </div>
            @endif
        </div>
    </div>
<style>
    #booking{color:orange;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:85%;
        height:100vh;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
        margin-right:.7rem;
        overflow-y: hidden;
        overflow-x: hidden;
        gap:.5rem;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .9rem;
    }
    p{
        display: flex;
        justify-content: space-between;
    }
    .receipt-wrapper{
        display: flex;
        flex-direction: row;
        position:relative;
        width:100%;
        height:100%;
        gap:.5rem;
    }
    .receipt{
        background:white;
        display:flex;
        flex-direction: column;
        position:relative;
        height:90%;
        width:30%;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
        border:1px solid black;
        padding:1rem;
        font-size:.8rem;
        overflow-y:auto;
    }
    hr {
        border: 1px solid black;  
        margin: 1rem 0;  
        width: 100%;
    }
    .no-selection{
        color:red;
        font-weight:bold;
    }
    .receipt span{
        font-weight:bold;
    }
    .label-container{
        display: flex;
        flex-direction: row;
        margin-bottom: 1rem;
        background:black;
        width: 100%;
        height:3rem;
        justify-content: space-between;
        align-items: center;
        padding:.5rem;
        font-size:.7rem;
        color:white;
        border-radius:.7rem;
    }
    .payment{
        display:flex;
        flex-direction: column;
        position:absolute;
        width:69.5%;
        height:90%;
        background:white;   
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
        border:1px solid black;
        padding:1rem;
        font-size:.8rem;
        gap:.5rem;
    }
    .payment-type-wrapper{
        display:flex;
        flex-direction: row;
        gap:.5rem;
        margin-bottom: 1rem;
    }
    .payment-type-selection{
        display:flex;
        height:4rem;
        width:8rem;
        border-radius:.7rem;
        justify-content:center;
        align-items:center;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        gap:.5rem;
        cursor:pointer;
        transition:all .2s ease;
        background: #f0f0f0;
    }
    .payment-type-selection:hover{
        background:orange;
        color:white;
        scale:1.05;
    }
    .payment-selection-wrapper{
        display:flex;
        flex-direction: row;
        gap:.5rem;
    }
    .payment-selection{
        display:flex;
        height:5rem;
        width:7rem;
        border-radius:.7rem;
        justify-content:center;
        align-items:center;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        gap:.5rem;
        cursor:pointer;
        transition:all .2s ease;
    }
    .payment-selection:hover{
        background:orange;
        color:white;
        scale:1.1;
    }
    .input{
        display:flex;
        width:100%;
        background: white;
        border:1px solid black;
        border-radius:.5rem;
        padding:.5rem;
        font-size: .8rem;
    }
    .button-container {
        position:absolute;
        display: flex;
        flex-direction: row;
        margin-top: auto;
        bottom:1rem;
    }.form-button{
        background: rgb(255, 255, 255);
        color: rgb(0, 0, 0);
        border: none;
        padding: .5rem 1rem;
        border-radius: .5rem;
        cursor: pointer;
        font-size: .8rem;
        margin-right: .5rem;
        transition: all .2s ease-in-out;
        border:rgb(0, 0, 0) solid 1px;
        box-shadow: .1rem .1rem 0 rgb(0, 0, 0);
        margin-bottom: 1rem;
    }
    .form-button:hover{
        background: orange;
        color: black;
        transform: translateY(-.1rem);
    }  

    .alert-message{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: fixed;
        right: 50%;
        transform: translate(50%, 0);
        bottom: 1rem;
        height: fit-content;
        min-height: 10rem;
        max-height: 30rem;
        width: fit-content;
        min-width: 20rem;
        max-width: 90vw;
        background: rgb(255, 255, 255);
        z-index: 1000;
        border-radius: 1rem;
        box-shadow: 0 0 1rem rgba(0,0,0,0.5);
        margin: auto;
        padding: 1rem;
        flex-wrap: wrap;
        word-wrap: break-word;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const message = document.querySelector('.alert-message');
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
        const fullPaymentRadio = document.getElementById('full-payment');
        const downpaymentRadio = document.getElementById('downpayment');

        const roomPrice = parseFloat("{{ $roomprice }}") || 0;
        const cottagePrice = parseFloat("{{ $cottageprice }}") || 0;
        const amenityPrice = parseFloat("{{ $amenityprice }}") || 0;

        let subtotal = roomPrice + cottagePrice + amenityPrice;
        let discountAmount = 0;
        let totalAmount = subtotal;
        let amountDue = subtotal;

        if (!amountPaidInput || !amountTenderedField || !discountSelect || !cashRadio || !gcashRadio) {
            console.error("One or more required elements are missing.");
            return;
        }
        
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        function updateAmountTendered() {
            const amountPaid = parseFloat(amountPaidInput.value) || 0;
            amountTenderedField.textContent = '₱ ' + amountPaid.toFixed(2);
            calculateChange(amountPaid);
        }

        function calculateChange(amountPaid) {
            const change = amountPaid - amountDue;
            changeField.textContent = '₱ ' + (change >= 0 ? change.toFixed(2) : '0.00');
            amountPaidInput.style.borderColor = amountPaid < amountDue ? 'red' : '';
        }

        function updateReceipt() {
            // Update subtotal display
            subtotalField.textContent = '₱ ' + subtotal.toFixed(2);

            // Get selected discount
            const selectedOption = discountSelect.options[discountSelect.selectedIndex];
            const discountDecimal = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            const discountPercentage = discountDecimal * 100;

            // Calculate discount amount
            if (discountDecimal > 0) {
                discountAmount = discountDecimal * subtotal;
            } else {
                discountAmount = 0;
            }

            // Calculate total after discount
            totalAmount = subtotal - discountAmount;

            // Calculate amount due based on payment type
            if (downpaymentRadio.checked) {
                amountDue = totalAmount * 0.5; // 50% downpayment
            } else {
                amountDue = totalAmount; // Full payment
            }

            // Update display fields
            discountField.textContent = discountPercentage + '%';
            totalPriceField.textContent = '₱ ' + totalAmount.toFixed(2);
            amountDueField.textContent = '₱ ' + amountDue.toFixed(2);

            // Update amount tendered and change
            if (cashRadio.checked) {
                updateAmountTendered();
            } else {
                // For GCash, amount tendered equals amount due
                amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
                changeField.textContent = '₱ 0.00';
            }
        }

        function toggleCashAmount() {
            if (cashRadio.checked) {
                cashAmountWrapper.style.display = 'block';
                updateAmountTendered();
            } else {
                cashAmountWrapper.style.display = 'none';
                amountPaidInput.value = '';
                // For GCash, set amount tendered to amount due
                amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
                changeField.textContent = '₱ 0.00';
            }
        }

        // Event listeners
        discountSelect.addEventListener('change', updateReceipt);
        amountPaidInput.addEventListener('input', updateAmountTendered);
        cashRadio.addEventListener('change', function() {
            toggleCashAmount();
            updateReceipt();
        });
        gcashRadio.addEventListener('change', function() {
            toggleCashAmount();
            updateReceipt();
        });
        fullPaymentRadio.addEventListener('change', updateReceipt);
        downpaymentRadio.addEventListener('change', updateReceipt);

        // Initialize
        updateReceipt();
        toggleCashAmount();
    });
</script>
</html>