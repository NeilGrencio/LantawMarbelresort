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
                <h1 id="h2">Kiddy Pool Cottages</h1>
                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_cottages') }}">
                            <h3>Add Cottage</h3>
                            <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                        </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_cottage') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_cottage') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="cottage-layout">
                @foreach($cottage as $cottages)
                <div id="cottage-container">
                    <div id="image-container">
                                <img  src="{{ route('cottage.image', ['filename' => basename($cottages->image)]) }}" alt={{ $cottages->cottagename }}>
            
                    </div>
                    
                    <div id="cottage-information">
                        <h1>Cottage Name: {{$cottages->cottagename}}</h1>
                        <h2>Capacity: {{ $cottages->capacity}}</h2>
                        <h2>Price: ₱ {{ $cottages->price}}</h2>
                        <h2>Status: {{ $cottages->status}}</h2>
                    </div>

                    <div id="button-container">
                        <button class="update-btn" data-url="{{ url('manager/edit_cottage/' . $cottages->cottageID) }}">
                            UPDATE <i class="fa-solid fa-pencil fa-lg"></i>
                        </button>

                        @if($cottages->status == 'Available')
                            <button class="status-btn" data-url="{{ url('manager/deactivate_cottage/' . $cottages->cottageID) }}">
                                DEACTIVATE <i class="fa-solid fa-times-circle fa-lg" style="color:red;"></i>
                            </button>
                        @else
                            <button class="status-btn" data-url="{{ url('manager/activate_cottage/' . $cottages->cottageID) }}">
                                ACTIVATE <i class="fas fa-circle fa-lg" style="color:green;"></i>
                            </button>
                        @endif

                        <button class="maintenance-btn" data-url="{{ url('manager/maintenance_cottage/' . $cottages->cottageID) }}">
                            MAINTENANCE <i class="fa-solid fa-wrench fa-lg"></i>
                        </button>
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
</body>
<style>
    #cottages{color:#F78A21;}
    body{overflow-y: scroll;}
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
        margin-top: 1rem;
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
    .cottage-layout{
        display:flex;
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
        padding:1rem;
        gap:2rem;
        justify-content: center;
        align-items: center;
    }
    #cottage-container{
        width:20rem;
        height:23rem;
        display:flex;
        flex-direction:column;
        padding:1rem;
        border-radius:.9rem;
        background:white;
        box-shadow: .1rem .2em 0rem black;
        border:solid 1px black;
    }
    #image-container img{
        width:100%;
        height:10rem;
        background:brown;
        border-top-right-radius: .7rem;
        border-top-left-radius: .7rem;
        object-fit: cover;
    }
    #cottage-information{
        font-size:.5rem;
    }
    #cottage-information h1{
        display: flex;
        align-items: center;
        justify-content: center;
        font-size:.8rem;
    }
    #cottage-information :nth-child(2){
        margin-top:2rem;
    }
    #button-container{
        display: flex;
        flex-direction: row;
        gap:1rem;
        bottom:1rem;
        margin-top:auto;
        align-items: center;
        justify-content:center;
        position: relative;
    }
    button{
        position: relative;
        width:auto;
        height:2rem;
        color:white;
        background:black;
        border:unset;
        font-size:.6rem;
        font-weight:bold;
        justify-content: space-between;
        gap:1rem;
        border-radius:.5rem;
        transition:all .2s ease-in;
    }
    button:hover{
        background:rgba(209, 139, 0, 0.815);
        cursor:pointer;
    }
    #status-dropdown{
        display:none;
        flex-direction: column;

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
    const addCottage = document.getElementById('add-container');
    const updateButtons = document.querySelectorAll('.update-btn');
    const statusButtons = document.querySelectorAll('.status-btn');
    const maintenanceButtons = document.querySelectorAll('.maintenance-btn');
    const messages = document.querySelectorAll('.alert-message');

    // Hide alert messages after 1.5s
    if (messages.length) {
        setTimeout(() => {
            messages.forEach(msg => msg.style.display = 'none');
        }, 2500);
    }

    // Redirect on Add Cottage
    if (addCottage) {
        addCottage.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    }

    // Redirect on Update
    updateButtons.forEach(button => {
        button.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });

    // Redirect on Maintenance
    maintenanceButtons.forEach(button => {
        button.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });

    // Redirect on Activate/Deactivate
    statusButtons.forEach(button => {
        button.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });

</script>
