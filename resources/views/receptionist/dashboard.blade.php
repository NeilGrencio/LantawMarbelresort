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
            <div class="dashboard-wrapper">
                <div class="title-contianer">
                    <p>Welcome Receptionist!</p>
                </div>
                <div class="card-container">
                    <div class="card">
                        <p>Pending Bookings</p>
                        @if($pendingBookingCount == 0)
                            <div id="new-notification" style="display:none">{{$dueBookingCount}}</div>
                        @else
                            <div id="new-notification" style="display:flex">{{$dueBookingCount}}</div>
                        @endif
                    </div>  
                    
                    <div class="card">
                        <p>Due Bookings</p>
                        @if($dueBookingCount == 0)
                            <div id="new-notification" style="display:none">{{$dueBookingCount}}</div>
                        @else
                            <div id="new-notification" style="display:flex">{{$dueBookingCount}}</div>
                        @endif
                    </div>  

                    <div class="card">
                        <p>Cancelled Reservations</p>
                        @if($cancelledBookingsCount == 0)
                            <div id="new-notification" style="display:none">{{$cancelledBookingsCount}}</div>
                        @else
                            <div id="new-notification" style="display:flex">{{$cancelledBookingsCount}}</div>
                        @endif
                    </div>  

                    <div class="card">
                        <p>Pending Kiddy Pool Reservation</p>
                        <div id="new-notification">1</div>
                    </div>  
                    
                    <div class="card">
                        <p>Cancelled Kiddy Pool Reservation</p>
                        <div id="new-notification">1</div>
                    </div>  

                    <div class="card">
                        <p>Accepted Kiddy Pool Reservation</p>
                        <div id="new-notification">1</div>
                    </div>  

                    <div class="card">
                        <p>Chat</p>
                        <div id="new-notification">1</div>
                    </div>  
                    
                    <div class="card">
                        <p>Feedback</p>
                        <div id="new-notification">1</div>
                    </div>  

                    <div class="card">
                        <p>Available Discounts</p>
                        <div id="new-notification">1</div>
                    </div>  
                </div>
            </div>
            <div class="calendar-wrapper">
                <div id="calendar"></div>
                <div class="task-container">
                    <h2>To Do List</h2>
                    <div style="display:flex;justify-content:space-between;">
                        <h4>Booking Amount:</h4>  <strong>{{$dueBookingCount}}</strong>
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
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        padding:1rem;
        margin-left:12rem;
        display:flex;
        flex-direction: row;
        gap:1rem;
        width:100%;
    }
    .dashboard-wrapper{
        position:relative;
        height:100%;
        width:63%;
        padding:.5rem;
        background:white;
        border-radius:.7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
    }
    .title-contianer{
        margin-top:-1.5rem;
        font-size:3rem;
        font-weight: lighter;
    }
    .card-container{
        margin-top:-1.5rem;
        display:flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap:1rem;
    }
    .card{
        width:19rem;
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
    document.getElementById('dashboard').style = "color:#F78A21;"
    document.addEventListener('DOMContentLoaded', function () {
        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }
        let calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: false,
                editable: false,
                events: @json(url('receptionist/events')),
                eventClick: function (info) {
                    const bookingID = info.event.id;
                    window.location.href = `/receptionist/booking`;
                },
                eventDidMount: function(info) {
                    info.el.style.height = '1rem';
                    info.el.style.lineHeight = '1rem';
                    info.el.style.overflow = 'hidden';
                    info.el.style.whiteSpace = 'wrap';
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
    });
</script>