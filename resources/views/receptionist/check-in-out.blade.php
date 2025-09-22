<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort</title>
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
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
        margin-left:12rem;
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
        font-size: .9rem;
    }
    .check-wrapper{
        display:grid;
        grid-template-columns: 1fr 1fr 2fr;
        height:90%;
        width:100%;
        gap:.5rem;
    }
    .check-container{
        display:flex;
        flex-direction: column;
        height:100%;
        width:100%;
        background:white;
        border:solid 1px black;
        border-radius:.4rem;
        box-shadow:.1rem .1rem 0 black;
        padding:1rem;
        overflow-y: auto;
    }
    .check-header-wrapper{
        height:3rem;
        width:100%;
        display:flex;
        align-items:center;
        justify-content:space-evenly;
        flex-shrink: 0;
    }
    .list-wrapper{
        display: flex;
        flex-direction: column;
        gap:.5rem;
        flex: 1;
        overflow-y: auto;
    }
    .check-card{
        display:flex;
        flex-direction: column;
        gap:.3rem;
        width:100%;
        min-height:5rem;
        border-radius:.4rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.6rem;
        padding:.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }
    .check-card:hover{
        background-color: #f0f0f0;
        transform: translateY(-1px);
        box-shadow:.15rem .15rem 0 black;
    }
    .check-card:active{
        transform: translateY(0);
        box-shadow:.05rem .05rem 0 black;
    }
    .booking-information{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: .5rem;
    }
    .card-wrapper{
        width:100%;
        display:grid;
        grid-template-columns: .5fr 4fr;
        gap: .3rem;
        align-items: flex-start;
    }
    .card-number{
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: .8rem;
        color: #666;
        padding-top: .5rem;
    }
    #calendar{
        width:100%;
        height:90%;   
        font-size:.7rem;
        border-radius:.7rem;     
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    #billingDetails{
        display:flex;
        flex-direction: column; 
        background:white;
        width:300px;
        padding:1rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        border-radius:.7rem;
    }
    #billingDetails hr {
        margin: -1rem 0 1rem 0;
        border: none;
        border-top: 2px solid #000000;
        width: 100%;
    }
    #form-container{
        display:flex;
        flex-direction: column; 
        background:white;
        width:300px;
        padding:1rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        border-radius:.7rem;
    }
    .modal-content {
        display:flex;
        flex-direction: column;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        gap:1rem;
        background:white;
        border: 2px solid black;
    }
    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1rem;
    }
    .modal-buttons button {
        padding: .5rem 1rem;
        border: 1px solid black;
        border-radius: .3rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .modal-buttons button[type="submit"] {
        background: #e87f00e9;
        color: white;
    }
    .modal-buttons button[type="submit"]:hover {
        background: #c35f01;
    }
    .modal-buttons button[type="button"] {
        background: #757575;
        color: white;
    }
    .modal-buttons button[type="button"]:hover {
        background: #3d3d3d;
    }
    
    .no-bookings {
        text-align: center;
        color: #666;
        font-style: italic;
        padding: 2rem;
    }
    #or-log{
        display:flex;
        object-fit: cover;
        height:5rem;
        width:9rem;
        justify-content: center;
        align-items: center;
        align-self:center;
        margin-bottom:1rem;
    }
    .billing-information{
        display:flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        font-size:.7rem;
        margin-top:-.7rem;
    }
    .billing-information.title{
        justify-content: center;
    }
    .billing-information.deduct{
        justify-content:start;
        gap:3rem;   
    }
    .billing-information.name{
        justify-content:start;
        gap:1rem;   
    }
    .billing-information.date{
        justify-content:start;
        gap:3.5rem;   
    }
    .billing-information.discount{
        justify-content:start;
        gap:4.5rem;   
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
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <div id="layout-header">
                <h1>Check-in / Check-out</h1>
            </div>

            <div class="check-wrapper">
                <div class="check-container">
                    <div class="check-header-wrapper">
                        <h2>Check-In List</h2>
                        <i class="fa-solid fa-person-walking-luggage fa-2x"></i>
                    </div>

                    <div class="list-wrapper">
                        @php
                            $count = 0;
                        @endphp

                        @if($checkin->isEmpty())
                            <div class="no-bookings">
                                <p>No booking to check-in today.</p>
                            </div>
                        @else
                            @foreach($checkin as $booking)
                            <div class="card-wrapper">
                                @php $count++; @endphp
                                <div class="card-number">{{$count}}</div>
                                <div class="check-card" 
                                    data-booking-id="{{ $booking->bookingID }}" 
                                    data-guest-name="{{ $booking->guestname }}"
                                    data-url="{{ url('receptionist/checkin/' . $booking->bookingID) }}">
                                    <div>
                                        <p><strong>Guest:</strong> {{ $booking->guestname }}</p>
                                    </div>
                                    <div class="booking-information">
                                        @if($booking->roomBookings->isNotEmpty())
                                            <div>
                                                <p><strong>Rooms:</strong></p>
                                                @foreach($booking->roomBookings as $roomBook)
                                                    <p>Room #: {{ $roomBook->room->roomnum ?? 'Room not found' }}</p>
                                                @endforeach
                                            </div>
                                        @else
                                            <div><p>No rooms booked.</p></div>
                                        @endif

                                        @if($booking->cottageBookings->isNotEmpty())
                                            <div>
                                                <p><strong>Cottages:</strong></p>
                                                @foreach($booking->cottageBookings as $cottageBook)
                                                    <p>{{ $cottageBook->cottage->cottagename ?? 'Cottage not found' }}</p>
                                                @endforeach
                                            </div>
                                        @else
                                            <div><p>No cottages booked.</p></div>
                                        @endif
                    
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="check-container">
                    <div class="check-header-wrapper">
                        <h2>Check-Out List</h2>
                        <i class="fa-solid fa-person-walking-luggage fa-2x" style="transform: rotateY(180deg);"></i>
                    </div>
                    <div class="list-wrapper">
                        @php $count = 0; @endphp

                        @if($checkout->isEmpty())
                            <div class="no-bookings">
                                <p>No booking to check-out today.</p>
                            </div>
                        @else
                            @foreach($checkout as $booking)
                            <div class="card-wrapper">
                                @php $count++; @endphp
                                <div class="card-number">{{ $count }}</div>
                                <div class="check-card" 
                                     data-booking-id="{{ $booking->bookingID }}" 
                                     data-guest-name="{{ $booking->guestname }}"
                                     data-url="{{ url('receptionist/checkout/' . $booking->bookingID) }}">
                                    <div>
                                        <p><strong>Guest:</strong> {{ $booking->guestname }}</p>
                                    </div>
                                    <div class="booking-information">
                                        @if($booking->roomBookings->isNotEmpty())
                                            <div>
                                                <p><strong>Rooms:</strong></p>
                                                @foreach($booking->roomBookings as $roomBook)
                                                    <p>Room #: {{ $roomBook->room->roomnum ?? 'Room not found' }}</p>
                                                @endforeach
                                            </div>
                                        @else
                                            <div><p>No rooms booked.</p></div>
                                        @endif

                                        @if($booking->cottageBookings->isNotEmpty())
                                            <div>
                                                <p><strong>Cottages:</strong></p>
                                                @foreach($booking->cottageBookings as $cottageBook)
                                                    <p>{{ $cottageBook->cottage->cottagename ?? 'Cottage not found' }}</p>
                                                @endforeach
                                            </div>
                                        @else
                                            <div><p>No cottages booked.</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="check-container">
                    <div id="calendar"></div>
                </div>
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
        const cards = document.querySelectorAll('.check-card');
        // Cache DOM elements
        const message = document.querySelector('.alert-message');
        

        // Hide alert message after 3.5 seconds
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        cards.forEach(function(card) {
            card.addEventListener('click', function() {
                const url = card.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Initialize FullCalendar
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: false,
                editable: false,
                events: @json(url('receptionist/checkEvents')),
                eventClick: function (info) {
                    // Handle event click here if needed
                },
                eventDidMount: function (info) {
                    const el = info.el;
                    el.style.height = '2rem';
                    el.style.lineHeight = '1.5rem';
                    el.style.overflow = 'hidden';
                    el.style.whiteSpace = 'wrap';
                    el.style.textOverflow = 'ellipsis';
                    el.style.cursor = "pointer";
                    el.style.zIndex = '999';
                    el.style.position = 'relative';
                    el.style.marginTop = '-.5rem';
                    el.style.fontSize = '.7rem';
                }
            });
            calendar.render();
        }
    });
</script>


</html>