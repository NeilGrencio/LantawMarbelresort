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
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .7rem;
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
</style>
</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Day Tour QR Codes</h1>
            <div class="add-action">
                <i id="add-action" class="fas fa-plus-circle fa-2x" data-url="{{ url('receptionist/daytour') }}" style="cursor:pointer;"></i>
                <small>Create Day Tour</small>
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
                    <img src="{{ asset('storage/' . $rec->qrcode) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                    <p><strong>Guest:</strong>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    {{ $rec->guest->firstname }} {{ $rec->guest->lastname }}</p>
                    <p><strong>Amenity:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       {{ $rec->amenity->amenityname }}</p>
                    <p><strong>Access Date:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $rec->accessdate }}</p>
                    
                </div>
            @endforeach
            <div class="qr-label"><h2>All QRCODES</h2></div>
            @foreach($qrcode as $qr)    
                <div class="qr-card">
                    <img src="{{ asset('storage/' . $qr->qrcode) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
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