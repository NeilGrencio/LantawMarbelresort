<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<title>Lantaw-Marbel Resort</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    .main-container{
        display:grid;
        grid-template-columns: 2fr .5fr;
        gap:.5rem;
        position:relative;
    }
    .form-form{
        width:100%;
        position: relative;
    }
    .rec{
        width: 100%;
        position:sticky;
    }
    #daytour{color:orange;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:85%;
        height:100vh;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
        margin-right:.7rem;
        overflow-y: auto;
        overflow-x: hidden;
        gap:.5rem;
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
    .add-action{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
        cursor: pointer;
        font-size: .8rem;
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
        align-items: center;
        justify-content: flex-end;
        margin: 15px 0;
        gap: 1rem;
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

    .qr-container{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap:.5rem;
        width: 100%;
        height: auto;
        padding: .5rem;
        border-radius: .7rem;
        margin-top: 1rem;
        align-items: center;
        align-content: center;
    }
    .qr-card {
        display: flex;
        flex-direction: column;
        width:24%;
        border: 1px solid #ddd;
        border-radius: 7px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f9f9f9;
        box-shadow: .1rem 2px 0 black;
        align-items:center;
        justify-content: center;
    }
    .qr-card img {
        width: 150px;
        height: 150px;
        object-fit: contain;
    }
    .qr-label{
        width:100%;
        height:3rem;
        background:black;
        color:white;
        display: flex;
        align-items:center;
        padding:.5rem;
        border-radius:.5rem;
    }
    p{
        display: flex;
        margin-bottom:.5rem;
        font-size:.7rem;
        align-self: start;
    }
    .amenity-container{
        display:flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap:.5rem;
    }
    .amenity-card{
        display: flex;
        background:white;
        border-radius:.4rem;
        box-shadow:.1rem .1rem 0 black;
        padding:.4rem;
    }
    .add-action{
        display:flex;
        flex-direction: column;
        align-items:center;
        justify-content:space-evenly;
        height:100%;
        font-size: .8rem;
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
</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Day Tour QR Codes</h1>

            <div class="search-container">
                <form action="{{ route('receptionist.search_daytour') }}" method="GET">
                    <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('receptionist.search_daytour') }}" class="reset-btn">Clear Search</a>
                    @endif
                </form>

                <div class="add-action" style="margin-left: 1rem;">
                    <i id="add-action" class="fas fa-plus-circle fa-2x" 
                    data-url="{{ url('receptionist/daytour') }}" 
                    style="cursor:pointer;"></i>
                    <small>Create Day Tour</small>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif
        <div class="amenity-container">
            @foreach($amenity as $a)
            <div class="amenity-card">
                <small>{{$a->amenityname}} is currently <strong>{{$a->status}}</strong></small>
            </div>
            @endforeach
        </div>
        <div class="qr-container">
            <div class="qr-label"><h2>Today</h2></div>
            @foreach($recent as $rec)
                <div class="qr-card">
                    <img src="{{ route('qr.code', ['filename' => basename($rec->qrcode)]) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                    <p><strong>Guest:</strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    {{ $rec->guest->firstname }} {{ $rec->guest->lastname }}</p>
                    <p><strong>Amenity:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       {{ $rec->amenity->amenityname }}</p>
                    <p><strong>Access Date:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $rec->accessdate }}</p>
                    
                </div>
            @endforeach
            <div class="qr-label"><h2>All QRCODES</h2></div>
            @foreach($qrcode as $qr)    
                <div class="qr-card">
                    <img src="{{ route('qr.code', ['filename' => basename($qr->qrcode)]) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                    <p><strong>Guest:</strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    {{ $qr->guest->firstname }} {{ $qr->guest->lastname }}</p>
                    <p><strong>Amenity:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       {{ $qr->amenity->amenityname }}</p>
                    <p><strong>Access Date:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $qr->accessdate }}</p>
                    
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    const createDayTour = document.getElementById('add-action');
    
    if (createDayTour) { 
        const url = createDayTour.dataset.url;

        createDayTour.addEventListener('click', function() {
            window.location.href = url;
        });
    }
});

</script>
