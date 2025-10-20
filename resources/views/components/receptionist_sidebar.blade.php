@php
    if (!session()->get('logged_in')) {
        header('Location: ' . route('login'));
        exit;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort</title>
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f5f9;
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 240px;
            background: linear-gradient(180deg, #001334 0%, #012A66 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 12px rgba(0,0,0,0.25);
            border-top-right-radius: 1rem;
            z-index: 9999;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        #sidebar:hover { width: 260px; }

        .logo-container {
            margin-top: 1rem;
            text-align: center;
            height: 3rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo-container img {
            height: 100%;
            width: 80%;
            max-width: 160px;
            object-fit: contain;
        }

        .sidebar-menu {
            flex: 1;
            padding-top: 1rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #d6e1f5;
            border-left: 4px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .sidebar-item i {
            font-size: 1.3rem;
            min-width: 25px;
            text-align: center;
        }

        .sidebar-item:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border-left: 4px solid #ff9100;
        }

        .sidebar-item.active {
            background: rgba(255,255,255,0.15);
            border-left: 4px solid #ff9100;
            color: white;
        }

        .sidebar-popUp {
            display: none;
            position: absolute;
            top: 0;
            left: 30%;
            background: #012A66;
            border-radius: 0.5rem;
            flex-direction: column;
            padding: 0.5rem;
            gap: 0.4rem;
            width: 200px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            opacity: 0;
            pointer-events: none;
            transform: translateY(5px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 2000;
        }

        .sidebar-item:hover .sidebar-popUp {
            display: flex;
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .sidebar-popUp-select {
            color: white;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.3rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .sidebar-popUp-select:hover {
            background: #ff9100;
            color: black;
        }

        #profile-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: rgba(255,255,255,0.05);
            cursor: pointer;
            border-top: 1px solid rgba(255,255,255,0.1);
            transition: background 0.2s ease;
        }

        #profile-container img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff9100;
        }

        #profile-container h2 {
            font-size: 0.9rem;
            font-weight: 500;
            color: #fff;
        }

        .drop-down {
            display: none;
            position: absolute;
            bottom: 80px;
            left: 20px;
            background: white;
            color: black;
            border-radius: 0.5rem;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            flex-direction: column;
            width: 200px;
            overflow: hidden;
            z-index: 2000;
        }

        .sidebar-select, #logout-side {
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            border: none;
            background: white;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-select:hover, #logout-side:hover {
            background-color: #ff9100;
            color: white;
        }

        main {
            flex: 1;
            width:100%;
            margin-left: 15rem;
            transition: margin-left 0.3s ease;
        }
    </style>
</head>
<body>
    <aside id="sidebar">
        <div>
            <div class="logo-container">
                <img src="{{ asset('images/logo.png')}}" alt="Lantaw-Marbel Resort">
            </div>

            <div class="sidebar-menu">
                <div id="dashboard" class="sidebar-item" data-url="{{ url('receptionist/dashboard') }}">
                    <i class="fas fa-house"></i> <span>Dashboard</span>
                </div>

                <div id="booking" class="sidebar-item" data-url="{{ url('receptionist/booking') }}">
                    <i class="fa-regular fa-calendar-xmark"></i> <span>Booking</span>
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
                    <i class="fas fa-door-open"></i> <span>Check-in/out</span>
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
                    <i class="fas fa-sun"></i> <span>Day Tour</span>
                    <div class="sidebar-popUp">
                        <div class="sidebar-popUp-select" data-url="{{ url('receptionist/daytour') }}">
                            <i class="fa-regular fa-calendar-plus"></i> Create Day Tour
                        </div>
                    </div>
                </div>

                <div id="inquiry" class="sidebar-item" data-url="{{ url('receptionist/chat') }}">
                    <i class="fa-regular fa-message"></i> <span>Inquiry</span>
                </div>

                <div id="guest" class="sidebar-item" data-url="{{ url('receptionist/guest_list') }}">
                    <i class="fas fa-person-walking-luggage"></i> <span>Guests</span>
                </div>

                <div id="billingside" class="sidebar-item" data-url="{{ url('receptionist/billing') }}">
                    <i class="fa-regular fa-money-bill-1"></i> <span>Billing</span>
                </div>

                <div id="menu" class="sidebar-item" data-url="{{ url('receptionist/orderlist') }}">
                    <i class="fas fa-utensils"></i> <span>Menu</span>
                    <div class="sidebar-popUp">
                        <div class="sidebar-popUp-select" data-url="{{ url('receptionist/order') }}">
                            <i class="fa-solid fa-burger"></i> Orders
                        </div>
                        <div class="sidebar-popUp-select" data-url="{{ url('receptionist/services') }}">
                            <i class="fas fa-bell-concierge"></i> Services
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="profile-container">
            <img src="{{ asset('storage/' . session('avatar')) }}" alt="Avatar" />
            <h2>{{ session('username') }}</h2>
            <i class="fas fa-chevron-down fa-lg"></i>
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
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profile = document.getElementById('profile-container');
            const dropdown = document.querySelector('.drop-down');

            profile.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
            });

            document.addEventListener('click', (e) => {
                if (!profile.contains(e.target)) dropdown.style.display = 'none';
            });

            document.querySelectorAll('.sidebar-item[data-url]').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const url = item.dataset.url;
                    if (url) window.location.href = url;
                });
            });

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
</html>
