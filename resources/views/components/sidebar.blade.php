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
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f5f9;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* --- Modern Sidebar --- */
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
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.25);
            transition: width 0.3s ease;
            border-top-right-radius: 1rem;
            z-index:9999;
            overflow: hidden;
        }

        #sidebar:hover {
            width: 260px;
        }

        .logo-container {
            margin-top:1rem;
            text-align: center;
            height:3rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo-container img {
            height:100%;
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
            cursor:pointer;
            transition: all 0.2s ease-in-out;
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

        /* --- Profile Section --- */
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

        #profile-container:hover {
            background: rgba(255,255,255,0.1);
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

        .more-action {
            position: absolute;
            bottom: 80px;
            left: 20px;
            background: white;
            color: black;
            border-radius: 0.5rem;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            width: 200px;
            overflow: hidden;
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

        #logout-side {
            width: 100%;
        }

        main {
            flex: 1;
            margin-left: 12rem;
            padding: 2rem;
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
                <div id="dashboard" class="sidebar-item" data-url="{{ route('manager.dashboard') }}">
                    <i class="fas fa-house"></i> <span>Dashboard</span>
                </div>
                <div id="discount" class="sidebar-item" data-url="{{ url('manager/discount') }}">
                    <i class="fa-solid fa-tag"></i> <span>Discount</span>
                </div>
                <div id="inquiry" class="sidebar-item" data-url="{{ url('manager/chat') }}">
                    <i class="fa-regular fa-message"></i> <span>Inquiry</span>
                </div>
                <div id="user" class="sidebar-item" data-url="{{ url('manager/manage_user') }}">
                    <i class="fas fa-users"></i> <span>Users</span>
                </div>
                <div id="guest" class="sidebar-item" data-url="{{ url('manager/guest_list') }}">
                    <i class="fas fa-person-walking-luggage"></i> <span>Guests</span>
                </div>
                <div id="session" class="sidebar-item" data-url="{{ url('manager/session_logs') }}">
                    <i class="fas fa-chart-line"></i> <span>Session Logs</span>
                </div>
                <div id="rooms" class="sidebar-item" data-url="{{ url('manager/room_list') }}">
                    <i class="fas fa-bed"></i> <span>Rooms</span>
                </div>
                <div id="amenities" class="sidebar-item" data-url="{{ url('manager/amenity_list') }}">
                    <i class="fas fa-person-swimming"></i> <span>Amenities</span>
                </div>
                <div id="cottages" class="sidebar-item" data-url="{{ url('manager/cottage_list') }}">
                    <i class="fas fa-campground"></i> <span>Cottages</span>
                </div>
                <div id="menu" class="sidebar-item" data-url="{{ url('manager/menu_list') }}">
                    <i class="fas fa-utensils"></i> <span>Menu</span>
                </div>
                <div id="service" class="sidebar-item" data-url="{{ url('manager/services_list') }}">
                    <i class="fas fa-bell-concierge"></i> <span>Service</span>
                </div>
                <div id="report" class="sidebar-item" data-url="{{ url('manager/report') }}">
                    <i class="fas fa-chart-simple"></i> <span>Report</span>
                </div>
                <div id="feedback" class="sidebar-item" data-url="{{ url('manager/feedback') }}">
                    <i class="fas fa-star"></i> <span>Feedback</span>
                </div>
            </div>
        </div>

        <div id="profile-container">
            <img src="{{ session('avatar') }}" alt="Avatar" />
            <h2>{{ session('username') }}</h2>
            <i class="fas fa-chevron-down fa-lg"></i>
        </div>

        <div class="more-action">
            <div class="sidebar-select" data-url="{{ url('manager/view_profile/' . session()->get('user_id')) }}">
                Profile
            </div>
            <div class="sidebar-select" data-url="{{ url('manager/notifications') }}">
                Notifications
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button id="logout-side" type="submit">Log Out</button>
            </form>
        </div>
    </aside>

    <main>
        <!-- Your main page content -->
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profile = document.getElementById('profile-container');
            const dropdown = document.querySelector('.more-action');
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            const extraItems = document.querySelectorAll('.sidebar-select');

            profile.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
            });

            document.addEventListener('click', (e) => {
                if (!profile.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            extraItems.forEach(item => {
                item.addEventListener('click', () => {
                    const url = item.dataset.url;
                    if (url) window.location.href = url;
                });
            });

            sidebarItems.forEach(item => {
                item.addEventListener('click', () => {
                    const url = item.dataset.url;
                    if (url) window.location.href = url;
                });
            });
        });
    </script>
</body>
</html>
