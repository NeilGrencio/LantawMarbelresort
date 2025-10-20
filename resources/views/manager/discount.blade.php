<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Discounts</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<div id="layout">
    @include('components.sidebar')

    <div id="main-layout">
        <!-- Header -->
        <div id="layout-header">
            <h1>Discounts</h1>

            <div class="header-actions">
                <div id="add-container" data-url="{{ url('manager/add_discount') }}">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    <span>Add Discount</span>
                </div>

                <div class="search-container">
                    <form action="{{ route('manager.search_discount') }}" method="GET">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                        @if(request()->has('search') && request('search') !== '')
                            <a href="{{ route('manager.search_discount') }}" class="reset-btn">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table id="discount-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discount as $d)
                    @php
                        $rowClass = $d->status == 'Available' ? 'available' : 'unavailable';
                        $textColor = $d->status == 'Available' ? 'green' : 'red';
                        $type = $d->type ? 'Promo' : 'Discount';
                        $amount = $d->percentamount ?? $d->flatamount;
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $d->discountID }}</td>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->type  }}</td>
                        <td>
                            {{ isset($amount) && $amount >= 1 ? '₱' : '' }} {{ $amount ?? '' }}
                        </td>
                        <td style="color: {{ $textColor }}">{{ $d->status }}</td>
                        <td class="action-toggle">
                            <i class="fa-solid fa-ellipsis-vertical fa-lg"></i>
                            <div class="action-dropdown">
                                <div data-url="{{ url('manager/edit_discount/' . $d->discountID) }}">
                                    <i class="fa-solid fa-pencil"></i> Update
                                </div>
                                @if($d->status == 'Available')
                                    <div style="color:red;" data-url="{{ url('manager/deactivate_discount/' . $d->discountID) }}">
                                        <i class="fa-solid fa-circle-xmark"></i> Deactivate
                                    </div>
                                @else
                                    <div style="color:green;" data-url="{{ url('manager/activate_discount/' . $d->discountID) }}">
                                        <i class="fa-solid fa-circle-check"></i> Activate
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="page-container">{{ $discount->links() }}</div>

            @if(session('success'))
                <div class="alert-message success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-message error">{{ session('error') }}</div>
            @endif
        </div>
    </div>
</div>

<style>
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

#layout-header {
    display:flex; justify-content:space-between; align-items:center;
    background:#fff; padding:1rem; border-radius:0.7rem;
    box-shadow:0 3px 6px rgba(0,0,0,0.1); width:100%;
    flex-wrap:wrap;
}
#layout-header h1 { font-size:1.8rem; color:#333; flex:1; }

.header-actions { display:flex; gap:1rem; flex-wrap:wrap; }

#add-container {
    display:flex; align-items:center; gap:0.5rem; cursor:pointer;
    color:#000000; font-weight:600; padding:0.5rem 1rem;
    border-radius:0.5rem; transition:0.2s; flex-shrink:0;
}
#add-container:hover { background:#FFB74D; transform:translateY(-10px);}

.search-container { display:flex; gap:0.5rem; flex-wrap:wrap; }
.search-container input {
    padding:0.5rem 1rem; border-radius:25px 0 0 25px; border:1px solid #ccc; outline:none;
    width: 200px; min-width:120px; flex-grow:1;
}
.search-container button {
    padding:0.5rem 1rem; border:none; border-radius:0 25px 25px 0;
    background:#000; color:#fff; cursor:pointer;
}
.search-container button:hover { background:#F78A21; }
.search-container .reset-btn {
    padding:0.5rem 1rem; background:#e53935; color:#fff; border-radius:25px; text-decoration:none;
    transition:0.3s;
}
.search-container .reset-btn:hover { background:#b71c1c; }

.table-container {
    background:#fff; padding:1rem; border-radius:1rem;
    box-shadow:0 4px 12px rgba(0,0,0,0.1); width:100%; overflow-x:auto;
}

#discount-table { width:100%; border-collapse:collapse; font-size:0.9rem; min-width:700px; }
thead { background:linear-gradient(90deg,#F78A21,#FFB74D); color:#fff; }
th, td { padding:12px 10px; text-align:center; }
tbody tr { background:#fff; border-bottom:1px solid #eee; transition:0.2s; }
tbody tr:hover { background:#fff3e0; }

.action-toggle { position:relative; cursor:pointer; }
.action-dropdown {
    display:none; flex-direction:column; position:absolute; top:100%; right:0;
    background:#f9f9f9; border:1px solid #ccc; border-radius:0.5rem; min-width:10rem; z-index:10;
    box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:0.5rem;
}
.action-dropdown div {
    display:flex; align-items:center; gap:0.5rem; padding:0.5rem; border-radius:0.5rem; transition:0.2s;
}
.action-dropdown div:hover { background:#F78A21; color:#fff; }

.available { background:white; }
.unavailable { background:#f0f0f0; }

.alert-message {
    position:fixed; bottom:1rem; right:50%; transform:translateX(50%);
    padding:1rem 2rem; border-radius:1rem; box-shadow:0 0 1rem rgba(0,0,0,0.5);
    background:#fff; z-index:1000; font-weight:600;
}
.alert-message.success { border-left:5px solid green; }
.alert-message.error { border-left:5px solid red; }

.pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
}
.page-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid #F78A21;
    border-radius: 50%;
    color: #F78A21;
    text-decoration: none;
}
.page-item.active .page-link {
    background: #F78A21;
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const toggles = document.querySelectorAll('.action-toggle');
    const addDiscount = document.getElementById('add-container');
    const message = document.querySelector('.alert-message');

    if(message) setTimeout(()=>{ message.style.display='none'; }, 3500);
    if(addDiscount) addDiscount.addEventListener('click',()=>{ window.location.href=addDiscount.dataset.url; });

    toggles.forEach(toggle=>{
        toggle.addEventListener('click', function(e){
            document.querySelectorAll('.action-dropdown').forEach(drop=>{
                if(drop!==this.querySelector('.action-dropdown')) drop.style.display='none';
            });
            const dropdown = this.querySelector('.action-dropdown');
            if(dropdown) dropdown.style.display = dropdown.style.display==='flex'?'none':'flex';
            e.stopPropagation();
        });
    });

    document.querySelectorAll('.action-dropdown div[data-url]').forEach(btn=>{
        btn.addEventListener('click',()=>{ window.location.href=btn.dataset.url; });
    });

    document.addEventListener('click', ()=>{ document.querySelectorAll('.action-dropdown').forEach(d=>d.style.display='none'); });
});
</script>
</body>
</html>
