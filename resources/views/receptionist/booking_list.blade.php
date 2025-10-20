<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort</title>
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* Layout */
        #layout {
            display: flex;
            height: 100vh;
            width:98vw;
            background: #f5f5f5;
        }

        #main-layout {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            margin-left: 15rem;
            overflow: visible;
        }

        /* Header */
        #layout-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        #layout-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }

        /* Add buttons */
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
            transition: transform 0.2s, box-shadow 0.2s;
            padding: 0.5rem;
            border-radius: 0.7rem;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .add-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .add-action i {
            color: #F78A21;
        }

        .add-action small {
            margin-top: 0.3rem;
            font-size: 0.75rem;
            color: #555;
        }

        /* Filter */
        #status-filter {
            padding: 0.5rem 0.8rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            background: #fff;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Table */
        .table-container {
            flex: 1;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 1rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
        }

        thead {
            background: linear-gradient(90deg, #F78A21, #FFB74D);
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        th, td {
            padding: 12px 10px;
            text-align: center;
        }

        tbody tr {
            background: #fff;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #fff3e0;
        }

        /* Buttons */
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease-in-out;
            margin: 0 0.1rem;
        }

        .btn-primary {
            background: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background: #0069d9;
        }

        .btn-info {
            background: #17a2b8;
            color: #fff;
        }

        .btn-info:hover {
            background: #138496;
        }

        .btn-success {
            background: #28a745;
            color: #fff;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* DataTables search */
        .dataTables_filter {
            display: flex !important;
            justify-content: flex-start;
            margin-bottom: 1rem;
        }

        .dataTables_filter label {
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .dataTables_filter input {
            padding: 0.4rem 0.6rem;
            border-radius: 0.4rem;
            border: 1px solid #ccc;
        }

        @media screen and (max-width: 1200px) {
            #main-layout {
                margin-left: 0;
                padding: 1rem;
            }

            .add-action small {
                display: none;
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
                    <div class="add-action" data-url="{{ url('receptionist/walk-booking') }}">
                        <i class="fas fa-hotel fa-2x"></i>
                        <small>Walk In Booking</small>
                    </div>
                    <div class="add-action" data-url="{{ url('receptionist/create_booking') }}">
                        <i class="fas fa-plus-circle fa-2x"></i>
                        <small>Normal Booking</small>
                    </div>
                    <div class="add-action" data-url="{{ url('receptionist/booking') }}">
                        <i class="fa-solid fa-calendar-days fa-2x"></i>
                        <small>Calendar View</small>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <label for="status-filter">Filter by Status:</label>
                <select id="status-filter">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Booked">Booked</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Finished">Finished</option>
                </select>

                <table id="booking-table" class="display nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest Name</th>
                            <th>Guest Count</th>
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
                        @foreach ($bookings as $index => $booking)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $booking->guestname }}</td>
                            <td>{{ $booking->guestamount }}</td>
                            <td>{{ $booking->amenityname }}</td>
                            <td>{{ $booking->bookingstart }}</td>
                            <td>{{ $booking->bookingend }}</td>
                            <td>{{ $booking->totalprice }}</td>
                            <td>{{ $booking->booking_type }}</td>
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
        // DataTables initialization
        $(document).ready(function() {
            var table = $('#booking-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                autoWidth: false,
                responsive: true,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                dom: '<"top"f>rt<"bottom"lip><"clear">'
            });

            // Status filter
            $('#status-filter').on('change', function() {
                var val = $(this).val();
                if (val) {
                    table.column(8).search('^' + val + '$', true, false).draw();
                } else {
                    table.column(8).search('').draw();
                }
            });
        });

        // Add action buttons
        document.querySelectorAll('.add-action').forEach(btn => {
            btn.addEventListener('click', function() {
                window.location.href = this.dataset.url;
            });
        });
    </script>
</body>

</html>
