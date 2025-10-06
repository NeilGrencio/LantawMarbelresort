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
        @include ('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1 id="h2">Resort Amenities</h1>

                <div class="button-group">
                    <div id="add-container" data-url="{{ url('manager/add_amenity') }}">
                        <h2 id="add-text">Add Amenity</h2>
                        <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                    </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_amenity') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_amenity') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{--Container for the amenities--}}
            <div class="amenity-container">
                @foreach($amenities as $amenity)
                @php
                    $amenityCardClass = '';
                    if($amenity->status == 'Unavailable' || $amenity->status == 'Maintenance'){
                        $amenityCardClass = 'amenity-card deactivated';
                    }
                    else {
                        $amenityCardClass = 'amenity-card';
                    }
                @endphp
                <div class="{{$amenityCardClass}}" data-url={{ url('manager/edit_amenity/' . $amenity->amenityID) }}>
                    <div id="amenity-wrapper">
                        <div id="image-wrapper">
                            <img id="amenity-img" src="{{ route('amenity.image', ['filename' => basename($amenity->image)]) }}" alt={{ $amenity->amenityname }}>   
                        </div>
                        <div id="amenity-info">
                            <h1>{{$amenity->amenityname}}</h1>
                            <h4>{{$amenity->description}}</h4>
                            <h4>₱{{$amenity->adultprice}}.00</h4>
                            <h4>₱{{$amenity->childprice}}.00</h4>
                            <h4>This amenity is {{$amenity->status}}</h4>
                        </div>
                    </div>
                    <div id="manage-wrapper" class="manage-dropdown-wrapper">
                        <div class="manageBtn">
                            <p>
                                Manage
                                <i class="fas fa-chevron-down fa-lg"></i>
                            </p>
                        </div>
                    </div>
                </div> 

                <!-- Dropdown kept outside so card hover does not affect it -->
                <div class="dropdown-content">
                    <div data-url="{{ url('manager/edit_amenity/' . $amenity->amenityID) }}">
                        <h4>Update</h4>
                        <i class="fas fa-pen fa-lg"></i>
                    </div>
                    @if ($amenity->status == 'Available')
                        <div data-url="{{ url('manager/deactivate_amenity/' . $amenity->amenityID) }}">
                            <h4>Deactivate</h4>
                            <i class="fas fa-ban fa-lg" style="color:#d9534f;"></i>
                        </div>
                    @elseif ($amenity->status == 'Unavailable' || $amenity->status == 'Maintenance')
                        <div data-url="{{ url('manager/activate_amenity/' . $amenity->amenityID) }}">
                            <h4>Activate</h4>
                            <i class="fas fa-check-circle fa-lg" style="color:#5cb85c;"></i>
                        </div>
                    @elseif ($amenity->status == 'Booked')
                        <div style="background:#dddddd; color:#949494;">
                            <h4>Booked</h4>
                            <i class="fas fa-calendar-check fa-lg" style="color:#949494;"></i>
                        </div>
                    @endif

                    @if($amenity->status == 'Maintenance')
                        <div data-url="{{ url('manager/maintenance_amenity/' . $amenity->amenityID) }}" style="display:none;">
                            <h4>Maintenance Amenity</h4>
                            <i class="fas fa-wrench fa-lg" style="color:#9d9d9d;"></i>
                        </div>
                    @else
                        <div data-url="{{ url('manager/maintenance_amenity/' . $amenity->amenityID) }}">
                            <h4>Maintenance Amnenity</h4>
                            <i class="fas fa-wrench fa-lg" style="color:#9d9d9d;"></i>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            @if (session('success'))
                <div class="alert-message">
                    <h2>{{ session('success') }}</h2>
                </div>
            @endif
        </div>
    </div>
</body>

<style>
    body{overflow-y:auto;}
    #amenities { color: #F78A21;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:12rem;
    }
    #amenity-info {
        font-size:.7rem;
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
    .amenity-container{
        display:flex;
        flex-wrap: wrap;
        width: 100%;
        height:auto;
        gap:1rem;   
        margin-top:1rem;
    }
    #amenity-wrapper{
        display:flex;
        flex-direction: row;
        gap:1rem;
        height:100%;
    }
    .amenity-card{
        display:flex;
        flex-direction: row;
        width:100%;
        min-height:15rem;
        max-height:30rem;
        justify-content: space-between;
        border-radius:.7rem;
        padding:1rem;
        background:white;
        align-items:center;
        gap:1rem;
        box-shadow: .2rem .2rem 0 black;
        border:1px solid black;
        font-weight:900;
        cursor:pointer;
        transition:all 0.3s ease;
    }
    .amenity-card.deactivated {
        background: #dfdfdf;
        color: #b7b7b7;
    }   
    .amenity-card.deactivated img{
        background: rgba(115, 115, 115, 0.9);
        opacity: .5;
    }
    .amenity-card:hover{
        background:rgb(238, 156, 101);
        color:rgb(255, 255, 255);
        border:2px solid rgb(0, 0, 0);
        box-shadow:.1rem .1rem 0 rgb(0, 0, 0);
        transform:scale(1.01);
    }
    #image-wrapper {
        height: 15rem;
        width: 15rem;
        border-radius: .7rem;
        overflow: hidden;
        flex-shrink: 0; 
    }
    #amenity-img {
        width: 100%;
        height: 100%; 
        object-fit: cover; 
        display: block;       
        border-radius: .7rem;
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
        transition: all 0.2s ease;
    }
    .manageBtn:hover {
        background:orange;
        color:black;
    }
    .dropdown-content {
        display: none;
        flex-direction: column;
        position: absolute;
        min-width: 10rem;
        margin-right:1rem;
        background-color: #8d8d8d;
        padding: 0.5rem;
        border-radius: 0.5rem;
        gap:.7rem;
        z-index: 9999;
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
    const addAmenity = document.getElementById('add-container');
    const amenityCards = document.querySelectorAll('.amenity-card');
    const manageBtns = document.querySelectorAll('.manageBtn');
    const dropdowns = document.querySelectorAll('.dropdown-content');
    const messages = document.querySelectorAll('.alert-message');

    // Hide alert messages after 1.5s
    if (messages.length) {
        setTimeout(() => {
            messages.forEach(msg => msg.style.display = 'none');
        }, 2500);
    }

    // Navigate on card click
    amenityCards.forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.manageBtn') || e.target.closest('.dropdown-content')) {
                return;
            }
            window.location.href = this.dataset.url;
        });
    });

    // Add Amenity click
    addAmenity.addEventListener('click', function () {
        window.location.href = this.dataset.url;
    });

    // Manage button toggle with absolute positioning
    manageBtns.forEach((btn, index) => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            // Close others
            dropdowns.forEach(d => d.classList.remove('active'));

            const dropdown = dropdowns[index];
            const rect = btn.getBoundingClientRect();

            dropdown.style.top = `${rect.bottom + window.scrollY}px`;
            dropdown.style.left = `${rect.left + window.scrollX - 45}px`

            dropdown.classList.add("active");
        });
    });

    // Close dropdown if clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.manageBtn') && !e.target.closest('.dropdown-content')) {
            dropdowns.forEach(d => d.classList.remove('active'));
        }
    });

    // Confirmation for Activate/Deactivate
    document.querySelectorAll('.dropdown-content div[data-url]').forEach(item => {
        item.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            const url = this.dataset.url;

            if (url.includes('deactivate_amenity')) {
                if (!confirm('Are you sure you want to deactivate this amenity?')) return;
            }
            if (url.includes('activate_amenity')) {
                if (!confirm('Are you sure you want to activate this amenity?')) return;
            }

            window.location.href = url;
        });
    });
</script>
</html>
