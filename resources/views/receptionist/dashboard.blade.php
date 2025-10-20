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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div class="dashboard-wrapper">
                <div class="title-contianer">
                    <p>Welcome Receptionist!</p>
                </div>
                <div class="card-container">
                   <div class="card" data-url="{{url('receptionist/booking')}}">
                        <p>Pending Bookings</p>
                        @if($pendingBook > 0)
                            <div id="new-notification" style="display:flex">{{$pendingBook}}</div>
                        @endif
                    </div>
                    
                    <div class="card" data-url="{{url('receptionist/booking')}}">
                        <p>Due Bookings</p>
                        @if($dueBooking > 0)
                            <div id="new-notification" style="display:flex">{{$dueBooking}}</div>
                        @endif
                    </div>  

                    <div class="card" data-url="{{url('receptionist/booking')}}">
                        <p>Cancelled Bookings</p>
                        @if($cancelledBook > 0)
                            <div id="new-notification" style="display:flex">{{$cancelledBook}}</div>
                        @endif
                    </div>  
                </div>

                <div id="todo-task">
                    <h2>TO-DO LIST</h2>
                    <div class="task-row" data-url="{{url('receptionist/check-in-out')}}">
                        <span class="task-label">Total Bookings:</span>
                        <span class="task-value">{{$dueBooking}}</span>
                    </div>
                    <div class="task-row" data-url="{{url('receptionist/chat')}}">
                        <span class="task-label">Chat Messages:</span>
                        <span class="task-value">{{$chats}}</span>
                    </div>
                </div>

                <div id="chart-container">
                    <div class="kpi-container">
                        <h2 style="text-align:center;">Key Performance Indicators</h2>
                    </div>
                    <div id="chart-wrapper">
                        <div>
                            <h3>Total Check-In and Check-Out</h3>
                            <canvas id="totalCheck"></canvas>
                        </div>

                        <div>
                            <h3>Resort Occupancy</h3>
                            <canvas id="resortCapacity"></canvas>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        @if (session('success'))
            <div class="alert-message">
                <h2>{{ session('success') }}</h2>
            </div>
        @endif
    </div>
</body>

<style>
    #dashboard{color:orange;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
        width:100%;
    }
    #main-layout{
        padding:1rem;
        margin-left:15rem;
        display:flex;
        flex-direction: row;
        gap:1rem;
        width:100%;
    }
    .dashboard-wrapper{
        position:relative;
        height:100%;
        width:100%;
        padding:.5rem;
        background:white;
        border-radius:.7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        overflow-y:auto;
    }
    .title-contianer{
        margin-top:-1.5rem;
        font-size:3rem;
        font-weight: lighter;
    }
    .card-container{
        margin-top:-1.5rem;
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap:1rem;
        padding:.5rem;
    }
    #chart-wrapper{
        display: grid;
        grid-template-columns: 1fr 1fr;
        width:100%;
        height:25rem;
    }
    #chart-wrapper div{
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items:center;
    }
    #totalCheck, #resortCapacity{
        width:100%;
        height:100%;
    }
    .card{
        width:100%;
        height:5rem;
        display:flex;
        position: relative;
        align-items: center;
        padding:1rem;
        font-size:.9rem;
        background:white;
        border-radius:.5rem;
        border:1px solid black;
        box-shadow:.1rem .2rem 0 black;
        transition:all .2s ease;
    }
    .card:hover{
        cursor:pointer;
        scale:1.05;
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
    .calendar-wrapper{
        position:absolute;
        right:.5rem;
        display:flex;
        flex-direction: column;
        width:30%;
        height:95.5%;
        background:white;
        border-radius:.7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        margin:auto;
        padding:1rem;
    }
    #calendar{
        height:50%;
        width:100%;
        font-size:.4rem;
    }

    .task-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-top:1rem;
    }

    #todo-task {
        display: flex;
        flex-direction: column;
        gap: 0.8rem; 
        width: 100%;
    }

    .task-row {
        display: flex;
        justify-content: space-between; 
        align-items: center;
        padding: 0.5rem 1rem;
        background: #ffffff;
        border-radius: 0.5rem;
        border: 1px solid #000000;
        transition:all .2s ease;
    }
    .task-row:hover{
        background:whitesmoke;
        cursor:pointer;
        border:1px solid orange;
        scale:1.05; 
    }

    .task-label {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .task-value {
        font-weight: bold;
        font-size: 1rem;
        color: #333;
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
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 3500);
    }

    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: false,
            editable: false,
            events: @json(url('receptionist/events')),
            eventClick: function (info) {
                const bookingID = info.event.id;
                window.location.href = `/receptionist/booking/${bookingID}`;
            },
            eventDidMount: function(info) {
                info.el.style.height = '1rem';
                info.el.style.lineHeight = '1rem';
                info.el.style.overflow = 'hidden';
                info.el.style.whiteSpace = 'nowrap';
                info.el.style.textOverflow = 'ellipsis';
                info.el.style.border = '1px solid black';
                info.el.style.fontSize = '.4rem';
                info.el.style.zIndex = '999';
                info.el.style.position = 'relative';
                info.el.style.marginTop = '-.5rem';
            },
        });

        calendar.render();
    }

    const ctxCheck = document.getElementById('totalCheck').getContext('2d');
    const checkInToday = {{ $checkInToday ?? 0 }};
    const checkOutToday = {{ $checkOutToday ?? 0 }};

    const totalCheckChart = new Chart(ctxCheck, {
        type: 'bar',
        data: {
            labels: ['Check-Ins Today', 'Check-Outs Today'],
            datasets: [{
                label: 'Number of Guests',
                data: [checkInToday, checkOutToday],
                backgroundColor: ['#4CAF50', '#FF9800'],
                borderColor: ['#388E3C', '#F57C00'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Total Check-Ins & Check-Outs Today' }
            },
            scales: {
                y: { beginAtZero: true, stepSize: 1 }
            }
        }
    });

    const ctxCapacity = document.getElementById('resortCapacity').getContext('2d');
    const totalRooms = {{ $totalRooms ?? 1 }};
    const currentOccupied = {{ $currentOccupied ?? 0 }};
    const occupancyPercent = Math.min(Math.round((currentOccupied / totalRooms) * 100), 100);
    const remainingPercent = 100 - occupancyPercent;

    const resortCapacityChart = new Chart(ctxCapacity, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available'],
            datasets: [{
                label: 'Occupancy',
                data: [occupancyPercent, remainingPercent],
                backgroundColor: ['#FF5722', '#E0E0E0'],
                borderColor: ['#BF360C', '#BDBDBD'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: `Resort Occupancy: ${occupancyPercent}%` }
            }
        }
    });

    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const url = card.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });

    const tasks = document.querySelectorAll('.task-row');
    tasks.forEach(task => {
        task.addEventListener('click', () => {
            const url = task.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });

});
</script>
