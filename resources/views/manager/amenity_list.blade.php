<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Amenities</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    #amenities {
        background: rgba(255,255,255,0.15);
        border-left: 4px solid #ff9100;
        color: white;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: white;
    }
    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
        width: 100%;
    }
    #main-layout {
        padding: 1.5rem;
        width: calc(100% - 14rem);
        overflow-x: auto;
    }

    /* Header */
    #layout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    #layout-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #F78A21;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        background: #F78A21;
        color: #fff;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.7rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #add-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-container input {
        padding: 0.6rem 1rem;
        border-radius: 25px 0 0 25px;
        border: 1px solid #ccc;
        outline: none;
        width: 200px;
        transition: 0.3s;
    }

    .search-container input:focus {
        border-color: #F78A21;
    }

    .search-container button {
        padding: 0.6rem 1rem;
        border-radius: 0 25px 25px 0;
        border: none;
        background: #F78A21;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-container button:hover {
        background: #e07b1f;
    }

    .reset-btn {
        padding: 0.5rem 1rem;
        background: #d9534f;
        color: #fff;
        border-radius: 25px;
        text-decoration: none;
        transition: 0.3s;
        font-size: 0.85rem;
    }

    .reset-btn:hover {
        background: #b71c1c;
    }

    /* Amenity Cards */
    .amenity-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .amenity-card {
        background: #fff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .amenity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .amenity-card.deactivated {
        opacity: 0.6;
        pointer-events: auto;
    }

    .amenity-card.deactivated .amenity-info,
    .amenity-card.deactivated img {
        pointer-events: none;
    }

    .amenity-card.deactivated .manage-dropdown-wrapper {
        pointer-events: auto;
    }

    .amenity-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .amenity-info {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .amenity-info h2 {
        font-size: 1.2rem;
        margin: 0;
        font-weight: 600;
    }

    .amenity-info h4 {
        margin: 0;
        font-weight: 400;
        color: #555;
        font-size: 0.9rem;
    }

    /* Manage Dropdown */
    .manage-dropdown-wrapper {
        position: relative;
    }

    .manageBtn {
        padding: 0.5rem 1rem;
        background: #333;
        color: #fff;
        text-align: center;
        border-radius: 0.7rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s ease;
        margin: 1rem;
    }

    .manageBtn:hover {
        background: #F78A21;
        color: #fff;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: -110%;
        right: 0;
        background: #fff;
        border-radius: 0.7rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        flex-direction: column;
        gap: 0.5rem;
        padding: 0.5rem;
        z-index: 999;
        width: 180px;
    }

    .dropdown-content div {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        cursor: pointer;
        border-radius: 0.5rem;
        transition: background 0.2s ease;
    }

    .dropdown-content div:hover {
        background: #F78A21;
        color: #fff;
    }

    .dropdown-content.active {
        display: flex;
    }

    /* Alert Messages */
    .alert-message {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 1rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 0 1rem rgba(0,0,0,0.3);
        font-weight: 600;
        z-index: 1000;
        text-align: center;
    }

    /* Modal Styles */
    .modal {
        display: none; /* hidden by default */
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: #fff;
        padding: 1.5rem;
        border-radius: 1rem;
        width: 90%;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .modal-content h2 {
        margin: 0;
        color: #F78A21;
    }

    .modal-content textarea {
        width: 100%;
        padding: 0.5rem;
        border-radius: 0.7rem;
        border: 1px solid #ccc;
        resize: none;
        font-size: 1rem;
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .modal-buttons button {
        padding: 0.5rem 1rem;
        border-radius: 0.7rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    #cancel-btn {
        background: #d9534f;
        color: #fff;
    }

    #cancel-btn:hover { background: #b71c1c; }

    #submit-reason-btn {
        background: #F78A21;
        color: #fff;
    }

    #submit-reason-btn:hover { background: #e07b1f; }

</style>
</head>

<body>
<div id="layout">
    @include('components.sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Resort Amenities</h1>
            <div class="button-group">
                <div id="add-container" data-url="{{ url('manager/add_amenity') }}">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    <span>Add Amenity</span>
                </div>
                <div class="search-container">
                    <form action="{{ route('manager.search_amenity') }}" method="GET">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                        @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('manager.search_amenity') }}" class="reset-btn">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="amenity-container">
            @foreach($amenities as $amenity)
            @php
                $cardClass = ($amenity->status != 'Available') ? 'amenity-card deactivated' : 'amenity-card';
            @endphp
            <div class="{{ $cardClass }}" data-url="{{ url('manager/edit_amenity/' . $amenity->amenityID) }}">
                <img src="{{ route('amenity.image', ['filename' => basename($amenity->image)]) }}" alt="{{ $amenity->amenityname }}">
                <div class="amenity-info">
                    <h2>{{ $amenity->amenityname }}</h2>
                    <h4>{{ $amenity->description }}</h4>
                    <h4>Adult: ₱{{ $amenity->adultprice }} | Child: ₱{{ $amenity->childprice }}</h4>
                    <h4>Status: {{ $amenity->status }}</h4>
                </div>
                <div class="manage-dropdown-wrapper">
                    <div class="manageBtn">Manage <i class="fas fa-chevron-down"></i></div>
                    <div class="dropdown-content">
                        <div data-url="{{ url('manager/edit_amenity/' . $amenity->amenityID) }}">Update <i class="fas fa-pen"></i></div>
                        @if($amenity->status == 'Available')
                        <div data-url="{{ url('manager/deactivate_amenity/' . $amenity->amenityID) }}">Deactivate <i class="fas fa-ban" style="color:#d9534f;"></i></div>
                        @else
                        <div data-url="{{ url('manager/activate_amenity/' . $amenity->amenityID) }}">Activate <i class="fas fa-check-circle" style="color:#5cb85c;"></i></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="reason-modal" class="modal">
            <div class="modal-content">
                <h2 id="modal-title">Reason</h2>
                <textarea id="reason-input" placeholder="Enter reason..." rows="4"></textarea>
                <div class="modal-buttons">
                    <button id="cancel-btn">Cancel</button>
                    <button id="submit-reason-btn">Submit</button>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-message">{{ session('success') }}</div>
        @endif
    </div>
</div>

<script>
const addBtn = document.getElementById('add-container');
const cards = document.querySelectorAll('.amenity-card');
const manageBtns = document.querySelectorAll('.manageBtn');
const dropdowns = document.querySelectorAll('.dropdown-content');
const modal = document.getElementById('reason-modal');
const reasonInput = document.getElementById('reason-input');
const cancelBtn = document.getElementById('cancel-btn');
const submitReasonBtn = document.getElementById('submit-reason-btn');

let actionUrl = ''; // store URL to redirect after modal

// Auto-hide existing alerts
document.querySelectorAll('.alert-message').forEach(a=>{
    setTimeout(()=> a.remove(), 2500);
});

// Card click navigation
cards.forEach(card=>{
    card.addEventListener('click', e=>{
        if(!e.target.closest('.manageBtn') && !e.target.closest('.dropdown-content')){
            window.location.href = card.dataset.url;
        }
    });
});

// Add amenity
addBtn.addEventListener('click', ()=>window.location.href = addBtn.dataset.url);

// Dropdown toggle
manageBtns.forEach((btn, index)=>{
    btn.addEventListener('click', e=>{
        e.stopPropagation();
        dropdowns.forEach(d=>d.classList.remove('active'));
        dropdowns[index].classList.add('active');
    });
});

// Close dropdowns on outside click
document.addEventListener('click', ()=>dropdowns.forEach(d=>d.classList.remove('active')));

// Handle dropdown item clicks
document.querySelectorAll('.dropdown-content div[data-url]').forEach(div=>{
    div.addEventListener('click', e=>{
        e.stopPropagation();
        e.preventDefault();
        const url = div.dataset.url;

        if(url.includes('deactivate_amenity') || url.includes('activate_amenity')){
            // Show modal
            modal.style.display = 'flex';
            reasonInput.value = '';
            reasonInput.focus();
            actionUrl = url; // store the action URL
        } else {
            window.location.href = url;
        }
    });
});

// Cancel modal
cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    actionUrl = '';
});

// Submit modal
submitReasonBtn.addEventListener('click', () => {
    const reason = reasonInput.value.trim();
    if(!reason){
        alert('Please enter a reason.');
        return;
    }

    // Hide modal
    modal.style.display = 'none';

    // Optional: show temporary alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert-message';
    alertDiv.innerText = `Reason submitted: ${reason}`;
    document.body.appendChild(alertDiv);
    setTimeout(()=> alertDiv.remove(), 2000);

    // Redirect with reason as query param
    window.location.href = actionUrl + '?reason=' + encodeURIComponent(reason);
});

// Close modal if clicking outside
window.addEventListener('click', e => {
    if(e.target === modal){
        modal.style.display = 'none';
        actionUrl = '';
    }
});

</script>
</body>
</html>
