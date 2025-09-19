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
            <h1>Day Tour Management</h1>
        </div>
        <div class="main-container">
            <div class="form-form">
                <form method="POST" action="{{ route('receptionist.createDayTour') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="amenity-container">
                        <div class="label-container toggle-header" data-target="amenity-content">
                            <h2>Amenity Selection</h2>
                        </div>
                        
                        <div class="room-selection-wrapper">     
                            <button type="button" class="scroll-btn left-btn">&#9664;</button>
                            <div id="amenity-selection">
                                @foreach($amenities as $amenity)
                                    <div class="amenity">
                                        <label for="amenity-{{ $amenity->amenityID }}" id="amenity-name">
                                            {{ $amenity->amenityname }}
                                            <input 
                                                type="checkbox" 
                                                id="amenity-{{ $amenity->amenityID }}" 
                                                name="amenity[]" 
                                                data-name="{{ $amenity->amenityname }}" 
                                                data-adult-price="{{ $amenity->adultprice }}" 
                                                data-child-price="{{ $amenity->childprice}}"
                                                value="{{ $amenity->amenityID }}"  
                                                class="amenity-checkbox"
                                            >
                                            <div class="amenity-card">
                                                <img src="{{ asset('storage/' . $amenity->image) }}" alt="Amenity Image">
                                                <div class="amenity-details">
                                                    <p>
                                                        Adult Price: ₱ {{ $amenity->adultprice }}<br/>
                                                        Child Price: ₱ {{ $amenity->childprice }}
                                                    </p>
                                                </div>
                                            </div>                                    
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="scroll-btn right-btn">&#9654;</button>
                        </div>
                    </div>

                    <div id="form-container">
                        <div id="form-header-1">
                            <h3>Personal Information</h3>
                            <p id="hasAccount"><i class="fas fa-info-circle fa-lg"></i> Click here if guest is registered/ second time day tour guest</p>
                        </div>

                        <div id="row1">
                            <div>
                                <label for="txtfirstname">Firstname:</label>
                                <input class="input" id="txtfirstname" type="text" placeholder="Firstname.." name="firstname">
                                @error('firstname')
                                    <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="txtalstname">Lastname:</label> 
                                <input class="input" id="txtalstname" type="text" placeholder="Lastname.." name="lastname">
                                    @error('lastname')
                                    <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="additional-info">
                            <div id="row2">
                                <div>
                                    <label for="txtcontactnum">Contact #:</label>
                                    <div style="display:flex; flex-direction: row;gap:.2rem;">
                                        <span style="display:flex; align-items:center; padding:.5rem; background:white; border: 1px solid black; border-radius: .5rem .2rem .2rem .5rem; width:9%; margin-top:.5rem;margin-bottom:.5rem;">+63</span>
                                        <input class="input" id="txtcontactnum" type="text"  maxlength="10" placeholder="912345678" name="contactnum" style="border-radius: .2rem .5rem .5rem .2rem; width:90.3%">
                                            @error('contactnum')
                                            <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="txtemail">Email:</label> 
                                    <input class="input" id="txtemail" type="email" placeholder="@email.com.." name="email">  
                                    @error('email')
                                        <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="row3">
                                <div>
                                    <label for="txtgender">Gender:</label>
                                    <select id="txtgender"  name="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non_Binary">Non-Binary</option>
                                        <option value="Trans_Female">Transgender Female</option>
                                        <option value="Trans_Male">Transgender Male</option>
                                        <option value="Genderqueer">Genderqueer</option>
                                        <option value="Agender">Agender</option>
                                        <option value="Bigender">Bigender</option>
                                        <option value="Genderfluid">Genderfluid</option>
                                        <option value="Two_Spirit">Two-Spirit</option>
                                        <option value="Other">Other</option>
                                        <option value="Prefer_not_to_say">Prefer not to say</option>
                                    </select>
                                    @error('gender')
                                        <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div> 
                                    {{-- Birthday --}}
                                <label id="lblbirthday" for="txtbirthday">
                                    Birthday:
                                </label>
                                <input class="input" id="txtbirthday" type="date" name="birthday"
                                    value="{{ old('birthday') }}">
                                    @error('birthday')
                                        <div class="error-message" style="color: red; font-style: italic;">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>

                            {{-- Valid ID --}}
                            <div class="cl-validID" id="row4">
                                <label for="txtvalidid">Import Valid ID
                                <div>
                                    <img id="id-preview" src="{{ asset('images/photo.png') }}">
                                    <input id="txtvalidid" type="file" accept=".png, .jpg, .jpeg, .webp" name="validID">
                                </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="guest-count">
                        <div>
                            <label id="label" for="guestamount">Guest Count:
                                <input class="input" type="text" id="guestamount" name="guestamount" value="{{ old('guestamount', $bookingData->guestamount ?? '') }}" required>
                            </label>
                            
                            @error('guestamount')
                                <small class="text-danger" style="color: red; font-style: italic;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="guest-counts" id="guest-counts">
                            <label id="label">Adult Guests:
                                <input type="number" min="0" id="adultcount" name="amenity_adult_guest" class="input" value="{{ old('adultguest', $bookingData->adultguest ?? '')}}" placeholder="Enter number of adults">
                            </label>
                            <label id="label">Child Guests:
                                <input type="number" min="0" id="childcount" name="amenity_child_guest" class="input" value="{{ old('childguest', $bookingData->childguest ?? '')}}" placeholder="Enter number of children">
                            </label>
                        </div>
                    </div>
                    
                    <div class="payment-selection-wrapper">
                        <label for="cash">Cash:
                            <div class="payment-selection">
                                <i class="fas fa-money-bill-wave fa-2x"></i> 
                            </div>
                            <input class="radio" type="radio" id="cash" name="payment" value="cash" {{ old('payment') == 'cash' ? 'checked' : '' }} required> 
                        </label>
                        @error('payment')
                            <small style="color: red; font-style: italic;">{{ $message }}</small>
                        @enderror

                        <label for="gcash">Gcash:
                            <div class="payment-selection">
                                <i class="fas fa-mobile-alt fa-2x"></i> 
                            </div>
                            <input class="radio" type="radio" id="gcash" name="payment" value="gcash" {{ old('payment') == 'gcash' ? 'checked' : '' }} required> 
                        </label>    
                        @error('payment')
                            <small style="color: red; font-style: italic;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div id="cash-amount-wrapper" style="display:none; margin-top: 10px;">
                        <label for="cash-amount">Amount Paid:</label>
                        <input class="input" type="number" id="cash-amount" name="cashamount" min="0" step="0.01" placeholder="Enter amount paid" value="{{ old('cash_amount') }}">
                        @error('cashamount')
                            <small style="color: red; font-style: italic;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="discount-container">
                        <div id="form-header-1">
                            <h4>Discount Information</h4>
                        </div>
                        <label for="discount">Discount
                            <select class="input" name="discount" id="discount">
                                <option value="0" data-amount="0" {{ old('discount') == '0' ? 'selected' : '' }}>No Discount</option>
                                @foreach($discount as $d)
                                    <option value="{{ $d->discountID }}" data-amount="{{ $d->amount }}" {{ old('discount') == $d->discountID ? 'selected' : '' }}>
                                        {{ $d->name }}: {{ $d->amount }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        @error('discount')
                            <small style="color: red; font-style: italic;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div id="button-container">
                        <div>
                            <button id="btncancel" type="button" data-url="{{ url('receptionist/daytourDashboard')}}">Cancel</button>
                            <button id="btnsubmit" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="rec">
                 <div class="receipt">
                    <h2>Booking Information</h2>
                        <p><span>Guest: </span><span id="guest-name-receipt">N/A</span></p>
                        <p><span>Guest Count: </span><span id="guest-amount-receipt">0</span></p>
                        <hr/>
                        <p>Selected Amenity:</p>
                        <ul id="selected-amenities-list">
                            <li class="no-selection">No amenities selected</li>
                        </ul>
                        <hr/>   
                    <h2>Total</h2>
                        <p><span>Adult Total: </span><span id="adult-total-receipt">₱ 0.00</span></p>
                        <p><span>Child Total: </span><span id="child-total-receipt">₱ 0.00</span></p>
                        <p><span>Amenity Total: </span><span id="amenity-total-receipt">₱ 0.00</span></p>
                        
                        <p><span>SubTotal: </span><span id="subtotal-receipt">₱ 0.00</span></p>
                        <p><span>Discount: </span><span id="discount-receipt">0%</span></p>
                        <p><span>Total: </span><span id="total-receipt">₱ 0.00</span></p>
                        <p><span>Amount Due: </span><span id="amount-due-receipt">₱ 0.00</span></p>

                    <h2>Payment</h2>
                        <p><span>Amount Tendered: </span><span id="amount-tendered">₱ 0.00</span></p>
                        <p><span>Total Change:</span><span id="change-receipt">₱ 0.00</span></p>
                </div>
            </div>
        @if (session('error'))
            <div class="alert-message">
                <h2>{{ session('error') }}</h2>
            </div>
        @endif
        </div>
    </body>
<style>
    .main-container{
        display:grid;
        grid-template-columns: 2fr .5fr;
        gap:.5rem;
        position:relative;
    }
    .form-form{
        width:100%;
        position: relative;
    }
    .rec{
        width: 100%;
        position:sticky;
        display: flex;
        flex-direction: row;
        width:100%;
        height:99.5%;
        gap:.5rem;
    }
        .receipt{
            background:white;
            display:flex;
            flex-direction: column;
            position:relative;
            height:97.3%;
            width:100%;
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
            border:1px solid black;
            padding:1rem;
            font-size:.8rem;
            overflow-y:auto;
        }
        hr {
            border: 1px solid black;  
            margin: 1rem 0;  
            width: 100%;
        }
        .no-selection{
            color:red;
            font-weight:bold;
        }
        .receipt span{
            font-weight:bold;
        }
        .label-container{
            display: flex;
            flex-direction: row;
            margin-bottom: 1rem;
            background:black;
            width: 100%;
            height:3rem;
            justify-content: space-between;
            align-items: center;
            padding:.5rem;
            font-size:.7rem;
            color:white;
            border-radius:.7rem;
        }
        .payment{
            display:flex;
            flex-direction: column;
            position:absolute;
            width:69.5%;
            height:90%;
            background:white;   
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
            border:1px solid black;
            padding:1rem;
            font-size:.8rem;
            gap:.5rem;
        }
        .payment-type-wrapper{
            display:flex;
            flex-direction: row;
            gap:.5rem;
            margin-bottom: 1rem;
        }
        .payment-type-selection{
            display:flex;
            height:4rem;
            width:8rem;
            border-radius:.7rem;
            justify-content:center;
            align-items:center;
            border:1px solid black;
            box-shadow:.1rem .1rem 0 black;
            gap:.5rem;
            cursor:pointer;
            transition:all .2s ease;
            background: #f0f0f0;
        }
        .payment-type-selection:hover{
            background:orange;
            color:white;
            scale:1.05;
        }
        .payment-selection-wrapper{
            display:flex;
            flex-direction: row;
            gap:.5rem;
        }
        .payment-selection{
            display:flex;
            height:5rem;
            width:7rem;
            border-radius:.7rem;
            justify-content:center;
            align-items:center;
            border:1px solid black;
            box-shadow:.1rem .1rem 0 black;
            gap:.5rem;
            cursor:pointer;
            transition:all .2s ease;
        }
        .payment-selection:hover{
            background:orange;
            color:white;
            scale:1.1;
        }
        .input{
            display:flex;
            width:100%;
            background: white;
            border:1px solid black;
            border-radius:.5rem;
            padding:.5rem;
            font-size: .8rem;
        }
        .button-container {
            position:absolute;
            display: flex;
            flex-direction: row;
            margin-top: auto;
            bottom:1rem;
        }
        .form-button{
            background: rgb(255, 255, 255);
            color: rgb(0, 0, 0);
            border: none;
            padding: .5rem 1rem;
            border-radius: .5rem;
            cursor: pointer;
            font-size: .8rem;
            margin-right: .5rem;
            transition: all .2s ease-in-out;
            border:rgb(0, 0, 0) solid 1px;
            box-shadow: .1rem .1rem 0 rgb(0, 0, 0);
            margin-bottom: 1rem;
        }
        .form-button:hover{
            background: orange;
            color: black;
            transform: translateY(-.1rem);
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
    #daytour{color:orange;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:85%;
        height:100vh;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
        margin-right:.7rem;
        overflow-y: auto;
        overflow-x: hidden;
        gap:.5rem;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .7rem;
    }
    .amenity-container{
        display:flex;
        flex-direction: column;
        background:white;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        border-radius:.7rem;
        padding:1rem;
    }
    .amenity{
        display: flex;
        flex-direction: column; 
        gap:.5rem;
        margin-bottom:.5rem;
    }
    #amenity-name{
        font-size: .9rem;
        color: black;
        text-align: center;
        font-weight: bold;
    }

    .amenity-card{
        display: flex;
        flex-direction: column;
        width:15rem;
        height:10rem;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 rgb(0,0,0);
        border: solid 1px black;
        background: white;
        padding:.5rem;
        gap:.5rem;
        cursor: pointer;
        align-content: center;
        justify-content: center;
        transition:all .2s ease;
    }
    .amenity-card img{
        display:flex;
        height:60%;
        width:100%;
        object-fit: cover;
        margin-top:.5rem;
    }
    .amenity-card:hover{
        background:orange;
        transform:translateY(-.5rem);
    }
    .amenity-card.active{
        background: rgb(0, 86, 0);
        color:white;
        scale: .9;
    }

    #amenity-selection{
        display:flex;
        flex-direction: row;
        gap:.5rem;
    }
    .scroll-btn{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
        font-size: 1.2rem;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgb(174, 174, 174);
        width: 2.5rem;
        height: 2.5rem;
        color: black;
        border: 1px solid black;
        border-radius: 100%;
        transition: all 0.2s ease;
    }
    .scroll-btn:hover {
        background: rgba(0, 0, 0, 0.5);
        color:white;
        scale: 1.1;
    }

    .left-btn {
        position: absolute;
        left: 0.5rem;
    }
    .right-btn {
        position: absolute;
        right: 0.5rem;
    }
    #form-header-1{
        display:flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        background:black;
        color:white;
        width:100%; 
        border-radius:.5rem;
        padding-left:.5rem;
        padding-right:.5rem;
        margin-bottom:.5rem;
    }
    #hasAccount{
        font-style:italic;
        color:white;
        cursor:pointer;
        transition:all .3s ease;
    }
    #hasAccount:hover{
        color:red;
        scale:1.1;
    }
    #form-container{
        width:100%;
        display:flex;
        flex-direction: column;
        border-radius:.7rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        background:white;
        padding:1rem;
        margin-top:1rem;
    }
    .guest-count{
        width:100%;
        display:flex;
        flex-direction: column;
        border-radius:.7rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        background:white;
        padding:1rem;
        margin-top:1rem;
    }
    .guest-counts{
        display:grid;
        grid-template-columns:1fr 1fr;
        width:100%;
        gap:.5rem;
    }
    .guest-counts input{
        width:100%;
    }
    .payment-type-wrapper{
        width:100%;
        display:flex;
        flex-direction: row;
        border-radius:.7rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        background:white;
        padding:1rem;
        margin-top:1rem;
    }
    .payment-selection-wrapper{
        width:100%;
        display:flex;
        flex-direction: row;
        border-radius:.7rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        background:white;
        padding:1rem;
        margin-top:1rem;
    }
    #cash-amount-wrapper{
        border-radius:.7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        background: white;
        padding:1rem;
    }
    .discount-container{
        width:100%;
        display:flex;
        flex-direction: column;
        border-radius:.7rem;
        border:solid 1px black;
        box-shadow:.1rem .1rem 0 black;
        font-size:.8rem;
        background:white;
        padding:1rem;
        margin-top:1rem;
        margin-bottom:1rem;
    }
    .additional-info {
        display:block;
        gap: .5rem;
    }
    .additional-info.active {
        display:none;
        gap: .5rem;
    }

    #row1, #row2, #row3{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap:.5rem;
        width:100%;
    }
    .input, select{
        display:flex;
        padding:.5rem;
        border:2px solid black;
        border-radius:.5rem;
        width:100%;
        background:white;
        margin-top:.5rem;
        margin-bottom:.5rem;
    }
    #row4{
        display:flex;
        flex-direction:column;
        gap:.5rem;
    }
    #id-preview{
        display:flex;
        min-width:30rem;
        min-height:15rem;
        max-height:25rem;
        border: 1px solid black;
        border-radius:.5rem;
        box-shadow:.1rem .1rem 0 black;
        object-fit:cover;
        margin-top:.5rem;
        margin-bottom:.5rem;
    }
    #button-containers{

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
    function toggleCashAmount() {
        const cashRadio = document.getElementById('cash');
        const gcashRadio = document.getElementById('gcash');
        const cashWrapper = document.getElementById('cash-amount-wrapper');

        if (cashRadio.checked) {
            cashWrapper.style.display = 'block';
        } else {
            cashWrapper.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        const cancelBtn = document.getElementById('btncancel');
        if(cancelBtn){
            const cancelurl = cancelBtn.dataset.url;
            cancelBtn.addEventListener('click', function(){
                window.location.href = cancelurl;
            })
        }

        toggleCashAmount();

        const cashRadio = document.getElementById('cash');
        const gcashRadio = document.getElementById('gcash');

        // Add change event listeners to both payment options
        if (cashRadio) cashRadio.addEventListener('change', toggleCashAmount);
        if (gcashRadio) gcashRadio.addEventListener('change', toggleCashAmount);
    });
    // Amenity checkbox toggle
    document.querySelectorAll('.amenity-checkbox').forEach(checkbox => {
        const card = checkbox.closest('label').querySelector('.amenity-card');
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    });

    // Toggle visibility of additional info
    const hasAccount = document.getElementById('hasAccount');
    const additionalInfo = document.querySelector('.additional-info');

    hasAccount.addEventListener('click', () => {
        additionalInfo.classList.toggle('active'); // Toggle the 'active' class
        if (additionalInfo.classList.contains('active')){
            hasAccount.innerHTML = '<i class="fas fa-info-circle fa-lg"></i> Click here if guest is not registered/ first time day tour guest';
        } else {
            hasAccount.innerHTML = '<i class="fas fa-info-circle fa-lg"></i> Click here if guest is registered/ second time day tour guest';
        }
    });

    // Image preview fallback
    const fileInput = document.getElementById('txtvalidid');
    const preview = document.getElementById('id-preview');
    const defaultImage = "{{ asset('images/photo.png') }}";
    let currentImage = defaultImage;

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                currentImage = e.target.result; // Save valid image
            };
            reader.readAsDataURL(file);
        } else {
            // If user cancels, revert to last known image
            preview.src = defaultImage;
        }
    });

    // -------------------------------------- RECEIPT LOGIC --------------------------------------
    document.addEventListener('DOMContentLoaded', function () {
        // Get all form elements
        const firstNameInput = document.getElementById('txtfirstname');
        const lastNameInput = document.getElementById('txtalstname');
        const guestAmountInput = document.getElementById('guestamount');
        const adultGuestInput = document.getElementById('adultcount');
        const childGuestInput = document.getElementById('childcount');
        const amountPaidInput = document.getElementById('cash-amount');
        const discountSelect = document.getElementById('discount');
        const cashRadio = document.getElementById('cash');
        const gcashRadio = document.getElementById('gcash');
        const fullPaymentRadio = document.getElementById('full-payment');
        const downpaymentRadio = document.getElementById('downpayment');
        const amenityCheckboxes = document.querySelectorAll('.amenity-checkbox');

        // Get all receipt elements
        const guestNameField = document.getElementById('guest-name-receipt');
        const guestAmountField = document.getElementById('guest-amount-receipt');
        const selectedAmenitiesList = document.getElementById('selected-amenities-list');
        const adultTotalField = document.getElementById('adult-total-receipt');
        const childTotalField = document.getElementById('child-total-receipt');
        const amenityTotalField = document.getElementById('amenity-total-receipt');
        const subtotalField = document.getElementById('subtotal-receipt');
        const discountField = document.getElementById('discount-receipt');
        const totalPriceField = document.getElementById('total-receipt');
        const amountDueField = document.getElementById('amount-due-receipt');
        const paymentTypeField = document.getElementById('payment-type-receipt');
        const paymentMethodField = document.getElementById('payment-method-receipt');
        const amountTenderedField = document.getElementById('amount-tendered');
        const changeField = document.getElementById('change-receipt');
        const cashAmountWrapper = document.getElementById('cash-amount-wrapper');

        let subtotal = 0;
        let discountAmount = 0;
        let totalAmount = 0;
        let amountDue = 0;

        function updateGuestName() {
            const firstName = firstNameInput ? firstNameInput.value.trim() : '';
            const lastName = lastNameInput ? lastNameInput.value.trim() : '';
            const fullName = `${firstName} ${lastName}`.trim();
            if (guestNameField) {
                guestNameField.textContent = fullName || 'N/A';
            }
        }

        function updateGuestAmount() {
            const guestAmount = guestAmountInput ? guestAmountInput.value : '0';
            if (guestAmountField) {
                guestAmountField.textContent = guestAmount || '0';
            }
        }

        function updateSelectedAmenities() {
            const selectedAmenities = [];
            const adultCount = parseInt(adultGuestInput ? adultGuestInput.value : '0') || 0;
            const childCount = parseInt(childGuestInput ? childGuestInput.value : '0') || 0;

            amenityCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const amenityName = checkbox.dataset.name;
                    const adultPrice = parseFloat(checkbox.dataset.adultPrice) || 0;
                    const childPrice = parseFloat(checkbox.dataset.childPrice) || 0;

                    const totalPrice = (adultPrice * adultCount) + (childPrice * childCount);

                    selectedAmenities.push({
                        name: amenityName,
                        adultPrice: adultPrice,
                        childPrice: childPrice,
                        totalPrice: totalPrice
                    });
                }
            });

            if (selectedAmenitiesList) {
                selectedAmenitiesList.innerHTML = '';
                if (selectedAmenities.length === 0) {
                    selectedAmenitiesList.innerHTML = '<li class="no-selection">No amenities selected</li>';
                } else {
                    selectedAmenities.forEach(amenity => {
                        const li = document.createElement('li');
                        li.textContent = `${amenity.name} - ₱${amenity.totalPrice.toFixed(2)}`;
                        selectedAmenitiesList.appendChild(li);
                    });
                }
            }
        }

        // Function to calculate totals
        function calculateTotals() {
            const adultCount = parseInt(adultGuestInput ? adultGuestInput.value : '0') || 0;
            const childCount = parseInt(childGuestInput ? childGuestInput.value : '0') || 0;

            let totalAdultPrice = 0;
            let totalChildPrice = 0;
            let totalAmenityPrice = 0;

            // Calculate selected amenities total
            amenityCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const adultPrice = parseFloat(checkbox.dataset.adultPrice) || 0;
                    const childPrice = parseFloat(checkbox.dataset.childPrice) || 0;

                    totalAdultPrice += adultPrice * adultCount;
                    totalChildPrice += childPrice * childCount;
                    totalAmenityPrice += (adultPrice * adultCount) + (childPrice * childCount);
                }
            });

            // Update individual totals
            if (adultTotalField) adultTotalField.textContent = '₱ ' + totalAdultPrice.toFixed(2);
            if (childTotalField) childTotalField.textContent = '₱ ' + totalChildPrice.toFixed(2);
            if (amenityTotalField) amenityTotalField.textContent = '₱ ' + totalAmenityPrice.toFixed(2);

            // Calculate subtotal
            subtotal = totalAmenityPrice;
            if (subtotalField) subtotalField.textContent = '₱ ' + subtotal.toFixed(2);

            // Calculate discount
            if (discountSelect) {
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const discountDecimal = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
                const discountPercentage = discountDecimal * 100;
                discountAmount = discountDecimal * subtotal;
                if (discountField) discountField.textContent = discountPercentage + '%';
            }

            // Calculate total after discount
            totalAmount = subtotal - discountAmount;
            if (totalPriceField) totalPriceField.textContent = '₱ ' + totalAmount.toFixed(2);

            // Calculate amount due based on payment type
            if (downpaymentRadio && downpaymentRadio.checked) {
                amountDue = totalAmount * 0.5; // 50% downpayment
            } else {
                amountDue = totalAmount; // Full payment
            }
            if (amountDueField) amountDueField.textContent = '₱ ' + amountDue.toFixed(2);
        }

        // Function to update payment information
        function updatePaymentInfo() {
            // Update payment type
            if (paymentTypeField) {
                if (fullPaymentRadio && fullPaymentRadio.checked) {
                    paymentTypeField.textContent = 'Full Payment';
                } else if (downpaymentRadio && downpaymentRadio.checked) {
                    paymentTypeField.textContent = '50% Downpayment';
                } else {
                    paymentTypeField.textContent = 'N/A';
                }
            }

            // Update payment method
            if (paymentMethodField) {
                if (cashRadio && cashRadio.checked) {
                    paymentMethodField.textContent = 'Cash';
                } else if (gcashRadio && gcashRadio.checked) {
                    paymentMethodField.textContent = 'GCash';
                } else {
                    paymentMethodField.textContent = 'N/A';
                }
            }

            // Update amount tendered and change
            if (cashRadio && cashRadio.checked) {
                if (cashAmountWrapper) cashAmountWrapper.style.display = 'block';
                const amountPaid = parseFloat(amountPaidInput ? amountPaidInput.value : '0') || 0;
                if (amountTenderedField) amountTenderedField.textContent = '₱ ' + amountPaid.toFixed(2);
                const change = amountPaid - amountDue;
                if (changeField) changeField.textContent = '₱ ' + (change >= 0 ? change.toFixed(2) : '0.00');
                if (amountPaidInput) amountPaidInput.style.borderColor = amountPaid < amountDue ? 'red' : '';
            } else {
                if (cashAmountWrapper) cashAmountWrapper.style.display = 'none';
                if (amountTenderedField) amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
                if (changeField) changeField.textContent = '₱ 0.00';
            }
        }

        // Main function to update entire receipt
        function updateReceipt() {
            updateGuestName();
            updateGuestAmount();
            updateSelectedAmenities();
            calculateTotals();
            updatePaymentInfo();
        }

        // Add event listeners to all form fields
        if (firstNameInput) firstNameInput.addEventListener('blur', updateReceipt);
        if (lastNameInput) lastNameInput.addEventListener('blur', updateReceipt);
        if (guestAmountInput) guestAmountInput.addEventListener('blur', updateReceipt);
        if (adultGuestInput) adultGuestInput.addEventListener('blur', updateReceipt);
        if (childGuestInput) childGuestInput.addEventListener('blur', updateReceipt);

        // Amenity checkboxes
        amenityCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateReceipt);
        });

        // Payment fields
        if (amountPaidInput) {
            amountPaidInput.addEventListener('blur', updateReceipt);
            amountPaidInput.addEventListener('input', updateReceipt); // real-time feedback
        }
        if (discountSelect) discountSelect.addEventListener('change', updateReceipt);
        if (cashRadio) cashRadio.addEventListener('change', updateReceipt);
        if (gcashRadio) gcashRadio.addEventListener('change', updateReceipt);
        if (fullPaymentRadio) fullPaymentRadio.addEventListener('change', updateReceipt);
        if (downpaymentRadio) downpaymentRadio.addEventListener('change', updateReceipt);

        // Change listeners for number inputs
        if (adultGuestInput) adultGuestInput.addEventListener('change', updateReceipt);
        if (childGuestInput) childGuestInput.addEventListener('change', updateReceipt);
        if (guestAmountInput) guestAmountInput.addEventListener('change', updateReceipt);

        // Initialize the receipt
        updateReceipt();
    });

</script>

        