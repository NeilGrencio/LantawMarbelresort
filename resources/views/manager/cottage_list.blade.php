<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Kiddy Pool Cottages</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    #cottages {
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
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    #layout-header h1 {
        font-size: 2rem;
        color: #F78A21;
        font-weight: 700;
    }

    .button-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        background: #fff;
        padding: 0.7rem 1rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    #add-container:hover { background: #F78A21; color: #fff; }

    .add-container h3 { font-size: 1rem; font-weight: 600; }

    .search-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .search-container input[type="text"] {
        padding: 0.5rem 1rem;
        border-radius: 25px 0 0 25px;
        border: 1px solid #ccc;
        outline: none;
        width: 200px;
        font-size: 0.95rem;
    }
    .search-container button {
        padding: 0.5rem 1rem;
        border-radius: 0 25px 25px 0;
        border: none;
        background-color: #000;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s;
    }
    .search-container button:hover { background-color: #F78A21; }

    .search-container .reset-btn {
        padding: 0.5rem 1rem;
        background: #e53935;
        color: #fff;
        text-decoration: none;
        border-radius: 25px;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    .search-container .reset-btn:hover { background: #b71c1c; }

    /* Cottage Cards */
    .cottage-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
    }

    .cottage-card {
        background: #fff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .cottage-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }

    .cottage-card.deactivated {
        opacity: 0.6;
        filter: grayscale(60%);
        pointer-events: none;
    }

    .cottage-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .cottage-info {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .cottage-info h1 { font-size: 1rem; font-weight: 600; color: #000000; }
    .cottage-info h2 { font-size: 0.85rem; color: #555; }

    .cottage-actions {
        display: flex;
        gap: 0.5rem;
        padding: 0.75rem 1rem 1rem 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .cottage-actions button {
        flex: 1 1 40%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.75rem;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .update-btn { background: #000000; color: #fff; }
    .update-btn:hover { background: #34373a; }

    .status-btn { background: #a0140a; color: #fff; }
    .status-btn:hover { background: #d32f2f; }

    .maintenance-btn { background: #ff9800; color: #fff; }
    .maintenance-btn:hover { background: #f57c00; }

    /* Alerts */
    .alert-message {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 1rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        font-weight: 600;
        z-index: 1000;
        text-align: center;
        min-width: 250px;
    }

    @media (max-width: 768px) {
        #main-layout { margin-left: 0; padding: 1rem; }
        .search-container input[type="text"] { width: 150px; }
        .cottage-actions { flex-direction: column; gap: 0.5rem; }
        .cottage-actions button { flex: 1; }
    }
    .modal {
        display: none; 
        position: fixed; 
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        justify-content: center; 
        align-items: center; 
        z-index: 2000;
    }

    /* Modal box */
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
        margin-bottom: 0.5rem;
    }

    .modal-content textarea {
        resize: none;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #ccc;
        width: 100%;
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .modal-buttons button {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
    }

    #cancel-btn { background: #ccc; }
    #submit-reason-btn { background: #F78A21; color: #fff; }
</style>
</head>
<body>
<div id="layout">
    @include('components.sidebar')
    <div id="main-layout">

        <!-- Header -->
        <div id="layout-header">
            <h1>Kiddy Pool Cottages</h1>
            <div class="button-group">
                <div id="add-container" data-url="{{ url('manager/add_cottages') }}" class="add-container">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    <h3>Add Cottage</h3>
                </div>
                <div class="search-container">
                    <form action="{{ route('manager.search_cottage') }}" method="GET">
                        <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                        @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('manager.search_cottage') }}" class="reset-btn">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Cottage Cards -->
        <div class="cottage-layout">
            @foreach($cottage as $cottages)
           <div class="cottage-card @if($cottages->status != 'Available') deactivated @endif">
                <img src="{{ route('cottage.image', ['filename' => basename($cottages->image)]) }}" alt="{{ $cottages->cottagename }}">
                <div class="cottage-info">
                    <h1>{{ $cottages->cottagename }}</h1>
                    <h2>Capacity: {{ $cottages->capacity }}</h2>
                    <h2>Price: ₱ {{ $cottages->price }}</h2>
                    <h2>Status: {{ $cottages->status }}</h2>
                </div>
                <div class="cottage-actions">
                    <button class="update-btn" data-url="{{ url('manager/edit_cottage/' . $cottages->cottageID) }}">UPDATE <i class="fa-solid fa-pencil"></i></button>
                    @if($cottages->status == 'Available')
                        <button class="status-btn" data-url="{{ url('manager/deactivate_cottage/' . $cottages->cottageID) }}">DEACTIVATE <i class="fa-solid fa-times-circle"></i></button>
                    @else
                        <button class="status-btn" data-url="{{ url('manager/activate_cottage/' . $cottages->cottageID) }}">ACTIVATE <i class="fas fa-circle"></i></button>
                    @endif
                    <button class="maintenance-btn" data-url="{{ url('manager/maintenance_cottage/' . $cottages->cottageID) }}">MAINTENANCE <i class="fa-solid fa-wrench"></i></button>
                </div>
            </div>

            @endforeach
        </div>

        <div id="reason-modal" class="modal">
            <div class="modal-content">
                <h2>Provide Reason</h2>
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
        @if(session('error'))
            <div class="alert-message">{{ session('error') }}</div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const reasonModal = document.getElementById('reason-modal');
    const reasonInput = document.getElementById('reason-input');
    const cancelBtn = document.getElementById('cancel-btn');
    const submitBtn = document.getElementById('submit-reason-btn');

    const updateButtons = document.querySelectorAll('.update-btn');

    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get URL from data attribute
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });

    const addCottageBtn = document.getElementById('add-container');

    if (addCottageBtn) {
        // Redirect to add cottage page on click
        addCottageBtn.addEventListener('click', () => {
            const url = addCottageBtn.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    }

    let actionUrl = '';

    // Handle Deactivate & Maintenance Buttons
    const requestButtons = document.querySelectorAll('.status-btn, .maintenance-btn');
    requestButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            actionUrl = this.dataset.url;
            reasonInput.value = '';
            reasonModal.style.display = 'flex';
            reasonInput.focus();
        });
    });

    // Cancel modal
    cancelBtn.addEventListener('click', () => {
        reasonModal.style.display = 'none';
        actionUrl = '';
    });

    // Submit modal
    submitBtn.addEventListener('click', () => {
        const reason = reasonInput.value.trim();
        if (!reason) {
            alert('Please enter a reason.');
            return;
        }

        // Hide modal
        reasonModal.style.display = 'none';

        // Redirect with reason as query param (you can handle via POST/AJAX too)
        window.location.href = `${actionUrl}?reason=${encodeURIComponent(reason)}`;
    });

    // Close modal when clicking outside content
    window.addEventListener('click', (e) => {
        if (e.target === reasonModal) {
            reasonModal.style.display = 'none';
            actionUrl = '';
        }
    });
});
</script>

</body>
</html>
