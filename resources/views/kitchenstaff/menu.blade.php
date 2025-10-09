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
        <div id="main-layout">
            <div id="layout-header">
                <h2>Welcome Kitchen Staff</h2>

                <div class="nav-links">
                    <div id="menu-section" data-url="{{ route('kitchen.dashboard') }}">
                        <i class="fa-solid fa-utensils fa-2x"></i>
                        <strong>Dashboard</strong>
                    </div>
                    <div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <div id="out">  
                                <i class="fa-solid fa-power-off fa-2x"></i>
                                <button type="submit" style="all:unset; cursor:pointer; font-size:12px;">Log Out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="navbar">
                <div class="navbar-item" data-filter="All"><h3>All</h3></div>
                @foreach($uniqueMenuTypes as $menutypes)
                    <div class="navbar-item" data-filter="{{ $menutypes}}">
                        <h3>{{$menutypes}}</h3>
                    </div>
                @endforeach
            </div>
            <div class='menu-contianer'>
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
* {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
    }

    #layout {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100%;
        overflow: hidden;
        background: rgba(248, 242, 230, 0.5);
    }
    .total-container{ 
        display:grid; 
        grid-template-columns: 1fr 1fr 1fr; 
        text-align: center; 
    }
    #layout-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100vw;
        height: 8%;
        padding: 1rem 3rem 1rem 2rem;
        background: #F78A21;
        color:white;
        font-size: .7rem;
        border-bottom: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    #layout-wrapper {
        display: flex;
        flex-direction: row;
        padding: 1rem;
        width: 100%;
        height: 92%;
        overflow: hidden;
        gap: .5rem;
        background: transparent;
    }
    #main-content {
        flex: 1;
        height: 100%;
        overflow-y: auto;
    }

    #layout-side {
        flex: 0 0 25%;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 0.7rem;
        overflow-y: auto;
        padding: 1rem;
        gap: 1rem;
        overflow:none;
    }
    .nav-links{
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: .3rem;
        font-size: 12px;
        cursor:pointer;
        gap:1rem;
    }
    .nav-links div{
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all .2s ease-in-out;
    }
    .nav-links div:hover{
        color: black;
        transform: rotate(-10deg);
        scale: 1.1;
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
    .menu-card {
        height: 15rem;
        width: 12rem;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 0.6rem;
        padding: 0.6rem;
        font-size: 0.75rem;
        box-shadow: 0.1rem 0.2rem 0 rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .menu-card:hover {
        transform: translateY(-3px);
        box-shadow: 0.2rem 0.3rem 0 rgba(0, 0, 0, 0.3);
    }

    .menu-card img {
        height: 8rem;
        width: 100%;
        border-radius: 0.5rem;
        object-fit: cover;
        margin-bottom: 0.4rem;
    }

    .menu-card h2 {
        font-size: 0.8rem;
        line-height: 1.1rem;
        margin: 0.15rem 0;
    }

    #manage-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
        margin-top: auto;
    }

    .menu-contianer {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.8rem;
        padding: 0.7rem;
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

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = Array.from(document.querySelectorAll('.navbar-item'));
    const menuCards = Array.from(document.querySelectorAll('.menu-card'));
    const message = document.querySelector('.alert-message');

    const menuSection = document.getElementById('menu-section');
    menuSection.addEventListener('click', () => {
        window.location.href = menuSection.dataset.url;
    });

    if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
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
