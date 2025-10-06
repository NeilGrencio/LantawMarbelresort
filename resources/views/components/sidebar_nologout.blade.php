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
            * {
            box-sizing: border-box;
        }
        body {
            font-family: Roboto, sans-serif;
            padding: 0;
            margin: 0;
            background-color: #E1E5EA;
            overflow: hidden;
        }
        #sidebar {
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 0.5rem;
            color: #ffffff;
            padding: 0.3rem;
            cursor: pointer;
            transition: width 0.3s ease-in;
            width: 12rem;
            background: rgb(0, 13, 49);
            z-index: 1000;
            border-top-right-radius: 1rem;
            position: fixed;
        }
        #sidebar div {
            display: flex;
            height: 2rem;
            gap: 0.4rem;
            cursor: pointer;
            width: 100%;
            padding-left: 0.4rem;
            align-items: center;
        }
        .sidebar-item {
            transition: all 0.3s ease-in;
        }
        .sidebar-item:hover {
            background: rgb(255, 145, 0);
            color: black;
        }
        .icons {
            display: flex;
            gap: 0.4rem;
            width: 2.5rem;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
        }
        .label {
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        #sidebar :nth-child(5),
        #sidebar :nth-child(8),
        #sidebar :nth-child(11),
        #sidebar :nth-child(13) {
            border-top: solid 1px #919191;
        }
        #sidebar :nth-child(4),
        #sidebar :nth-child(7),
        #sidebar :nth-child(10),
        #sidebar :nth-child(12) {
            margin-bottom: 0.6rem;
            border-bottom: solid 1px #919191;
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

        .more-action {
            display: flex;
            position: absolute;
            bottom: 60px; 
            left: 2rem;    
            width: 10%; 
            height:3rem;
            background: white;
            color: black;
            border-radius: 0.7rem;
            padding: 0.5rem;
            flex-direction: column;
            align-items: flex-start;
            font-size: 11px;
            box-shadow: 0 0.3rem 0.5rem rgba(0,0,0,0.2);
            z-index: 2000;
        }

        .more-action div {
            width: 100%;
            height:2rem;
            list-style: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            padding:.5rem;
            display:flex;
            align-items: center;
            justify-content: start;
            border-radius: 0.5rem;
            gap:.5rem;
        }

        .more-action div:hover {
            background-color: rgb(255,145,0);
            color: black;
        }

        .logo-container img {
            object-fit: contain;
            height: 2.5rem;
            width: 100%;
        }
        </style>
    </head>
    <body>
        <div id="sidebar" style="cursor:pointer">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png')}}">
            </div>
            <div id="dashboard" class="sidebar-item" data-url="{{ route('manager.dashboard') }}" >
                <div class="icons">
                    <i class="fas fa-house fa-2x"></i>
                </div>
                <div class="label">
                    <span >Dashboard</span>
                </div>
            </div>
            <div id="discount" class="sidebar-item" data-url="{{ url('manager/discount') }}">
                <div class="icons">
                <i class="fa-solid fa-tag fa-2x"></i>
                </div>
                <div class="label">
                    <span class="label">Discount</span>
                </div>
            </div>
            <div id="inquiry" class="sidebar-item" data-url="{{ url('manager/chat') }}">
                <div class="icons">
                    <i class="fa-regular fa-message fa-2x"></i>
                </div>
                <div class="label">
                    <span>Inquiry</span>
                </div>
            </div>

            <div id="user" class="sidebar-item" data-url="{{ url('manager/manage_user') }}">
                <div class="icons">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div class="label">
                    <span >Users</span>
                </div>
            </div>
            <div id="guest" class="sidebar-item" data-url="{{ url('manager/guest_list') }}">
                <div class="icons">
                    <i class="fas fa-person-walking-luggage fa-2x"></i>
                </div>
                <div class="label">
                    <span >Guests</span>
                </div>
            </div>
            <div id="session" class="sidebar-item" data-url="{{ url('manager/session_logs') }}">
                <div class="icons">
                <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <div class="label">
                    <span >Session Logs</span>
                </div>
            </div>

            <div id="rooms" class="sidebar-item" data-url="{{ url('manager/room_list') }}">
                <div class="icons">
                    <i class="fas fa-bed fa-2x"></i>
                </div>
                <div class="label">
                    <span>Rooms</span>
                </div>
            </div>
            <div id="amenities" class="sidebar-item" data-url="{{ url('manager/amenity_list') }}">
                <div class="icons">
                    <i class="fas fa-person-swimming fa-2x"></i>
                </div>
                <div class="label">
                    <span>Amenities</span>
                </div>
            </div>
            <div id="cottages" class="sidebar-item" data-url="{{ url('manager/cottage_list') }}">
                <div class="icons">
                    <i class="fas fa-campground fa-2x"></i>
                </div>
                <div class="label">
                    <span >Cottages</span>
                </div>
            </div>
            <div id="menu" class="sidebar-item" data-url="{{ url('manager/menu_list') }}">
                <div class="icons">
                    <i class="fas fa-utensils fa-2x"></i>
                </div>
                <div class="label">
                    <span>Menu</span>
                </div>
            </div>
            <div id="service" class="sidebar-item" data-url="{{ url('manager/services_list') }}">
                <div class="icons">
                    <i class="fas fa-bell-concierge fa-2x"></i>
                </div>
                <div class="label">
                    <span>Service</span>
                </div>
            </div>
            <div id="report" class="sidebar-item" data-url="{{ url('manager/report') }}">
                <div class="icons">
                    <i class="fas fa-chart-simple fa-2x"></i>
                </div>
                <div class="label">
                    <span>Report</span>
                </div>
            </div>

            <div id="feedback" class="sidebar-item" data-url="{{ url('manager/feedback') }}">
                <div class="icons">
                    <i class="fas fa-star fa-2x"></i>
                </div>
                <div class="label">
                    <span>Feedback</span>
                </div>
            </div>

            <div id="profile-container">
                <img src="{{ asset('storage/' . session('avatar')) }}" alt="Avatar" />
                <h2>{{ session('username') }}</h2>
                <i class="fas fa-chevron-down fa-lg"></i>
            </div>
        </div>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('sidebar-container'); // now correctly points to the toggle
            const sidebar = document.getElementById('sidebar');
            const body = document.body; // fallback for layout expansion if no #main-layout

            const sidebarItems = sidebar.querySelectorAll('[data-url]');
            const labels = sidebar.querySelectorAll('.label');
            const profile = document.getElementById('profile-container');
            const dropdown = document.querySelector('.more-action');

            profile.addEventListener('click', function () {
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!profile.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Handle navigation clicks inside the dropdown
            document.querySelectorAll('.more-action div').forEach(item => {
                item.addEventListener('click', function () {
                    const url = this.dataset.url;
                    if (url) {
                        window.location.href = `/${url}`;
                    }
                });
            });

            sidebarItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation(); // prevent sidebar toggle from closing it
                    const targetURL = item.dataset.url;
                    if (targetURL) {
                        window.location.href = targetURL;
                    }
                });
            });
        });

    </script>
    </body>