<html lang="en">
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
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        #layout {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        #main-layout {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            margin-left: 12rem;
            width: calc(100% - 12rem);
            overflow: hidden;
        }
        #layout-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: .5;
            padding-left:1rem;
            padding-right:1rem;
            background: white;
            border-radius: .7rem;
            border: 1px solid black;
            box-shadow: .1rem .1rem 0 black;
            font-size: .8rem;
            flex-shrink: 0;
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
            margin-left: 0.5rem;
        }
        .search-container {
            display: flex;
            align-items: center;
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
            background-color: #000;
            color: white;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-container button:hover {
            background-color: #F78A21;
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
        .navbar {
            display: flex;
            flex-direction: row;
            align-items: center;
            height: 4rem;
            gap: 1rem;
            padding: 1rem;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            width: 100%;
            justify-content: flex-start;
            box-sizing: border-box;
            flex-shrink: 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(0,0,0,0.3) transparent;
        }
        .navbar::-webkit-scrollbar {
            height: 6px;
        }
        .navbar::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.3);
            border-radius: 3px;
        }
        .navbar-item {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            height: 3rem;
            width: 7rem;
            background: #ffffff;
            border-radius: .5rem;
            font-size: .7rem;
            box-shadow: .1rem .2rem 0 rgba(0,0,0,0.2);
            transition: all .3s ease;
        }
        .navbar-item:hover {
            background: rgb(53, 53, 53);
            color: white;
            cursor: pointer;
        }
        .navbar-item.active {
            background-color: rgb(150, 55, 0);
            color: white;
        }
        .menu-contianer {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1rem;
            width: 100%;
            overflow-y: auto;
            justify-content: center;
            box-sizing: border-box;
        }
        .menu-card {
            position: relative;
            width: 15rem;
            min-height:auto;
            max-height: 23rem;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: .5rem;
            padding: .5rem;
            font-size: .7rem;
            box-shadow: .1rem .3rem 0 rgba(0,0,0,0.2);
        }
        .menu-card img {
            height: 10rem;
            width: 100%;
            border-top-right-radius: 1rem;
            border-top-left-radius: 1rem;
            object-fit: cover;
        }
        .menu-card.unavailable {
            opacity: 0.5;
        }
        .unavailable-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10rem;
            background: rgba(72, 72, 72, 0.6);
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            text-transform: uppercase;
            pointer-events: none; 
        }
        #manage-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 3rem;
            justify-content: space-evenly;
            margin-top: auto;
        }
        #manage-button {
            border: none;
            display:flex;
            justify-content: space-evenly;
            align-items: center;
            border-radius: .5rem;
            background: black;
            color: white;
            padding: 0.5rem 1rem;
            transition: all .2s ease;
        }
        #manage-button:hover {
            background: orange;
            color: black;
            cursor: pointer;
        }
        .drop-down {
            display: none;
            flex-direction: column;
            width: 10rem;
            position: absolute;
            background: rgb(182, 182, 182);
            padding: .5rem;
            z-index: 1;
            gap: .5rem;
            border-radius: .5rem;
            align-items: center;
            justify-content: center;
            margin-left: 4rem;
        }
        .drop-down div {
            display: flex;
            flex-direction: row;
            width: 100%;
            align-items: center;
            justify-content: space-evenly;
            background: white;
            border-radius: .5rem;
            cursor: pointer;
            transition: all .3s ease;
        }
        .drop-down div:hover {
            background: grey;
            color: white;
        }
        .alert-message {
            display: flex;
            justify-content: center;
            text-align: center;
            position: fixed;
            right: 50%;
            transform: translate(50%, 0);
            bottom: 1rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0 1rem rgba(0,0,0,0.5);
            padding: 1rem 2rem;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Menu Items</h1>
                <div class="button-group">
                    <div id="add-container" data-url="{{ url('manager/add_menu') }}">
                        <h2 id="add-text">Add Menu</h2>
                        <i class="fas fa-plus-circle fa-3x"></i>
                    </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_menu') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_menu') }}" class="reset-btn">Clear</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="navbar">
                <div class="navbar-item" data-filter="All"><h3>All</h3></div>
                @foreach($uniqueMenuTypes as $menutypes)
                    <div class="navbar-item" data-filter="{{ $menutypes }}">
                        <h3>{{ $menutypes }}</h3>
                    </div>
                @endforeach
            </div>

            <div class="menu-contianer">
                @foreach($menu as $menuitem)
                    <div class="menu-card {{ $menuitem->status !== 'Available' ? 'unavailable' : '' }}" 
                        data-type="{{ $menuitem->itemtype }}">
                        <div id="img-container">
                            <img src="{{ route('menu.image', ['filename' => basename($menuitem->image)]) }}" 
                                alt="{{ $menuitem->menuname }}">
                            @if($menuitem->status !== 'Available')
                                <div class="unavailable-overlay">Unavailable</div>
                            @endif
                        </div>
                        <div id="menu-details">
                            <h2>Name: {{$menuitem->menuname}}</h2>
                            <h2>Type: {{$menuitem->itemtype}}</h2>
                            <h2>Price: {{$menuitem->price}}</h2>
                            <hr/>
                            <div id="manage-container">
                                <h2>Status: {{$menuitem->status}}</h2>
                                <button id="manage-button">Manage <i class="fas fa-chevron-down fa-lg"></i></button>
                            </div>
                            <div class="drop-down">
                                <div data-url="{{url('manager/edit_menu/' . $menuitem->menuID)}}">
                                    <h2>Update</h2>
                                    <i class="fa-solid fa-pencil fa-lg"></i>
                                </div>
                                @if($menuitem->status == 'Available')
                                    <div data-url="{{url('manager/deactivate_menu/' . $menuitem->menuID)}}">
                                        <h2>Deactivate</h2>
                                        <i class="fa-solid fa-times-circle fa-lg" style="color:red;"></i>
                                    </div>
                                @else 
                                    <div data-url="{{url('manager/activate_menu/' . $menuitem->menuID)}}">
                                        <h2>Activate</h2>
                                        <i class="fas fa-circle fa-lg" style="color:green;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(session('success'))
                <div class="alert-message"><h2>{{ session('success') }}</h2></div>
            @endif
            @if(session('error'))
                <div class="alert-message"><h2>{{ session('error') }}</h2></div>
            @endif
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.navbar-item');
    const menuCards = document.querySelectorAll('.menu-card');
    const message = document.querySelector('.alert-message');
    const addMenu = document.getElementById('add-container');

    if (message) setTimeout(() => message.remove(), 2500);

    if (addMenu) {
        addMenu.addEventListener('click', () => {
            const url = addMenu.dataset.url;
            if (url) window.location.href = url;
        });
    }

    function normalize(s) {
        return (s || '').toString().trim().toLowerCase();
    }

    function applyFilter(filter) {
        const f = normalize(filter);
        menuCards.forEach(card => {
            const type = normalize(card.dataset.type);
            card.style.display = (f === 'all' || f === type) ? '' : 'none';
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            button.classList.add('active');
            applyFilter(button.dataset.filter || 'All');
        });
    });

    const allButton = document.querySelector('.navbar-item[data-filter="All"]');
    if (allButton) allButton.click();

    document.querySelectorAll('#manage-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            const dropdown = this.closest('.menu-card').querySelector('.drop-down');
            document.querySelectorAll('.drop-down').forEach(d => d.style.display = 'none');
            dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.drop-down').forEach(d => d.style.display = 'none');
    });
});
</script>
</body>
</html>
