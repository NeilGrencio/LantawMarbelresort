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
            gap:1rem;
            color:#ffffff;
            padding:.5rem;
            cursor:pointer;
            transition: width 0.3s ease-in;
            width: 14.5rem;
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
            width:14rem;
            padding-left:.5rem;
        }
        .sidebar-item{transition:all .3s ease-in; gap:.5rem;}
        .sidebar-item:hover{
            background:rgb(255, 145, 0);
            color:black;
        }
        .icons{
            display: flex;
            gap:.7rem;
            width:4rem;
            align-items: center;
            text-align: center;
            justify-content: center;
            font-size:.7rem;
        }
        #sidebar :nth-child(5), #sidebar :nth-child(8) {
            border-top:solid 1px #919191;
        }
        #sidebar :nth-child(4), #sidebar :nth-child(7), #sidebar :nth-child(11) {
            margin-bottom:1rem;
            border-bottom:solid 1px #919191;
        }
        .label {
            display: inline;
            text-align: center;
            align-items: center;
            justify-content: start;
            font-size:13px;
        }
        #profile-container{
            display: flex;
            flex-direction: row;
            gap:1rem;
            height:100%;
            color:white;
            align-content:center;
            align-items:center;
            text-align: center;
            cursor:pointer;
            position:relative;
            bottom:1rem;
            margin-top:auto;
            font-size:12px;
        }
        #profile-container img{
            height: 100%;
            width:3rem;
            border-radius: 50%;
            object-fit: cover;
        }

        .drop-down {
            display: none;
            flex-direction: column;
            width: 100%;
            z-index: 1000;
            align-items:center;
            justify-content: center;
            text-align:start;
            padding:.2rem;
            gap:.5rem;
        }
        .drop-down ul   {
            width: 100%;
            font-size:12px;
        }
        .logo-container img{
            object-fit: contain;
            height:3rem;
            width:100%;
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
        <div id="report" class="sidebar-item" data-url="{{ url('manager/report') }}">
            <div class="icons">
                <i class="fas fa-chart-simple fa-2x"></i>
            </div>
            <div class="label">
                <span>Report</span>
            </div>
        </div>
        
        <div class="drop-down" style="display:none;">
            <ul>
                <li><p data-url="view_profile">View Profile</p></li>
                <li><p data-url="settings">Settings</p></li>
                <li><p data-url="darkmode">Darken</p></li>
                <li><p data-url="log_out">Log Out</p></li>
            </ul>
        </div>

        <div id="profile-container">
            <img src="{{ asset("storage/" . session('avatar') ?? 'images/profile-trial.jpg') }}" alt="Profile Avatar">
            <h2>{{session('username')}}</h2>
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
        const dropdown = document.querySelector('.drop-down');

        // Toggle dropdown visibility when profile is clicked
        profile.addEventListener('click', function () {
            if (dropdown.style.display === 'flex') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'flex';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Handle navigation clicks inside the dropdown
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