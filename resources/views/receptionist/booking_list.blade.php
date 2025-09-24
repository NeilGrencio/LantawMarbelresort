<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* Highlight active menu item */
        #booking {
            color: orange;
        }

        /* Layout */
        #layout {
            display: flex;
            height: 100vh;
        }

        #main-layout {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            margin-left: 12rem; /* sidebar width */
            height: 100vh;
            overflow: hidden;
        }

        /* Header */
        #layout-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            height: 4rem;
            background: white;
            border-radius: 0.7rem;
            border: 1px solid black;
            box-shadow: 0.1rem 0.1rem 0 black;
            font-size: 0.9rem;
            gap: 1rem;
        }

        #add-container {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .add-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Table container */
        .table-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-top: 1rem;
            padding: 0.5rem;
            background: white;
            border-radius: 0.7rem;
            box-shadow: 0.1rem 0.1rem 0 black;
            overflow-x: auto;
            overflow-y: hidden;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            table-layout: fixed; /* make columns responsive */
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            word-wrap: break-word;
        }

        thead {
            background: orange;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        /* Search box styling */
        .dataTables_filter {
            display: flex !important;
            justify-content: flex-start;
            margin-bottom: 1rem;
            width: 100%;
        }

        .dataTables_filter label {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .dataTables_filter input {
            margin-left: 0.5rem;
            padding: 0.4rem 0.6rem;
            width: 300px;
            max-width: 100%;
            border-radius: 0.4rem;
            border: 1px solid #F78A21;
        }

        /* Table buttons */
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 0.4rem;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
            color: #fff;
            border: none;
        }

        .btn-info {
            background: #17a2b8;
            color: #fff;
            border: none;
        }

        .btn-success {
            background: #28a745;
            color: #fff;
            border: none;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
            border: none;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 1200px) {
            #main-layout {
                margin-left: 0;
            }
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

            <!-- Table -->
            <div class="table-container">
                <table id="booking-table">
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
                                    <button class="btn btn-primary"
                                        onclick="window.location='{{ route('receptionist.view_booking', ['bookingID' => $booking->bookingID]) }}'">
                                        View
                                    </button>
                                    <button class="btn btn-info"
                                        onclick="window.location='{{ route('booking.edit', ['bookingID' => $booking->bookingID]) }}'">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Calendar button
        document.addEventListener("DOMContentLoaded", function () {
            const calendar_view = document.getElementById('add-action-btn');
            if (calendar_view) {
                const url = calendar_view.dataset.url;
                calendar_view.addEventListener('click', function () {
                    window.location.href = url;
                });
            }
        });

        // Initialize DataTables
        $(document).ready(function () {
            $('#booking-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: false,
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 } // Actions column not sortable
                ],
                dom: '<"top"f>rt<"bottom"ip><"clear">'
            });
        });
    </script>
</body>

</html>
