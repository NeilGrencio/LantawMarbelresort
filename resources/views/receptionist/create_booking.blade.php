<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Booking</h1>
        </div>
            <form action="{{ url('receptionist/submit_booking') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="form-container">
                    <div class="input-container">

                        <div class="label-container">
                            <h2>Booking Information</h2>
                        </div>
                        <label for="checkin">Check-in Date:
                            <input type="date" id="checkin" name="checkin" value="{{ old('checkin') }}" required>
                        </label>

                        <label for="checkout">Check-out Date:
                            <input class="input" type="date" id="checkout" name="checkout" value="{{ old('checkout') }}" required> 
                        </label>
                        
                        <!--Room Selection-->
                        <div class="label-container toggle-header" id="room-label" data-target="room-content" style="margin-top:.5rem;">
                            <h2>Room Selection</h2>
                            <i class="fas fa-chevron-up toggle-icon fa-2x"></i>
                        </div>                 
                        <div id="room-content" class="collapsible-content active">
                            <div class="room-selection-wrapper">     
                                <button type="button" class="scroll-btn left-btn">&#9664;</button>
                                <div id="room-selection">  
                                    @foreach($rooms as $room)
                                        <div class="room">
                                            <label id="room-name" for="room">Room {{ $room->roomnum }}
                                                <input type="checkbox" id="room" name="room[]" data-name="Room {{ $room->roomnum }}data-price="{{ $room->price }}" value="{{ $room->roomID }}" {{ in_array($room->roomID, old('roomID', [])) ? 'checked' : '' }} class="room-checkbox">
                                                <div class="room-card">
                                                    <img src="{{ asset('storage/' . $room->image) }}" alt="Room Image">
                                                    <div class="room-details">
                                                        <p>Price: ₱ {{ $room->price }}</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>  
                                    @endforeach
                                </div>
                                <button type="button" class="scroll-btn right-btn">&#9654;</button>
                            </div>
                        </div>

                        <!--Cottage Selection-->
                        <div class="label-container toggle-header" data-target="cottage-content">
                            <h2>Cottage Selection</h2>
                            <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
                        </div>
                        <div id="cottage-content" class="collapsible-content">
                            <div class="room-selection-wrapper">     
                                <button type="button" class="scroll-btn left-btn">&#9664;</button>
                                <div id="cottage-selection">
                                    @foreach($cottages as $cottage)
                                        <div class="cottage">
                                            <label id="cottage-name" for="cottage">{{ $cottage->cottagename }}
                                                <input type="checkbox" id="cottage" name="cottage[]" data-name="{{ $cottage->cottagename }}" data-price="{{ $cottage->price }}" value="{{ $cottage->cottageID }}" {{ in_array($cottage->cottageID, old('cottageID', [])) ? 'checked' : '' }} class="cottage-checkbox">
                                                <div class="cottage-card">
                                                    <img src="{{ asset('storage/' . $cottage->image) }}" alt="Cottage Image">
                                                    <div class="cottage-details">
                                                        <p>Price: ₱ {{ $cottage->price }}</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="scroll-btn right-btn">&#9654;</button>
                            </div>
                        </div>
                        
                        <!--Amenity Selection-->
                        <!--Amenity Selection-->
                        <div class="label-container toggle-header" data-target="amenity-content">
                            <h2>Amenity Selection</h2>
                            <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
                        </div>
                        <div id="amenity-content" class="collapsible-content">
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
                                                    data-price="{{ $amenity->price }}" 
                                                    value="{{ $amenity->amenityID }}" 
                                                    {{ in_array($amenity->amenityID, old('amenity', [])) ? 'checked' : '' }} 
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

                    </div>
                    <div class="label-container" id="guest-label">
                        <h2>Guest Information</h2>
                        <button type="button" id="alreadyLogin" class="form-button">Click here if guest already has account</button>
                    </div>
                    <div class="guest-info-container">
                        <div>
                            <label id="label" for="firstname">Firstname:
                                <input class="input" type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                            </label>
                            
                            @error('firstname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <label id="label" for="lastname">Lastname:
                                <input class="input" type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                            </label>
                            
                            @error('lastname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div id="row2">
                        <div>
                            <label for="txtcontactnum">Contact #:</label>
                            <div style="display:flex; flex-direction: row;gap:.2rem;">
                                <span style="display:flex; align-items:center; padding:.5rem; background: rgba(240, 240, 240, 0.822); border: 1px solid black; border-radius: .5rem .2rem .2rem .5rem; width:9%;">+63</span>
                                <input class="input" id="txtcontactnum" type="text"  maxlength="10" placeholder="912345678" name="contactnum" value="{{ old('contactnum')}}" style="border-radius: .2rem .5rem .5rem .2rem; width:90.3%">
                            </div>
                        </div>
                        <div>
                            <label for="txtemail">Email:</label> 
                            <input class="input" id="txtemail" type="email" placeholder="@email.com.." name="email" value="{{ old('email')}}" required>  
                        </div>
                    </div>

                    <div id="row3">
                        <div>
                            <label for="txtgender">Gender:</label>
                            <select class="input" id="txtgender"  name="gender">
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
                        </div>
                        <div> 
                        {{-- Birthday --}}
                        <label id="lblbirthday" for="txtbirthday">
                            Birthday:
                        </label>
                        <input class="input" id="txtbirthday" type="date" name="birthday"
                            value="{{ old('birthday') }}">
                        </div>
                    </div>

                    {{-- Valid ID --}}
                     <div class="cl-validID" id="row4">
                        <label for="txtvalidid">Import Valid ID</label>
                        <div>
                            <img id="id-preview" src="{{ asset('images/photo.png') }}">
                            <input class="input" id="txtvalidid" type="file" accept=".png, .jpg, .jpeg, .webp" name="validID">
                        </div>
                    </div>

                    <div class="label-container">
                        <h3>Account Information</h3>
                    </div>

                    <div id="row5" class="user-information">
                        <div>
                            <label for="txtusername">Username:</label> 
                            <input class="input" id="txtusername" type="text" placeholder="Username" name="username" value="{{ old('username')}}" required>
                        </div>
                         
                    </div>

                    <div id="row6" class="user-information">
                        <div>
                            <label for="txtpassword">Password:</label> 
                            <input class="input" id="txtpassword" type="text" placeholder="Password" name="password">
                        </div> 
                        <div>
                            <label for="txtcpassword">Confirm Password:</label> 
                            <input class="input" id="txtcpassword" type="text" placeholder="Confirm Password" name="password_confirmation">
                            <small id="password-match-msg" style="color: red; display: none; margin-top:.5rem;"><i class="fas fa-info-circle"></i> Password does not match.</small>
                        </div> 
                    </div>

                    <div id="row7">
                        <div>
                            <label for="avatar">Select Avatar:</label>
                            <img id="pfp-preview" src="{{ asset('images/profile.jpg')}}">
                            <input id="txtavatar" type="file" accept=".png, .jpg, .jpeg, .webp" name="avatar">
                            @error('validAvatar')
                                <div class="error-message" id="avatar-error-message">{{ $message}}</div>
                            @enderror 
                        </div> 
                    </div>

                    <div class="label-container">
                        <h3>Guest Count</h3>
                    </div>

                        <div>
                            <label id="label" for="guestamount">Number of Guest:
                                <input class="input" type="text" id="guestamount" name="guestamount" value="{{ old('guestamount') }}" required>
                            </label>
                            
                            @if($errors->has('guestamount'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('guestamount') }}
                                </div>
                            @endif
                        </div>

                        <div class="guest-counts" id="guest-counts" style="display:flex;">
                            <label id="label">Adult Guests:
                                <input type="number" min="0" name="amenity_adult_guest" class="input" placeholder="Enter number of adults" value="{{ old('amenity_adult_guest') }}">
                            </label>
                            <label id="label">Child Guests:
                                <input type="number" min="0" name="amenity_child_guest" class="input" placeholder="Enter number of children" value="{{ old('amenity_child_guest') }}">
                            </label>
                        </div>

                    </div>

                </div>
                <div class="button-container">
                    <button type="button" id="cancel-button" class="form-button" data-url="{{ url('receptionist/booking') }}">Cancel</button>
                    <button type="submit" id="submit-button" class="form-button">Submit Booking</button>
                </div>
            </form>
        </div>
        @if($errors->any())
            <alert class="alert-message">
                <strong>Something went wrong:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>   
                    @endforeach
                </ul>
            </alert>
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
        #booking{color:orange;}
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
            transition: width 0.3s ease-in-out;
            margin-left:12rem;
            margin-right:.7rem;
            overflow-y: hidden;
            overflow-x: hidden;
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
            font-size: .9rem;
        }
        #form-container{
            display:flex;
            flex-direction: column;
            height:80vh;
            width:100%;
            padding:1rem;
            margin-top:.5rem;
            border-radius:.7rem;
            background:white;
            overflow-y:auto;
            overflow-x:hidden;
            gap:.5rem;
            border:1px solid black;
            box-shadow:.1rem .2rem 0 black;
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
        .room{
            display: flex;
            flex-direction: column; 
            gap:.5rem;
            margin-bottom:.5rem;
        }
        #room-name{
            font-size: .9rem;
            color: black;
            text-align: center;
            font-weight: bold;
        }

        .room-card{
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
        .room-card:hover{
            background:orange;
            transform:translateY(-.5rem);
        }
        .room-card.active{
            background: rgb(0, 86, 0);
            color:white;
            scale: .9;
        }
        .room-card img{
            width:100%;
            height:70%;
            object-fit: cover;
            margin-top:1rem;
        }
        .room-selection-wrapper {
            position: relative;
            display: flex;
            width: 100%;
            height:13rem;
        }
        #room-selection{
            display:flex;
            flex-direction: row;
            gap:.5rem;
            overflow-x: auto;
            scroll-behavior: smooth;
        }
        .cottage{
            display: flex;
            flex-direction: column; 
            gap:.5rem;
            margin-bottom:.5rem;
        }
        #cottage-name{
            font-size: .9rem;
            color: black;
            text-align: center;
            font-weight: bold;
        }

        .cottage-card{
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
        .cottage-card img{
            width:100%;
            height:70%;
            object-fit: cover;
            margin-top:1rem;
        }
        .cottage-card:hover{
            background:orange;
            transform:translateY(-.5rem);
        }
        .cottage-card.active{
            background: rgb(0, 86, 0);
            color:white;
            scale: .9;
        }

        #cottage-selection{
            display:flex;
            flex-direction: row;
            gap:.5rem;
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
        .collapsible-content {
            display: none;
            transition: all 0.3s ease;
        }

        .collapsible-content.active {
            display: block;
        }

        .toggle-header {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .toggle-icon.rotate {
            transform: rotate(180deg);
        }

        .guest-info-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width:100%;
            flex-wrap: wrap;
        }
        .guest-info-container div {
            display: flex;
            flex-direction: row;
            gap: .5rem;
            width: 100%;
        }
        #label{
            display:flex;
            width:100%;
            flex-direction: column;
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
        #checkin, #checkout {
            width: 100%;
            padding: .5rem;
            border: solid 1px black;
            background: white;
            border-radius: .5rem;
            font-size: .8rem;
        }
        

        #row1 div, #row2 div, #row3 div, #row5 div, #row6 div, #row7 div {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        #pfp-preview {
            height: 8rem;
            width: 8rem;
            border-radius: 50%;
            object-fit: cover;
        }

        #row4 div {
            display: flex;
            height: 15rem;
            width: auto;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        #row4 img {
            display: flex;
            height: auto;
            max-height: 80%;
            width: auto;
            min-width: 25%;
            object-fit: contain;
            border: 2px solid black;
            border-radius: 1.5rem;
        }
        .button-container {
            display: flex;
            flex-direction: row;
            margin-top: 1rem;
            
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

        .text-danger{
            color:red;
            font-style:italic;
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

    // ===== Alert Auto-Hide =====
    const message = document.querySelector('.alert-message');
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 3500);
    }

    // ===== Flatpickr Setup ====
    flatpickr("#txtbirthday", {
        dateFormat: "m/d/Y",
        maxDate: "today",
        defaultDate: "today",
        allowInput: true,
    });

    const today = new Date();
    today.setDate(today.getDate() + 1);

    const maxCheckin = new Date(today);
    maxCheckin.setMonth(maxCheckin.getMonth() + 1);

    let checkinDate = null;

    const checkinCalendar = flatpickr("#checkin", {
        dateFormat: "m/d/Y",
        allowInput: true,
        minDate: today,
        maxDate: maxCheckin,
        defaultDate: today,
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                checkinDate = selectedDates[0];

                const minCheckout = new Date(checkinDate);
                minCheckout.setDate(minCheckout.getDate() + 1);

                const maxCheckout = new Date(checkinDate);
                maxCheckout.setMonth(maxCheckout.getMonth() + 1);

                checkoutCalendar.set("minDate", minCheckout);
                checkoutCalendar.set("maxDate", maxCheckout);
                checkoutCalendar.clear();
            }
        }
    });

    const checkoutCalendar = flatpickr("#checkout", {
        dateFormat: "m/d/Y",
        allowInput: true
    });

    // ===== Toggle collapsible sections =====
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            const targetId = header.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = header.querySelector('.toggle-icon');

            const isOpen = content.classList.toggle('active');

            icon.classList.toggle('fa-chevron-up', isOpen);
            icon.classList.toggle('fa-chevron-down', !isOpen);
        });
    });

    const roomContent = document.getElementById('room-content');
    const roomIcon = document.querySelector('[data-target="room-content"] .toggle-icon');
    roomContent.classList.add('active');
    roomIcon.classList.add('fa-chevron-up');
    roomIcon.classList.remove('fa-chevron-down');

    // ===== Card activation =====
    function activateCard(cardSelector) {
        document.querySelectorAll(cardSelector).forEach(card => {
            card.addEventListener('click', function (e) {
                e.preventDefault();
                const checkbox = this.closest('label').querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('active', checkbox.checked);
                }
            });
        });
    }

    activateCard('.room-card');
    activateCard('.cottage-card');
    activateCard('.amenity-card');

    // ===== Scroll buttons =====
    const scrollAmount = 300;

    function updateScrollButtons(wrapper) {
        const container = wrapper.querySelector('.scroll-container') || wrapper.querySelector('div[id$="-selection"]');
        const leftBtn = wrapper.querySelector('.left-btn');
        const rightBtn = wrapper.querySelector('.right-btn');

        if (!container || !leftBtn || !rightBtn) return;

        const isScrollable = container.scrollWidth > container.clientWidth;

        leftBtn.style.display = isScrollable ? 'flex' : 'none';
        rightBtn.style.display = isScrollable ? 'flex' : 'none';

        leftBtn.onclick = () => container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        rightBtn.onclick = () => container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }

    function refreshAllScrollButtons() {
        document.querySelectorAll('.room-selection-wrapper').forEach(updateScrollButtons);
    }

    refreshAllScrollButtons();
    window.addEventListener('resize', refreshAllScrollButtons);

    // ===== Cancel button functionality =====
    const cancelBTN = document.getElementById('cancel-button');
    if (cancelBTN) {
        cancelBTN.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            window.location.href = url;
        });
    }

    // ===== Image Preview (Valid ID & Avatar) =====
    function previewImage(inputSelector, imgSelector) {
        const input = document.querySelector(inputSelector);
        const img = document.querySelector(imgSelector);
        if (!input || !img) return;

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => img.src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    previewImage('#txtvalidid', '#id-preview');
    previewImage('#txtavatar', '#pfp-preview');

    // ===== Password Match Validation =====
    const password = document.getElementById('txtpassword');
    const confirmPassword = document.getElementById('txtcpassword');
    const matchMsg = document.getElementById('password-match-msg');

    function checkPasswordMatch() {
        if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
            matchMsg.style.display = 'inline';
        } else {
            matchMsg.style.display = 'none';
        }
    }

    password.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    // ===== Guest Already Has Account Toggle =====
    const guestInfoLabel = document.getElementById('guest-label');
    const accountInfoFields = document.querySelectorAll('.user-information, #row4, #row2, #row3, #row7');

    const toggleBtn = document.getElementById('alreadyLogin');
    toggleBtn.type = 'button';
    toggleBtn.textContent = 'Click here if guest already has account';
    toggleBtn.style.marginLeft = '1rem';
    toggleBtn.classList.add('form-button');
    guestInfoLabel.appendChild(toggleBtn);

    let guestHasAccount = false;

    toggleBtn.addEventListener('click', function () {
        guestHasAccount = !guestHasAccount;

        accountInfoFields.forEach(field => {
            field.style.display = guestHasAccount ? 'none' : '';
            field.querySelectorAll('input, select').forEach(input => {
                if (input.id !== 'firstname' && input.id !== 'lastname') {
                    input.required = !guestHasAccount;
                }
            });
        });

        toggleBtn.textContent = guestHasAccount
            ? 'Click here if guest does NOT have account'
            : 'Click here if guest already has account';
    });

    // ===== Guest & User Info Validation on Submit =====
    document.getElementById('submit-button').addEventListener('click', function (e) {
        let valid = true;
        let errors = [];

        const firstName = document.getElementById('firstname');
        const lastName = document.getElementById('lastname');
        const contactNum = document.getElementById('txtcontactnum');
        const email = document.getElementById('txtemail');

        if (!firstName.value.trim()) { valid = false; errors.push('First name is required.'); }
        if (!lastName.value.trim()) { valid = false; errors.push('Last name is required.'); }

        if (!guestHasAccount) {
            if (!/^\d{10}$/.test(contactNum.value)) {
                valid = false; errors.push('Contact number must be 10 digits.');
            }
            if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                valid = false; errors.push('Invalid email format.');
            }
            if (password.value && password.value !== confirmPassword.value) {
                valid = false; errors.push('Passwords do not match.');
            }
        }

        if (!valid) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });

    // =================== HIDE SELECTIONS IF CONFLICT ====================
    
});
</script>



