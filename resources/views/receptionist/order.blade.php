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
                <div id="add-container">
                    <i id="add-menu" class="fa-solid fa-burger fa-2x" style="cursor:pointer;"></i>
                    <small>View Orders</small>
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
                @foreach($menu as $menuitem)
                    <div class="menu-card" data-type="{{ $menuitem->itemtype }}" data-price="{{ $menuitem->price}}" data-name="{{ $menuitem->menuname}}">
                        <div id="img-container">
                            <img src="{{asset('storage/' . $menuitem->image)}}">
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

            <div class="order-receipt">
                <h2>Order Details</h2>

                <strong>Selected Items</strong>

                <div class="selected-items">
                    <strong>Dish 1</strong> 
                    <p>Price</p>
                </div>

                <div class="order-button">
                    <button id="btn-clear" type="reset">Clear All</button>
                    <button id="orderBtn" type="button">Place Order</button>
                </div>
                
            </div>


            <div id="orderModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Enter Booking Information</h2>
                    <form id="orderForm" action="{{url('receptionist.submitorder')}}">
                        @csrf
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>

                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>

                        <label for="bookingType">Booking Type</label>
                        <select id="bookingType" name="bookingType" required>
                            <option value="">-- Select --</option>
                            <option value="room">Room Number</option>
                            <option value="cottage">Cottage</option>
                            <option value="amenity">Amenity</option>
                        </select>

                        <div id="bookingInputContainer"></div>

                        <button type="submit">Confirm Order</button>
                    </form>
                </div>

            @if(session('success'))
                <div class="alert-message">
                    <h2>{{ session('success') }}</h2>
                </div>
            @endif

        @if (session('error'))
            <div class="alert-message">
                <h2>{{ session('error') }}</h2>
           </div>
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
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
    }
    #layout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: .5rem;
        height:3rem;
        background: white; 
        border-radius: .7rem;
        font-size: 70%;
        gap: 1rem;
        box-shadow:.1rem .1rem 0 black;
        border:1px solid black;
    }
     #add-container {
        display: flex;
        height:100%;
        flex-direction: column;
        align-items: center;
        position: relative;
        cursor: pointer;
    }

    #add-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }
    .navbar{
        display:flex;
        flex-direction: row;
        width:100%;
        height: 3rem;
        gap:1rem;
        padding:1rem;
        justify-content:center;
        align-items:center;
        -webkit-overflow-scrolling: touch;
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
        flex-wrap: wrap;
        gap:1rem;
        padding:1rem;
        width:100%;
        height: 100%;
        overflow-y: auto;
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
        display: none;
        flex-direction: column;
        height:100%;
        width:15rem;
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
        background: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    .modal-content button:hover {
        background: #218838;
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
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.navbar-item');
    const menuCards = document.querySelectorAll('.menu-card');
    const message = document.querySelector('.alert-message');
    const receipt = document.querySelector('.order-receipt');
    const selectedItemsContainer = document.querySelector('.selected-items');
    const addContainer = document.getElementById('add-container');

    // Store cart data
    let cart = {};

    // Toggle receipt visibility when clicking the "View Orders" container
    if (addContainer && receipt) {
        addContainer.addEventListener('click', function () {
            if (receipt.style.display === 'none' || receipt.style.display === '') {
                receipt.style.display = 'flex';
            } else {
                receipt.style.display = 'none';
            }
        });
    }

    // Auto-hide message alert
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 2500);
    }

    // Update receipt UI
    function updateReceipt() {
        selectedItemsContainer.innerHTML = ''; // Clear old items

        if (Object.keys(cart).length === 0) {
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
                <div class="flex justify-between">
                    <span><strong>${item.name}</strong> </span> <span>x${item.quantity}</span>
                    <span>₱${subtotal.toFixed(2)}</span>
                </div>
                <small>₱${item.price.toFixed(2)} each</small>
            `;
            selectedItemsContainer.appendChild(div);
        });

        // Append grand total
        const totalDiv = document.createElement('div');
        totalDiv.classList.add('receipt-total');
        totalDiv.innerHTML = `
            <hr>
            <div class="flex justify-between font-bold" style="width:100%">
                <span>Total:</span>
                <span>₱${grandTotal.toFixed(2)}</span>
            </div>
        `;
        selectedItemsContainer.appendChild(totalDiv);
    }

    // Filter menu items
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            menuCards.forEach(card => {
                const type = card.getAttribute('data-type');
                card.style.display = (filter === 'All' || filter === type) ? 'block' : 'none';
            });
        });
    });

    // Add/Subtract menu item
    document.querySelectorAll('.menu-card').forEach(card => {
        const addBtn = card.querySelector('.amount-btn.add');
        const subBtn = card.querySelector('.amount-btn.sub');
        const counter = card.querySelector('.amount-wrapper h3');

        const menuID = card.getAttribute('data-id') || card.dataset.type + Math.random();
        const menuName = card.querySelector('#menu-details h2').textContent || 'Unnamed Item';
        const menuPrice = parseFloat(card.getAttribute('data-price')) || 0;

        let count = 0;

        if (addBtn) {
            addBtn.addEventListener('click', function () {
                count++;
                counter.textContent = count;

                cart[menuID] = {
                    id: menuID,
                    name: menuName,
                    price: menuPrice,
                    quantity: count
                };
                updateReceipt();
            });
        }

        if (subBtn) {
            subBtn.addEventListener('click', function () {
                if (count > 0) {
                    count--;
                    counter.textContent = count;

                    if (count === 0) {
                        delete cart[menuID];
                    } else {
                        cart[menuID].quantity = count;
                    }
                    updateReceipt();
                }
            });
        }
    });

    // Initialize empty receipt
    updateReceipt();

    function closeReceipt() {
        receipt.style.display = "none";
    }

    document.addEventListener("click", function (event) {
        if (receipt.style.display === "block" || receipt.style.display === "flex") {
            // Check if the click happened outside the receipt
            if (!receipt.contains(event.target) && !event.target.closest("#add-menu")) {
                closeReceipt();
            }
        }
    });

    const modal = document.getElementById("orderModal");
    const orderBtn = document.getElementById("orderBtn");
    const closeBtn = document.querySelector(".close");
    const bookingType = document.getElementById("bookingType");
    const bookingInputContainer = document.getElementById("bookingInputContainer");

    // Show modal when Order is pressed
    orderBtn.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Close modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close if clicked outside
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Change input based on booking type
    bookingType.addEventListener("change", function() {
        let inputHTML = "";
        if (this.value === "room") {
            inputHTML = `<label for="roomNumber">Room Number</label>
                        <input type="text" id="roomNumber" name="roomNumber" required>`;
        } else if (this.value === "cottage") {
            inputHTML = `<label for="cottage">Cottage</label>
                        <input type="text" id="cottage" name="cottage" required>`;
        } else if (this.value === "amenity") {
            inputHTML = `<label for="amenity">Amenity</label>
                        <input type="text" id="amenity" name="amenity" required>`;
        }
        bookingInputContainer.innerHTML = inputHTML;
    });

    // Handle form submit
    document.getElementById("orderForm").addEventListener("submit", function(e) {
        e.preventDefault();
        modal.style.display = "none";
    });
});
</script>





