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
                    <div class="add-action">
                        <i id="add-action" class="fa-solid fa-list-ol fa-2x" data-url="{{ url('receptionist/booking_list') }}" style="cursor:pointer;"></i>
                        <small>Booking List</small>
                    </div>
                    <div class="add-action">
                        <i id="add-action" class="fas fa-hotel fa-2x" data-url="{{ url('receptionist/walk-booking') }}" style="cursor:pointer;"></i>
                        <small>Walk In Booking</small>
                    </div>
                    <div class="add-action">
                        <i id="add-action" id="add-menu" class="fas fa-plus-circle fa-2x" data-url="{{ url('receptionist/create_booking') }}" style="cursor:pointer;"></i>
                        <small>Normal Booking</small>
                    </div>  
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
                    <div id="calendar-legend">
                        <h3>Legend</h3>
                        <div id="calendar-legend">
                            <div class="legend-item"><span style="background:#1E90FF;"></span>Booked</div>
                            <div class="legend-item"><span style="background:#FFD700;"></span>Pending</div>
                            <div class="legend-item"><span style="background:#A9A9A9;"></span>Cancelled</div>
                            <div class="legend-item"><span style="background:#32CD32;"></span>Finished</div>
                            <div class="legend-item"><span style="background:#FF6347;"></span>Ongoing</div>
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
                <div class="booking-container">
                    <div class="booking-filter">
                        <select id="booking-status-select">
                            <option value="Booked">Booked</option>
                            <option value="Pending">Pending</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Finished">Finished</option>
                            <option value="Ongoing">Ongoing</option>
                        </select>
                    </div>
                    <h2>Booking Display</h2>
                    <div id="empty-message" style="display: none;">There are currently no bookings for this filter.</div>
                        @foreach($booked as $booked)
                            <div class="booking-card" data-status="Booked" data-url="{{url('receptionist/view_booking/' . $booked->bookingID)}}" style="background:#1E90FF;">
                                <h3>{{ $booked->fullname }}</h3>
                                <p>Check-in: {{ $booked->bookingstart }}</p>
                                <p>Check-out: {{ $booked->bookingend }}</p>
                            </div>
                        @endforeach
                        @foreach($pending as $pending)
                            <div class="booking-card" data-status="Pending" data-url="{{url('receptionist/view_booking/' . $pending->bookingID)}}" style="background:#FFD700;">
                                <h3>{{ $pending->fullname }}</h3>
                                <p>Check-in: {{ $pending->bookingstart }}</p>
                                <p>Check-out: {{ $pending->bookingend }}</p>
                            </div>
                        @endforeach
                        @foreach($cancelled as $cancelled)
                            <div class="booking-card" data-status="Cancelled" data-url="{{url('receptionist/view_booking/' . $cancelled->bookingID)}}" style="background:#A9A9A9;">
                                <h3>{{ $cancelled->fullname }}</h3>
                                <p>Check-in: {{ $cancelled->bookingstart }}</p>
                                <p>Check-out: {{ $cancelled->bookingend }}</p>
                            </div>
                        @endforeach

                        @foreach($finished as $finished)
                            <div class="booking-card" data-status="Finished" data-url="{{url('receptionist/view_booking/' . $finished->bookingID)}}" style="background:#32CD32;">
                                <h3>{{ $finished->fullname }}</h3>
                                <p>Check-in: {{ $finished->bookingstart }}</p>
                                <p>Check-out: {{ $finished->bookingend }}</p>
                            </div>
                        @endforeach

                        @foreach($ongoing as $ongoing)
                            <div class="booking-card" data-status="Ongoing" data-url="{{url('receptionist/view_booking/' . $ongoing->bookingID)}}" style="background:#FF6347;">
                                <h3>{{ $ongoing->fullname }}</h3>
                                <p>Check-in: {{ $ongoing->bookingstart }}</p>
                                <p>Check-out: {{ $ongoing->bookingend }}</p>
                            </div>
                        @endforeach
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
    </div>
</body>
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
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
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
    .add-action{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
        cursor: pointer;
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
        height:2rem;
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
     #calendar-legend { 
        display:flex; 
        flex-direction:row; 
        gap:.3rem; 
        background:#fffaf0; 
        padding:.5rem; 
        border-radius:.5rem;
        border:1px solid #ccc; 
        margin-top:.5rem; 
        font-size:.8rem; 
        justify-content: center;
        align-items: center;
    }
    #calendar-legend h3 { 
        margin-bottom:.3rem; 
        font-size:.9rem; 
    }
    .legend-item { 
        display:flex; 
        align-items:center; 
        gap:.5rem; 
    }
    .legend-item span { 
        width:1rem; 
        height:1rem; 
        border-radius:.2rem; 
        display:inline-block; 
        border:1px solid black; 
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
        height:2.5rem;
        width: 100%;   
    }
    #booking-status-select {
        display: block;
        width: 100%;
        background: #fff;
        color: black;
        font-weight: bold;
        border: 1px solid black;
        border-radius: 0.5rem;
        box-shadow: 0.1rem 0.1rem 0 black;
        padding: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
        box-sizing: border-box;
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
    document.querySelectorAll('.booking-card').forEach(card => {
        card.addEventListener('click', function () {
            const url = this.dataset.url;
            if (url) window.location.href = url;
        });
    });

    const addBtns = document.querySelectorAll('#add-action');
    addBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });

    const bookingSelect = document.getElementById('booking-status-select');
    const bookingCards = document.querySelectorAll('.booking-card');
    const bookingEmptyMessage = document.getElementById('empty-message');
    const bookingMessages = {
        Booked: 'There are no booked reservations.',
        Pending: 'There are no pending bookings.',
        Cancelled: 'There are no cancelled bookings.',
        Finished: 'There are no finished bookings.',
        Ongoing: 'There are no ongoing bookings.'
    };

    function updateBookingFilter(status) {
        let hasMatch = false;
        bookingCards.forEach(card => {
            if (card.dataset.status === status) {
                card.style.display = 'flex';
                hasMatch = true;
            } else {
                card.style.display = 'none';
            }
        });
        bookingEmptyMessage.textContent = hasMatch ? '' : bookingMessages[status];
        bookingEmptyMessage.style.display = hasMatch ? 'none' : 'block';
    }

    if (bookingSelect) updateBookingFilter(bookingSelect.value);
    if (bookingSelect) {
        bookingSelect.addEventListener('change', function () {
            updateBookingFilter(this.value);
        });
    }

    const roomFilterCards = document.querySelectorAll('#filter-container .filter-card');
    const roomCards = document.querySelectorAll('.room-card');
    const roomEmptyMessage = document.getElementById('room-empty-message');

    function updateRoomFilter(category) {
        let hasMatch = false;
        roomCards.forEach(card => {
            if (card.dataset.category === category) {
                card.style.display = 'flex';
                hasMatch = true;
            } else {
                card.style.display = 'none';
            }
        });
        roomEmptyMessage.textContent = hasMatch ? '' : `No ${category}s available.`;
        roomEmptyMessage.style.display = hasMatch ? 'none' : 'block';
    }

    roomFilterCards.forEach(card => {
        card.addEventListener('click', function () {
            roomFilterCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            updateRoomFilter(this.dataset.filter);
        });
    });

    if (roomFilterCards.length > 0) roomFilterCards[0].click();

    let calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: '90%',
            handleWindowResize: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/receptionist/events?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            eventClick: function(info) {
                const bookingID = info.event.id;
                window.location.href = `/receptionist/view_booking/${bookingID}`;
            },
            eventDidMount: function(info) {
                info.el.style.height = '2.2rem';
                info.el.style.overflow = 'hidden';
                info.el.style.whiteSpace = 'nowrap';
                info.el.style.textOverflow = 'ellipsis';
                info.el.style.cursor = "pointer";
                info.el.style.borderRadius = '.3rem';
            },
        });
        calendar.render();
    }
});

</script>

</html>
