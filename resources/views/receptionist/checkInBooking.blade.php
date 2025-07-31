<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort</title>
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    #check{color:orange;}
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
        gap:.5rem;
        transition: width 0.3s ease-in-out;
        margin-left:15rem;
        margin-right:.7rem;
        overflow-y: hidden;
        overflow-x: hidden;
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
        font-size: .7rem;
    }
    .content-wrapper{
        display:flex;
        flex-direction: row;
        width:100%;
        height:100%;
        gap:1rem;
    }
    .receipt-container{
        display:flex;
        flex-direction: column;
        background:white;
        border:black;
        border-radius:.5rem;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        padding:1rem;
        width:35%;
        height:89%;
        overflow:hidden;
        transition:all .3s ease;
    }
    .receipt-container:hover{
        overflow-y:auto;
    }

    .receipt-container img{
        object-fit: center;
        height:4rem;
        width:8rem;
        align-self:center;
    }
    .bill-information{
        margin-bottom:-.5rem;
    }
    li{
        margin-top:.5rem;
    }
    .bill-information.logo{
        align-self: center;
    }
    #room,
    #cottage, 
    #amenity,
    #total{
        display:flex;
        justify-content: space-between;
    }
    hr {
        margin: 1rem 0rem 0 0rem ;
        border: none;
        border-top: 1px solid black;
        width: 100%;
    }

    .payment-container{
        display:flex;
        flex-direction: column;
        background:white;
        border-radius:.5rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        padding:1rem;
        width:65%;
        height:89%;
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
    .payment-label{
        width:100%;
        color:white;
        background:black;
        border-radius:.5rem;
        padding-left:.5rem;
        font-size:.7rem;
        margin-bottom:1rem;
    }
    .payment-selection:hover{
        background:orange;
        color:white;
        scale:1.1;
    }

    #payment-content{
        display: flex;
        flex-direction: row;
        gap:1rem;
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
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <div id="layout-header">
                <h1>Check-in {{$booking->guestname}}</h1>
            </div>
            <div class="content-wrapper">
                <div class="receipt-container">
                    <img src="{{asset('images/logo.png')}}" alt="logo"/>
                    <h5 class="bill-information logo">Lantaw Marbel Resort Booking</h5>
                    <hr/>
                    <h5 class="bill-information">Date: <strong id="date">{{$today}}</strong></h5>
                    <h5 class="bill-information">Guest: <strong id="guest">{{$booking->guestname}}</strong></h5>
                    <h5 class="bill-information">Guest Count: <strong id="date">{{$booking->guestamount}}</strong></h5>
                    <h5 class="bill-information">Adult Count: <strong id="date">{{$booking->adultguest}}</strong></h5>
                    <h5 class="bill-information">Children Count: <strong id="date">{{$booking->childguest}}</strong></h5>
                    <hr/>
                    <h5 class="bill-information">Booking:</h5>

                    <!-- ROOM -->
                    @if($room->isNotEmpty())
                        <h5 class="bill-information"><strong>Room * {{$room->count()}}</strong></h5>
                        @foreach($room as $rooms)
                            <li id="room">{{ $rooms->roomnum }}: <strong>₱ {{ $rooms->price }}</strong></li>
                        @endforeach
                    @else
                        <h5 class="bill-information"><strong>No rooms</strong></h5>
                    @endif

                    <!-- COTTAGE -->
                    @if($cottage->isNotEmpty())
                        <h5 class="bill-information"><strong>Cottage * {{$cottage->count()}}</strong></h5>
                        @foreach($cottage as $cottages)
                            <li id="cottage">{{ $cottages->cottagename }}: <strong>₱ {{$cottages->price}}</strong></li>
                        @endforeach
                    @else
                        <h5 class="bill-information"><strong>No cottages</strong></h5>
                    @endif

                    <!-- AMENITY -->
                    @if($booking->amenityID)
                        <h5 class="bill-information"><strong>Amenity: {{ $booking->amenityname }}</strong></h5>
                        <li id="amenity">Adult Price: <strong>₱ {{$booking->adultprice}}</strong></li>
                        <li id="amenity">Children Price: <strong>₱ {{$booking->childprice}}</strong></li>
                    @else
                        <h5 class="bill-information"><strong>No amenity</strong></h5>
                    @endif
                    <hr/>

                    @php
                        $roomtotal = $room->sum('price') ?? 0;
                        $cottagetotal = $cottage->sum('price') ?? 0;
                        $adulttotal = $booking->adultprice * $booking->adultguest ?? 0;
                        $childtotal = $booking->childprice * $booking->childguest ?? 0;

                        $subtotal = $roomtotal + $cottagetotal + $adulttotal + $childtotal;
                        $discount = $billing->amount * 100;
                        $totalafterdiscount = ($discount && $discount != 0) ? $subtotal / $discount : $subtotal;

                        $amountpaid = $payment->totaltender;
                        $remainingbalance = $billing->totalamount;

                    @endphp

                    <h5 class="bill-information" id="total">Room Total: <strong>₱ {{ number_format($roomtotal, 2) }}</strong></h5>
                    <h5 class="bill-information" id="total">Cottage Total: <strong>₱ {{ number_format($cottagetotal, 2) }}</strong></h5>
                    <h5 class="bill-information" id="total">Adult Total: <strong>₱ {{ number_format($adulttotal, 2) }}</strong></h5>
                    <h5 class="bill-information" id="total">Child Total: <strong>₱ {{ number_format($childtotal, 2) }}</strong></h5>
                    <hr/>

                    <h5 class="bill-information" id="total">Subtotal: <strong>₱ {{ number_format($subtotal, 2) }}</strong></h5>
                    <h5 class="bill-information" id="total">Discount: <strong>{{$discount}} %</strong></h5>
                    <h5 class="bill-information" id="total">Total after discount: <strong>₱ {{ number_format($totalafterdiscount, 2) }}</strong></h5>
                    <hr/>

                    <h5 class="bill-information" id="total">Amount Paid: <strong>₱ {{ number_format($amountpaid, 2) }}</strong></h5>
                    <h5 class="bill-information" id="total">Remaining Balance: <strong>₱ {{ number_format($remainingbalance, 2) }}</strong></h5>
                </div>

                <div class="payment-container">
                    <div class="payment-label">
                        <h2>Select Payment Method</h2>
                    </div> 
                    <form action="{{url('receptionist/checkin/' . $booking->bookingID)}}" method="post">
                        @csrf
                        <div id="payment-content">
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
                    </div>
                        <div id="cash-amount-input" style="display: none; margin-top: 1rem;">
                                <label for="amount_paid">Amount Paid:</label>
                                <input type="number" id="amount_paid" name="amount_paid" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="button-container">
                            <button id="cancel-button" type="button" data-url="{{url('receptionist/check-in-out')}}">Cancel</button>
                            <button type="submit">Submit</button>
                        </div>
                    </form >

                </div>
            </div>
            @if (session('success'))
                <div class="alert-message">
                    <h2>{{ session('success') }}</h2>
                </div>
            @endif
            @if (session('error'))
                <div class="alert-message">
                    <h2>{{ session('error') }}</h2>
                </div>
            @endif
        </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const message = document.querySelector('.alert-message');

        // Hide alert message after 3.5 seconds
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }
        const cashRadio = document.getElementById('cash');
        const gcashRadio = document.getElementById('gcash');
        const amountInputDiv = document.getElementById('cash-amount-input');
        const cancelButton = document.getElementById('cancel-button');

        if('cancelButton'){
            let url = cancelButton.dataset.url;
            cancelButton.addEventListener('click', function(){
                window.location.href = url;
            });
        };

        function toggleAmountInput() {
            if (cashRadio.checked) {
                amountInputDiv.style.display = 'block';
            } else {
                amountInputDiv.style.display = 'none';
            }
        }

        cashRadio.addEventListener('change', toggleAmountInput);
        gcashRadio.addEventListener('change', toggleAmountInput);

        toggleAmountInput();
    });
</script>