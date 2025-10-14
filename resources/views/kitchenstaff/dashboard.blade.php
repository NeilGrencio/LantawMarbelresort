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
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
    }

    #layout {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100%;
        overflow: hidden;
        background: rgba(248, 242, 230, 0.5);
    }
    .total-container{ 
        display:grid; 
        grid-template-columns: 1fr 1fr 1fr; 
        text-align: center; 
    }
    #layout-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100vw;
        height: 8%;
        padding: 1rem 3rem 1rem 2rem;
        background: #F78A21;
        color:white;
        font-size: .7rem;
        border-bottom: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    #layout-wrapper {
        display: flex;
        flex-direction: row;
        padding: 1rem;
        width: 100%;
        height: 92%;
        overflow: hidden;
        gap: .5rem;
        background: transparent;
    }
    #main-content {
        flex: 1;
        height: 100%;
        overflow-y: auto;
    }

    #layout-side {
        flex: 0 0 25%;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 0.7rem;
        overflow-y: auto;
        padding: 1rem;
        gap: 1rem;
        overflow:none;
    }
    #menu-list{
        display: flex;
        flex-direction: column;
        gap: .7rem;
        overflow-y: auto;
        height: 100%;
        width:100%;
        scrollbar-width: thin;
        scrollbar-color: #F78A21 transparent;
        padding: .5rem;
    }
    #menu-list::-webkit-scrollbar {
        width: 6px;
    }
    #menu-liste::-webkit-scrollbar-thumb {
        background-color: #F78A21;
        border-radius: 3px;
    }
    #side-header{
        display:flex;
        flex-direction: row;
        width: 100%;
        justify-content: space-between;
    }
    #filters {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }

    #filter-time, #filter-type {
        padding: 0.5rem;
        border: 1px solid #F78A21;
        border-radius: 0.5rem;
        background: white;
        color: #333;
        font-size: 0.7rem;
        box-shadow: 0.1rem 0.1rem 0 rgb(184, 94, 24);
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    #filter-time:hover, #filter-type:hover {
        background: #F78A21;
        color: white;
    }

    #menu-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #fff;
        padding: 1rem;
        border-radius: .5rem;
        box-shadow: 0.1rem 0.1rem 0 #000;
        width: 100%;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0.25rem 0.25rem 0 #000;
    }

    #menu-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }

    #menu-card h3 {
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 0.3rem;
    }

    #menu-card p {
        font-size: 0.8rem;
        color: #555;
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
        background: white;
        box-shadow: .1rem .1rem 0 black;
        border:1px solid black;
        overflow-x: auto;
        padding:.1rem;
    }
    #order-table {
        width: 100%;
        font-size: .7rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    #order-table th, 
    #order-table td {
        padding: 10px;
        text-align: center;
    }
    #order-table th {
        background-color: #F78A21;
        color: #fff;
    }
    #order-table img {
        border-radius: 50%;
        object-fit: contain;
        width: 40px;
        height: 40px;
        display: block;
        margin: 0 auto;
    }

    #page-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
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
    .page-link, .pagination span {
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

    .even-row {
        background-color: #e2e2e2;
    }
    .odd-row {
        background-color: #ffffff;
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
    .nav-links{
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: .3rem;
        font-size: 12px;
        cursor:pointer;
        gap:1rem;
    }
    .nav-links div{
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all .2s ease-in-out;
    }
    .nav-links div:hover{
        color: black;
        transform: rotate(-10deg);
        scale: 1.1;
    }
</style>
<body>
    <div id="layout">
        <div id="layout-header">
            <h2>Welcome Kitchen Staff</h2>

            <div class="nav-links">
                <div id="menu-section" data-url="{{ route('kitchen.menu_list') }}">
                    <i class="fa-solid fa-utensils fa-2x"></i>
                    <strong>Menu</strong>
                </div>
                <div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <div id="out">  
                            <i class="fa-solid fa-power-off fa-2x"></i>
                            <button type="submit" style="all:unset; cursor:pointer; font-size:12px;">Log Out</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="layout-wrapper">
            <div id="main-content">
                <div class="total-container">
                    <div class="total-card">
                        <h3>Total Orders</h3>
                        <p>{{ $totalOrders }}</p>
                    </div>

                    <div class="total-card">
                        <h3>Preparing Orders</h3>
                        <p>{{ $pendingOrders }}</p>
                    </div>

                    <div class="total-card">
                        <h3>Finished Orders</h3>
                        <p>{{ $confirmedOrders }}</p>
                    </div>
                </div>

                <div class="info-container">
                    <div class="info-card">
                        <h3>Now Serving</h3>
                        @if(!$serving->isEmpty()) 
                            @foreach($serving as $serve)
                                <p>{{ $serve->bookingTicket }} </p>
                            @endforeach
                        @else
                            <p>No orders being served currently.</p>
                        @endif
                    </div>
                </div>

                <div class="table-container">
                    <table id="order-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Reference</th>
                                <th>Order Ticket</th>
                                <th>Item Names</th>
                                <th>Total Quantity</th>
                                <th>Order Date & Time</th>
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
                                    <td>{{ $order->orderdate }}</td>
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
                                            <form action="{{ route('orders.prepare', $order->booking_id) }}" method="POST">
                                                @csrf
                                                <button type="submit">Prepare Order</button>
                                            </form>
                                        @elseif (strtolower($order->status) === 'confirmed')
                                            <form action="{{ route('orders.serve', $order->booking_id) }}" method="POST">
                                                @csrf
                                                <button type="submit">Serve Order</button>
                                            </form>
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
                <div id="layout-side">
                    <div id="side-header">
                        <h3>Trending Items</h3>
                        <select id="filter-time">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                        </select>

                        <select id="filter-type">
                            <option value="all">All</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="meal">Meal</option>
                            <option value="maincourse">Main Course</option>
                            <option value="dinner">Dinner</option>
                            <option value="drinks">Drinks</option>
                            <option value="dessert">Dessert</option>
                            <option value="appetizer">Appetizer</option>
                        </select>
                    </div>
                <div id="menu-list">
                    @foreach($topMenuItems as $top)
                        <div id="menu-card">
                            @if ($top->image_url)
                                <img src="{{ $top->image_url }}" alt="{{ $top->menuname }}" width="100" height="100">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No image" width="100" height="100">
                            @endif
                            <h3>{{ $top->menuname }}</h3>
                            <p>Total Ordered: {{ $top->totalOrdered }}</p>
                        </div>
                    @endforeach
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
    
   document.getElementById('out').addEventListener('click', function() {
        const url = this.dataset.url;  
        if (url) {
            window.location.href = url;
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        const timeSelect = document.getElementById("filter-time");
        const typeSelect = document.getElementById("filter-type");

        const urlParams = new URLSearchParams(window.location.search);
        timeSelect.value = urlParams.get("time") || "all";
        typeSelect.value = urlParams.get("type") || "all";

        function applyFilters() {
            const params = new URLSearchParams();
            const time = timeSelect.value;
            const type = typeSelect.value;

            if (time !== "all") params.set("time", time);
            if (type !== "all") params.set("type", type);

            const url = `${window.location.pathname}?${params.toString()}`;
            window.location.href = url;
        }

        timeSelect.addEventListener("change", applyFilters);
        typeSelect.addEventListener("change", applyFilters);

        const menuSection = document.getElementById('menu-section');
        menuSection.addEventListener('click', () => {
            window.location.href = menuSection.dataset.url;
        });
    });
</script>
