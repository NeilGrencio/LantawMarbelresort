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
<style>
    #booking {
        background: rgba(255,255,255,0.15);
        border-left: 4px solid #ff9100;
        color: white;
    }
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background: #f5f6fa;
        color: #2f3640;
    }

    #layout {
        display: flex;
        height: 100vh;
        width:100%;
        overflow: hidden;
    }

    #main-layout {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
        overflow-y: auto;
        background: #f5f6fa;
        margin-left:15rem;
    }

    #layout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
        height: 4rem;
    }

    #layout-header h1 {
        font-size: 1rem;
        font-weight: 600;
        color: #1e272e;
    }

    #add-container {
        display: flex;
        gap: 1rem;
    }

    .add-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease;
        color: #2f3640;
    }

    .add-action i {
        font-size: 1rem;
        padding: 0.6rem;
        background: #dcdde1;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .add-action small {
        margin-top: 0.3rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .add-action:hover i {
        background: #ff9f43;
        color: white;
        transform: scale(1.1);
    }

    .booking-view {
        display: grid;
        grid-template-columns: 250px 1fr 320px;
        gap: 1rem;
        height: calc(100vh - 4rem);
    }

    .room-container {
        background: #fff;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        gap: .5rem;
        overflow-y: auto;
    }

    #filter-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .filter-card {
        flex: 1;
        text-align: center;
        padding: 0.5rem;
        background: #f1f2f6;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-card:hover, .filter-card.active {
        background: #ff9f43;
        color: white;
    }

    .room-card {
        display: flex;
        gap: 0.8rem;
        background: #f1f2f6;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .room-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
    }

    .room-card img {
        width: 90px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px 0 0 10px;
    }

    .room-card div {
        padding: 0.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        font-size: 0.85rem;
    }

    .calendar-container {
        background: #fff;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }

    #calendar-legend {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .legend-item span {
        width: 14px;
        height: 14px;
        border-radius: 4px;
        display: inline-block;
    }

    .booking-container {
        background: #fff;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        overflow-y: auto;
    }

    .booking-filter {
        margin-bottom: 0.8rem;
    }

    #booking-status-select {
        width: 100%;
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #dcdde1;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
    }

    .booking-card {
        background: #dcdde1;
        border-radius: 10px;
        padding: 0.7rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .booking-card h3 {
        font-size: 1rem;
        margin-bottom: 0.3rem;
    }

    .booking-card p {
        font-size: 0.85rem;
        margin: 0;
    }

    .alert-message {
        position: fixed;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        z-index: 1000;
        font-weight: 500;
    }

    /* Scrollbars */
    .room-container::-webkit-scrollbar,
    .booking-container::-webkit-scrollbar,
    #main-layout::-webkit-scrollbar {
        width: 8px;
    }

    .room-container::-webkit-scrollbar-thumb,
    .booking-container::-webkit-scrollbar-thumb,
    #main-layout::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 4px;
    }
</style>
</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Booking</h1>
            <div id="add-container">
                <div class="add-action" data-url="{{ url('receptionist/booking_list') }}">
                    <i class="fa-solid fa-list-ol"></i>
                    <small>Booking List</small>
                </div>
                <div class="add-action" data-url="{{ url('receptionist/walk-booking') }}">
                    <i class="fas fa-hotel"></i>
                    <small>Walk In</small>
                </div>
                <div class="add-action" data-url="{{ url('receptionist/create_booking') }}">
                    <i class="fas fa-plus-circle"></i>
                    <small>Normal Booking</small>
                </div>
            </div>
        </div>

        <div class="booking-view">
            <div class="room-container">
                <div id="filter-container">
                    <div class="filter-card" data-filter="room">Rooms</div>
                    <div class="filter-card" data-filter="cottage">Cottages</div>
                    <div class="filter-card" data-filter="amenity">Amenities</div>
                </div>
                <div id="room-empty-message" style="display:none;">No rooms available in this category.</div>

                @foreach($rooms as $room)
                    <div class="room-card" data-category="room">
                        <img src="{{asset('storage/' . $room->image)}}"/>
                        <div>
                            <h3>Room {{ $room->roomnum }}</h3>
                            <p>Status: {{ $room->status }}</p>
                        </div>
                    </div>
                @endforeach

                @foreach($cottages as $cottage)
                    <div class="room-card" data-category="cottage">
                        <img src="{{asset('storage/' . $cottage->image)}}"/>
                        <div>
                            <h3>{{ $cottage->cottagename }}</h3>
                            <p>Status: {{ $cottage->status }}</p>
                        </div>
                    </div>
                @endforeach

                @foreach($amenities as $amenity)
                    <div class="room-card" data-category="amenity">
                        <img src="{{asset('storage/' . $amenity->image)}}"/>
                        <div>
                            <h3>{{ $amenity->amenityname }}</h3>
                            <p>Status: {{ $amenity->status }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="calendar-container">
                <div id="calendar-legend">
                    <div class="legend-item"><span style="background:#1E90FF;"></span>Booked</div>
                    <div class="legend-item"><span style="background:#FFD700;"></span>Pending</div>
                    <div class="legend-item"><span style="background:#A9A9A9;"></span>Cancelled</div>
                    <div class="legend-item"><span style="background:#32CD32;"></span>Finished</div>
                    <div class="legend-item"><span style="background:#FF6347;"></span>Ongoing</div>
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

                <div id="empty-message" style="display: none;">No bookings available.</div>

                @foreach($booked as $booked)
                    <div class="booking-card" data-status="Booked" data-url="{{url('receptionist/view_booking/' . $booked->bookingID)}}" style="background:#1E90FF;color:white;">
                        <h3>{{ $booked->fullname }}</h3>
                        <p>Check-in: {{ $booked->bookingstart }}</p>
                        <p>Check-out: {{ $booked->bookingend }}</p>
                    </div>
                @endforeach

                @foreach($pending as $pending)
                    <div class="booking-card" data-status="Pending" data-url="{{url('receptionist/view_booking/' . $pending->bookingID)}}" style="background:#FFD700;color:#2f3640;">
                        <h3>{{ $pending->fullname }}</h3>
                        <p>Check-in: {{ $pending->bookingstart }}</p>
                        <p>Check-out: {{ $pending->bookingend }}</p>
                    </div>
                @endforeach

                @foreach($cancelled as $cancelled)
                    <div class="booking-card" data-status="Cancelled" data-url="{{url('receptionist/view_booking/' . $cancelled->bookingID)}}" style="background:#A9A9A9;color:white;">
                        <h3>{{ $cancelled->fullname }}</h3>
                        <p>Check-in: {{ $cancelled->bookingstart }}</p>
                        <p>Check-out: {{ $cancelled->bookingend }}</p>
                    </div>
                @endforeach

                @foreach($finished as $finished)
                    <div class="booking-card" data-status="Finished" data-url="{{url('receptionist/view_booking/' . $finished->bookingID)}}" style="background:#32CD32;color:white;">
                        <h3>{{ $finished->fullname }}</h3>
                        <p>Check-in: {{ $finished->bookingstart }}</p>
                        <p>Check-out: {{ $finished->bookingend }}</p>
                    </div>
                @endforeach

                @foreach($ongoing as $ongoing)
                    <div class="booking-card" data-status="Ongoing" data-url="{{url('receptionist/view_booking/' . $ongoing->bookingID)}}" style="background:#FF6347;color:white;">
                        <h3>{{ $ongoing->fullname }}</h3>
                        <p>Check-in: {{ $ongoing->bookingstart }}</p>
                        <p>Check-out: {{ $ongoing->bookingend }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        @if (session('success'))
            <div class="alert-message">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-message">{{ session('error') }}</div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Navigate booking cards
    document.querySelectorAll('.booking-card').forEach(card => {
        card.addEventListener('click', () => {
            const url = card.dataset.url;
            if(url) window.location.href = url;
        });
    });

    // Navigate add buttons
    document.querySelectorAll('.add-action').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.dataset.url;
            if(url) window.location.href = url;
        });
    });

    // Booking filter
    const bookingSelect = document.getElementById('booking-status-select');
    const bookingCards = document.querySelectorAll('.booking-card');
    const emptyMessage = document.getElementById('empty-message');

    function filterBooking(status) {
        let found = false;
        bookingCards.forEach(card => {
            card.style.display = card.dataset.status === status ? 'flex' : 'none';
            if(card.dataset.status === status) found = true;
        });
        emptyMessage.style.display = found ? 'none' : 'block';
    }

    filterBooking(bookingSelect.value);
    bookingSelect.addEventListener('change', () => filterBooking(bookingSelect.value));

    // Room filter
    const roomFilters = document.querySelectorAll('#filter-container .filter-card');
    const rooms = document.querySelectorAll('.room-card');
    const roomEmpty = document.getElementById('room-empty-message');

    roomFilters.forEach(filter => {
        filter.addEventListener('click', () => {
            roomFilters.forEach(f => f.classList.remove('active'));
            filter.classList.add('active');
            let found = false;
            rooms.forEach(r => {
                if(r.dataset.category === filter.dataset.filter) {
                    r.style.display = 'flex'; found = true;
                } else { r.style.display = 'none'; }
            });
            roomEmpty.style.display = found ? 'none' : 'block';
        });
    });

    if(roomFilters.length) roomFilters[0].click();

    // FullCalendar
    const calendarEl = document.getElementById('calendar');
    if(calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/receptionist/events?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
                    .then(res => res.json()).then(data => successCallback(data)).catch(err => failureCallback(err));
            },
            eventClick: function(info) {
                const id = info.event.id;
                window.location.href = `/receptionist/view_booking/${id}`;
            },
            eventDidMount: function(info) {
                info.el.style.height = '2.4rem';
                info.el.style.borderRadius = '6px';
                info.el.style.cursor = 'pointer';
                info.el.style.overflow = 'hidden';
                info.el.style.whiteSpace = 'nowrap';
                info.el.style.textOverflow = 'ellipsis';
            }
        });
        calendar.render();
    }
});
</script>
</body>
</html>
