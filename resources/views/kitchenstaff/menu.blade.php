<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort | Kitchen</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
/* ===== Base ===== */
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
html, body { width: 100%; height: 100%; background: #f8f2e6; color: #333; }

/* ===== Layout ===== */
#layout { display: flex; flex-direction: column; height: 100vh; }
#layout-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background: #F78A21; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 0 0 12px 12px; }
#layout-header h2 { font-size: 1.4rem; }
.nav-links { display: flex; gap: 1rem; align-items: center; }
.nav-links div { display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.2s ease; }
.nav-links div:hover { transform: scale(1.1) rotate(-5deg); color: #fff; }

/* ===== Navbar filter ===== */
.navbar { display: flex; gap: 0.8rem; justify-content: center; padding: 1rem; overflow-x: auto; max-width:100vw;}
.navbar-item { padding: 0.5rem 1rem; background: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; transition: 0.3s ease; text-align: center; white-space: nowrap; font-weight: 600; font-size: 0.85rem; }
.navbar-item:hover, .navbar-item.active { background: #F78A21; color: white; transform: scale(1.05); }

/* ===== Menu container ===== */
.menu-contianer { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; padding: 1rem; overflow-y: auto; }
.menu-card { width: 12rem; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: transform 0.2s ease, box-shadow 0.2s ease; display: flex; flex-direction: column; }
.menu-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }

/* ===== Menu image ===== */
#img-container { position: relative; width: 100%; height: 8rem; overflow: hidden; border-top-left-radius: 12px; border-top-right-radius: 12px; }
#img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.menu-card:hover img { transform: scale(1.05); }
.unavailable-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(72,72,72,0.7); display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 1.2rem; text-transform: uppercase; }

/* ===== Menu details ===== */
#menu-details { padding: 0.5rem 0.7rem; display: flex; flex-direction: column; gap: 0.2rem; }
#menu-details h2 { font-size: 0.8rem; line-height: 1.1rem; font-weight: 600; color: #333; }
#manage-container { display: flex; justify-content: space-between; align-items: center; margin-top: auto; font-size: 0.75rem; }
#manage-button { padding: 0.25rem 0.5rem; background: #333; color: white; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
#manage-button:hover { background: #F78A21; color: white; }

/* ===== Alerts ===== */
.alert-message { position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%); background: #fff; padding: 1rem 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); z-index: 1000; text-align: center; font-weight: 600; animation: fadeIn 0.5s; }
@keyframes fadeIn { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

/* ===== Unavailable card ===== */
.menu-card.unavailable { opacity: 0.5; pointer-events: none; }

/* ===== Scrollbar styling ===== */
.menu-contianer::-webkit-scrollbar { width: 8px; height: 8px; }
.menu-contianer::-webkit-scrollbar-thumb { background: #F78A21; border-radius: 4px; }
.menu-contianer::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); }

</style>
</head>
<body>
<div id="layout">
    <!-- Header -->
    <div id="layout-header">
        <h2>Welcome Kitchen Staff</h2>
        <div class="nav-links">
            <div id="menu-section" data-url="{{ route('kitchen.dashboard') }}">
                <i class="fa-solid fa-utensils fa-2x"></i>
                <strong>Dashboard</strong>
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="all:unset; cursor:pointer; display:flex; align-items:center;">
                        <i class="fa-solid fa-power-off fa-2x"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Navbar Filters -->
    <div class="navbar">
        <div class="navbar-item active" data-filter="All"><h3>All</h3></div>
        @foreach($uniqueMenuTypes as $menutypes)
            <div class="navbar-item" data-filter="{{ $menutypes}}"><h3>{{ $menutypes }}</h3></div>
        @endforeach
    </div>

    <!-- Menu Cards -->
    <div class='menu-contianer'>
        @foreach($menu as $menuitem)
        <div class="menu-card {{ $menuitem->status !== 'Available' ? 'unavailable' : '' }}" data-type="{{ $menuitem->itemtype }}">
            <div id="img-container">
                <img src="{{ route('menu.image', ['filename' => basename($menuitem->image)]) }}" alt="{{ $menuitem->menuname }}">
                @if($menuitem->status !== 'Available')
                <div class="unavailable-overlay">Unavailable</div>
                @endif
            </div>
            <div id="menu-details">
                <h2>Name: {{$menuitem->menuname}}</h2>
                <h2>Type: {{$menuitem->itemtype}}</h2>
                <h2>Price: ₱{{$menuitem->price}}</h2>
                <div id="manage-container">
                    <h2>Status: {{$menuitem->status}}</h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-message">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-message">{{ session('error') }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter logic
    const filterButtons = document.querySelectorAll('.navbar-item');
    const menuCards = document.querySelectorAll('.menu-card');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter.toLowerCase();
            menuCards.forEach(card => {
                const type = card.dataset.type.toLowerCase();
                card.style.display = (filter === 'all' || type === filter) ? 'flex' : 'none';
            });
        });
    });

    // Menu section navigation
    document.getElementById('menu-section').addEventListener('click', function(){
        window.location.href = this.dataset.url;
    });

    // Hide alerts
    document.querySelectorAll('.alert-message').forEach(msg => {
        setTimeout(()=>msg.style.display='none', 3000);
    });
});
</script>
</body>
</html>
