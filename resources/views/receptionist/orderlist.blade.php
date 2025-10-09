@php
    if (!session()->get('logged_in')) {
        header('Location: ' . route('login'));
        exit;
    }
@endphp
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
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    #menu{color:orange;}
    html, body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        font-family: Arial, sans-serif;
    }
    #layout {
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout {
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
        color:black;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-links div {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        font-size: 12px;
        color: rgb(0, 0, 0);
    }

    .nav-links div:hover {
        color: black;
        transform: scale(1.1);
    }

    #layout-wrapper {
        flex: 1;
        padding: 1rem;
        overflow-y: auto;
    }

    .table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0.1rem 0.1rem 0 black;
        overflow-x: auto;
        border: 1px solid black;
        margin-bottom: 1rem;
    }

    #order-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    #order-table th, #order-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #order-table th {
        background: #F78A21;
        color: white;
    }

    #order-table tr:nth-child(even) {
        background-color: #f3f3f3;
    }

    #order-table img {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

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

    .page-link, .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        min-height: 2rem;
        padding: 0.5rem;
        background: #fff;
        color: #F78A21;
        border: 1.5px solid #F78A21;
        border-radius: 50%;
        text-decoration: none;
        font-weight: 500;
    }

    .page-item.active .page-link,
    .page-link:hover {
        background: #F78A21;
        color: #fff;
    }

    .alert-message {
        position: fixed;
        bottom: 1rem;
        right: 50%;
        transform: translateX(50%);
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0 1rem rgba(0,0,0,0.3);
        padding: 1rem 2rem;
        text-align: center;
        z-index: 1000;
        font-size: 1rem;
    }

    .btn-edit {
        background: #F78A21;
        color: white;
        padding: 0.4rem 0.7rem;
        border-radius: 0.3rem;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-edit:hover {
        background: #d97319;
    }
</style>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar');
        <div id="main-layout">
            <div id="layout-header">
                <h2>Welcome Kitchen Staff</h2>

                <div class="nav-links">
                    <div class="menu-section" data-url="{{ route('receptionist.order') }}">
                        <i class="fas fa-utensils fa-2x"></i>
                        <strong>Menu</strong>
                    </div>
                    
                    <div class="menu-section" data-url="{{ route('receptionist.service') }}">
                        <i class="fas fa-bell-concierge fa-2x"></i>
                        <strong>Services</strong>
                    </div>
                </div>
            </div>

            <div id="layout-wrapper">
                <div id="main-content">
                    <div class="table-container">
                        <table id="order-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Booking Reference</th>
                                    <th>Order Ticket</th>
                                    <th>Item Names</th>
                                    <th>Total Quantity</th>
                                    <th>Order Total</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ $orders->firstItem() + $index }}</td>
                                        <td>{{ $order->bookingID }}</td>
                                        <td>{{ $order->bookingTicket }}</td>
                                        <td>{{ $order->menuname }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>₱{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @if (strtolower($order->status) === 'pending')
                                                <span>Pending</span>
                                            @elseif (strtolower($order->status) === 'confirmed')
                                                <span>Preparing</span>
                                            @else
                                                <span>Served</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if (strtolower($order->status) === 'pending')
                                            <a href="{{ url('receptionist/edit_order/' . $order->booking_id) }}" class="btn-edit">Edit</a>
                                        @else
                                            <span>Order Already Served!</span>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach

                                @if($orders->isEmpty())
                                    <tr>
                                        <td colspan="8"><h2>Currently No Orders.</h2></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                        @if (session('success'))
                            <div class="alert-message">
                                <h2>{{ session('success') }}</h2>
                            </div>
                        @endif
                    <div id="page-container">
                        <div class="pagination">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    const message = document.querySelector('.alert-message');
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 3500);
    }

    document.querySelectorAll('.menu-section').forEach(section => {
        section.addEventListener('click', function() {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });

    const logoutButton = document.getElementById('out');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    }
</script>