<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #booking {
            color: orange;
        }

        #layout {
            display: flex;
            flex-direction: row;
            height: 100vh;
        }

        #main-layout {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            width: 100%;
            transition: width 0.3s ease-in-out;
            margin-left: 12rem;
        }

        #layout-header {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 4rem;
            padding: 1rem;
            background: white;
            border-radius: .7rem;
            border: black 1px solid;
            box-shadow: .1rem .1rem 0 black;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: .9rem;
        }

        #add-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
        }

        .add-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-evenly;
            cursor: pointer;
        }

        .table-container {
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

        table {
            width: 100%;
            font-size: .7rem;
            border-collapse: collapse;
            transition: all 0.3s ease-in;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        thead {
            background: orange;
            color: white;
            justify-content: center;
            align-items: center;
        }

        tbody {
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
</head>

<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Booking List</h1>
                <div id="add-container">
                    <div class="add-action">
                        <i id="add-action-btn" class="fa-solid fa-calendar-days fa-2x"
                            data-url="{{ url('receptionist/booking') }}"></i>
                        <small>Calendar View</small>
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
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $count = 0; @endphp
                        @foreach ($bookings as $booking)
                            @php $count++; @endphp
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $booking->guestname }}</td>
                                <td>{{ $booking->guestamount }}</td>
                                <td>{{ $booking->roomcount }}</td>
                                <td>{{ $booking->cottagecount }}</td>
                                <td>{{ $booking->amenityname }}</td>
                                <td>{{ $booking->bookingstart }}</td>
                                <td>{{ $booking->bookingend }}</td>
                                <td>{{ $booking->totalprice }}</td>
                                <td>{{ $booking->type }}</td>
                                <td>{{ $booking->status }}</td>
                                <td>
                                    <!-- Approve Booking -->
                                    <button class="btn btn-success"
                                        onclick="approveBooking({{ $booking->bookingID }})">Approve</button>
                                    <button class="btn btn-danger"
                                        onclick="declineBooking({{ $booking->bookingID }})">Decline</button>

                                    <a
                                        href="{{ url('receptionist/cancel_booking', ['id' => $booking->bookingID]) }}">Cancel</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="page-container">
                {{ $bookings->links() }}
            </div>
</body>
<script>
    function approveBooking(id) {
        fetch("{{ route('receptionist.approve_booking') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    booking_id: id
                })
            })
            .then(res => res.json())
            .then(data => {
                alert("Booking approved!");
                console.log(data);
            });
    }

    function declineBooking(id) {
        fetch("{{ route('receptionist.decline_booking') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    booking_id: id
                })
            })
            .then(res => res.json())
            .then(data => {
                alert("Booking declined!");
                console.log(data);
            });
    }
    document.addEventListener("DOMContentLoaded", function() {
        const calendar_view = document.getElementById('add-action-btn')
        if (calendar_view) {
            const url = calendar_view.dataset.url;
            calendar_view.addEventListener('click', function() {
                window.location.href = url;
            });
        };
    });
</script>
