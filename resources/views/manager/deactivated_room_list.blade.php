<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
  <title>Lantaw-Marbel Resort</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    #rooms {
        background: rgba(255,255,255,0.15);
        border-left: 4px solid #ff9100;
        color: white;
    }
    body {
      font-family: "Poppins", sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
      color: #222;
      overflow-x: hidden;
    }
    #layout {
      min-height: 100vh;
      width: 100%;
    }
    #main-layout {
      margin-left: 14rem;
      margin-top: -4.5rem;
      width: calc(100% - 14rem);
      padding: 2rem 2rem;
      overflow-y: auto;
    }
    #layout-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.2rem 2rem;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      margin-bottom: 1.5rem;
    }
    #layout-header h1 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #333;
    }
    .right-actions {
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
      transition: all 0.3s ease;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      background: #fff;
      border: 1px solid #ddd;
    }
    #add-container:hover {
      background: #f78a21;
      color: white;
      border-color: #f78a21;
    }
    .search-container form {
      display: flex;
      align-items: center;
      border-radius: 2rem;
      overflow: hidden;
      background: white;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }
    .search-container input[type="text"] {
      border: none;
      padding: 0.7rem 1rem;
      outline: none;
      font-size: 0.9rem;
      width: 220px;
      background: transparent;
    }
    .search-container button {
      border: none;
      background: #000;
      color: white;
      padding: 0.7rem 1rem;
      cursor: pointer;
      transition: 0.3s ease;
    }
    .search-container button:hover {
      background: #f78a21;
    }
    .search-container .reset-btn {
      margin-left: 0.5rem;
      padding: 0.6rem 1rem;
      border-radius: 2rem;
      background: #e53935;
      color: white;
      text-decoration: none;
      font-size: 0.9rem;
      transition: 0.3s ease;
    }
    .search-container .reset-btn:hover {
      background: #c62828;
    }
    .room-container {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }
    .room-card {
      display: flex;
      align-items: stretch;
      background: white;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
    }
    .room-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    }
    .room-card.deactivated {
      background: #eeeeee;
      color: #9c9c9c;
    }
    .room-card.booked {
      background: linear-gradient(to right, #ffe3c5, #ffffff);
    }
    #room-image {
      width: 280px;
      height: 200px;
      overflow: hidden;
      flex-shrink: 0;
    }
    #room-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    #room-details {
      flex: 1;
      padding: 1rem 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    #room-details h4 {
      margin: 0;
      font-size: 1.1rem;
      color: #333;
    }
    #room-details p {
      color: #555;
      font-size: 0.9rem;
      margin: 0.3rem 0;
      line-height: 1.4;
    }
    .manage-dropdown-wrapper {
      position: relative;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-end;
      width: 180px;
      background: transparent;
      z-index: 100;
    }
    .manageBtn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      background: #111;
      color: white;
      border-radius: 0.5rem;
      padding: 0.6rem 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      width: fit-content;
      z-index: 100;
    }
    .manageBtn:hover {
      background: #f78a21;
      color: black;
    }
    .dropdown-content {
      display: none;
      flex-direction: column;
      position: absolute;
      top: 5rem;
      right: 1rem;
      background: white;
      border: 1px solid #ddd;
      border-radius: 0.5rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      width: 10rem;
      z-index: 9999;
    }
    .dropdown-content.active {
      display: flex;
    }
    .dropdown-content div {
      padding: 0.7rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .dropdown-content div:hover {
      background: #f78a21;
      color: white;
    }
    .alert-message {
      position: fixed;
      bottom: 1.5rem;
      right: 50%;
      transform: translateX(50%);
      background: white;
      padding: 1rem 2rem;
      border-radius: 0.75rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      font-weight: 500;
      color: #333;
      z-index: 999;
      animation: fadeOut 2.5s forwards;
    }
    @keyframes fadeOut {
      0% { opacity: 1; }
      90% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }
    .modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 10000;
    }
    .modal.active {
      display: flex;
    }
    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      width: 400px;
      text-align: center;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
      animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-10px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .modal-content h3 {
      margin-bottom: 1rem;
      color: #333;
    }
    .modal-content textarea {
      width: 100%;
      height: 80px;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
      padding: 0.5rem;
      font-family: inherit;
      resize: none;
      margin-bottom: 1rem;
    }
    .modal-actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
    }
    .modal-actions button {
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 500;
    }
    .modal-actions .confirm {
      background: #f78a21;
      color: white;
    }
    .modal-actions .cancel {
      background: #ccc;
      color: #222;
    }
    .modal-actions .confirm:hover {
      background: #000;
    }
    .modal-actions .cancel:hover {
      background: #999;
    }
    @media (max-width: 900px) {
      .room-card {flex-direction: column;}
      #room-image {width: 100%; height: 180px;}
      #room-details {padding: 1rem;}
    }
  </style>
</head>
<body>
  <div id="layout">
    @include('components.sidebar')
    <div id="main-layout">
      @php $count = count($rooms) @endphp
      <div id="layout-header">
        <h1>Room List | Active Rooms: {{ $count }}</h1>
        <div class="right-actions">
            <div class="action-btn" data-url="{{ url('manager/room_list') }}">
                <i class="fas fa-plus-circle fa-lg"></i>
                <span> Rooms List</span>
            </div>
          <div class="search-container">
            <form action="{{ route('manager.search_room') }}" method="GET">
              <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
              <button type="submit"><i class="fa fa-search"></i></button>
              @if(request()->has('search') && request('search') !== '')
                <a href="{{ route('manager.search_room') }}" class="reset-btn">Clear</a>
              @endif
            </form>
          </div>
        </div>
      </div>
      <div class="room-container">
        @foreach($rooms as $room)
        @php
          $roomCardClass = match($room->status) {
              'Unavailable', 'Maintenance' => 'room-card deactivated',
              'Booked' => 'room-card booked',
              default => 'room-card'
          };
        @endphp
        <div class="{{ $roomCardClass }}">
          <div id="room-image">
            <img src="{{ route('room.image', ['filename' => basename($room->image)]) }}" alt="{{ $room->roomnum }}">
          </div>
          <div id="room-details">
            <h4>Room {{ $room->roomnum }}</h4>
            <h5>Room Type {{ $room->roomtype }}</h5>
            <p>{{ $room->description }}</p>
            <h5>Base Capacity {{ $room->basecapacity }}</h5>
            <h5>Room Capacity {{ $room->maxcapacity }}</h5>
           <h4>
                @php
                    $roomDiscount = $discount->firstWhere('discountID', $room->discountID);
                    $discountAmount = $roomDiscount->flatamount ?? 0;
                @endphp

                @if($discountAmount > 0)
                    <span style="text-decoration: line-through; color:#888;">
                        ₱ {{ number_format($room->price, 2) }}
                    </span>
                    <span style="color:#F78A21; margin-left:0.5rem;">
                        ₱ {{ number_format($room->price - $discountAmount, 2) }}
                    </span>
                @else
                    ₱ {{ number_format($room->price, 2) }}
                @endif
            </h4>


          </div>
            <div class="manage-dropdown-wrapper">
                <span>{{ $room->status == 'Maintenance' ? 'Currently under Maintenance' : 'Status: ' . $room->status }}</span>
                <div class="manageBtn">Manage <i class="fas fa-chevron-down"></i></div>
                <div class="dropdown-content">
                    @if ($room->status == 'Unavailable' || $room->status == 'Maintenance')
                    <div data-url="{{ url('manager/activate_room/' . $room->roomID) }}"><span>Activate</span><i class="fas fa-check-circle"></i></div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="modal" id="confirmationModal">
    <div class="modal-content">
      <h3 id="modalTitle"></h3>
      <textarea id="reason" placeholder="Enter reason for this action..."></textarea>
      <div class="modal-actions">
        <button class="confirm" id="confirmBtn">Confirm</button>
        <button class="cancel" id="cancelBtn">Cancel</button>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert-message">{{ session('success') }}</div>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', () => {
    const actionBtns = document.querySelectorAll('.action-btn');

    actionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });
    const manageBtns = document.querySelectorAll('.manageBtn');
    const dropdowns = document.querySelectorAll('.dropdown-content');
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const reasonInput = document.getElementById('reason');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    let currentUrl = "";

    // Toggle dropdowns
    manageBtns.forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            dropdowns.forEach(d => d.classList.remove('active'));
            const dd = btn.parentElement.querySelector('.dropdown-content');
            dd.classList.toggle('active');
        });
    });

    // Close dropdowns if clicking outside
    document.addEventListener('click', () => dropdowns.forEach(d => d.classList.remove('active')));

    // Handle Activate button click
    dropdowns.forEach(dd => {
        dd.querySelectorAll('div[data-url]').forEach(item => {
            item.addEventListener('click', e => {
                e.stopPropagation();
                currentUrl = item.dataset.url;

                // Only Activate triggers modal
                modalTitle.textContent = "Activate this room?";
                reasonInput.value = "";
                modal.classList.add('active');
            });
        });
    });

    // Confirm modal
    confirmBtn.addEventListener('click', () => {
        const reason = reasonInput.value.trim();
        if (!reason) {
            reasonInput.style.border = "1px solid red";
            return;
        }
        window.location.href = `${currentUrl}?reason=${encodeURIComponent(reason)}`;
    });

    cancelBtn.addEventListener('click', () => modal.classList.remove('active'));
});

  </script>
</body>
</html>