<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div class="title_container">
                <p>Welcome Manager!</p>
            </div>
            <div class="card_container">                
                <div class="card" data-url="{{ url('manager/feedback') }}" id="feedback">
                    <p>Feedback</p>
                    @if($feedbackNotification > 0)
                        <div id="new-notification" >{{$feedbackNotification}}</div>
                    @endif
                </div>  

                <div class="card" data-url="{{ url('manager/chat') }}" id="inquiry">
                    <p>Chat Messages</p>
                    @if($notificationInquiry > 0)
                        <div id="new-notification" >{{$notificationInquiry}}</div>
                    @endif
                </div>  
                <div class="card" data-url="{{ url('manager/session_logs') }}" id="logs">
                    <p>User Log Ins</p>
                    @if($userLogIns > 0)
                        <div id="new-notification" >{{$userLogIns}}</div>
                    @endif
                </div> 
                <div class="card"  data-url="{{ url('manager/room_list') }}" id="availablerooms">
                    <p>Available Rooms</p>
                    @if ($availableRooms > 0)
                        <div id="new-notification">{{$availableRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/room_list') }}" id="unavailablerooms">
                    <p>Unavailable Rooms</p>
                    @if ($unavailableRooms > 0)
                        <div id="new-notification" >{{$unavailableRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/room_list') }}" id="maintenancedrooms">
                    <p>Maintenanced Rooms</p>
                    @if ($maintenancedRooms > 0)
                        <div id="new-notification" >{{$maintenancedRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="availablecottages">
                    <p>Available Cottages</p>
                    @if ($availableCottages > 0)
                        <div id="new-notification" >{{$availableCottages}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="unavailablecottages">
                    <p>Unavailable Cottages</p>
                    @if ($unavailableCottages > 0)
                        <div id="new-notification" >{{$unavailableCottages}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="maintenancedcottages">
                    <p>Maintenanced Cottages</p>
                    @if ($maintenancedCottages > 0)
                        <div id="new-notification" >{{$maintenancedCottages}}</div>
                    @endif
                </div> 
                 
            </div>

            <div id="kpi-charts-container">
                <h3>Key Performance Indicators</h3>
                <div class="filter-wrapper">
                    <label for="filterType">Filter By: </label>
                    <select id="filterType" name="filterType" onchange="updateFilter(this.value)">
                        <option value="year" {{ $filterType === 'year' ? 'selected' : '' }}>Year</option>
                        <option value="month" {{ $filterType === 'month' ? 'selected' : '' }}>Month</option>
                        <option value="week" {{ $filterType === 'week' ? 'selected' : '' }}>Week</option>
                    </select>

                    @if($filterType === 'year')
                        <label for="yearSelect">Year: </label>
                        <select id="yearSelect" name="yearSelect" onchange="updateYear(this.value)">
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    @endif
                </div>
                <div class="chart-wrapper">
                    <div class="row 1 column 1">
                        <h2>Booking This Month</h2>
                        <canvas id="bookingPerMonth"></canvas>
                    </div>

                    <div class="row 1 column 2">
                        <h2>Hotel Guest vs Day Tour</h2>
                        <canvas id="bookingsVsRevenue"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div class="row 2 column-full">
                        <h2>Rooms Booked This Month</h2>
                        <canvas id="roomBookedPerMonth"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper">
                    <div class="row 4 column-full">
                        <h2>Amenities Toured This Month</h2>
                        <canvas id="amenityTourPerMonth"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper">
                    <div class="row 5 column-full">
                        <h2>Revenue Per Month</h2>
                        <canvas id="revenuPerMonth"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<style>
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        padding:1.5rem;
        margin-left:12rem;
        width:100%;
        overflow-x: auto;
    }
    .title_container{
        display:flex;
        height:5rem;    
        font-size:2rem;
        font-weight: lighter;
        background:white;
        border:1px solid black;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        padding:.5rem;
    }
    .card_container{
        display:grid;
        grid-template-columns:1fr 1fr 1fr;
        gap:1.5rem;
        width:100%;
        margin-top:1rem;
        padding:1.5rem;
        background:white;
        border:1px solid black;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
    }

    #kpi-charts-container{
        display:flex;
        flex-direction: column;
        gap:1rem;
        width:100%;
        height:auto;
        margin-top:1rem;
        padding:1.5rem;
        background:white;
        border:1px solid black;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
        object-fit:contain;
        justify-content: center;
        align-items:center;
    }

    .chart-wrapper {
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap:5rem;
        width: 100%;
        height: 100%;
        text-align: center;
        font-size:.7rem;
    }
    #roomBookedPerMonth, #amenityTourPerMonth, #revenuPerMonth{
        width:100%;
    }


    .column-full{
        grid-column: span 2;
        place-items: center;
        height: 25rem;
        width: 100%;
        margin-bottom: 1.5rem;
    }

    .column 1, .column 2{
        height:30rem;
        width:25rem;
    }
    
    .card{
        width:;
        height:3.5rem;
        display:flex;
        position: relative;
        align-items: center;
        padding:1rem;
        font-size:.9rem;
        background:white;
        border-radius:.5rem;
        border:1px solid black;
        box-shadow:.1rem .2rem 0 black;
        transition: all 0.2s ease-in-out;
    }
    .card:hover{
        cursor:pointer;
        scale:1.1;
        background:whitesmoke;
    }
    #new-notification{
        display:flex;
        position:absolute;
        height:2rem;
        width: 2rem;
        align-items: center;
        justify-content: center;
        top:-1rem;
        right:-1rem;
        margin-left:auto;
        background:red;
        color:white;
        border-radius:50%;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        font-size:1rem;
    }
</style>

<script>
document.getElementById('dashboard').style.color = "#F78A21";

document.addEventListener("DOMContentLoaded", function () {
    // Booking per Month Chart
    const ctxBooking = document.getElementById("bookingPerMonth").getContext("2d");
    new Chart(ctxBooking, {
        type: 'line',
        data: {
            labels: @json($bookingLabels),
            datasets: [{
                label: 'Bookings',
                data: @json($bookingData),
                borderColor: 'blue',
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) { return Number.isInteger(value) ? value : null; }
                    }
                }
            }
        }
    });

    // Hotel Guest vs Day Tour Chart
    const ctxGuestVsDayTour = document.getElementById("bookingsVsRevenue").getContext("2d");
    new Chart(ctxGuestVsDayTour, {
        type: 'line',
        data: {
            labels: @json($daytourLabels),
            datasets: [
                {
                    label: 'Hotel Guest',
                    data: @json($bookingData),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Day Tour Guest',
                    data: @json($daytourData),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) { return Number.isInteger(value) ? value : null; }
                    }
                }
            }
        }
    });

    // Rooms Booked Chart
    const ctxRoom = document.getElementById("roomBookedPerMonth").getContext("2d");
    new Chart(ctxRoom, {
        type: 'bar',
        data: {
            labels: @json($roomlabels),
            datasets: [{
                label: 'Total Bookings per Room',
                data: @json($roombookData),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.4)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' }, tooltip: { enabled: true } },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { stepSize: 1, callback: function(value) { return Number.isInteger(value) ? value : null; } }
                }
            }
        }
    });

    // Amenities Chart
    const ctxAmenity = document.getElementById("amenityTourPerMonth").getContext("2d");
    new Chart(ctxAmenity, {
        type: 'bar',
        data: {
            labels: @json($amenityLabels),
            datasets: [{
                label: 'Amenities Accessed',
                data: @json($amenityData),
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' }, tooltip: { enabled: true } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { ticks: { autoSkip: false } } }
        }
    });

    // Revenue Chart
    const ctxRevenue = document.getElementById("revenuPerMonth").getContext("2d");
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueValues),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.4)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' }, tooltip: { enabled: true } },
            scales: { y: { beginAtZero: true }, x: { ticks: { autoSkip: false } } }
        }
    });

    // Card click navigation
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });

    // Filter functions
    window.updateFilter = function(filterType) {
        let year = document.getElementById("yearSelect") ? document.getElementById("yearSelect").value : "{{ $year }}";
        window.location.href = `{{ url('manager/dashboard') }}?filterType=${filterType}&year=${year}`;
    }

    window.updateYear = function(year) {
        let filterType = document.getElementById("filterType").value;
        window.location.href = `{{ url('manager/dashboard') }}?filterType=${filterType}&year=${year}`;
    }
});
</script>
