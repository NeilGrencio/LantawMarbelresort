<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<title>Lantaw-Marbel Resort</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .main-container{
        display:grid;
        height:88vh;
        padding:.5rem;
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
        width:100%;
        height:100vh;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
        margin-right:.7rem;
        overflow-y: hidden;
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

    .qr-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: .5rem;
        width: 100%;
        height: 100%;
        border-radius: .7rem;
        padding: .5rem;
        overflow-y: auto;
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
    .amenity-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        gap: .5rem;
        width: 100%;
        padding: .5rem;
        border-radius: .5rem;
    }

    .amenity-card {
        display: flex;
        align-items: center;
        background: white;
        border-radius: .4rem;
        box-shadow: .05rem .05rem 0 black;
        padding: .4rem .7rem;
        font-size: .75rem;
        white-space: nowrap;
    }
    .add-action{
        display:flex;
        flex-direction: column;
        align-items:center;
        justify-content:space-evenly;
        height:100%;
        font-size: .8rem;
    }
    .capacity-container{
        display: flex;
        flex-direction: column;
        width: 100%;
        height: auto;
        border-radius: .7rem;
        margin-top: 1rem;
        background: white;
        box-shadow: .1rem .1rem 0 black;
        padding: 1rem;
        overflow-y: auto;
    }
    .modal{
        display:none;
        position:fixed;
        z-index:2000;
        left:0;
        top:0;
        width:100%;
        height:100%;
        background-color:rgba(0,0,0,0.7);
        justify-content:center;
        align-items:center;
    }
    .modal-content{
        background:white;
        padding:1rem;
        border-radius:.7rem;
        box-shadow:0 0 1rem rgba(0,0,0,0.5);
        max-width:80vw;
        max-height:80vh;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
    }
    .modal-content img{
        max-width:100%;
        max-height:70vh;
        object-fit:contain;
    }
    .close-modal{
        position:absolute;
        top:1rem;
        right:1.5rem;
        font-size:2rem;
        color:white;
        cursor:pointer;
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

            <div class="main-container">
                <div class="qr-container">
                    <div class="amenity-container">
                        @forelse($amenity as $a)
                            <div class="amenity-card">
                                <small>{{ $a->amenityname }} is currently <strong>{{ $a->status }}</strong></small>
                            </div>
                        @empty
                            <p>No amenities found.</p>
                        @endforelse
                    </div>

                    <div class="qr-label"><h2>Today</h2></div>
                    @forelse($recent as $rec)
                        <div class="qr-card">
                            <img src="{{ route('qr.code', ['filename' => basename($rec->qrcode)]) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                            <p><strong>Guest:</strong> {{ $rec->guest->firstname }} {{ $rec->guest->lastname }}</p>
                            <p><strong>Amenity:</strong> {{ $rec->amenity->amenityname }}</p>
                            <p><strong>Access Date:</strong> {{ $rec->accessdate }}</p>
                        </div>
                    @empty
                        <p style="padding:1rem;">No day tours for today.</p>
                    @endforelse
                    <div class="qr-label"><h2>All QRCODES</h2></div>
                    @forelse($qrcode as $qr)
                        <div class="qr-card">
                            <img src="{{ route('qr.code', ['filename' => basename($qr->qrcode)]) }}" alt="QR Code" class="w-48 h-48 mt-2 object-contain">
                            <p><strong>Guest:</strong> {{ $qr->guest->firstname }} {{ $qr->guest->lastname }}</p>
                            <p><strong>Amenity:</strong> {{ $qr->amenity->amenityname }}</p>
                            <p><strong>Access Date:</strong> {{ $qr->accessdate }}</p>
                        </div>
                    @empty
                        <p style="padding:1rem;">No QR codes available.</p>
                    @endforelse

                </div>
                <div class="capacity-container">
                    <h3>Amenities Capacity Overview (Today)</h3>
                    @forelse($differentAmenities as $amenitynames)
                        <div style="margin-bottom: 40px;">
                            <h4>{{ $amenitynames }}</h4>
                            <canvas id="chart-{{ Str::slug($amenitynames) }}" width="400" height="200"></canvas>
                        </div>
                    @empty
                        <p>No available amenities for chart display.</p>
                    @endforelse
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success mt-4">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>
<div id="qrModal" class="modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="">
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

        const qrImages = document.querySelectorAll('.qr-card img');
        const modal = document.getElementById('qrModal');
        const modalImg = document.getElementById('modalImage');
        const closeModal = document.querySelector('.close-modal');

        qrImages.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                modal.style.display = 'flex';
                modalImg.src = this.src;
            });
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        const amenities = @json($amenitiesData);

        const grouped = {};
        amenities.forEach(item => {
            if (!grouped[item.amenityname]) grouped[item.amenityname] = [];
            grouped[item.amenityname].push(item);
        });

        Object.keys(grouped).forEach(amenity => {
            const data = grouped[amenity][0];
            const ctx = document.getElementById(`chart-${slugify(amenity)}`);
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Used', 'Available'],
                        datasets: [{
                            label: 'Capacity Status',
                            data: [data.used, data.available],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(75, 192, 192, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: `${amenity} (Total Capacity: ${data.capacity})`
                            },
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Capacity' }
                            }
                        }
                    }
                });
            }
        });

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w-]+/g, '');
        }
    });
</script>
