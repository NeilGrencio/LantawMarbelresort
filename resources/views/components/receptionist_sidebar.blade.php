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
            font-family: Robot, sans-serif;
            padding:0;
            margin:0;
            background-color:#E1E5EA ;
            overflow:hidden;
        }
        #sidebar{
            display: flex;
            flex-direction: column;
            height:100%;
            gap:.5rem;
            color:#ffffff;
            padding:.5rem;
            cursor:pointer;
            transition: width 0.3s ease-in;
            width: 12rem;
            background: rgb(0, 13, 49);
            z-index:1000;
            border-top-right-radius:1rem;
            position:fixed;
        }
        #sidebar div{
            display: flex;
            height:2rem;
            gap:.5rem;
            cursor: pointer;
            width:12rem;
            padding-left:.5rem;
        }
        #dashboard{
            margin-top:1rem;
        }
        .sidebar-item{transition:all .3s ease-in; gap:.5rem;}
        .sidebar-item:hover{
            background:rgb(255, 145, 0);
            color:black;
        }
        .icons{
            display: flex;
            gap:.7rem;
            width:3rem;
            align-items: center;
            text-align: center;
            justify-content: center;
            font-size:.6rem;
        }
        .label {
            display: inline;
            text-align: center;
            align-items: center;
            justify-content: start;
            font-size:10px;
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
            flex-direction: column;
            width: 100%;
            z-index: 1000;
            align-items: flex-start;
            padding: 0.3rem;
            font-size: 11px;
        }
        .drop-down ul {
            width: 100%;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .drop-down li {
            padding: 0.2rem 0;
        }
        .logo-container img{
            object-fit: contain;
            height:3rem;
            width:100%;
        }

        .booking-popUp{
            display: none; 
            position: absolute;
            left: 100%;
            top: 0;
            background: rgb(0, 13, 49);
            border-radius: 0.5rem;
            flex-direction: column;
            padding: 0.5rem;
            z-index: 2000;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 10rem;
            height:5rem;
            transition:all .2s ease;
        }
        #booking:hover .booking-popUp{
            display: flex;
        }
        #booking {
            position: relative;
        }
    </style>
</head>
<body>
    <div id="sidebar" style="cursor:pointer">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png')}}">
        </div>
        <div id="dashboard" class="sidebar-item" data-url="{{ url('receptionist/dashboard') }}" >
            <div class="icons">
                <i class="fas fa-house fa-2x"></i>
            </div>
            <div class="label">
                <span >Dashboard</span>
            </div>
        </div>
        <div id="booking" class="sidebar-item" data-url="{{ url('receptionist/booking') }}">
            <div class="icons">
               <i class="fa-regular fa-calendar-xmark fa-2x"></i>
            </div>
            <div class="label">
                <span class="label">Booking</span>
            </div>
            
        </div>
        <div id="check" class="sidebar-item" data-url="{{ url('receptionist/check-in-out') }}">
            <div class="icons">
               <i class="fas fa-door-open fa-2x"></i>
            </div>
            <div class="label">
                <span class="label">Check-in/out</span>
            </div>
        </div>

        <div id="daytour" class="sidebar-item" data-url="{{ url('receptionist/daytourDashboard') }}">
            <div class="icons">
                  <i class="fas fa-sun fa-2x"></i>
            </div>
            <div class="label">
                <span >Day Tour</span>
            </div>
        </div>

        <div id="inquiry" class="sidebar-item" data-url="{{ url('receptionist/chat') }}">
            <div class="icons">
                <i class="fa-regular fa-message fa-2x"></i>
            </div>
            <div class="label">
                <span>Inquiry</span>
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
        <div id="session" class="sidebar-item" data-url="{{ url('receptionist/billing') }}">
            <div class="icons">
            <i class="fa-regular fa-money-bill-1 fa-2x"></i>
            </div>
            <div class="label">
                <span>Billing</span>
            </div>
        </div>

        <div id="menu" class="sidebar-item" data-url="{{ url('receptionist/order') }}">
            <div class="icons">
                <i class="fas fa-utensils fa-2x"></i>
            </div>
            <div class="label">
                <span>Order</span>
            </div>
        </div>
        
        <div class="drop-down" style="display:none;">
            <ul>
                <li><p data-url="view_profile">View Profile</p></li>
                <li><p data-url="settings">Settings</p></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="all:unset; cursor:pointer; font-size:12px;">Log Out</button>
                </form>
            </ul>
        </div>

        <div id="profile-container">
            <img src="{{ asset('storage/' . session('avatar')) }}" alt="Avatar" />
            <h2>{{ session('username') }}</h2>
            <i class="fas fa-chevron-down fa-lg"></i>
        </div>
    </div>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('sidebar-container'); 
        const sidebar = document.getElementById('sidebar');
        const body = document.body;

        const sidebarItems = sidebar.querySelectorAll('[data-url]');
        const labels = sidebar.querySelectorAll('.label');
        const profile = document.getElementById('profile-container');
        const dropdown = document.querySelector('.drop-down');

        profile.addEventListener('click', function () {
            if (dropdown.style.display === 'flex') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'flex';
            }
        });

        document.addEventListener('click', function (event) {
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        document.querySelectorAll('.drop-down div').forEach(item => {
            item.addEventListener('click', function () {
                const url = this.dataset.url;
                if (url) {
                    window.location.href = `/${url}`;
                }
            });
        });

        sidebarItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation(); 
                const targetURL = item.dataset.url;
                if (targetURL) {
                    window.location.href = targetURL;
                }
            });
        });
        document.querySelectorAll('.booking-popUp-select').forEach(item => {
            item.addEventListener('click', () => {
                const url = item.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });

</script>
</body>