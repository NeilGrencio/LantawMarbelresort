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
            {{--Container for the amneities--}}
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
                    <div>
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
    .amenity-card{
        display:flex;
        flex-direction: row;
        width:100%;
        height:11rem;
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
        background:rgb(255, 197, 144);
        border:2px solid orange;
        box-shadow:.1rem .1rem 0 orange;
    }
    #amenity-img{
        display: flex;
        height:100%;
        width:15rem;
        border-radius:.7rem;
        object-fit: cover;
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
    

    amenityCards.forEach(card => {
        card.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    });

    addAmenity.addEventListener('click', function(){
        window.location.href = this.dataset.url;
    });
</script>
