<<<<<<< HEAD
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort Service List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1 id="h2">Service Items</h1>
                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_service') }}">
                            <h2 id="add-text">Add Service</h2>
                            <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                        </div>
                    {{--<div class="search-container">
                        <form action="{{ route('manager.search_service') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_menu') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>--}}
                    
                </div>
            </div>
            <div class='menu-contianer'>
                @foreach($service as $menuitem)
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
                                <button id="manage-button">Manage&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-chevron-down fa-lg"></i></button>
                            </div>
                            <div class="drop-down">
                                <div data-url="{{url('manager/edit_service/' . $menuitem->menuID)}}">
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
            <div class="alert-message">
                <h2>{{ session('success') }}</h2>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-message">
                <h2>{{ session('error') }}</h2>
           </div>
        @endif
        </div>
    </div>
</body>
<style>
    #service{color:#F78A21;}   
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
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
    .navbar{
        display:flex;
        flex-direction: row;
        width:100%;
        height: 4rem;
        gap:1rem;
        padding:1rem;
        justify-content:center;
        align-items:center;
        -webkit-overflow-scrolling: touch;
    }

    .navbar-item{
        display: flex;
        height:3rem;    
        width:7rem;
        background:#ffffff;
        border-radius:.5rem;
        font-size:.7rem;
        align-items:center;
        justify-content:center;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,0.2);
        transition:all .3s ease;
    }
    .navbar-item:hover{
        background:rgb(53, 53, 53);
        color:white;
        cursor:pointer;
    }
    .menu-contianer{
        display:flex;
        flex-direction:row;
        flex-wrap: wrap;
        gap:1rem;
        padding:1rem;
        width:100%;
        height: 100%;
        overflow-y: auto;
        justify-content:center;
    }
    #manage-container{
        display:flex;
        flex-direction:row;
        width:100%;
        height:3rem;
        justify-content: space-evenly;
        margin-top:auto;
        bottom:1;
    }
    #manage-button{
        height:100%;
        margin-left:auto;
        right:1;
        border-radius:.5rem;
        background:black;
        color:white;
        align-items:center;
        justify-content: space-evenly;
        transition:all .2s ease;
        border:none;
        box-shadow: .2rem .2rem 0 rgba(0,0,0,0.2);
        position: relative;
    } 
    #manage-button:hover{
        background:orange;
        color:black;
        cursor:pointer;
    }
    .menu-card{
        height:22rem;
        width:15rem;
        display:flex;
        flex-direction:column;
        background:white;
        border-radius:.5rem;
        padding: .5rem;
        font-size:.7rem;
        border:1px solid black;
        box-shadow: .1rem .1rem 0 rgba(0,0,0);
    }
    .menu-card img{
        height:10rem;
        width:100%;
        border-top-right-radius:1rem;
        border-top-left-radius:1rem;
        object-fit:cover;
    }
    .menu-card.unavailable {
        opacity: 0.5;
        position: relative;
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
    .drop-down{
        display:none;
        flex-direction:column;
        width:10rem;
        position: absolute;
        background:rgb(182, 182, 182);
        padding:.5rem;
        z-index: 1;
        gap:.5rem;
        border-radius:.5rem;
        align-items: center;
        justify-content: center;
        margin-left:4rem;
    }
    .drop-down div{
        display: flex;
        flex-direction: row;
        width:100%;
        align-items: center;
        justify-content: space-evenly;
        background: white;
        border-radius:.5rem;
        cursor:pointer;
        transition:all .3s ease;
    }
    .drop-down div:hover{
        background:grey;
        color:white;
    }
    .navbar-item.active {
    background-color: rgb(150, 55, 0); 
    color: white;
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = Array.from(document.querySelectorAll('.navbar-item'));
    const menuCards = Array.from(document.querySelectorAll('.menu-card'));
    const addMenu = document.getElementById("add-container");
    const message = document.querySelector('.alert-message');

    if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

    if (addMenu) {
        addMenu.addEventListener("click", function () {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            } else {
                console.error("No data-url found on #add-container");
            }
        });
    }

    function normalize(s) {
        return (s || '').toString().trim().toLowerCase();
    }

    function applyFilter(filter) {
        const f = normalize(filter);
        menuCards.forEach(card => {
            const rawType = card.dataset.type || card.getAttribute('data-type') || '';
            const types = rawType.split(',').map(t => normalize(t));
            const matches = (f === 'all') || types.includes(f);
            card.style.display = matches ? '' : 'none';
        });
    }

    if (filterButtons.length) {
        const allBtn = filterButtons.find(b => normalize(b.dataset.filter) === 'all') || filterButtons[0];
        filterButtons.forEach(b => b.classList.remove('active'));
        if (allBtn) {
            allBtn.classList.add('active');
            applyFilter(allBtn.dataset.filter || allBtn.textContent);
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            button.classList.add('active');
            const filter = button.dataset.filter || button.textContent || 'All';
            applyFilter(filter);
        });
    });

    document.querySelectorAll('#manage-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            const menuCard = this.closest('.menu-card');
            const dropdown = menuCard.querySelector('.drop-down');
            document.querySelectorAll('.drop-down').forEach(dd => {
                if (dd !== dropdown) dd.style.display = 'none';
            });
            dropdown.style.display = (dropdown.style.display === 'flex') ? 'none' : 'flex';
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.drop-down').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    });

    document.querySelectorAll('.drop-down').forEach(dropdown => {
        dropdown.querySelectorAll('div[data-url]').forEach(item => {
            item.addEventListener('click', function (ev) {
                ev.stopPropagation();
                const url = this.dataset.url;
                if (url) window.location.href = url;
            });
        });
        dropdown.addEventListener('click', function (ev) {
            ev.stopPropagation();
        });
    });
});
</script>
=======
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort Service List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1 id="h2">Service Items</h1>
                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_service') }}">
                            <h2 id="add-text">Add Service</h2>
                            <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                        </div>
                    {{--<div class="search-container">
                        <form action="{{ route('manager.search_service') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_menu') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>--}}
                    
                </div>
            </div>
            <div class='menu-contianer'>
                @foreach($service as $menuitem)
                    <div class="menu-card" data-type="{{ $menuitem->itemtype }}">
                        <div id="img-container">
                            <img  src="{{ route('menu.image', ['filename' => basename($menuitem->image)]) }}" alt={{ $menuitem->menuname }}>
                            
                        </div>
                        <div id="menu-details">
                            <h2>Name: {{$menuitem->menuname}}</h2>
                            <h2>Type: {{$menuitem->itemtype}}</h2>
                            <h2>Price: {{$menuitem->price}}</h2>
                            <hr/>
                            <div id="manage-container">
                                <h2>Status: {{$menuitem->status}}</h2>
                                <button id="manage-button">Manage&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-chevron-down fa-lg"></i></button>
                                
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
            <div class="alert-message">
                <h2>{{ session('success') }}</h2>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-message">
                <h2>{{ session('error') }}</h2>
           </div>
        @endif
        </div>
    </div>
</body>
<style>
    #service{color:#F78A21;}   
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
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
    .navbar{
        display:flex;
        flex-direction: row;
        width:100%;
        height: 4rem;
        gap:1rem;
        padding:1rem;
        justify-content:center;
        align-items:center;
        -webkit-overflow-scrolling: touch;
    }

    .navbar-item{
        display: flex;
        height:3rem;    
        width:7rem;
        background:#ffffff;
        border-radius:.5rem;
        font-size:.7rem;
        align-items:center;
        justify-content:center;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,0.2);
        transition:all .3s ease;
    }
    .navbar-item:hover{
        background:rgb(53, 53, 53);
        color:white;
        cursor:pointer;
    }
    .menu-contianer{
        display:flex;
        flex-direction:row;
        flex-wrap: wrap;
        gap:1rem;
        padding:1rem;
        width:100%;
        height: 100%;
        overflow-y: auto;
        justify-content:center;
    }
    #manage-container{
        display:flex;
        flex-direction:row;
        width:100%;
        height:3rem;
        justify-content: space-evenly;
        margin-top:auto;
        bottom:1;
    }
    #manage-button{
        height:100%;
        margin-left:auto;
        right:1;
        border-radius:.5rem;
        background:black;
        color:white;
        align-items:center;
        justify-content: space-evenly;
        transition:all .2s ease;
        border:none;
        box-shadow: .2rem .2rem 0 rgba(0,0,0,0.2);
        position: relative;
    } 
    #manage-button:hover{
        background:orange;
        color:black;
        cursor:pointer;
    }
    .menu-card{
        height:22rem;
        width:15rem;
        display:flex;
        flex-direction:column;
        background:white;
        border-radius:.5rem;
        padding: .5rem;
        font-size:.7rem;
        box-shadow: .1rem .3rem 0 rgba(0,0,0,0.2);
    }
    .menu-card img{
        height:10rem;
        width:100%;
        border-top-right-radius:1rem;
        border-top-left-radius:1rem;
        object-fit:cover;
    }
    .drop-down{
        display:none;
        flex-direction:column;
        width:10rem;
        position: absolute;
        background:rgb(182, 182, 182);
        padding:.5rem;
        z-index: 1;
        gap:.5rem;
        border-radius:.5rem;
        align-items: center;
        justify-content: center;
        margin-left:4rem;
    }
    .drop-down div{
        display: flex;
        flex-direction: row;
        width:100%;
        align-items: center;
        justify-content: space-evenly;
        background: white;
        border-radius:.5rem;
        cursor:pointer;
        transition:all .3s ease;
    }
    .drop-down div:hover{
        background:grey;
        color:white;
    }
    .navbar-item.active {
    background-color: rgb(150, 55, 0); 
    color: white;
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = Array.from(document.querySelectorAll('.navbar-item'));
    const menuCards = Array.from(document.querySelectorAll('.menu-card'));
    const addMenu = document.getElementById("add-container");

    if (addMenu) {
        addMenu.addEventListener("click", function () {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            } else {
                console.error("No data-url found on #add-container");
            }
        });
    }

    function normalize(s) {
        return (s || '').toString().trim().toLowerCase();
    }

    function applyFilter(filter) {
        const f = normalize(filter);
        menuCards.forEach(card => {
            const rawType = card.dataset.type || card.getAttribute('data-type') || '';
            const types = rawType.split(',').map(t => normalize(t));
            const matches = (f === 'all') || types.includes(f);
            card.style.display = matches ? '' : 'none';
        });
    }

    if (filterButtons.length) {
        const allBtn = filterButtons.find(b => normalize(b.dataset.filter) === 'all') || filterButtons[0];
        filterButtons.forEach(b => b.classList.remove('active'));
        if (allBtn) {
            allBtn.classList.add('active');
            applyFilter(allBtn.dataset.filter || allBtn.textContent);
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            button.classList.add('active');
            const filter = button.dataset.filter || button.textContent || 'All';
            applyFilter(filter);
        });
    });

    document.querySelectorAll('#manage-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            const menuCard = this.closest('.menu-card');
            const dropdown = menuCard.querySelector('.drop-down');
            document.querySelectorAll('.drop-down').forEach(dd => {
                if (dd !== dropdown) dd.style.display = 'none';
            });
            dropdown.style.display = (dropdown.style.display === 'flex') ? 'none' : 'flex';
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.drop-down').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    });

    document.querySelectorAll('.drop-down').forEach(dropdown => {
        dropdown.querySelectorAll('div[data-url]').forEach(item => {
            item.addEventListener('click', function (ev) {
                ev.stopPropagation();
                const url = this.dataset.url;
                if (url) window.location.href = url;
            });
        });
        dropdown.addEventListener('click', function (ev) {
            ev.stopPropagation();
        });
    });
});
</script>
>>>>>>> d927b3a3dbe225427cfaf6d569765ffb9f95c0be
