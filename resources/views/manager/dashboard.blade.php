<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                        <div class="new-notification">{{$feedbackNotification}}</div>
                    @endif
                </div>  

                <div class="card" data-url="{{ url('manager/chat') }}" id="inquiry">
                    <p>Chat Messages</p>
                    @if($notificationInquiry > 0)
                        <div class="new-notification">{{$notificationInquiry}}</div>
                    @endif
                </div>  

                <div class="card" data-url="{{ url('manager/session_logs') }}" id="logs">
                    <p>User Log Ins</p>
                    @if($userLogIns > 0)
                        <div class="new-notification">{{$userLogIns}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/room_list') }}" id="availablerooms">
                    <p>Available Rooms</p>
                    @if ($availableRooms > 0)
                        <div class="new-notification">{{$availableRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/room_list') }}" id="unavailablerooms">
                    <p>Unavailable Rooms</p>
                    @if ($unavailableRooms > 0)
                        <div class="new-notification">{{$unavailableRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/room_list') }}" id="maintenancedrooms">
                    <p>Maintenanced Rooms</p>
                    @if ($maintenancedRooms > 0)
                        <div class="new-notification">{{$maintenancedRooms}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="availablecottages">
                    <p>Available Cottages</p>
                    @if ($availableCottages > 0)
                        <div class="new-notification">{{$availableCottages}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="unavailablecottages">
                    <p>Unavailable Cottages</p>
                    @if ($unavailableCottages > 0)
                        <div class="new-notification">{{$unavailableCottages}}</div>
                    @endif
                </div> 

                <div class="card" data-url="{{ url('manager/cottage_list') }}" id="maintenancedcottages">
                    <p>Maintenanced Cottages</p>
                    @if ($maintenancedCottages > 0)
                        <div class="new-notification">{{$maintenancedCottages}}</div>
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
                    <div class="chart-card">
                        <h2>Booking This Month</h2>
                        <canvas id="bookingPerMonth"></canvas>
                    </div>
                    <div class="chart-card">
                        <h2>Hotel Guest vs Day Tour</h2>
                        <canvas id="bookingsVsRevenue"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div class="chart-card full-width">
                        <h2>Rooms Booked This Month</h2>
                        <canvas id="roomBookedPerMonth"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div class="chart-card full-width">
                        <h2>Amenities Toured This Month</h2>
                        <canvas id="amenityTourPerMonth"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div class="chart-card full-width">
                        <h2>Revenue Per Month</h2>
                        <canvas id="revenuPerMonth"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<style>
#dashboard {
    background: rgba(255,255,255,0.15);
    border-left: 4px solid #ff9100;
    color: white;
}
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: 'Poppins', sans-serif;
    background: white;
}
#layout {
    display: flex;
    flex-direction: row;
    height: 100vh;
    width: 100%;
}
#main-layout {
    padding: 1.5rem;
    width: calc(100% - 14rem);
    overflow-x: auto;
}
.title_container {
    display: flex;
    height: 5rem;    
    font-size: 2rem;
    font-weight: 300;
    background: white;
    border: 1px solid black;
    border-radius: 0.7rem;
    box-shadow: 0.1rem 0.1rem 0 black;
    align-items: center;
    padding: 0.5rem;
}
.card_container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1.5rem;
    width: 100%;
    margin-top: 1rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid black;
    border-radius: 0.7rem;
    box-shadow: 0.1rem 0.1rem 0 black;
}
.card {
    height: 3.5rem;
    display: flex;
    position: relative;
    align-items: center;
    padding: 1rem;
    font-size: 0.9rem;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid black;
    box-shadow: 0.1rem 0.2rem 0 black;
    transition: all 0.2s ease-in-out;
}
.card:hover {
    cursor: pointer;
    transform: scale(1.1);
    background: whitesmoke;
}
.new-notification {
    display: flex;
    position: absolute;
    height: 2rem;
    width: 2rem;
    align-items: center;
    justify-content: center;
    top: -1rem;
    right: -1rem;
    background: red;
    color: white;
    border-radius: 50%;
    border: 1px solid black;
    box-shadow: 0.1rem 0.1rem 0 black;
    font-size: 1rem;
}
#kpi-charts-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    margin-top: 1rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid black;
    border-radius: 0.7rem;
    box-shadow: 0.1rem 0.1rem 0 black;
    justify-content: center;
    align-items: center;
}
.filter-wrapper {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}
.chart-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    width: 100%;
    margin-bottom: 2rem;
}
.chart-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.chart-card h2 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}
.chart-card.full-width {
    grid-column: 1 / -1;
}
select {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: white;
    font-size: 0.9rem;
    cursor: pointer;
}
label {
    font-size: 0.9rem;
    font-weight: 500;
}
@media (max-width: 1024px) {
    .chart-wrapper {
        grid-template-columns: 1fr;
    }
    .card_container {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 768px) {
    .card_container {
        grid-template-columns: 1fr;
    }
}
</style>
<script>
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
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1 }
                },
                x: { grid: { display: false } }
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
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Day Tour Guest',
                    data: @json($daytourData),
                    borderColor: '#f5576c',
                    backgroundColor: 'rgba(245, 87, 108, 0.2)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { padding: 15, usePointStyle: true } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
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
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(245, 87, 108, 0.8)',
                    'rgba(67, 233, 123, 0.8)',
                    'rgba(252, 182, 159, 0.8)'
                ],
                borderRadius: 10,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
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
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderRadius: 10,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 10 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Revenue Chart
    const ctxRevenue = document.getElementById("revenuPerMonth").getContext("2d");
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenue (PHP)',
                data: @json($revenueValues),
                borderColor: '#43e97b',
                backgroundColor: 'rgba(67, 233, 123, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#43e97b',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: value => '₱'+value.toLocaleString() } },
                x: { grid: { display: false } }
            }
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
</html>
