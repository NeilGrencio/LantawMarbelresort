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
/* --- BASE --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f8;
    margin: 0;
    color: #333;
}
h1 {
    font-size: 1.6rem;
    margin: 0;
}

/* --- LAYOUT --- */
#layout {
    display: flex;
    min-height: 100vh;
}
#main-layout {
    flex: 1;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    background: #f4f6f8;
    overflow-x: hidden;
}

/* --- HEADER --- */
#layout-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 1rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* --- SEARCH & ACTIONS --- */
.button-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.search-container {
    display: flex;
    gap: 0.5rem;
}
.search-container input[type="text"] {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 2rem 0 0 2rem;
    outline: none;
    width: 200px;
    transition: all 0.3s;
}
.search-container input[type="text"]:focus {
    border-color: #FF7A00;
}
.search-container button {
    padding: 0.5rem 1rem;
    border: none;
    background: #FF7A00;
    color: #fff;
    border-radius: 0 2rem 2rem 0;
    cursor: pointer;
    transition: background 0.3s;
}
.search-container button:hover {
    background: #e65a00;
}
.reset-btn {
    padding: 0.4rem 0.8rem;
    background: #e53935;
    color: #fff;
    border-radius: 2rem;
    text-decoration: none;
    transition: background 0.3s;
}
.reset-btn:hover {
    background: #b71c1c;
}
.add-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.add-action:hover {
    transform: scale(1.1);
}
.add-action i {
    font-size: 2rem;
    color: #FF7A00;
}
.add-action small {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #555;
}

/* --- TABLE --- */
#table-container {
    overflow-x: auto;
    background: #fff;
    border-radius: 1rem;
    padding: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
thead {
    background: #FF7A00;
    color: #fff;
}
th, td {
    padding: 0.75rem 1rem;
    text-align: center;
    border-bottom: 1px solid #eee;
}
tr:hover {
    background: rgba(255,122,0,0.05);
    transition: background 0.3s;
}

/* --- BUTTONS --- */
button.btn {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
}
button.btn-primary {
    background: #FF7A00;
    color: #fff;
}
button.btn-primary:hover {
    background: #e65a00;
}
button.btn-info {
    background: #2196F3;
    color: #fff;
}
button.btn-info:hover {
    background: #1976D2;
}

/* --- PAGINATION --- */
#page-container {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}
.pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
}
.page-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid #FF7A00;
    color: #FF7A00;
    text-decoration: none;
}
.page-link:hover {
    background: #FF7A00;
    color: #fff;
}
.page-item.active .page-link {
    background: #FF7A00;
    color: #fff;
}
.page-item.disabled {
    display: none;
}

/* --- RESPONSIVE --- */
@media (max-width: 1024px) {
    #main-layout {
        padding: 1rem;
    }
}
@media (max-width: 768px) {
    #layout {
        flex-direction: column;
    }
    #main-layout {
        margin-left: 0;
    }
    .search-container input[type="text"] {
        width: 140px;
    }
}
</style>
</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Check In</h1>
            <div class="button-group">
                <div class="search-container">
                    <form action="{{ route('receptionist.search_checkin') }}" method="GET">
                        <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                        @if(request()->has('search') && request('search') !== '')
                            <a href="{{ route('receptionist.search_checkin') }}" class="reset-btn">Clear</a>
                        @endif
                    </form>
                </div>
                <div id="add-container">
                    <div class="add-action">
                        <i class="fa-solid fa-list-ol" data-url="{{ url('receptionist/check-in-out') }}"></i>
                        <small>Return</small>
                    </div>
                    <div class="add-action">
                        <i class="fas fa-hotel" data-url="{{ url('receptionist/checkout_list') }}"></i>
                        <small>Check Out</small>
                    </div>
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
                    @foreach ($checkin as $index => $booking)
                        <tr>
                            <td>{{ ($checkin->currentPage() - 1) * $checkin->perPage() + $index + 1 }}</td>
                            <td>{{ $booking->guestname }}</td>
                            <td>{{ $booking->guestamount }}</td>
                            <td>{{ $booking->bookingstart }}</td>
                            <td>{{ $booking->bookingend }}</td>
                            <td>{{ $booking->totalprice }}</td>
                            <td>{{ $booking->booking_type }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick="window.location='{{ route('receptionist.checkin', ['bookingID' => $booking->bookingID]) }}'">
                                    Check-In
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
            {{ $checkin->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-action i[data-url]').forEach(icon => {
        icon.addEventListener('click', function() {
            window.location.href = icon.dataset.url;
        });
    });
});
</script>
</body>
</html>
