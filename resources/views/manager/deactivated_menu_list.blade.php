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
      padding: 2rem;
      overflow-y: auto;
    }

    /* Header */
    #layout-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 1.5rem;
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      margin-bottom: 1.5rem;
    }

    #layout-header h1 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #333;
    }

    .right-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Add Button */
    .add-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      background: #f78a21;
      color: #fff;
      padding: 0.6rem 1rem;
      border-radius: 0.75rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .add-container:hover {
      background: #e67d1d;
      transform: translateY(-2px);
    }

    /* Search Bar */
    .search-container form {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 2rem;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
      height: 2.5rem;
    }

    .search-container input {
      border: none;
      padding: 0.6rem 1rem;
      font-size: 0.9rem;
      outline: none;
      width: 200px;
      background: transparent;
    }

    .search-container button {
      border: none;
      background: #000;
      color: #fff;
      padding: 0.6rem 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .search-container button:hover {
      background: #f78a21;
    }

    .reset-btn {
      margin-left: 0.5rem;
      background: #e53935;
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 2rem;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background 0.3s ease;
    }

    .reset-btn:hover {
      background: #c62828;
    }

    /* Filter Bar */
    .navbar {
      display: flex;
      gap: 0.8rem;
      margin-bottom: 1.5rem;
      overflow-x: auto;
    }

    .navbar-item {
      background: #fff;
      color: #333;
      padding: 0.6rem 1.2rem;
      border-radius: 0.75rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .navbar-item.active,
    .navbar-item:hover {
      background: #f78a21;
      color: #fff;
    }

    /* Menu Cards */
    .menu-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
      gap: 1.2rem;
    }

    .menu-card {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
    }

    .menu-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .menu-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .menu-card.unavailable {
      opacity: 0.6;
    }

    .unavailable-overlay {
      position: absolute;
      background: rgba(0,0,0,0.55);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #menu-details {
      padding: 1rem 1.2rem;
    }

    #menu-details h2 {
      font-size: 1rem;
      margin: 0.3rem 0;
    }

    /* Manage */
    .manage-wrapper {
      margin-top: 0.5rem;
      position: relative;
    }

    .manage-btn {
      background: #111;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      transition: all 0.3s ease;
      font-size: 0.85rem;
    }

    .manage-btn:hover {
      background: #f78a21;
      color: black;
    }

    .dropdown-content {
      display: none;
      flex-direction: column;
      position: absolute;
      right: 0;
      top: 2.6rem;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 0.5rem;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      width: 10rem;
      z-index: 100;
    }

    .dropdown-content div {
      padding: 0.6rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .dropdown-content div:hover {
      background: #f78a21;
      color: #fff;
    }

    .dropdown-content.show {
      display: flex;
    }

    /* Alert */
    .alert-message {
      position: fixed;
      bottom: 1.5rem;
      left: 50%;
      transform: translateX(-50%);
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
      0%,90%{opacity:1;}
      100%{opacity:0;display:none;}
    }
  </style>
</head>

<body>
<div id="layout">
  @include('components.sidebar')
  <div id="main-layout">
    <div id="layout-header">
      <h1>Menu List</h1>
      <div class="right-actions">
        <div class="add-container" data-url="{{ url('manager/deactivated_menu_list') }}">
          <i class="fas fa-ban"></i>
          <span>Menu List</span>
        </div>
        <div class="add-container" data-url="{{ url('manager/add_menu') }}">
          <i class="fas fa-plus-circle"></i>
          <span>Add Menu</span>
        </div>
        <div class="search-container">
          <form action="{{ route('manager.search_menu') }}" method="GET">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit"><i class="fa fa-search"></i></button>
            @if(request()->has('search') && request('search') !== '')
              <a href="{{ route('manager.search_menu') }}" class="reset-btn">Clear</a>
            @endif
          </form>
        </div>
      </div>
    </div>

    <div class="navbar">
      <div class="navbar-item active" data-filter="All">All</div>
      @foreach($uniqueMenuTypes as $menutypes)
        <div class="navbar-item" data-filter="{{ $menutypes }}">{{ $menutypes }}</div>
      @endforeach
    </div>

    <div class="menu-container">
      @foreach($menu as $menuitem)
        <div class="menu-card {{ $menuitem->status != 'Available' ? 'unavailable' : '' }}" data-type="{{ $menuitem->itemtype }}">
          <img src="{{ route('menu.image', ['filename' => basename($menuitem->image)]) }}" alt="{{ $menuitem->menuname }}">
          @if($menuitem->status != 'Available')
            <div class="unavailable-overlay">Unavailable</div>
          @endif
          <div id="menu-details">
            <h2><b>{{ $menuitem->menuname }}</b></h2>
            <h2>₱{{ number_format($menuitem->price, 2) }}</h2>
            <small>Status: {{ $menuitem->status }}</small>

            <div class="manage-wrapper">
              <button class="manage-btn">Manage <i class="fas fa-chevron-down"></i></button>
              <div class="dropdown-content">
                <div data-url="{{ url('manager/edit_menu/' . $menuitem->menuID) }}">
                  <span>Update</span><i class="fas fa-pen"></i>
                </div>
                @if($menuitem->status == 'Available')
                  <div data-url="{{ url('manager/deactivate_menu/' . $menuitem->menuID) }}">
                    <span>Deactivate</span><i class="fas fa-ban"></i>
                  </div>
                @else
                  <div data-url="{{ url('manager/activate_menu/' . $menuitem->menuID) }}">
                    <span>Activate</span><i class="fas fa-check-circle"></i>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if(session('success'))
      <div class="alert-message">{{ session('success') }}</div>
    @endif
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  const filterBtns = document.querySelectorAll('.navbar-item');
  const cards = document.querySelectorAll('.menu-card');
  const addBtns = document.querySelectorAll('.add-container');

  addBtns.forEach(btn=>{
    btn.addEventListener('click',()=>window.location.href=btn.dataset.url);
  });

  // Filter
  filterBtns.forEach(btn=>{
    btn.addEventListener('click',()=>{
      filterBtns.forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      const type = btn.dataset.filter.toLowerCase();
      cards.forEach(card=>{
        const ctype = card.dataset.type.toLowerCase();
        card.style.display = (type==='all'||type===ctype)?'':'none';
      });
    });
  });

  // Manage Dropdown
  document.querySelectorAll('.manage-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
      e.stopPropagation();
      const dropdown = btn.nextElementSibling;
      document.querySelectorAll('.dropdown-content').forEach(d=>d.classList.remove('show'));
      dropdown.classList.toggle('show');
    });
  });

  document.addEventListener('click',()=>document.querySelectorAll('.dropdown-content').forEach(d=>d.classList.remove('show')));

  document.querySelectorAll('.dropdown-content div').forEach(item=>{
    item.addEventListener('click',()=>window.location.href=item.dataset.url);
  });
});
</script>
</body>
</html>
