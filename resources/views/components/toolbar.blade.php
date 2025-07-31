<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        #toolbar{
            display: flex;
            flex-direction: row;
            height: 6%;
            width: 100%;
            background-color: #ffffff  ;
            padding:.5%;
            box-shadow: 0 0rem 2rem rgba(0, 0, 0, 0.2);
            align-items: center;
            justify-content: space-evenly;
            position: relative;
        }
        
        #logo-container{
            display:flex;
            flex-direction: row;
            height:100%;
            color:black;
            gap:1rem;
            align-content: center;
            justify-content:center; 
            margin-right:1rem;
        }
        #title-container{
            display:flex;
            height:100%;
            align-items: center;
            font-size:70%;
        }
        #notification-container{
            margin-left:auto;
            color:black;
            height:100%;
            align-content:center;
            margin-right:1rem;
            border-right: 1px black solid;
            padding-right:1rem;
            cursor:pointer;
            font-size:70%;
        }
        #profile-container{
            display: flex;
            flex-direction: row;
            gap:1rem;
            height:100%;
            color:black;
            align-content:center;
            align-items:center;
            cursor:pointer;
            position:relative;
        }
        #profile-container img{
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .drop-down {
            display: none;
            flex-direction: column;
            position: absolute;
            top:8%;
            right: 2%;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 15rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            align-items:center;
            justify-content: center;
            text-align:center;
            padding:1rem;
            gap:1rem;
        }
        .drop-down div{
            background: #ccc;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="toolbar">
        <div id="sidebar-space">
        </div>
        <div id="logo-container">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <div id="title-container">
            <h1>Lantaw-Marbel Resort</h1>
        </div>
        <div id="notification-container">
             <i class="fas fa-bell fa-2x"></i>
        </div>
        <div id="profile-container">
            <img src="{{ asset( 'images/profile.jpg') }}"></img>
            <h3>Username</h3>
            <i class="fas fa-chevron-down fa-2x"></i>
        </div>
         <div class="drop-down">
                <div data-url="view_profile">
                    <h2>View Profile</h2>
                </div>
                <div data-url="settings">
                    <h2>Settings</h2>
                </div>
                <div data-url="darkmode">
                    <h2>Darken</h2>
                </div>
                <div data-url="log_out">
                    <h2>Log Out</h2>
                </div>
            </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profile = document.getElementById('profile-container');
        const dropdown = document.querySelector('.drop-down');

        // Toggle dropdown visibility when profile is clicked
        profile.addEventListener('click', function () {
            if (dropdown.style.display === 'block') {
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
    });
</script>

</html>