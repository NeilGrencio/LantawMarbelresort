<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1 id="h2">Menu Items</h1>
                <div class="button-group">
                    <div class="search-container">
                        <div id="add-container" data-url={{url('receptionist/orderlist')}}>
                            <i id="add-menu" class="fa-solid fa-burger fa-2x"></i>
                            <small>View Orders</small>
                        </div>
                        <form action="{{ route('receptionist.search_menu') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('receptionist.search_menu') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="navbar">
                <div class="navbar-item" data-filter="All"><h3>All</h3></div>
                @foreach($uniqueMenuTypes as $menutypes)
                    <div class="navbar-item" data-filter="{{ $menutypes}}">
                        <h3>{{$menutypes}}</h3>
                    </div>
                @endforeach
            </div>

            <div class='menu-contianer'>
                <div class="menu-wrapper">
                    @foreach($menu as $menuitem)
                    @php
                        $orderItem = $orders->firstWhere('menu_id', $menuitem->menuID);
                        $initialQuantity = $orderItem ? $orderItem->quantity : 0;
                    @endphp
                    <div class="menu-card" 
                        data-id="{{ $menuitem->menuID }}" 
                        data-type="{{ $menuitem->itemtype }}" 
                        data-price="{{ $menuitem->price }}" 
                        data-name="{{ $menuitem->menuname }}"
                        data-initial="{{ $initialQuantity }}">
                        <div id="img-container">
                            <img src="{{ asset('storage/' . $menuitem->image) }}">
                        </div>
                        <div id="menu-details">
                            <h2>Name: {{$menuitem->menuname}}</h2>
                            <h2>Type: {{$menuitem->itemtype}}</h2>
                            <h2>Price: ₱ {{$menuitem->price}}</h2>
                            <hr/>
                            <div id="manage-container">
                                <h2>Add Item</h2>
                                <div class="amount-wrapper">
                                    <button class="amount-btn sub"><i class="fas fa-circle-minus fa-lg"></i></button>
                                    <h3>{{ $initialQuantity }}</h3>
                                    <button class="amount-btn add"><i class="fas fa-plus-circle fa-lg"></i></button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="receipt-container">
                    <div class="order-receipt">
                        <h2>Order Details</h2>
                        <strong>Selected Items</strong>
                        <div class="selected-items">
                            <p>No items selected</p>
                        </div>
                        <div class="order-button">
                            <button id="btn-clear" type="button">Clear All</button>
                            <button id="orderBtn" type="button">Update Order</button>
                        </div>
                    </div> 
                </div>
            </div>

            <div id="orderModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Enter Booking Information</h2>
                    <form id="orderForm" action="{{route('receptionist.editorder', $bookingID)}}" method="POST">
                        @csrf
                        <label for="guestSearch">Guest</label>
                        <div style="position: relative; width: 100%;">
                            <input 
                                type="text" 
                                id="guestSearch" 
                                name="guestFullName" 
                                class="input" 
                                value="{{ $guestName ?? '' }}" 
                                readonly
                                required
                            >
                        </div>
                        <label for="date">Date and time to Serve</label>
                        <input 
                            type="datetime-local" 
                            id="date" 
                            name="date" 
                            min="{{ \Carbon\Carbon::parse($bookingstart)->format('Y-m-d\TH:i') }}" 
                            max="{{ \Carbon\Carbon::parse($bookingend)->format('Y-m-d\TH:i') }}" 
                            required
                        >
                        <div id="bookingInputContainer"></div>
                        <div id="orderSummary">
                            <h3>Order Summary</h3>
                            <div id="orderItems"></div>
                            <div id="orderTotal"></div>
                        </div>
                        <button type="submit">Confirm Order</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-message"><h2>{{ session('success') }}</h2></div>
            @endif
            @if(session('error'))
                <div class="alert-message"><h2>{{ session('error') }}</h2></div>
            @endif
            @if(session('missingItems'))
                <div class="alert-message"><h2>{{ session('missingItems') }}</h2></div>
            @endif
            
        </div>
    </div>
</body>
<style>
    #menu{color:#F78A21;}   
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
        width: 100%;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:calc(100% - 15rem);
        transition: width 0.3s ease-in-out;
        margin-left:15rem;
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
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
        font-size:.8rem;
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
        justify-content: center;
        align-content: center;
        margin: 15px 0;
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
    .navbar{
        display:flex;
        flex-direction: row;
        width:100%;
        height: 4rem;
        gap:1rem;
        padding:1rem;
        justify-content:center;
        align-items:center;
        overflow-x:auto;
        overflow-y: hidden;
    }

    .navbar-item{
        display: flex;
        height:2rem;    
        width:5rem;
        background:#ffffff;
        border-radius:.4rem;
        border:1px solid black;
        font-size:.7rem;
        align-items:center;
        justify-content:center;
        box-shadow:.1rem .1rem 0 black;
        transition:all .3s ease;
    }
    .navbar-item:hover{
        background:rgb(53, 53, 53);
        color:white;
        cursor:pointer;
    }
    .menu-contianer{
        display:flex;
        flex-direction:row;
        gap:.5rem;
        width:100%;
        height: 100%;
        overflow-y: auto;
        justify-content:center;
    }
    .menu-wrapper{
        display:flex;
        flex-direction:row;
        flex-wrap: wrap;
        gap:1rem;
        padding:1rem;
        width:100%;
        height:100%;
        overflow-y:auto;
        justify-content:center;
    }
    .receipt-container{
        display:flex;
        flex-direction:column;
        width:25%;
        height:100%;
        position:relative;
        align-items:center;
        justify-content:center;
    }
    #manage-container{
        display:flex;
        flex-direction:column;
        width:100%;
        height:3rem;
        justify-content: space-evenly;
        margin-top:auto;
        bottom:1;
    }
    .amount-wrapper{
        display: flex;
        height:100%;
        width:100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding:.5rem;
        font-size:.9rem;
    }
    .amount-btn {
        border-radius: 50%;
        background: none;
        color: black;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }
    .amount-btn:hover{
        color:orange;
        cursor:pointer;
        transform:scale(1.2);
    }
    .menu-card{
        height:15rem;
        width:10rem;
        display:flex;
        flex-direction:column;
        background:white;
        border-radius:.5rem;
        padding: .5rem;
        font-size:.4rem;
        box-shadow: .1rem .1rem 0 black;
        border:solid 1px black;
    }
    .menu-card img{
        height:40%;
        width:100%;
        border-top-right-radius:1rem;
        border-top-left-radius:1rem;
        object-fit:cover;
    }
    .drop-down{
        display:none;
        flex-direction:column;
        width:10rem;
        position: absolute;
        background:rgb(182, 182, 182);
        padding:.5rem;
        z-index: 1;
        gap:.5rem;
        border-radius:.5rem;
        align-items: center;
        justify-content: center;
        margin-left:4rem;
    }
    .drop-down div{
        display: flex;
        flex-direction: row;
        width:100%;
        align-items: center;
        justify-content: space-evenly;
        background: white;
        border-radius:.5rem;
        cursor:pointer;
        transition:all .3s ease;
    }
    .drop-down div:hover{
        background:grey;
        color:white;
    }
    .navbar-item.active {
        background-color: rgb(150, 55, 0); 
        color: white;
    }
    .order-receipt{
        display: flex;
        flex-direction: column;
        height:100%;
        width:20rem;
        position:absolute;
        background:white;
        border-radius:.5rem;
        border:1px solid black;
        right:1;
        margin-left:auto;
        align-items: center;
        padding:.5rem;
    }
    .selected-items{
        width:100%;
        display:flex;
        flex-direction: column;
        align-items:start;
        justify-content: space-between;
    }
    .order-button{
        display:flex;
        flex-direction: row;
        width:100%;
        gap:.5rem;
        margin-top:auto;
        bottom:1rem;
        z-index:999;
        align-items:center;
        justify-content: center;
        height:3rem;
    }
    .order-button button{
        height:2rem;
        width:5rem;
        background:black;
        color:white;
        border-radius:.5rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        transition:all .2s ease;
    }
    .order-button #submit{
        background:orange;
        color:black;
    }
    .order-button button:hover{
        transform:scale(1.1);
        cursor:pointer
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 12px;
        width: 400px;
        max-width: 90%;
        box-shadow: 1px 1px 0 rgba(0,0,0);
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
    }
    .modal-content input, 
    .modal-content select, 
    .modal-content button {
        width: 100%;
        padding: 8px;
        margin: 6px 0;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    .modal-content button {
        background: #f87538;
        color: white;
        border: none;
        cursor: pointer;
    }
    .modal-content button:hover {
        background: #fe7f00;
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('date');

    // Use JSON encoding to safely pass Blade variables into JS
    const bookingStart = {!! json_encode($bookingstart) !!};
    const bookingEnd = {!! json_encode($bookingend) !!};

    if (bookingStart && bookingEnd) {
        const start = new Date(bookingStart);
        const end = new Date(bookingEnd);

        function formatDateTimeLocal(date) {
            const pad = (n) => n.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        dateInput.min = formatDateTimeLocal(start);
        dateInput.max = formatDateTimeLocal(end);
        dateInput.value = formatDateTimeLocal(start);
    }

    let cart = {};

    document.querySelectorAll('.menu-card').forEach(card => {
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const quantity = parseInt(card.dataset.initial) || 0;
        if (quantity > 0) cart[id] = { id, name, price, quantity };
        card.querySelector('h3').textContent = quantity;
    });

    function updateReceipt() {
        const receipt = document.querySelector('.selected-items');
        receipt.innerHTML = '';
        const items = Object.values(cart);
        if (!items.length) {
            receipt.innerHTML = '<p>No items selected</p>';
            document.getElementById('orderItems').innerHTML = '';
            document.getElementById('orderTotal').innerHTML = '';
            return;
        }

        let total = 0;
        items.forEach(i => {
            const itemTotal = i.price * i.quantity;
            total += itemTotal;
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.justifyContent = 'space-between';
            div.innerHTML = `<span>${i.name}</span><span>x${i.quantity}</span><span>₱${itemTotal.toFixed(2)}</span>`;
            receipt.appendChild(div);
        });

        const totalDiv = document.createElement('div');
        totalDiv.style.display = 'flex';
        totalDiv.style.justifyContent = 'space-between';
        totalDiv.style.fontWeight = 'bold';
        totalDiv.innerHTML = `<span>Total:</span><span>₱${total.toFixed(2)}</span>`;
        receipt.appendChild(document.createElement('hr'));
        receipt.appendChild(totalDiv);

        const modalItems = document.getElementById('orderItems');
        const modalTotal = document.getElementById('orderTotal');
        modalItems.innerHTML = '';
        items.forEach(i => {
            const div = document.createElement('div');
            div.innerHTML = `${i.name} x${i.quantity} = ₱${(i.price*i.quantity).toFixed(2)}`;
            modalItems.appendChild(div);
        });
        modalTotal.innerHTML = `<strong>Total: ₱${total.toFixed(2)}</strong>`;
    }

    updateReceipt();

    document.querySelectorAll('.menu-card').forEach(card => {
        const id = card.dataset.id;
        const qtyText = card.querySelector('h3');

        card.querySelector('.amount-btn.add').addEventListener('click', () => {
            let qty = parseInt(qtyText.textContent) || 0;
            qty++;
            qtyText.textContent = qty;
            cart[id] = { id, name: card.dataset.name, price: parseFloat(card.dataset.price), quantity: qty };
            updateReceipt();
        });

        card.querySelector('.amount-btn.sub').addEventListener('click', () => {
            let qty = parseInt(qtyText.textContent) || 0;
            qty = Math.max(0, qty - 1);
            qtyText.textContent = qty;
            if (qty === 0) delete cart[id];
            else cart[id] = { id, name: card.dataset.name, price: parseFloat(card.dataset.price), quantity: qty };
            updateReceipt();
        });
    });

    document.getElementById('btn-clear').addEventListener('click', () => {
        cart = {};
        document.querySelectorAll('.menu-card h3').forEach(h => h.textContent = '0');
        updateReceipt();
    });

    document.getElementById('orderForm').addEventListener('submit', e => {
        const existingInputs = e.target.querySelectorAll('input[name="order[]"], input[name="quantity[]"]');
        existingInputs.forEach(input => input.remove());

        Object.keys(cart).forEach((id) => {
            const item = cart[id];

            const hiddenOrder = document.createElement('input');
            hiddenOrder.type = 'hidden';
            hiddenOrder.name = 'order[]';
            hiddenOrder.value = item.id;
            e.target.appendChild(hiddenOrder);

            const hiddenQty = document.createElement('input');
            hiddenQty.type = 'hidden';
            hiddenQty.name = 'quantity[]';
            hiddenQty.value = item.quantity;
            e.target.appendChild(hiddenQty);
        });
    });

    const modal = document.getElementById('orderModal');
    const orderBtn = document.getElementById('orderBtn');
    const closeBtn = modal.querySelector('.close');

    orderBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target == modal) modal.style.display = 'none';
    });
});

</script>
</html>