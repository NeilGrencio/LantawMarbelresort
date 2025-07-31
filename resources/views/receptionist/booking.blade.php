<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Booking</h1>
                <div id="add-container">
                    <h2 id="add-text">Create Booking</h2>
                    <i id="add-menu" class="fas fa-plus-circle fa-3x" data-url="{{ url('receptionist/create_booking') }}" style="cursor:pointer;"></i>
                </div>
            </div>
            <div class="booking-view">
                <div class="room-container">
                    <div id="filter-container">
                        <div class="filter-card" data-filter="room">
                            <h2>Rooms</h2>
                        </div>
                        <div class="filter-card" data-filter="cottage">
                            <h2>Cottages</h2>
                        </div>
                        <div class="filter-card" data-filter="amenity">
                            <h2>Amenities</h2>
                        </div>
                    </div>
                    <div id="room-empty-message" style="display:none;">No rooms available in this category.</div>
                    @foreach($rooms as $room)
                    <div class="room-card" data-category="room">
                        <img src="{{asset('storage/' . $room->image)}}"/>
                        <div>
                            <h3>Room {{ $room->roomnum}}</h3>
                            <h3> Room Currently {{ $room->status }}</h3>
                        </div>
                    </div>
                    @endforeach

                    @foreach($cottages as $cottage)
                    <div class="room-card" data-category="cottage">
                        <img src="{{asset('storage/' . $cottage->image)}}"/>
                        <div>
                            <h3>{{ $cottage->cottagename}}</h3>
                            <h3> Cottage is {{ $cottage->status }}</h3>
                        </div>
                    </div>
                    @endforeach

                    @foreach($amenities as $amenity)
                    <div class="room-card" data-category="amenity">
                        <img src="{{asset('storage/' . $amenity->image)}}"/>
                        <div>
                            <h3>{{ $amenity->amenityname}}</h3>
                            <h3> Amenity is {{ $amenity->status }}</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="calendar-container">
                    <div id="calendar"></div>
                </div>
                <div class="booking-container">
                    <div class="booking-filter">
                        <div class="filter-card" data-filter="Today">
                            <h2>Today</h2>
                        </div>
                        <div class="filter-card" data-filter="Pending">
                            <h2>Unconfirmed</h2>
                        </div>
                        <div class="filter-card" data-filter="Confirmed">
                            <h2>Upcoming</h2>
                        </div>
                    </div>
                    <h2>Bookings</h2>
                    <div id="empty-message" style="display: none;">There are currently no bookings for this filter.</div>
                        @foreach($bookingtoday as $today)
                            <div class="booking-card" data-status="Today" data-url="{{url('receptionist/view_booking/' . $today->bookingID)}}">
                                <h3>{{ $today->fullname }}</h3>
                                <p>Check-in: {{ $today->bookingstart }}</p>
                                <p>Check-out: {{ $today->bookingend }}</p>
                            </div>
                        @endforeach
                        @foreach($bookingpending as $pending)
                            <div class="booking-card" data-status="Pending" data-url="{{url('receptionist/view_booking/' . $pending->bookingID)}}">
                                <h3>{{ $pending->fullname }}</h3>
                                <p>Check-in: {{ $pending->bookingstart }}</p>
                                <p>Check-out: {{ $pending->bookingend }}</p>
                            </div>
                        @endforeach
                        @foreach($bookingconfirmed as $confirmed)
                            <div class="booking-card" data-status="Confirmed" data-url="{{url('receptionist/view_booking/' . $confirmed->bookingID)}}">
                                <h3>{{ $confirmed->fullname }}</h3>
                                <p>Check-in: {{ $confirmed->boookingstart }}</p>
                                <p>Check-out: {{ $confirmed->bookingend }}</p>
                            </div>
                        @endforeach
                </div>
            </div>
            @if (session('success'))
                <div class="alert-message">
                    <h2>{{ session('success') }}</h2>
                </div>
            @endif
        </div>
    </div>
</body>
<style>
    /* Your existing styles here */
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
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:15rem;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:black 1px solid;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .9rem;
    }
    #add-container{
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1rem;
    }
    #add-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        gap:1rem;
    }
    #add-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }

    #add-container:hover #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
    }
    .booking-view {
        display: flex;
        flex-direction: row;
        width: 100%;
        height:85vh;
        gap: .5rem;
        margin-top:1rem;
    }
    .room-container{
        display:flex;
        flex-direction:column;
        width:20rem;
        height:90vh;
        height:100%;
        border-radius: .7rem;
        border:black 1px solid;
        box-shadow:.1rem .1rem 0 black;
        background: white;
        padding:.7rem;
        gap:.5rem;
        overflow-y: auto;
        overflow-x:hidden;
    }
    #filter-container{
        height:5rem;
        width:100%;
        gap:.5rem;
        display: flex;
        flex-direction: row;
    }
    .filter-card{
        border-radius: .2rem;
        background: white;
        border: solid 1px black;
        box-shadow: .1rem .1rem 0 rgba(0, 0, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .4rem;
        width:4rem;
        height:2rem;
        cursor: pointer;
        transition:all .2s ease;
    }
    .filter-card:hover{
        background: orange;
        color: black;
    }
    .room-card{
        display:flex;
        flex-direction: row;
        border-radius: .7rem;
        border: solid 1px black;
        box-shadow: .1rem .2rem 0 rgba(0, 0, 0);
        padding: .3rem;
        font-size: .5rem;
        gap:.5rem;
        cursor:pointer;
        transition: all .2s ease;
    }
    .room-card:hover{
        background: orange;
        color: black;
    }
    .room-card img{
        width:30%;
        height: 100%;
        background:grey;
        object-fit: cover;
    }
    .calendar-container {
        background: white;
        border-radius: 1rem;
        width: 100%;
        height: 100%;
        padding:.5rem;
        border-radius: .7rem;
        border:black 1px solid;
        box-shadow:.1rem .1rem 0 black;
    }
    #calendar {
        width: 100%;
        height: 100%;
        font-size:.8rem;
    }
    .booking-container{
        display: flex;
        flex-direction: column;
        width: 30%;
        height: 100%;
        background: white;
        border-radius: .7rem;
        border:black 1px solid;
        box-shadow:.1rem .1rem 0 black;
        padding: .7rem;
        overflow-y: auto;
    }
    .booking-card{
        display: flex;
        flex-direction: column;
        height:5rem;
        border-radius: .7rem;
        border: solid 1px black;
        box-shadow: .1rem .2rem 0 rgba(0, 0, 0);
        padding: .3rem;
        font-size: .6rem;
        margin-bottom: .5rem;
        cursor: pointer;
        transition: all .2s ease;
    }
    .booking-card h3{
        margin-bottom:1rem;
    }
    .booking-card p{
        margin-top:-.1rem;
        margin-bottom:.2rem;
    }
    .booking-card:hover{
        background: orange;
        color: black;
    }
    .booking-filter{
        display: flex;
        flex-direction: row;
        gap: .5rem;
        margin-bottom: .5rem;
        height:3rem;
        width: 100%;   
    }
    .filter-card.active {
        background: orange;
        color: black;
        font-weight: bold;
        border: 2px solid black;
    }
    #filter-container .filter-card.active {
        background: orange;
        color: black;
        font-weight: bold;
        border: 2px solid black;
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
        document.querySelectorAll('.booking-card').forEach(function(card) {
        card.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }
        // ===== Redirect on Add Booking Click =====
        const addBtn = document.getElementById('add-menu');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                window.location.href = this.dataset.url;
            });
        }

        // ===== Booking Filter (Today, Pending, Confirmed) =====
        const bookingFilterCards = document.querySelectorAll('.booking-filter .filter-card');
        const bookingCards = document.querySelectorAll('.booking-card');
        const bookingEmptyMessage = document.getElementById('empty-message');

        const bookingMessages = {
            Today: 'There are no bookings for today.',
            Pending: 'There are no pending bookings.',
            Confirmed: 'There are no confirmed bookings.'
        };

        function updateBookingFilter(filterType) {
            let hasMatch = false;

            bookingCards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (status === filterType) {
                    card.style.display = 'flex';
                    hasMatch = true;
                } else {
                    card.style.display = 'none';
                }
            });

            bookingEmptyMessage.textContent = hasMatch ? '' : bookingMessages[filterType];
            bookingEmptyMessage.style.display = hasMatch ? 'none' : 'block';
        }

        bookingFilterCards.forEach(card => {
            card.addEventListener('click', function () {
                bookingFilterCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const filterType = this.getAttribute('data-filter');
                updateBookingFilter(filterType);
            });
        });

        if (bookingFilterCards.length > 0) {
            bookingFilterCards[0].click(); // Trigger default filter
        }

        // ===== Room/Cottage/Amenity Filter =====
        const roomFilterCards = document.querySelectorAll('#filter-container .filter-card');
        const roomCards = document.querySelectorAll('.room-card');
        const roomEmptyMessage = document.getElementById('room-empty-message');

        function updateRoomFilter(category) {
            let hasMatch = false;

            roomCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (cardCategory === category) {
                    card.style.display = 'flex';
                    hasMatch = true;
                } else {
                    card.style.display = 'none';
                }
            });

            roomEmptyMessage.textContent = `No ${category} available.`;
            roomEmptyMessage.style.display = hasMatch ? 'none' : 'block';
        }

        roomFilterCards.forEach(card => {
            card.addEventListener('click', function () {
                roomFilterCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const category = this.getAttribute('data-filter');
                updateRoomFilter(category);
            });
        });

        if (roomFilterCards.length > 0) {
            roomFilterCards[0].click(); 
        }

        // ===== FullCalendar Setup =====
        let calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: false,
                editable: false,
                events: @json(url('receptionist/events')),
                eventClick: function (info) {
                    const bookingID = info.event.id;
                    window.location.href = `/receptionist/view_booking/${bookingID}`;
                },
                eventDidMount: function(info) {
                    info.el.style.height = '2.5rem';
                    info.el.style.lineHeight = '2.5rem';
                    info.el.style.overflow = 'hidden';
                    info.el.style.whiteSpace = 'nowrap';
                    info.el.style.textOverflow = 'ellipsis';
                    info.el.style.cursor = "pointer";
                    info.el.style.zIndex = '999';
                    info.el.style.position = 'relative';
                    info.el.style.marginTop = '-.5rem';
                },
            });

            calendar.render();
        }
    });
</script>

</html>
