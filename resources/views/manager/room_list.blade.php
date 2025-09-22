<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            @php
                $count = count($rooms)
            @endphp
            <div id="layout-header">
                <h1 id="h2">Room List | Total Room : {{ $count }}</h1>

                <div class="right-actions">
                    <div id="add-container" data-url="{{ url('manager/add_room') }}">
                        <h2 id="add-text">Add a Room</h2>
                        <i id="add-user" class="fas fa-plus-circle fa-3x" style="cursor:pointer;"></i>
                    </div>

                    <div class="search-container">
                        <form action="{{ route('manager.search_room') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_room') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>


            <div class="room-container">
                @foreach($rooms as $room)
                @php
                    $roomCardClass = '';
                    if($room->status == 'Unavailable' || $room->status == 'Maintenance'){
                        $roomCardClass = 'room-card deactivated';
                    }
                    elseif($room->status == 'Booked'){
                        $roomCardClass = 'room-card booked';
                    }
                    else {
                        $roomCardClass = 'room-card';
                    }
                @endphp
                    <div class="{{ $roomCardClass }}">
                        <div id="room-image">
                          <img src="{{ route('room.image', ['filename' => basename($room->image)]) }}" alt={{ $room->roomnum }}>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">

                            <div id="room-details">
                                <h4>Room {{ $room->roomnum }}</h4>
                                <h4 id="roomdetails"> Room Details</h4>
                                <p id="roomdetails">{{ $room->description }}</p>
                                <h4 id="roomdetails">Pricing</h4>
                                <h4 id="roomdetails">₱ {{ number_format($room->price, 2) }}</h4>

                            </div>
                            <div class="manage-dropdown-wrapper">
                                @if ($room->status == 'Maintenance')
                                    <h4>The room is currently in {{ $room->status }}</h4>
                                @else
                                    <h4>The room is currently {{ $room->status }}</h4>
                                @endif
                                <div class="manageBtn">
                                    <p>
                                        Manage
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </p>
                                </div>
                                <div class="dropdown-content">
                                    <div data-url="{{ url('manager/edit_room/' . $room->roomID) }}">
                                        <h4 >Update</h4>
                                            <i class="fas fa-pen fa-lg"></i>
                                    </div>
                                    @if ($room->status == 'Available')
                                        <div data-url="{{ url('manager/deactivate_room/' . $room->roomID) }}">
                                            <h4 >Deactivate</h4>
                                            <i class="fas fa-ban fa-lg" style="color:#d9534f;"></i>
                                        </div>
                                    @elseif ($room->status == 'Unavailable' || $room->status == 'Maintenance')
                                         <div data-url="{{ url('manager/activate_room/' . $room->roomID) }}">
                                            <h4 >Activate</h4>
                                            <i class="fas fa-check-circle fa-lg" style="color:#5cb85c;"></i>
                                        </div>
                                    @elseif ($room->status == 'Booked')
                                        <div style="background:#dddddd; color:#949494;">
                                            <h4>Booked</h4>
                                            <i class="fas fa-calendar-check fa-lg" style="color:#949494;"></i>
                                        </div>
                                    @endif

                                    @if($room->status == 'Maintenance')
                                    <div data-url="{{ url('manager/maintenance_room/' . $room->roomID) }}" style="display:none;">
                                        <h4 >Maintenance Room</h4>
                                        <i class="fas fa-wrench fa-lg" style="color:#9d9d9d;"></i>
                                    </div>
                                    @else
                                    <div data-url="{{ url('manager/maintenance_room/' . $room->roomID) }}">
                                        <h4 >Maintenance Room</h4>
                                        <i class="fas fa-wrench fa-lg" style="color:#9d9d9d;"></i>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
        </div>

    </div>
    @if(session('success'))
        <div class="alert-message">
            <h2>{{ session('success') }}</h2>
        </div>
    @endif
</body>
<style>
    #layout {
        height:100%;
        display: flex;
        flex-direction: row;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    #main-layout {
        width: 100%;
        padding: 1rem;
        margin-left: 12rem;
    }

    #layout-header {
        display: flex;
        align-items: center;
        font-size:.7rem;
        justify-content: space-between;
        width: 100%;
        height: 8%;
        padding: 1rem 2rem;
        background: white;
        border-radius: .7rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    .right-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
    }
    #add-container:hover {
        color: #F78A21;
    }

    .search-container .reset-btn {
        padding: 10px 15px;
        background-color: #e53935;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        margin-left: 10px;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    .search-container .reset-btn:hover {
        background-color: #b71c1c;
    }

    .button-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
    }
    #add-container:hover {
        color: #F78A21;
    }
    #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
        margin-left: 0.5rem;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-content: center;
        margin: 15px 0;
    }

    .search-container form {
        display: flex;
        align-items: center;
    }

    .search-container input[type="text"] {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 25px 0 0 25px;
        outline: none;
        width: 250px;
        font-size: 14px;
    }

    .search-container button {
        padding: 10px 15px;
        border-left: none;
        background-color: #000000;
        color: white;
        border-radius: 0 25px 25px 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-container button:hover {
        background-color: #F78A21;
        border: 1px solid #F78A21;
    }
    .room-container {
        display: flex;
        flex-direction: column;
        margin-top: 1rem;
        gap: 0.7rem;
        max-height: 89vh;
        overflow-y: auto;
    }

    /* Room Cards */
    .room-card {
        height:15rem;
        display: flex;
        flex-direction: row;
        padding: 1rem;
        border: 1px solid #ccc;
        border-radius: 0.7rem;
        background-color: #f9f9f9;
        box-shadow: 0.1rem 0.2rem rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .room-card.deactivated {
        background: #dfdfdf;
        color: #b7b7b7;
    }

    .room-card.booked {
        background: linear-gradient(to top, #ffcfad, #ffffff 80%);
    }

    #room-image {
        background: rgb(100, 100, 100);
        height: 100%;
        width: 30%;
        margin-right: 16px;
        border-radius: .7rem;
    }

    #room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #room-details {
        display: flex;
        flex-direction: column;
        width: 100%;
        font-size: .7rem;
    }
     #room-details #roomdetails {
        margin-top: -.5rem;
    }
    .manageBtn {
        display: flex;
        justify-content: center;
        height:2.5rem;
        width: 7rem;
        align-items: center;
        padding: 0.5rem;
        background:black;
        color:white;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .manageBtn:hover {
        background:orange;
        color:black;
    }
    .manage-dropdown-wrapper {
        display:flex;
        flex-direction:row;
        justify-content: space-between;
        position: relative;
        font-size:.7rem;
        margin-top:-.5rem;
    }

    .dropdown-content {
        display: none;
        flex-direction:column;
        position: absolute;
        width:10rem;
        top: 100%;
        right: 0;
        margin-left:auto;
        background-color: #8d8d8d;
        padding: 0.5rem;
        border-radius: 0.5rem;
        gap:.7rem;
    }
    .dropdown-content div{
        display:flex;
        flex-direction: row;
        width:100%;
        padding-left:.6rem;
        padding-right:.6rem;
        background:white;
        border-radius:.5rem;
        align-items: center;
        justify-content:space-between;
        cursor:pointer;
        transition:all .3s ease;
    }
    .dropdown-content div:hover{
        background:orange;
        color:black;
    }
    .dropdown-content.active {
        display: flex;
    }

    .alert-message {
        position: fixed;
        bottom: 1rem;
        right: 50%;
        transform: translate(50%, 0);
        background: white;
        padding: 1rem;
        box-shadow: 0 0 1rem rgba(0, 0, 0, 0.5);
        border-radius: 1rem;
        z-index: 1000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const curTab = document.getElementById('rooms');
        const manageBtn = document.querySelectorAll('.manageBtn');
        const dropdowns = document.querySelectorAll('.dropdown-content');
        const message = document.querySelector('.alert-message');
        const addRoom = document.getElementById('add-container');

        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        if (curTab) {
            curTab.style.color = "#F78A21";
        }

        addRoom.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        })

        manageBtn.forEach(function(btn) {
            btn.addEventListener('click', function(event) {
            // Close all dropdowns first
            dropdowns.forEach(function(drop) {
                drop.classList.remove('active');
            });
            // Toggle the dropdown for this button
            const dropdown = btn.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown-content')) {
                dropdown.classList.toggle('active');
            }
            event.stopPropagation();
            });
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-content').forEach(function(drop) {
            drop.classList.remove('active');
            });
        });

        dropdowns.forEach(function(dropdown) {
            dropdown.querySelectorAll('div[data-url]').forEach(function(item) {
                item.addEventListener('click', function(event) {
                    window.location.href = item.getAttribute('data-url');
                    event.stopPropagation(); // Prevent the click from propagating to the document
                });
            });
        });
    });


</script>
