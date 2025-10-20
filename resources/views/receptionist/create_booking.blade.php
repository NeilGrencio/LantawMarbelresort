<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background: #f3f4f6;
        }

        main {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
        }

        .booking-page {
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .section-title {
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: .5rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: .3rem;
        }

        input, select {
            padding: 0.6rem;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
        }

        .input-prefix {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .input-prefix span {
            background: #f5f5f5;
            padding: 0.5rem 0.75rem;
            border-right: 1px solid #ccc;
        }

        .input-prefix input {
            border: none;
            flex: 1;
            padding: 0.5rem;
        }

        .scroll-container {
            display: flex;
            overflow-x: auto;
            gap: 1rem;
            padding-bottom: 0.5rem;
        }

        .card-item {
            flex: 0 0 auto;
            width: 180px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0.5rem;
            background: #fff;
            text-align: center;
            transition: all 0.2s ease;
        }

        .card-item img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .card-item:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }

        .card-item input[type="checkbox"] {
            display: none;
        }

        .card-item h3 {
            font-size: 1rem;
            margin: 0.25rem 0;
        }

        .card-item p {
            font-size: 0.9rem;
            color: #555;
        }

        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
        }

        .alert-danger {
            background: #ffe5e5;
            color: #d10000;
        }

        .alert-warning {
            background: #fff8e1;
            color: #997000;
        }
        .card-item {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
        }
        .card-item.selected {
            border-color: #007bff;
            background-color: #e7f1ff;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
        }
        .suggestion-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
            z-index: 100;
            display: none;
            max-height: 160px;
            overflow-y: auto;
        }
        .suggestion-box li {
            padding: 8px 10px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .suggestion-box li:hover {
            background: #f2f2f2;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color, #007bff);
            border: 1px solid var(--primary-color, #007bff);
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-outline:hover {
            background: var(--primary-color, #007bff);
            color: #fff;
        }
        .error-border {
            border: 2px solid #e74c3c !important;
            background-color: #fdecea;
        }
        .flatpickr-calendar {
            font-size: 0.95rem;
            z-index: 9999 !important;
        }
        /* Quantity Selector Container */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Number Input */
        .quantity-selector input[type="number"] {
            width: 50px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 0.25rem;
            font-size: 1rem;
        }

        /* Increase/Decrease Buttons */
        .quantity-selector button {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }

        /* Hover Effect */
        .quantity-selector button:hover {
            background-color: #e0e0e0;
            transform: scale(1.1);
        }

        /* Disable button look */
        .quantity-selector button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

    </style>
</head>

<body>
    {{-- ===========================
        SIDEBAR INCLUDE
    ============================ --}}
    @include('components.receptionist_sidebar')

    <main>
        <div class="booking-page">

            {{-- ===========================
                🧾 BOOKING FORM START
            ============================ --}}
            <form action="{{ url('receptionist/submit_booking') }}" method="POST" enctype="multipart/form-data" class="booking-form">
                @csrf

                {{-- ===========================
                    SECTION 1: BOOKING DETAILS
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Booking Details</h2>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="checkin">Check-in Date</label>
                            <input type="date" name="checkin" id="checkin" value="{{ old('checkin') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check-out Date</label>
                            <input type="date" name="checkout" id="checkout" value="{{ old('checkout') }}" required>
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 2: GUEST COUNT
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Guest Count</h2>
                    <div class="form-group">
                        <label for="guestamount">Total Guests</label>
                        <input type="text" name="guestamount" id="guestamount" value="{{ old('guestamount') }}" readonly>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="amenity_adult_guest">Adults</label>
                            <input type="number" name="amenity_adult_guest" id="amenity_adult_guest" value="{{ old('amenity_adult_guest', 0) }}" min="0">
                        </div>
                        <div class="form-group">
                            <label for="amenity_child_guest">Children</label>
                            <input type="number" name="amenity_child_guest" id="amenity_child_guest" value="{{ old('amenity_child_guest', 0) }}" min="0">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 3: ROOM SELECTION
                ============================ --}}
                <section class="form-section">
                    <div class="form-group">
                        <label for="room_count">Selected Rooms</label>
                        <input type="text" name="room_count" id="room_count" value="{{ old('room_count', '') }}" readonly>
                    </div>

                    <h2 class="section-title">Room Selection</h2>
                    <div class="scroll-container">
                        @foreach($rooms as $room)
                            <div class="card-item" 
                                data-roomtype="{{ $room->roomtype }}" 
                                data-max="{{ $room->maxcapacity }}" 
                                data-base="{{ $room->basecapacity }}"
                                data-price="{{ $room->price }}"
                                data-extra="{{ $room->extra }}">
                                
                                <img src="{{ $room->image_url }}" alt="Room {{ $room->roomtype }}">
                                <h3>{{ $room->roomtype }}</h3>
                                <p>₱{{ number_format($room->price, 2) }} per room (Base capacity: {{ $room->basecapacity }} guests)</p>

                                <div class="quantity-selector">
                                    <button type="button" class="decrease">-</button>
                                    <input type="number" 
                                        name="room[{{ $room->roomtypeID }}]" 
                                        value="{{ old('room.' . $room->roomtypeID, 0) }}" 
                                        min="0" 
                                        max="{{ $room->maxcapacity }}">
                                    <button type="button" class="increase">+</button>
                                </div>

                                <small>Max capacity per room: {{ $room->maxcapacity }} Base Capacity per room: {{ $room->basecapacity }}
                                    <br> Extra per guest above base: ₱{{ number_format($room->extra, 2) }}</small>
                            </div>
                        @endforeach
                    </div>
                </section>


                {{-- ===========================
                    SECTION 4: COTTAGE SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Cottage Selection</h2>
                    <div class="scroll-container">
                        @foreach($cottages as $cottage)
                            <label class="card-item">
                                <input type="checkbox" name="cottage[]" value="{{ $cottage->cottageID }}" {{ in_array($cottage->cottageID, old('cottage', [])) ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $cottage->image) }}" alt="{{ $cottage->cottagename }}">
                                <h3>{{ $cottage->cottagename }}</h3>
                                <p>₱{{ number_format($cottage->price, 2) }}</p>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- ===========================
                    SECTION 5: AMENITY SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Amenity Selection</h2>
                    <div class="scroll-container">
                        @foreach($amenities as $amenity)
                            <label class="card-item">
                                <input type="checkbox" name="amenity[]" value="{{ $amenity->amenityID }}" {{ in_array($amenity->amenityID, old('amenity', [])) ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $amenity->image) }}" alt="{{ $amenity->amenityname }}">
                                <h3>{{ $amenity->amenityname }}</h3>
                                <p>₱{{ number_format($amenity->bookprice, 2) }}</p>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- ===========================
                    SECTION 5.5: Extra SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Extra Selection</h2>
                    <div class="scroll-container">
                        @foreach($extras as $extra)
                            <div class="card-item">
                                <img src="{{ asset('storage/' . $extra->image) }}" alt="{{ $extra->amenityname }}">
                                <h3>{{ $extra->amenityname }}</h3>
                                <p>₱{{ number_format($extra->bookprice, 2) }}</p>
                                <div class="quantity-selector">
                                    <button type="button" class="decrease">-</button>
                                    <input type="number" name="extra[{{ $extra->amenityID }}]" value="{{ old('extra.' . $extra->amenityID, 0) }}" min="0">
                                    <button type="button" class="increase">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>


                {{-- ===========================
                    SECTION 6: GUEST INFORMATION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Guest Information</h2>

                    {{-- Name Inputs with Suggestions --}}
                    <div class="grid-2">
                        <div class="form-group position-relative">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" required autocomplete="off">
                            <ul id="firstname-suggestions" class="suggestion-box"></ul>
                        </div>

                        <div class="form-group position-relative">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required autocomplete="off">
                            <ul id="lastname-suggestions" class="suggestion-box"></ul>
                        </div>
                    </div>

                    <button type="button" id="toggleReturningGuest" class="btn-outline mb-3">
                        Returning Guest? Click to Skip Personal Details
                    </button>

                    <div id="guestExtraFields">
                        <div class="grid-2">
                            <div class="form-group">
                                <label for="contactnum">Contact Number</label>
                                <div class="input-prefix">
                                    <span>+63</span>
                                    <input type="text" name="contactnum" id="contactnum" value="{{ old('contactnum') }}" maxlength="10">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Prefer not to say" {{ old('gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="validID">Upload Valid ID</label>
                            <input type="file" name="validID" id="validID" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 7: ACCOUNT INFORMATION
                ============================ --}}
                <section class="form-section" id="accountInformationSection">
                    <h2 class="section-title">Account Information</h2>

                    {{-- Toggle Button --}}
                    <div class="button-row mb-3">
                        <button type="button" id="toggleNoAccount" class="btn-outline">
                            Guest Without Account? Click to Skip Account Info
                        </button>
                    </div>

                    {{-- ACCOUNT FIELDS --}}
                    <div id="accountFields">
                        <div class="grid-2">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation">
                        </div>

                        <div class="form-group">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 8: FORM ACTIONS
                ============================ --}}
                <div class="form-actions">
                    <button type="button" id="cancel-button" class="btn btn-secondary" data-url="{{ url('receptionist/booking') }}">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Booking</button>
                </div>
            </form>

            {{-- ===========================
                FORM FEEDBACK
            ============================ --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ====== Alert Auto-Hide ======
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => alert.style.display = 'none', 3500);
    });

    // ====== Date Validation ======
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');

    function validateDates() {
        if (!checkinInput.value || !checkoutInput.value) return;
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);
        if (checkout <= checkin) {
            alert('Checkout date must be after check-in date.');
            checkoutInput.value = '';
        }
    }
    checkinInput?.addEventListener('change', validateDates);
    checkoutInput?.addEventListener('change', validateDates);

    // ====== Flatpickr ======
    flatpickr("#checkin", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1),
        maxDate: new Date().fp_incr(31),
        onChange: function (selectedDates) {
            if (!selectedDates[0]) return;
            flatpickr("#checkout", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                minDate: new Date(selectedDates[0]).fp_incr(1),
                maxDate: new Date(selectedDates[0]).fp_incr(31)
            }).open();
        }
    });

    flatpickr("#checkout", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(2),
        maxDate: new Date().fp_incr(32),
    });

    // ====== Guest Count ======
    const adultGuest = document.getElementById('amenity_adult_guest');
    const childGuest = document.getElementById('amenity_child_guest');
    const totalGuest = document.getElementById('guestamount');

    function updateTotalGuests() {
        const adults = parseInt(adultGuest?.value || 0);
        const children = parseInt(childGuest?.value || 0);
        if (totalGuest) totalGuest.value = adults + children;
    }

    [adultGuest, childGuest].forEach(i => i?.addEventListener('input', updateTotalGuests));
    updateTotalGuests();

    // ====== Checkbox Toggle for Cottages & Amenities ======
    document.querySelectorAll('.scroll-container .card-item').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (!checkbox) return; // skip rooms with number input

        // Sync class with checkbox
        checkbox.addEventListener('change', () => {
            card.classList.toggle('selected', checkbox.checked);
        });

        // Clicking card toggles checkbox
        card.addEventListener('click', e => {
            if (e.target.tagName === 'INPUT') return;
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    // ====== Quantity Selectors for Rooms & Extras ======
    document.querySelectorAll('.card-item').forEach(card => {
        const decreaseBtn = card.querySelector('.decrease');
        const increaseBtn = card.querySelector('.increase');
        const input = card.querySelector('input[type="number"]');
        if (!input) return; // skip checkbox-only cards

        const max = parseInt(input.max) || 999;

        decreaseBtn?.addEventListener('click', () => {
            let val = parseInt(input.value) || 0;
            if (val > 0) input.value = val - 1;
            updateRoomCount();
        });

        increaseBtn?.addEventListener('click', () => {
            let val = parseInt(input.value) || 0;
            if (val < max) input.value = val + 1;
            updateRoomCount();
        });

        input.addEventListener('input', () => {
            let val = parseInt(input.value) || 0;
            if (val < 0) input.value = 0;
            if (val > max) input.value = max;
            updateRoomCount();
        });
    });

    const roomCount = document.getElementById('room_count');

    function updateRoomCount() {
        let total = 0;
        document.querySelectorAll('.card-item input[type="number"][name^="room["]').forEach(input => {
            total += parseInt(input.value) || 0;
        });
        roomCount.value = total;
    }

    updateRoomCount(); // initialize on page load

    // ====== Validate at Least One Room Selection ======
    document.querySelector('.booking-form')?.addEventListener('submit', function (e) {
        updateRoomCount?.(); // ensure count is updated
        const roomInputs = Array.from(document.querySelectorAll('input[type="number"][name^="room["]'));
        const totalRooms = roomInputs.reduce((sum, input) => {
            const v = Number(input.value);
            return sum + (Number.isFinite(v) ? Math.max(0, Math.floor(v)) : 0);
        }, 0);

        if (totalRooms <= 0) {
            e.preventDefault();
            alert('Please select at least one room before submitting.');
            if (roomInputs.length) roomInputs[0].focus();
            return false;
        }
    });

    // ===== Toggle Returning Guest Fields =====
    const toggleReturningBtn = document.getElementById('toggleReturningGuest');
    const guestExtraFields = document.getElementById('guestExtraFields');

    toggleReturningBtn?.addEventListener('click', () => {
        const isVisible = guestExtraFields.style.display !== 'none';
        guestExtraFields.style.display = isVisible ? 'none' : '';
        ['contactnum','email','gender','birthday','validID'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (isVisible) el.removeAttribute('required');
            else if(id === 'email') el.setAttribute('required', 'required');
        });
        toggleReturningBtn.textContent = isVisible ? 'Show Personal Details' : 'Returning Guest? Click to Skip Personal Details';
    });

    // ===== Toggle Account Fields =====
    const toggleNoAccountBtn = document.getElementById('toggleNoAccount');
    const accountFields = document.getElementById('accountFields');

    toggleNoAccountBtn?.addEventListener('click', () => {
        const isVisible = accountFields.style.display !== 'none';
        accountFields.style.display = isVisible ? 'none' : '';
        ['username','password','password_confirmation','avatar'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (isVisible) el.removeAttribute('required');
            else if(id === 'username') el.setAttribute('required', 'required');
        });
        toggleNoAccountBtn.textContent = isVisible ? 'Show Account Information' : 'Guest Without Account? Click to Skip Account Info';
    });

});
</script>
