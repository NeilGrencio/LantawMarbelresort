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
            <div id="layout-header">
                <h1 id="h2">Menu Items</h1>
                <div id="add-container">
                    <h2 id="add-text">Add Menu Item</h2>
                    <i id="add-menu" class="fas fa-plus-circle fa-3x" data-url="{{ url('manager/add_menu') }}" style="cursor:pointer;"></i>
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
                    <div class="menu-card" data-type="{{ $menuitem->itemtype }}">
                        <div id="img-container">
                            <img src="{{asset('storage/' . $menuitem->image)}}">
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
    #menu{color:#F78A21;}   
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
        margin-left:15rem;
    }
    #layout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height:5%;
        padding: 1rem 3rem 1rem 2rem;
        background: white; 
        border-radius: 2rem;
        font-size: 70%;
        gap: 1rem;
    }
     #add-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        gap:1rem;
    }

    #add-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }

    #add-container:hover #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
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
        const manageButtons = document.querySelectorAll('#manage-button');
        const filterButtons = document.querySelectorAll('.navbar-item');
        const menuCards = document.querySelectorAll('.menu-card');
        const dropdownButtons = document.querySelectorAll('.drop-down div');
        const addMenu = document.getElementById('add-menu');
        const message = document.querySelector('.alert-message');

        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        addMenu.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });

        // Default: highlight first nav item
        if (filterButtons.length > 0) {
            filterButtons[0].classList.add('active');
        }

        // Filter logic
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                // Highlight the active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Show/hide cards
                menuCards.forEach(card => {
                    const type = card.getAttribute('data-type');
                    card.style.display = (filter === 'All' || filter === type) ? 'block' : 'none';
                });
            });
        });

        // Manage dropdown toggle
        manageButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation(); // Prevent closing dropdown immediately

                const menuCard = this.closest('.menu-card');
                const dropdown = menuCard.querySelector('.drop-down');

                // Close other dropdowns
                document.querySelectorAll('.drop-down').forEach(dd => {
                    if (dd !== dropdown) dd.style.display = 'none';
                });

                // Toggle current dropdown
                dropdown.style.display = (dropdown.style.display === 'flex') ? 'none' : 'flex';
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function () {
            document.querySelectorAll('.drop-down').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        });

        // Prevent dropdown itself from closing on click
        document.querySelectorAll('.drop-down').forEach(dropdown => {
            dropdown.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });

        dropdownButtons.forEach(button => {
            button.addEventListener('click', function () {
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>



