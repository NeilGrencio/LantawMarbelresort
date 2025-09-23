@php
    if (!session()->get('logged_in')) {
        header('Location: ' . route('checkLogin'));
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
    *{box-sizing:border-box;}
    #layout {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width:100%;
        overflow:hidden;
        padding:.5rem;
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
        overflow-x: auto;
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
</style>
<body>
    <div id="layout">
        <div id="layout-header">
            <h2>Welcome Kitchen Staff</h2>
            <h3 id="out" data-url="{{route('logout')}}">Log Out</h3>
        </div>
        <div class="table-container">
            <table id="order-table">
                <thead>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Order Quantity</th>
                    <th>Item Name</th>
                    <th>Order Total</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>{{ $order->firstname }} {{ $order->lastname }}</td>
                            <td>{{ $order->menuname }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>
                                @if ($order->status === 'pending')
                                    <form action="{{ route('orders.prepare', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit">Mark as Preparing</button>
                                    </form>
                                @else
                                    <span>{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (session('success'))
                <div class="alert-message">
                    <h2>{{ session('success') }}</h2>
                </div>
            @endif
        </div>
        <div>
            <div class="pagination">
                {{ $orders->links() }}
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
</script>
