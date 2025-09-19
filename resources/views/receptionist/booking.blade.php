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
    #layout-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 8%;
        padding: 1rem 3rem 1rem 2rem;
        background: white;
        border-radius: .7rem;
        font-size: .6rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    .add-action{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
        cursor: pointer;
        font-size: .8rem;
    }

    .search-container .reset-btn {
        padding: 10px 15px;
        background-color: #e53935;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        margin-left: 10px;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    .search-container .reset-btn:hover {
        background-color: #b71c1c;
    }
    .button-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
    }
    #add-container:hover {
        color: #F78A21;
    }
    #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
        margin-left: 0.5rem;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-content: center;
        margin: 15px 0;
    }

    .search-container form {
        display: flex;
        align-items: center;
    }

    .search-container input[type="text"] {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 25px 0 0 25px;
        outline: none;
        width: 250px;
        font-size: 14px;
    }

    .search-container button {
        padding: 10px 15px;
        border-left: none;
        background-color: #000000;
        color: white;
        border-radius: 0 25px 25px 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-container button:hover {
        background-color: #F78A21;
        border: 1px solid #F78A21;
    }

    .table-container{
        display: flex;
        flex-direction: row;
        width: 100%;
        height: auto;
        padding: .5rem;
        border-radius: .7rem;
        margin-top: 1rem;
        align-items: center;
        align-content: center;
        background: white;
        box-shadow: .1rem .1rem 0 black;
        overflow-x: auto;
    }
    table{
        width: 100%;
        font-size:.7rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    th, td{
        padding: 10px;
        text-align: center;
    }
    thead{
        background:orange;
        color:white;
        justify-content: center;
        align-items: center;
    }
    tbody{
        justify-content: center;
        align-items: center;
    }
    .booking-cancelled {
        background-color: #ffe5e5;
        color: #b71c1c;
        font-weight: bold;
    }

    .booking-completed {
        background-color: #e6ffe6;
        color: #2e7d32;
        font-weight: bold;
    }

    #page-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        background: transparent;
        align-items: center;
    }
    .page-item {
        display: flex;
        align-items: center;
    }
    .page-link,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        min-height: 2.5rem;
        padding: 0.5rem 0.75rem;
        background: #fff;
        color: #F78A21;
        text-decoration: none;
        border: 1.5px solid #F78A21;
        border-radius: 50%;
        font-size: 1.1rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, border 0.2s;
        margin: 0 0.15rem;
    }
    .page-item.active .page-link,
    .page-link:hover {
        background: #F78A21;
        color: #fff;
        border-color: #F78A21;
    }
    .page-item.disabled .page-link,
    .page-item.disabled span {
        color: #ccc;
        pointer-events: none;
        background: #f8f9fa;
        border-color: #eee;
    }
    .page-item.disabled {
        display: none !important;
    }
    .pagination .page-status {
        background: transparent;
        border: none;
        color: #333;
        font-size: 1rem;
        font-weight: 400;
        border-radius: 0;
        min-width: unset;
        min-height: unset;
        margin: 0 0.5rem;
        padding: 0;
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
                <h1>Booking List</h1>
                <div class="button-group">
                        <div id="add-container" data-url="{{url('receptionist/booking')}}">
                            <div class="add-action">
                                <i id="add-user" class="fa-solid fa-calendar-days fa-2x"  style="cursor:pointer;"></i>
                                <small>Calendar View</small>
                            </div>
                        </div>
                    <div class="search-container">
                        <form action="{{ route('receptionist.search_booking') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('receptionist.search_booking') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>

                    </div>

                </div>
            </div>

            <!----------TABLE VIEW STARTS HERE--------->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Booking #</th>
                            <th>Guest Name</th>
                            <th>Guest Count</th>
                            <th>Room Count</th>
                            <th>Cottage Count</th>
                            <th>Amenity</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $count = 0; @endphp
                        @foreach($bookingtoday as $booking)
                            @php
                                $count++;
                                $rowClass = '';
                                if ($booking->status === 'Cancelled') {
                                    $rowClass = 'booking-cancelled';
                                } elseif ($booking->status === 'Completed') {
                                    $rowClass = 'booking-completed';
                                }
                            @endphp

                            <tr class="{{ $rowClass }}">
                                <td>{{ $count }}</td>
                                <td>{{ $booking->guest ? $booking->guest->firstname . ' ' . $booking->guest->lastname : 'N/A' }}</td>
                                <td>{{ $booking->guestamount }}</td>
                                <td>
                                    @if($booking->roomBookings->count())
                                        {{ $booking->roomBookings->pluck('room.roomname')->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($booking->cottageBookings->count())
                                        {{ $booking->cottageBookings->pluck('cottage.cottagename')->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $booking->amenity->amenityname ?? 'N/A' }}</td>
                                <td>{{ $booking->bookingstart }}</td>
                                <td>{{ $booking->bookingend }}</td>
                                <td>{{ $booking->totalprice }}</td>
                                <td>{{ $booking->status }}</td>
                                <td>
                                    <a href="{{ route('receptionist.view_booking', ['bookingID' => $booking->bookingID]) }}">
                                        Edit
                                    </a> |
                                    <a href="{{ route('receptionist.cancel_booking', ['bookingID' => $booking->bookingID]) }}">
                                        Cancel
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div id="page-container">
                {{ $bookings->links() }}
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
</body>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        const calendar_view = document.getElementById('add-container')
        if(calendar_view){
            const url = calendar_view.dataset.url;
            calendar_view.addEventListener('click', function(){
                window.location.href = url;
            });
        };
    });
</script>
