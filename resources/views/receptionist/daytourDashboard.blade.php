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
        margin-left:15rem;
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
    .qrcode-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f9f9f9;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .qrcode-card img {
        width: 150px;
        height: 150px;
        object-fit: contain;
    }
</style>
</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Day Tour Management</h1>
        </div>
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($qrcode) && $qrcode->isNotEmpty())
            <h2 class="text-xl font-semibold mt-6">Generated QR Codes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach($qrcode as $qr)    
                    <div class="bg-white rounded shadow p-4">
                        <p><strong>Guest:</strong> {{ $qr->guest->firstname }} {{ $qr->guest->lastname }}</p>
                        <p><strong>Amenity:</strong> {{ $qr->amenity->amenityname }}</p>
                        <p><strong>Access Date:</strong> {{ $qr->accessdate }}</p>
                        <img src="{{ asset('storage/' . $qr->qrcode) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
</body>