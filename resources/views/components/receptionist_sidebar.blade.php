@php
    if (!session()->get('logged_in')) {
        header('Location: ' . route('login'));
        exit;
    }
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        *{ box-sizing:border-box;}
        body{
            font-family: Roboto, sans-serif;
            padding:0;
            margin:0;
            background-color:#E1E5EA;
            overflow:hidden;
        }

        #sidebar{
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 0.5rem;
            color: #ffffff;
            padding: 0.5rem;
            cursor: pointer;
            transition: width 0.3s ease-in;
            width: 12rem;
            background: rgb(0, 13, 49);
            z-index: 1000;
            border-top-right-radius: 1rem;
            position: fixed;
        }

        #dashboard, #booking, #inquiry, #billingside, #menu{
            border-top:1px solid white;
            margin-top:1rem;
        }

        #dashboard, #daytour, #guest, #billingside{
            border-bottom:1px solid white;
        }

        .sidebar-item{
            transition:all .3s ease-in; 
            gap:.5rem;
            position: relative;
            display: grid;
            grid-template-columns:1fr 1fr;
            height:2rem;
            gap:.5rem;
            cursor: pointer;
            width:auto;
            padding-left:.5rem;
        }

        .sidebar-item:hover{
            background:rgb(255, 145, 0);
            color:rgb(199, 134, 134);
        }

        .icons{
            display: flex;
            gap:.7rem;
            width:5rem;
            align-items: center;
            justify-content: flex-end;
            font-size:.6rem;
        }

        .label {
            font-size:10px;
            display: inline-flex;
            align-items: center;
        }

        .logo-container img {
            object-fit: contain;
            height: 2.5rem;
            width: 100%;
        }

        .sidebar-popUp {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
            background: rgb(0, 13, 49);
            border-radius: 0.5rem;
            flex-direction: column;
            align-items: flex-start;
            padding: 0.5rem;
            z-index: 2001;
            gap: 0.4rem;
            width: 11rem;
            height: auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            opacity: 0;
            pointer-events: none;
            transform: translateY(5px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .sidebar-item:hover .sidebar-popUp {
            display: flex;
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .sidebar-popUp-select {
            color: white;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.6rem;
            border-radius: 0.3rem;
            cursor: pointer;
            width: 100%;
            height:2rem;
            transition: background 0.2s ease;
            white-space: nowrap;
        }

        .sidebar-popUp-select:hover {
            background: rgb(255, 145, 0);
            color: black;
        }

        #profile-container {
            display: flex;
            flex-direction: row;
            gap: 0.5rem;
            align-items: center;
            color: white;
            text-align: center;
            cursor: pointer;
            margin-top: auto;
            font-size: 11px;
            padding: 0.4rem 0;
            margin-bottom:1rem;
        }

        #profile-container img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
        }

        .drop-down {
            display: none;
            position: absolute;
            bottom: 60px;
            left: 2rem;
            width:11rem;
            background: white;
            color: black;
            border-radius: 0.7rem;
            padding: 0.3rem;
            flex-direction: column;
            align-items: flex-start;
            font-size: 11px;
            box-shadow: 0 0.3rem 0.5rem rgba(0,0,0,0.2);
            z-index: 2000;
            gap:.5rem;
        }

        .sidebar-select{
            display:flex;
            height:2rem;
            font-size:.8rem;
            padding:.5rem;
            border-radius:0.3rem;
            width:100%;
            transition:all .2s ease;
            background:#ffffff;
            border:2px solid black;
        }
        .sidebar-select:hover {
            background-color: rgb(255,145,0);
            color:black;
        }

        #logout-form {
            width:100%;
            height:100%;
        }

        #logout-side {
            display:flex;
            padding:.5rem;
            border-radius:0.3rem;
            width:100%;
            transition:all .2s ease;
            cursor:pointer;
        }

        #logout-side:hover {
            background-color: rgb(255,145,0);
            color:black;
        }
    </style>
</head>
<body>
    @include('components.notification_receptionist')
    <div id="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png')}}">
        </div>

        <div id="dashboard" class="sidebar-item" data-url="{{ url('receptionist/dashboard') }}">
            <div class="icons"><i class="fas fa-house fa-2x"></i></div>
            <div class="label"><span>Dashboard</span></div>
        </div>

        <div id="booking" class="sidebar-item" data-url="{{ url('receptionist/booking') }}">
            <div class="icons"><i class="fa-regular fa-calendar-xmark fa-2x"></i></div>
            <div class="label"><span>Booking</span></div>
            <div class="sidebar-popUp">
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/booking_list') }}">
                    <i class="fa-regular fa-list-alt"></i> Booking List
                </div>
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/create_booking') }}">
                    <i class="fa-regular fa-calendar-check"></i> Normal Booking
                </div>
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/walk-booking') }}">
                    <i class="fa-solid fa-person-walking-luggage"></i> Walk-in Guest
                </div>
            </div>
        </div>

        <div id="check" class="sidebar-item" data-url="{{ url('receptionist/check-in-out') }}">
            <div class="icons"><i class="fas fa-door-open fa-2x"></i></div>
            <div class="label"><span>Check-in/out</span></div>
            <div class="sidebar-popUp">
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/checkin_list') }}">
                    <i class="fa-solid fa-door-open"></i> Check-In List
                </div>
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/checkout_list') }}">
                    <i class="fa-solid fa-door-closed"></i> Check-Out List
                </div>
            </div>
        </div>

        <div id="daytour" class="sidebar-item" data-url="{{ url('receptionist/daytourDashboard') }}">
            <div class="icons"><i class="fas fa-sun fa-2x"></i></div>
            <div class="label"><span>Day Tour</span></div>
            <div class="sidebar-popUp">
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/daytour') }}">
                    <i class="fa-regular fa-calendar-plus"></i> Create Day Tour
                </div>
            </div>
        </div>

        <div id="inquiry" class="sidebar-item" data-url="{{ url('receptionist/chat') }}">
            <div class="icons"><i class="fa-regular fa-message fa-2x"></i></div>
            <div class="label"><span>Inquiry</span></div>
        </div>

        <div id="guest" class="sidebar-item" data-url="{{ url('receptionist/guest_list') }}">
            <div class="icons"><i class="fas fa-person-walking-luggage fa-2x"></i></div>
            <div class="label"><span>Guests</span></div>
        </div>

        <div id="billingside" class="sidebar-item" data-url="{{ url('receptionist/billing') }}">
            <div class="icons"><i class="fa-regular fa-money-bill-1 fa-2x"></i></div>
            <div class="label"><span>Billing</span></div>
        </div>

        <div id="menu" class="sidebar-item" data-url="{{ url('receptionist/orderlist') }}">
            <div class="icons"><i class="fas fa-utensils fa-2x"></i></div>
            <div class="label"><span>Menu</span></div>
            <div class="sidebar-popUp">
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/order') }}">
                    <i class="fa-solid fa-burger"></i> Orders
                </div>
                <div class="sidebar-popUp-select" data-url="{{ url('receptionist/services') }}">
                    <i class="fas fa-bell-concierge"></i> Services
                </div>
            </div>
        </div>

        <div class="drop-down">
            <div class="sidebar-select" data-url="{{ url('receptionist/view_profile/' . session()->get('user_id')) }}">
                Profile
            </div>
            <div class="sidebar-select" data-url="{{ url('receptionist/notifications') }}">
                Notifications
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button id="logout-side" type="submit">Log Out</button>
            </form>
        </div>

        <div id="profile-container">
            <img src="{{ asset('storage/' . session('avatar')) }}" alt="Avatar" />
            <h2>{{ session('username') }}</h2>
            <i class="fas fa-chevron-down fa-lg"></i>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const profile = document.getElementById('profile-container');
    const dropdown = document.querySelector('.drop-down');

    // Toggle dropdown
    profile.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
    });
    document.addEventListener('click', (e) => {
        if (!profile.contains(e.target)) dropdown.style.display = 'none';
    });

    // Sidebar navigation (main items)
    sidebar.querySelectorAll('[data-url]').forEach(item => {
        item.addEventListener('click', (e) => {
            e.stopPropagation();
            const url = item.dataset.url;
            if (url) window.location.href = url;
        });
    });

    // Popup link navigation
    document.querySelectorAll('.sidebar-popUp-select').forEach(item => {
        item.addEventListener('click', (e) => {
            e.stopPropagation();
            const url = item.dataset.url;
            if (url) window.location.href = url;
        });
    });
});
</script>
</body>
