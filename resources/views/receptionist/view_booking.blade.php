{{-- <html lang="en">
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
            <h1>Edit Booking</h1>
        </div>
            <form action="{{ url('receptionist/update_booking/' . $bookingData->bookingID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="form-container">
                    <div class="input-container">
                        <div class="label-container toggle-header" data-target="room-content">
                            <h2>Room Selection</h2>
                            <i class="fas fa-chevron-up toggle-icon fa-2x"></i>
                        </div>
                        <!--Room Selection-->
                        <div id="room-content" class="room-selection-wrapper">
                            <button type="button" class="scroll-btn left-btn">&#9664;</button>
                            <div id="room-selection">
                                @foreach($rooms as $room)
                                    @php
                                        $selectedRooms = [];
                                        if (old('room')) {
                                            $selectedRooms = old('room');
                                        } elseif (isset($bookingData) && $bookingData->room) {
                                            $selectedRooms = is_array($bookingData->room) ? $bookingData->room : explode(',', $bookingData->room);
                                        }
                                    @endphp
                                    <div class="room">
                                        <label id="room-name" for="room-{{ $room->roomID }}">Room {{ $room->roomnum }}
                                            <input type="checkbox"
                                                id="room-{{ $room->roomID }}"
                                                name="room[]"
                                                value="{{ $room->roomID }}"
                                                data-name="Room {{ $room->roomnum }}"
                                                data-price="{{ $room->price }}"
                                                {{ in_array($room->roomID, $selectedRooms) ? 'checked' : '' }}
                                                class="room-checkbox">
                                            <div class="room-card {{ in_array($room->roomID, $selectedRooms) ? 'active' : '' }}">
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

                        <div class="label-container toggle-header" data-target="cottage-content">
                            <h2>Cottage Selection</h2>
                            <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
                        </div>
                        <!-- Cottage Selection - Prefilled -->
                        <div class="room-selection-wrapper">
                            <button type="button" class="scroll-btn left-btn">&#9664;</button>
                            <div id="cottage-selection">
                                @foreach($cottages as $cottage)
                                    @php
                                        $selectedCottages = [];
                                        if (old('cottage')) {
                                            $selectedCottages = old('cottage');
                                        } elseif (isset($bookingData) && $bookingData->cottage) {
                                            $selectedCottages = is_array($bookingData->cottage) ? $bookingData->cottage : explode(',', $bookingData->cottage);
                                        }
                                    @endphp
                                    <div class="cottage">
                                        <label id="cottage-name" for="cottage-{{ $cottage->cottageID }}">{{ $cottage->cottagename }}
                                            <input type="checkbox"
                                                id="cottage-{{ $cottage->cottageID }}"
                                                name="cottage[]"
                                                value="{{ $cottage->cottageID }}"
                                                data-name="{{ $cottage->cottagename }}"
                                                data-price="{{ $cottage->price }}"
                                                {{ in_array($cottage->cottageID, $selectedCottages) ? 'checked' : '' }}
                                                class="cottage-checkbox">
                                            <div class="cottage-card {{ in_array($cottage->cottageID, $selectedCottages) ? 'active' : '' }}">
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

                        <div class="label-container toggle-header" data-target="amenity-content">
                            <h2>Amenity Selection</h2>
                            <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
                        </div>
                        <div class="room-selection-wrapper">
                            <button type="button" class="scroll-btn left-btn">&#9664;</button>
                            <div id="amenity-selection">
                                @foreach($amenities as $amenity)
                                    @php
                                        // Handle prefilled amenity data
                                        $selectedAmenities = [];
                                        if (old('amenity')) {
                                            $selectedAmenities = old('amenity');
                                        } elseif (isset($bookingData) && $bookingData->amenity) {
                                            $selectedAmenities = is_array($bookingData->amenity) ? $bookingData->amenity : explode(',', $bookingData->amenity);
                                        }
                                    @endphp
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
                                                {{ in_array($amenity->amenityID, $selectedAmenities) ? 'checked' : '' }}
                                                class="amenity-checkbox"
                                            >
                                            <div class="amenity-card {{ in_array($amenity->amenityID, $selectedAmenities) ? 'active' : '' }}">
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

                        <div class="label-container">
                            <h2>Booking Information</h2>
                        </div>
                        <!-- Booking Information - Prefilled -->
                        <label for="checkin">Check-in Date:
                            <input type="date" id="checkin" name="checkin"
                                 value="{{ old('checkin') ? \Carbon\Carbon::parse(old('checkin'))->format('Y-m-d') : (isset($bookingData->checkin) ? \Carbon\Carbon::parse($bookingData->checkin)->format('Y-m-d') : '') }}" required>
                        </label>

                        <label for="checkout">Check-out Date:
                            <input class="input" type="date" id="checkout" name="checkout"
                                value="{{ old('checkout') ? \Carbon\Carbon::parse(old('checkout'))->format('Y-m-d') : (isset($bookingData->checkout) ? \Carbon\Carbon::parse($bookingData->checkout)->format('Y-m-d') : '') }}" required>
                        </label>
                    <div class="label-container">
                        <h2>Guest Information</h2>
                    </div>
                    <div class="guest-info-container">
                        <div>
                            <label id="label" for="firstname">Guest Firstname:
                                <input class="input" type="text" id="firstname" name="firstname" value="{{ old('firstname', $bookingData->firstname ?? '') }}" required>
                            </label>

                            @error('firstname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <label id="label" for="lastname">Guest Lastname:
                                <input class="input" type="text" id="lastname" name="lastname" value="{{ old('lastname', $bookingData->lastname ?? '') }}" required>
                            </label>

                            @error('lastname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label id="label" for="guestamount">Guest Count:
                                <input class="input" type="text" id="guestamount" name="guestamount" value="{{ old('guestamount', $bookingData->guestamount ?? '') }}" required>
                            </label>
                            @error('guestamount')
                                <small class="text-danger">Error {{ $message }}</small>
                            @enderror
                        </div>

                        <div class="guest-counts" id="guest-counts" style="display:flex;">
                            <label id="label">Adult Guests:
                                <input type="number" min="0" name="amenity_adult_guest" class="input" value="{{ old('adultguest', $bookingData->adultguest ?? '')}}" placeholder="Enter number of adults">
                            </label>
                            <label id="label">Child Guests:
                                <input type="number" min="0" name="amenity_child_guest" class="input" value="{{ old('childguest', $bookingData->childguest ?? '')}}" placeholder="Enter number of children">
                            </label>
                        </div>

                    </div>
                </div>
                </div>
                <div class="button-container">
                    <button type="button" id="cancel-button" class="form-button" data-url="{{ url('receptionist/booking') }}">Go Back</button>
                    <button type="submit" id="submit-button" class="form-button">Submit Booking</button>
                    <button type="button" id="cncl-button" class="form-button" data-url="{{ url('receptionist/cancel_booking') }}">Cancel Booking</button>
                </div>
            </form>
        </div>
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
        margin-left:15rem;
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
        flex-direction: column;
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
        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        const today = new Date();
        today.setDate(today.getDate());

        const initialCheckinDate = "{{ old('checkin', $bookingData->checkin ?? '') }}";
        let checkinDate = initialCheckinDate ? new Date(initialCheckinDate) : today;
        const initialCheckoutDate = "{{ old('chcekout', $bookingData->checkout ?? '') }}";
        let chcekoutDate = new Date(initialCheckoutDate);

        const maxCheckin = new Date(today);
        maxCheckin.setMonth(maxCheckin.getMonth() + 1);

        const checkinCalendar = flatpickr("#checkin", {
            dateFormat: "m/d/Y",
            allowInput: true,
            defaultDate: checkinDate,
            minDate: checkinDate,
            maxDate: maxCheckin,
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
            allowInput: true,
            defaultDate: chcekoutDate,
            dateFormat: "m/d/Y",
            minDate: checkinDate ? new Date(checkinDate.setDate(checkinDate.getDate() + 1)) : today,
            maxDate: maxCheckin,
        });

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

        if (roomContent) {
            roomContent.classList.add('active');

            const roomIcon = document.querySelector('[data-target="room-content"] .toggle-icon');
            if (roomIcon) {
                roomIcon.classList.add('fa-chevron-up');
                roomIcon.classList.remove('fa-chevron-down');
            } else {
                console.error('room-content toggle icon not found.');
            }
        } else {
            console.error('room-content element not found.');
        }


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

        const cancelBTN = document.getElementById('cancel-button');
        if (cancelBTN) {
            cancelBTN.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                window.location.href = url;
            });
        }

        const cancelBookBTN = document.getElementById('cncl-button');
        if (cancelBookBTN) {
            cancelBookBTN.addEventListener('click', function(){
                const url = this.getAttribute('data-url');
                window.location.href = url;
            });
        }

        document.querySelectorAll('.amenity-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function(e) {
                const amenityID = e.target.value;
                const guestCountDiv = document.getElementById(`guest-counts-${amenityID}`);
                if (guestCountDiv) {
                    guestCountDiv.style.display = e.target.checked ? 'block' : 'none';
                }
            });

            if (checkbox.checked) {
                const guestCountDiv = document.getElementById(`guest-counts-${checkbox.value}`);
                if (guestCountDiv) {
                    guestCountDiv.style.display = 'block';
                }
            }
        });

    });
</script>



 --}}<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort - View Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
    margin-left:15rem;
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
.room, .cottage, .amenity{
    display: flex;
    flex-direction: column;
    gap:.5rem;
    margin-bottom:.5rem;
}
#room-name, #cottage-name, #amenity-name{
    font-size: .9rem;
    color: black;
    text-align: center;
    font-weight: bold;
}

.room-card, .cottage-card, .amenity-card{
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
.room-card img, .cottage-card img, .amenity-card img{
    width:100%;
    height:70%;
    object-fit: cover;
    margin-top:1rem;
}
.room-card:hover, .cottage-card:hover, .amenity-card:hover{
    background:orange;
    transform:translateY(-.5rem);
}
.room-card.active, .cottage-card.active, .amenity-card.active{
    background: rgb(0, 86, 0);
    color:white;
    scale: .9;
}
.room-selection-wrapper, #cottage-content, #amenity-content{
    position: relative;
    display: flex;
    width: 100%;
    height:13rem;
}
#room-selection, #cottage-selection, #amenity-selection{
    display:flex;
    flex-direction: row;
    gap:.5rem;
    overflow-x: auto;
    scroll-behavior: smooth;
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
.left-btn { left: 0.5rem; }
.right-btn { right: 0.5rem; }

.guest-info-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width:100%;
    flex-wrap: wrap;
}
.guest-info-container div {
    display: flex;
    flex-direction: column;
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
.text-danger{ color:red; font-style:italic; }

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
</style>

</head>
<body>
<div id="layout">
    @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>View Booking #{{ $bookingData->bookingID }}</h1>
        </div>
        <div id="form-container">

            <!-- Room Selection -->
            <div class="label-container toggle-header" data-target="room-content">
                <h2>Room Selection</h2>
                <i class="fas fa-chevron-up toggle-icon fa-2x"></i>
            </div>
            <div id="room-content" class="room-selection-wrapper active">
                <button type="button" class="scroll-btn left-btn">&#9664;</button>
                <div id="room-selection">
                    @foreach($rooms as $room)
                        @php
                            $selectedRooms = $bookingData->rooms ?? [];
                        @endphp
                        <div class="room">
                            <label id="room-name" for="room-{{ $room->roomID }}">
                                Room {{ $room->roomnum }}
                                <input type="checkbox"
                                       id="room-{{ $room->roomID }}"
                                       name="room[]"
                                       value="{{ $room->roomID }}"
                                       class="room-checkbox"
                                       {{ in_array($room->roomID, $selectedRooms) ? 'checked' : '' }}
                                       disabled>
                                <div class="room-card {{ in_array($room->roomID, $selectedRooms) ? 'active' : '' }}">
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

            <!-- Cottage Selection -->
            <div class="label-container toggle-header" data-target="cottage-content">
                <h2>Cottage Selection</h2>
                <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
            </div>
            <div id="cottage-content" class="room-selection-wrapper">
                <button type="button" class="scroll-btn left-btn">&#9664;</button>
                <div id="cottage-selection">
                    @foreach($cottages as $cottage)
                        @php
                            $selectedCottages = $bookingData->cottages ?? [];
                        @endphp
                        <div class="cottage">
                            <label id="cottage-name" for="cottage-{{ $cottage->cottageID }}">
                                {{ $cottage->cottagename }}
                                <input type="checkbox"
                                       id="cottage-{{ $cottage->cottageID }}"
                                       name="cottage[]"
                                       value="{{ $cottage->cottageID }}"
                                       class="cottage-checkbox"
                                       {{ in_array($cottage->cottageID, $selectedCottages) ? 'checked' : '' }}
                                       disabled>
                                <div class="cottage-card {{ in_array($cottage->cottageID, $selectedCottages) ? 'active' : '' }}">
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

            <!-- Amenity Selection -->
            <div class="label-container toggle-header" data-target="amenity-content">
                <h2>Amenity Selection</h2>
                <i class="fas fa-chevron-down toggle-icon fa-2x"></i>
            </div>
            <div id="amenity-content" class="room-selection-wrapper">
                <button type="button" class="scroll-btn left-btn">&#9664;</button>
                <div id="amenity-selection">
                    @foreach($amenities as $amenity)
                        @php
                            $selectedAmenities = $bookingData->amenities ?? [];
                        @endphp
                        <div class="amenity">
                            <label id="amenity-name" for="amenity-{{ $amenity->amenityID }}">
                                {{ $amenity->amenityname }}
                                <input type="checkbox"
                                       id="amenity-{{ $amenity->amenityID }}"
                                       name="amenity[]"
                                       value="{{ $amenity->amenityID }}"
                                       class="amenity-checkbox"
                                       {{ in_array($amenity->amenityID, $selectedAmenities) ? 'checked' : '' }}
                                       disabled>
                                <div class="amenity-card {{ in_array($amenity->amenityID, $selectedAmenities) ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $amenity->image) }}" alt="Amenity Image">
                                    <div class="amenity-details">
                                        <p>Adult Price: ₱ {{ $amenity->adultprice }}<br/>
                                           Child Price: ₱ {{ $amenity->childprice }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="scroll-btn right-btn">&#9654;</button>
            </div>

            <!-- Booking Information -->
            <div class="label-container"><h2>Booking Information</h2></div>
            <label>Check-in:
                <input type="date" value="{{ \Carbon\Carbon::parse($bookingData->checkin)->format('Y-m-d') }}" disabled>
            </label>
            <label>Check-out:
                <input type="date" value="{{ \Carbon\Carbon::parse($bookingData->checkout)->format('Y-m-d') }}" disabled>
            </label>

            <!-- Guest Information -->
            <div class="label-container"><h2>Guest Information</h2></div>
            <p><strong>Firstname:</strong> {{ $bookingData->firstname }}</p>
            <p><strong>Lastname:</strong> {{ $bookingData->lastname }}</p>
            <p><strong>Total Guests:</strong> {{ $bookingData->guestamount }}</p>
            <p><strong>Adults:</strong> {{ $bookingData->adultguest }}</p>
            <p><strong>Children:</strong> {{ $bookingData->childguest }}</p>

            <!-- Menu Orders -->
            @if($bookingData->menuOrders->count())
                <div class="label-container"><h2>Menu Orders</h2></div>
                <ul>
                    @foreach($bookingData->menuOrders as $menuOrder)
                        <li>{{ $menuOrder->Menu->name ?? 'Menu Item' }} - Quantity: {{ $menuOrder->quantity }}</li>
                    @endforeach
                </ul>
            @endif

            <!-- Billing & Payments -->
            @if($bookingData->billing)
                <div class="label-container"><h2>Billing & Payments</h2></div>
                <p><strong>Total Amount:</strong> ₱{{ $bookingData->billing->total ?? 0 }}</p>
                <p><strong>Status:</strong> {{ $bookingData->billing->status ?? 'N/A' }}</p>

                @if($bookingData->payments->count())
                    <h3>Payments</h3>
                    <ul>
                        @foreach($bookingData->payments as $payment)
                            <li>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }} - ₱{{ $payment->amount }} - {{ $payment->method }}</li>
                        @endforeach
                    </ul>
                @endif
            @endif

            <div class="button-container">
                <button type="button" class="form-button" onclick="window.history.back()">Go Back</button>
            </div>

        </div>
    </div>
</div>

@include('components.view_booking_styles') {{-- Move all your CSS to a separate file for cleanliness --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle sections
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            const targetId = header.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = header.querySelector('.toggle-icon');
            content.classList.toggle('active');
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
        });
    });

    const scrollAmount = 300;
    function updateScrollButtons(wrapper) {
        const container = wrapper.querySelector('div[id$="-selection"]');
        const leftBtn = wrapper.querySelector('.left-btn');
        const rightBtn = wrapper.querySelector('.right-btn');
        if (!container || !leftBtn || !rightBtn) return;
        const isScrollable = container.scrollWidth > container.clientWidth;
        leftBtn.style.display = isScrollable ? 'flex' : 'none';
        rightBtn.style.display = isScrollable ? 'flex' : 'none';
        leftBtn.onclick = () => container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        rightBtn.onclick = () => container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
    document.querySelectorAll('.room-selection-wrapper').forEach(updateScrollButtons);
    window.addEventListener('resize', () => document.querySelectorAll('.room-selection-wrapper').forEach(updateScrollButtons));
});
</script>
</body>
</html>
