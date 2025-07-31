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
                <div id="add-container">
                    <h2 id="add-text">Add Amenity</h2>
                    <i id="add-menu" class="fas fa-plus-circle fa-3x" data-url="{{ url('manager/add_amenity') }}" style="cursor:pointer;"></i>
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
                        <img id="amenity-img" src="{{ asset('storage/' . $amenity->image) }}"></img>
                    </div>
                    <div>
                        <h1>{{$amenity->amenityname}}</h1>
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
        margin-left:15rem;
    }
    #layout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height:7%;
        padding: 1rem 3rem 1rem 2rem;
        background: white; 
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        border-radius: .7rem;
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
    #add-amenity{cursor:pointer;}
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
        width:50%;
        height:15rem;
        border-radius:.7rem;
        padding:1rem;
        background:white;
        align-items:center;
        gap:1rem;
        box-shadow: .2rem .2rem 0 black;
        border:1px solid black;
        font-weight:900;
        cursor:pointer;
        transition:background 0.3s ease-in;
    }
    .amenity-card.deactivated {
        background: #dfdfdf;
        color: #b7b7b7;
    }   
    .amenity-card.deactivated img{
        background: rgba(115, 115, 115, 0.9);
        opacity: .5;
    }
    .amenity-card:hover{background:rgb(194, 194, 194);}
    #amenity-img{
        display: flex;
        height:100%;
        width:100%;
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
    const addAmenity = document.getElementById('add-menu');
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