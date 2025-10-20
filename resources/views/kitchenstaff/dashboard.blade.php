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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { width: 100%; height: 100%; font-family: Arial, sans-serif; background: #f8f2e6; }
    
    #layout { display: flex; flex-direction: column; height: 100vh; width: 100%; }
    
    /* Header */
    #layout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        background: #F78A21;
        color: white;
        font-size: 0.9rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    #layout-header h2 { font-size: 1.3rem; }
    .nav-links { display: flex; gap: 1rem; align-items: center; }
    .nav-links div { cursor: pointer; display: flex; flex-direction: column; align-items: center; transition: 0.2s; }
    .nav-links div:hover { transform: scale(1.1) rotate(-5deg); }

    /* Layout wrapper */
    #layout-wrapper { display: flex; flex: 1; padding: 1rem; gap: 1rem; overflow: hidden; }

    /* Main content */
    #main-content { flex: 1; height: 100%; overflow-y: auto; }

    /* Dashboard totals */
    .total-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem; }
    .total-card { background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2); text-align: center; }
    .total-card h3 { margin-bottom: 0.5rem; color: #F78A21; }
    .total-card p { font-size: 1.2rem; font-weight: bold; }

    /* Info card */
    .info-container { margin-bottom: 1rem; }
    .info-card { background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }

    /* Table */
    .table-container { background: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    th, td { padding: 0.75rem; text-align: center; border-bottom: 1px solid #ddd; }
    th { background: #F78A21; color: white; }
    tr:nth-child(even) { background: #f2f2f2; }

    /* Buttons */
    button { background: #F78A21; color: white; padding: 0.3rem 0.5rem; border: none; border-radius: 0.3rem; cursor: pointer; transition: 0.2s; }
    button:hover { background: #e06700; }

    /* Pagination */
    #page-container { display: flex; justify-content: center; margin-top: 1rem; }
    .pagination { display: flex; gap: 0.5rem; list-style: none; padding: 0; }
    .page-link { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border: 1.5px solid #F78A21; border-radius: 50%; color: #F78A21; text-decoration: none; transition: 0.2s; }
    .page-link:hover, .page-item.active .page-link { background: #F78A21; color: white; }
    .page-item.disabled { display: none; }

    /* Alerts */
    .alert-message {
        position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%);
        background: white; padding: 1rem 2rem; border-radius: 0.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.3); z-index: 1000;
        text-align: center;
    }
    /* Highlight orders that are close to order date */
    .order-soon {
        background-color: #ff4d4d !important; /* strong red */
        color: white;
        font-weight: bold;
        border-left: 5px solid #990000;
        transition: all 0.3s ease-in-out;
    }

    /* Optional: make it blink subtly */
    @keyframes blink {
        0%, 50%, 100% { opacity: 1; }
        25%, 75% { opacity: 0.6; }
    }
    .order-soon {
        animation: blink 1.5s infinite;
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
                        <button type="submit" style="all:unset; cursor:pointer; display:flex; align-items:center;">
                            <i class="fa-solid fa-power-off fa-2x"></i> Log Out
                        </button>
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
                                <p>{{ $serve->bookingTicket }}</p>
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
                                <tr class="order-row" data-orderdate="{{ $order->orderdate }}">
                                    <td>{{ $orders->firstItem() + $index }}</td>
                                    <td>{{ $order->bookingID }}</td>
                                    <td>{{ $order->bookingTicket }}</td>
                                    <td>{{ $order->menuname }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->orderdate }}</td>
                                    <td>₱{{ number_format($order->total, 2) }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>
                                        @if (strtolower($order->status) === 'pending')
                                            <form action="{{ route('orders.prepare', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit">Prepare</button>
                                            </form>
                                        @elseif (strtolower($order->status) === 'confirmed')
                                            <form action="{{ route('orders.serve', $order->id  ) }}" method="POST">
                                                @csrf
                                                <button type="submit">Serve</button>
                                            </form>
                                        @else
                                            <span>Served</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($orders->isEmpty())
                                <tr><td colspan="9">No Orders Currently.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (session('success'))
                    <div class="alert-message">{{ session('success') }}</div>
                @endif

                <div id="page-container">
                    <div class="pagination">{{ $orders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll(".order-row");

    rows.forEach(row => {
        const orderDateStr = row.dataset.orderdate;
        const orderDate = new Date(orderDateStr);
        const now = new Date();

        // Thresholds in minutes or hours
        const thresholdMinutes = 30; // highlight if within 30 minutes
        const diffMinutes = (orderDate - now) / (1000 * 60);

        if (diffMinutes <= thresholdMinutes && diffMinutes > 0) {
            // Add a "warning" class for CSS
            row.classList.add("order-soon");
        }
    });

    // Auto-hide success messages
    const message = document.querySelector('.alert-message');
    if (message) setTimeout(() => message.style.display = 'none', 3500);

    // Menu navigation
    document.getElementById('menu-section').addEventListener('click', () => {
        window.location.href = document.getElementById('menu-section').dataset.url;
    });

    // Auto-refresh every 5 seconds
    setInterval(() => {
        window.location.reload();
    }, 5000);
});
</script>

</html>
