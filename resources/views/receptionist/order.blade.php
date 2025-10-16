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
                        <div class="menu-card" data-id="{{ $menuitem->menuID }}" data-type="{{ $menuitem->itemtype }}" data-price="{{ $menuitem->price }}" data-name="{{ $menuitem->menuname }}">
                            <div id="img-container">
                                <img src="{{ $menuitem->image_url }}" 
                                     alt="{{ $menuitem->menuname }}" >
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
                                        <h3>0</h3>
                                        <button class="amount-btn add"><i class="fas fa-plus-circle fa-lg"></i></button>
                                    </div>
                                </div>
                                <div class="drop-down">
                                    <div data-url="{{url('manager/edit_menu/' . $menuitem->menuID)}}">
                                        <h2>Update</h2>
                                        <i class="fa-solid fa-pencil fa-lg"></i>
                                    </div>
                                    @if($menuitem->status == 'Available')
                                        <div data-url="{{url('manager/deactivate_menu/' . $menuitem->menuID)}}">
                                            <h2>Deactivate</h2>
                                            <i class="fa-solid fa-times-circle fa-lg" style="color:red;"></i>
                                        </div>
                                    @else
                                        <div data-url="{{url('manager/activate_menu/' . $menuitem->menuID)}}">
                                            <h2>Activate</h2>
                                            <i class="fas fa-circle fa-lg" style="color:green;"></i>
                                        </div>
                                    @endif
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
                            <button id="orderBtn" type="button">Place Order</button>
                        </div>
                    </div> 
                </div>
            </div>

            <div id="orderModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Enter Booking Information</h2>
                    <form id="orderForm" action="{{route('receptionist.submitorder')}}" method="POST">
                        @csrf
                        <label for="guestSearch">Available Guest</label>
                        <div style="position: relative; width: 100%;">
                            <input type="text" id="guestSearch" name="guest_name" placeholder="Type guest name..." class="input" autocomplete="off" required>
                            <select id="firstname" name="guest_select" size="5" style="position: absolute; width: 100%; height:auto; top: 100%; left: 0; display: none; z-index: 10;">
                                @foreach($guest as $g)
                                    <option value="{{ $g->firstname . ' ' . $g->lastname }}">
                                        {{ $g->firstname . ' ' . $g->lastname }}
                                    </option>
                                @endforeach
                                @if($guest->isEmpty())
                                <option value="" disabled>No Available Guest</option>
                                @endif
                            </select>
                        </div>
                        <label for="date">Date and time to Serve</label>
                        <input type="datetime-local" id="date" name="date" min="1 week from now" max="length of booking" autocomplete="off" required>
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
        </div>
    </div>
</body>
<style>
    * {
            box-sizing: border-box;
        }
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        #layout {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        #main-layout {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            margin-left: 12rem;
            width: calc(100% - 12rem);
            overflow: hidden;
        }
        #layout-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: .5;
            padding-left:1rem;
            padding-right:1rem;
            background: white;
            border-radius: .7rem;
            border: 1px solid black;
            box-shadow: .1rem .1rem 0 black;
            font-size: .8rem;
            flex-shrink: 0;
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
            margin-left: 0.5rem;
        }
        .search-container {
            display: flex;
            align-items: center;
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
            background-color: #000;
            color: white;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-container button:hover {
            background-color: #F78A21;
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
    .navbar {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 4rem;
        gap: 1rem;
        padding: 1rem;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        width: 100%;
        justify-content: flex-start;
        box-sizing: border-box;
        flex-shrink: 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,0.3) transparent;
    }
    .navbar::-webkit-scrollbar {
        height: 6px;
    }
    .navbar::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.3);
        border-radius: 3px;
    }

    .navbar-item{
        display: flex;
        height:2rem;    
        width:auto;
        padding:.5rem;
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
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem;
        width: 100%;
        overflow-y: auto;
        justify-content: center;
        box-sizing: border-box;
    }
    .menu-wrapper{
        display:flex;
        flex-direction:row;
        flex-wrap: wrap;
        gap:1rem;
        padding:1rem;
        width:70%;
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
        height:5rem;
        width: 100%;
        border-top-right-radius: 1rem;
        border-top-left-radius: 1rem;
        object-fit: cover;
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
        const guestSearch = document.getElementById('guestSearch');
        const selectBox = document.getElementById('firstname');
        const addContainer = document.getElementById('add-container');
        const orderBtn = document.getElementById('orderBtn');
        const btnClear = document.getElementById('btn-clear');
        const modal = document.getElementById('orderModal');
        const closeBtn = document.querySelector('.close');
        const orderItemsContainer = document.getElementById('orderItems');
        const orderTotalContainer = document.getElementById('orderTotal');
        const bookingInputContainer = document.getElementById('bookingInputContainer');

        let cart = {};
        let guestBookings = {};

        @foreach($guest as $g)
            guestBookings["{{ $g->firstname }} {{ $g->lastname }}"] = {
                start: new Date("{{ $g->bookingstart }}"),
                end: new Date("{{ $g->bookingend }}")
            };
        @endforeach

        function setDateLimits(guestName) {
            const today = new Date();
            today.setHours(0,0,0,0);

            if (!guestBookings[guestName]) {
                dateInput.value = '';
                dateInput.min = today.toISOString().slice(0,16);
                dateInput.max = '';
                return;
            }

            const bookingStart = guestBookings[guestName].start;
            const bookingEnd = guestBookings[guestName].end;

            const minDate = bookingStart > today ? bookingStart : today;
            const maxDate = new Date(bookingEnd);
            maxDate.setHours(12,0,0,0); // last selectable time 12:00 PM on checkout day

            dateInput.min = minDate.toISOString().slice(0,16);
            dateInput.max = maxDate.toISOString().slice(0,16);

            if (dateInput.value) {
                const selected = new Date(dateInput.value);
                if (selected < minDate) dateInput.value = minDate.toISOString().slice(0,16);
                if (selected > maxDate) dateInput.value = maxDate.toISOString().slice(0,16);
            }

            dateInput.addEventListener('input', () => {
                const selected = new Date(dateInput.value);
                if (selected < minDate) dateInput.value = minDate.toISOString().slice(0,16);
                if (selected > maxDate) dateInput.value = maxDate.toISOString().slice(0,16);
            });
        }

        selectBox.addEventListener('change', () => {
            const guestName = selectBox.value;
            guestSearch.value = guestName;
            selectBox.style.display = 'none';
            setDateLimits(guestName);
        });

        guestSearch.addEventListener('input', () => {
            const filter = guestSearch.value.toLowerCase();
            let matchCount = 0;
            for (let i = 0; i < selectBox.options.length; i++) {
                const option = selectBox.options[i];
                const text = option.text.toLowerCase();
                const match = text.includes(filter);
                option.style.display = match ? '' : 'none';
                if (match) matchCount++;
            }
            selectBox.style.display = matchCount ? 'block' : 'none';
            dateInput.value = '';
            dateInput.min = new Date().toISOString().slice(0,16);
            dateInput.max = '';
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#guestSearch') && !e.target.closest('#firstname')) {
                selectBox.style.display = 'none';
            }
        });

        addContainer.addEventListener('click', () => {
            const targetUrl = addContainer.getAttribute('data-url');
            if (targetUrl) window.location.href = targetUrl;
        });

        function updateReceipt() {
            const selectedItemsContainer = document.querySelector('.selected-items');
            selectedItemsContainer.innerHTML = '';
            if (!Object.keys(cart).length) {
                selectedItemsContainer.innerHTML = '<p>No items selected</p>';
                return;
            }
            let grandTotal = 0;
            Object.values(cart).forEach(item => {
                const subtotal = item.price * item.quantity;
                grandTotal += subtotal;
                const div = document.createElement('div');
                div.classList.add('receipt-item');
                div.innerHTML = `
                    <div style="display:flex; justify-content:space-between; width:100%;">
                        <span><strong>${item.name}</strong></span>
                        <span>x${item.quantity}</span>
                        <span>₱${subtotal.toFixed(2)}</span>
                    </div>`;
                selectedItemsContainer.appendChild(div);
            });
            const totalDiv = document.createElement('div');
            totalDiv.innerHTML = `
                <hr>
                <div style="display:flex; justify-content:space-between; font-weight:bold; width:100%;">
                    <span>Total:</span>
                    <span>₱${grandTotal.toFixed(2)}</span>
                </div>`;
            selectedItemsContainer.appendChild(totalDiv);
        }

        document.querySelectorAll('.menu-card').forEach(card => {
            const addBtn = card.querySelector('.amount-btn.add');
            const subBtn = card.querySelector('.amount-btn.sub');
            const counter = card.querySelector('.amount-wrapper h3');
            const menuID = card.getAttribute('data-id');
            const menuName = card.getAttribute('data-name');
            const menuPrice = parseFloat(card.getAttribute('data-price'));
            let count = 0;

            addBtn.addEventListener('click', () => {
                count++;
                counter.textContent = count;
                cart[menuID] = { id: menuID, name: menuName, price: menuPrice, quantity: count };
                updateReceipt();
            });

            subBtn.addEventListener('click', () => {
                if (count > 0) {
                    count--;
                    counter.textContent = count;
                    if (count === 0) delete cart[menuID];
                    else cart[menuID].quantity = count;
                    updateReceipt();
                }
            });
        });

        btnClear.addEventListener('click', () => {
            cart = {};
            document.querySelectorAll('.menu-card h3').forEach(h3 => h3.textContent = 0);
            updateReceipt();
        });

        orderBtn.addEventListener('click', () => {
            orderItemsContainer.innerHTML = '';
            orderTotalContainer.innerHTML = '';
            bookingInputContainer.innerHTML = '';
            if (!Object.keys(cart).length) {
                orderItemsContainer.innerHTML = "<p>No items selected</p>";
            } else {
                let grandTotal = 0;
                Object.values(cart).forEach(item => {
                    const subtotal = item.price * item.quantity;
                    grandTotal += subtotal;
                    const div = document.createElement('div');
                    div.classList.add('modal-order-item');
                    div.innerHTML = `
                        <div style="display:flex; justify-content:space-between; width:100%;">
                            <span>${item.name} (x${item.quantity})</span>
                            <span>₱${subtotal.toFixed(2)}</span>
                        </div>
                        <small>₱${item.price.toFixed(2)} each</small>`;
                    orderItemsContainer.appendChild(div);

                    const hiddenId = document.createElement('input');
                    hiddenId.type = 'hidden';
                    hiddenId.name = 'order[]';
                    hiddenId.value = item.id;
                    bookingInputContainer.appendChild(hiddenId);

                    const hiddenQty = document.createElement('input');
                    hiddenQty.type = 'hidden';
                    hiddenQty.name = 'quantity[]';
                    hiddenQty.value = item.quantity;
                    bookingInputContainer.appendChild(hiddenQty);
                });
                orderTotalContainer.innerHTML = `
                    <hr>
                    <div style="display:flex; justify-content:space-between; font-weight:bold; width:100%;">
                        <span>Total:</span>
                        <span>₱${grandTotal.toFixed(2)}</span>
                    </div>`;
            }
            modal.style.display = 'flex';
        });

        closeBtn.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

        document.querySelectorAll('.navbar-item').forEach(navItem => {
            navItem.addEventListener('click', () => {
                const filter = navItem.getAttribute('data-filter');
                document.querySelectorAll('.navbar-item').forEach(item => item.classList.remove('active'));
                navItem.classList.add('active');
                document.querySelectorAll('.menu-card').forEach(card => {
                    card.style.display = filter === 'All' || card.getAttribute('data-type') === filter ? 'flex' : 'none';
                });
            });
        });
    });
</script>