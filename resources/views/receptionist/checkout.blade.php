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
        width:100%;
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
        font-size: .7rem;
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

    #table-container{
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
    </style>
    <body>
        <div id="layout">
            @include('components.receptionist_sidebar')
            <div id="main-layout">
                <div id="layout-header">
                    <h1>Check Out</h1>
                    <div class="button-group">
                        <div class="search-container">
                            <form action="{{ route('receptionist.search_checkout') }}" method="GET">
                                <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                                <button type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if(request()->has('search') && request('search') !== '')
                                    <a href="{{ route('receptionist.search_checkout') }}" class="reset-btn">Clear Search</a>
                                @endif
                            </form>
                        </div>
                        <div class="add-action">
                            <i class="fas fa-hotel fa-2x" data-url="{{ url('receptionist/checkin_list') }}" style="cursor:pointer;"></i>
                            <small>Check In Booking</small>
                        </div>
                        <div class="add-action">
                            <i class="fa-solid fa-list-ol fa-2x" data-url="{{ url('receptionist/check-in-out') }}" style="cursor:pointer;"></i>
                            <small>Return</small>
                        </div>
                        
                    </div>
                    
                </div>

                <div id="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking #</th>
                                <th>Guest Name</th>
                                <th>Guest Count</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Total Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checkout as $index => $booking)
                                <tr>
                                    <td>{{ ($checkout->currentPage() - 1) * $checkout->perPage() + $index + 1 }}</td>
                                    <td>{{ $booking->guestname }}</td>
                                    <td>{{ $booking->guestamount }}</td>
                                    <td>{{ $booking->bookingstart }}</td>
                                    <td>{{ $booking->bookingend }}</td>
                                    <td>{{ $booking->totalprice }}</td>
                                    <td>{{ $booking->booking_type }}</td>
                                    <td>{{ $booking->status }}</td>
                                    <td>
                                        <button class="btn btn-primary"
                                            onclick="window.location='{{ route('receptionist.checkout', ['bookingID' => $booking->bookingID]) }}'">
                                            Check-Out
                                        </button>
                                        <button class="btn btn-info"
                                            onclick="window.location='{{ route('receptionist.view_booking', ['bookingID' => $booking->bookingID]) }}'">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="page-container">
                    {{ $checkout->links() }}
                </div>

            </div>
        </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const icons = document.querySelectorAll('.add-action i[data-url]');

            icons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const url = icon.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
        });

    </script>
</html>