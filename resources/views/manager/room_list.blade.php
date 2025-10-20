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
    /* Highlight sidebar item */
    #rooms { background: rgba(255,255,255,0.15); border-left: 4px solid #ff9100; color: white; }

    body {
      font-family: "Poppins", sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
      color: #222;
      overflow-x: hidden;
    }

    #layout { min-height: 100vh; width: 100%; }
    #main-layout { margin-left: 14rem; margin-top: -4.5rem; width: calc(100% - 14rem); padding: 2rem 2rem; }

    #layout-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1.2rem 2rem; background: white; border-radius: 1rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); margin-bottom: 1.5rem;
    }

    #layout-header h1 { font-size: 1.25rem; font-weight: 600; color: #333; }

    .right-actions { display: flex; align-items: center; gap: 1rem; }
    .action-btn {
      display: flex; align-items: center; gap: 0.5rem;
      cursor: pointer; color: #333; transition: all 0.3s ease;
      padding: 0.5rem 1rem; border-radius: 2rem;
      background: #fff; border: 1px solid #ddd;
    }
    .action-btn:hover { background: #f78a21; color: white; border-color: #f78a21; }

    .search-container form {
      display: flex; align-items: center; border-radius: 2rem;
      background: white; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .search-container input[type="text"] {
      border: none; padding: 0.7rem 1rem; outline: none;
      font-size: 0.9rem; width: 220px; background: transparent;
    }

    .search-container button {
      border: none; background: #000; color: white;
      padding: 0.7rem 1rem; cursor: pointer; transition: 0.3s;
    }
    .search-container button:hover { background: #f78a21; }

    .search-container .reset-btn {
      margin-left: 0.5rem; padding: 0.6rem 1rem; border-radius: 2rem;
      background: #e53935; color: white; text-decoration: none;
      font-size: 0.9rem; transition: 0.3s;
    }
    .search-container .reset-btn:hover { background: #c62828; }

    /* Room cards */
    .room-container { display: flex; flex-direction: column; gap: 1.2rem; }
    .room-card {
      display: flex; align-items: stretch; background: white; border-radius: 1rem;
      overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s; position: relative;
    }
    .room-card:hover { transform: translateY(-3px); box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12); }

    #room-image { width: 280px; height: 200px; overflow: hidden; flex-shrink: 0; }
    #room-image img { width: 100%; height: 100%; object-fit: cover; }

    #room-details { flex: 1; padding: 1rem 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
    #room-details h4 { margin: 0; font-size: 1.1rem; color: #333; }
    #room-details p { color: #555; font-size: 0.9rem; margin: 0.3rem 0; }

    /* Manage dropdown */
    .manage-dropdown-wrapper { position: relative; padding: 1rem; width: 180px; text-align: right; }
    .manageBtn {
      background: #111; color: white; border-radius: 0.5rem;
      padding: 0.6rem 1rem; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 0.3rem;
    }
    .manageBtn:hover { background: #f78a21; color: black; }

    .dropdown-content {
      display: none; flex-direction: column; position: absolute;
      top: 3rem; right: 0; background: white; border: 1px solid #ddd;
      border-radius: 0.5rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      width: 10rem; z-index: 999;
    }
    .dropdown-content.active { display: flex; }
    .dropdown-content div {
      padding: 0.7rem 1rem; cursor: pointer; font-size: 0.9rem; transition: 0.3s;
    }
    .dropdown-content div:hover { background: #f78a21; color: white; }

    /* Modal */
    .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 10000; }
    .modal.active { display: flex; }
    .modal-content {
      background: white; padding: 2rem; border-radius: 1rem; width: 400px;
      text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
    .modal-content h3 { margin-bottom: 1rem; color: #333; }
    .modal-content textarea {
      width: 100%; height: 80px; border: 1px solid #ccc; border-radius: 0.5rem;
      padding: 0.5rem; font-family: inherit; resize: none; margin-bottom: 1rem;
    }
    .modal-actions { display: flex; justify-content: center; gap: 1rem; }
    .modal-actions button {
      border: none; padding: 0.6rem 1.2rem; border-radius: 0.5rem;
      cursor: pointer; transition: 0.3s; font-weight: 500;
    }
    .modal-actions .confirm { background: #f78a21; color: white; }
    .modal-actions .cancel { background: #ccc; color: #222; }
    .modal-actions .confirm:hover { background: #000; }
    .modal-actions .cancel:hover { background: #999; }

    @media (max-width: 900px) {
      .room-card { flex-direction: column; }
      #room-image { width: 100%; height: 180px; }
    }
  </style>
</head>
<body> 
 <body>
  <div id="layout">
    @include('components.sidebar')

    <div id="main-layout">
      @php $count = count($rooms); @endphp

      <div id="layout-header">
        <h1>{{ $viewType === 'rooms' ? 'Room List' : 'Room Type List' }} | Active: {{ $count }}</h1>
        <div style="display:flex; align-items:center; gap:1rem;">
          <div class="action-btn" data-url="{{ url('manager/deactivated_room_list') }}">
            <i class="fas fa-list"></i> Deactivated
          </div>

          <select id="viewSelect">
            <option value="rooms" {{ $viewType === 'rooms' ? 'selected' : '' }}>Show Rooms</option>
            <option value="roomtypes" {{ $viewType === 'roomtypes' ? 'selected' : '' }}>Show Room Types</option>
          </select>

          <div class="action-btn" data-url="{{ url('manager/add_roomtype') }}">
            <i class="fas fa-plus-circle"></i> Create Room Type
          </div>

          @if($viewType === 'roomtypes')
            <div class="action-btn" data-url="{{ url('manager/add_room') }}">
              <i class="fas fa-plus-circle"></i> Assign Rooms
            </div>
          @endif
        </div>
      </div>

      <div class="room-container">
        {{-- ROOM VIEW --}}
        @if($viewType === 'rooms')
          @foreach($rooms as $room)
            <div class="room-card">
              <div id="room-image">
                <img src="{{ $room->image_url }}" alt="Room {{ $room->roomnum }}">
              </div>
              <div id="room-details">
                <h4>Room #{{ $room->roomnum }}</h4>
                <p>Status: {{ $room->status }}</p>
                <p>Room Type: {{ $room->roomtype->roomtype ?? 'N/A' }}</p>
              </div>
              <div class="manage-dropdown-wrapper">
                <div class="manageBtn"><i class="fas fa-gear"></i> Manage</div>
                <div class="dropdown-content">
                  <div data-url="{{ url('manager/update_room/'.$room->roomID) }}">Update</div>
                  <div data-url="{{ url('manager/deactivate_room/'.$room->roomID) }}">Deactivate</div>
                  <div data-url="{{ url('manager/maintenance_room/'.$room->roomID) }}">Maintenance</div>
                </div>
              </div>
            </div>
          @endforeach

        {{-- ROOM TYPE VIEW --}}
        @elseif($viewType === 'roomtypes')
          @foreach($roomtypes as $type)
            <div class="room-card">
              <div id="room-image">
                <img src="{{ $type->image_url }}" alt="{{ $type->roomtype }}">
              </div>
              <div id="room-details">
                <h4>{{ $type->roomtype }}</h4>
                <p>{{ $type->description }}</p>
                <p>Base Capacity: {{ $type->basecapacity }}</p>
                <p>Max Capacity: {{ $type->maxcapacity }}</p>
                <h4>
                  @if($type->flatamount > 0)
                    <span style="text-decoration: line-through; color:#888;">₱ {{ number_format($type->price, 2) }}</span>
                    <span style="color:#f78a21; margin-left:0.5rem;">₱ {{ number_format($type->price - $type->flatamount, 2) }}</span>
                  @else
                    ₱ {{ number_format($type->price, 2) }}
                  @endif
                </h4>
              </div>
              <div class="manage-dropdown-wrapper">
                <div class="manageBtn"><i class="fas fa-gear"></i> Manage</div>
                <div class="dropdown-content">
                  <div data-url="{{ url('manager/edit_roomtype/'.$type->roomtypeID) }}">Edit Room Type</div>
                  <div data-url="{{ url('manager/roomtype_rooms/'.$type->roomtypeID) }}">View Assigned Rooms</div>
                  <div data-url="{{ url('manager/deactivate_roomtype/'.$type->roomtypeID) }}">Deactivate</div>
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>

  {{-- Confirmation Modal --}}
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
      document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', () => window.location.href = btn.dataset.url);
      });

      const manageBtns = document.querySelectorAll('.manageBtn');
      const dropdowns = document.querySelectorAll('.dropdown-content');
      const modal = document.getElementById('confirmationModal');
      const modalTitle = document.getElementById('modalTitle');
      const reasonInput = document.getElementById('reason');
      const confirmBtn = document.getElementById('confirmBtn');
      const cancelBtn = document.getElementById('cancelBtn');
      let currentUrl = "";

      manageBtns.forEach(btn => {
        btn.addEventListener('click', e => {
          e.stopPropagation();
          dropdowns.forEach(d => d.classList.remove('active'));
          btn.nextElementSibling.classList.toggle('active');
        });
      });

      document.addEventListener('click', () => dropdowns.forEach(d => d.classList.remove('active')));

      dropdowns.forEach(dd => {
        dd.querySelectorAll('div[data-url]').forEach(item => {
          item.addEventListener('click', e => {
            e.stopPropagation();
            currentUrl = item.dataset.url;
            if (currentUrl.includes("deactivate") || currentUrl.includes("maintenance")) {
              modalTitle.textContent = "Please confirm this action";
              reasonInput.value = "";
              modal.classList.add('active');
            } else {
              window.location.href = currentUrl;
            }
          });
        });
      });

      confirmBtn.addEventListener('click', () => {
        const reason = reasonInput.value.trim();
        if (!reason) { reasonInput.style.border = "1px solid red"; return; }
        window.location.href = `${currentUrl}?reason=${encodeURIComponent(reason)}`;
      });
      cancelBtn.addEventListener('click', () => modal.classList.remove('active'));

      document.getElementById('viewSelect').addEventListener('change', function() {
        window.location.href = `{{ url('manager/room_list') }}?view=${this.value}`;
      });
    });
  </script>
</body>
</html>