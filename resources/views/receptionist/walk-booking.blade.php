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

    <main style="margin-left:15rem;">
        <div class="booking-page">
            <h3>Walk-In Guest</h3>

            {{-- ===========================
                🧾 WALK-IN GUEST FORM
            ============================ --}}
            <form action="{{ url('receptionist/walk-booking') }}" method="POST" enctype="multipart/form-data" class="booking-form">
                @csrf

                {{-- ===========================
                    SECTION 1: BOOKING INFORMATION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Booking Information</h2>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="checkin">Check-in Date</label>
                            <input type="date" id="checkin" name="checkin" value="{{ old('checkin') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check-out Date</label>
                            <input type="date" id="checkout" name="checkout" value="{{ old('checkout') }}" required>
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 5: GUEST COUNT
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Guest Count</h2>
                    <div class="form-group">
                        <label for="guestamount">Total Guests</label>
                        <input type="text" id="guestamount" name="guestamount" value="{{ old('guestamount') }}" readonly>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="adult">Adult Guests</label>
                            <input type="number" id="adult" name="amenity_adult_guest" value="{{ old('amenity_adult_guest', 0) }}" min="0">
                        </div>
                        <div class="form-group">
                            <label for="child">Child Guests</label>
                            <input type="number" id="child" name="amenity_child_guest" value="{{ old('amenity_child_guest', 0) }}" min="0">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 2: ROOM SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Room Selection</h2>
                    <div class="scroll-container">
                        @foreach($rooms as $room)
                            <div class="card-item card-room" 
                                data-roomtype="{{ $room->roomtype }}" 
                                data-price="{{ $room->price }}" 
                                data-extra="{{ $room->extra }}"
                                data-roomid="{{ $room->roomtypeID }}">
                                
                                <img src="{{ asset('storage/' . $room->image) }}" alt="Room {{ $room->roomtype }}">
                                <h3>{{ $room->roomtype }}</h3>
                                <p>₱{{ number_format($room->price, 2) }} per night</p>

                                <div class="quantity-selector">
                                    <button type="button" class="decrease">-</button>
                                    <input type="number" 
                                        name="room[{{ $room->roomtypeID }}]" 
                                        value="{{ old('room.' . $room->roomtypeID, 0) }}" 
                                        min="0">
                                    <button type="button" class="increase">+</button>
                                </div>

                                <p class="room-subtotal">
                                    Subtotal: ₱<span>0.00</span>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-between font-semibold">
                        <span>Total Room Price:</span>
                        <span id="total-room-price">₱0.00</span>
                    </div>
                </section>


                {{-- ===========================
                    SECTION 3: COTTAGE SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Cottage Selection</h2>
                    <div class="scroll-container">
                        @foreach($cottages as $cottage)
                            <label class="card-item card-cottage">
                                <input type="checkbox" name="cottage[]" value="{{ $cottage->cottageID }}" {{ in_array($cottage->cottageID, old('cottage', [])) ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $cottage->image) }}" alt="{{ $cottage->cottagename }}">
                                <h3>{{ $cottage->cottagename }}</h3>
                                <p>₱{{ number_format($cottage->price, 2) }}</p>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- ===========================
                    SECTION 4: AMENITY SELECTION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Amenity Selection</h2>
                    <div class="scroll-container">
                        @foreach($amenities as $amenity)
                            <label class="card-item card-amenity">
                                <input type="checkbox" name="amenity[]" value="{{ $amenity->amenityID }}" {{ in_array($amenity->amenityID, old('amenity', [])) ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $amenity->image) }}" alt="{{ $amenity->amenityname }}">
                                <h3>{{ $amenity->amenityname }}</h3>
                                <p>Adult ₱{{ number_format($amenity->adultprice, 2) }} / Child ₱{{ number_format($amenity->childprice, 2) }}</p>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- ===========================
                    SECTION 6: GUEST INFORMATION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Guest Information</h2>

                    <button type="button" id="alreadyLogin" class="btn-outline mb-3">
                        Returning Guest? Click to Skip Details
                    </button>

                    <div class="grid-2">
                        <div class="form-group position-relative">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" autocomplete="off" required>
                            <ul id="firstname-suggestions" class="suggestion-box"></ul>
                        </div>

                        <div class="form-group position-relative">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" autocomplete="off" required>
                            <ul id="lastname-suggestions" class="suggestion-box"></ul>
                        </div>
                    </div>

                    <div id="guest-extra">
                        <div class="grid-2">
                            <div class="form-group">
                                <label for="contactnum">Contact Number</label>
                                <div class="input-prefix">
                                    <span>+63</span>
                                    <input type="text" id="contactnum" name="contactnum" value="{{ old('contactnum') }}" maxlength="10">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Prefer_not_to_say" {{ old('gender') == 'Prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="validID">Upload Valid ID</label>
                            <input type="file" id="validID" name="validID" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 7: ACCOUNT INFORMATION
                ============================ --}}
                <section class="form-section">
                    <h2 class="section-title">Account Information</h2>

                    <button type="button" id="toggleAccount" class="btn-outline mb-3">
                        Skip Account Creation
                    </button>

                    <div id="account-section">
                        <div class="grid-2">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                        <div class="form-group">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>
                </section>

                {{-- ===========================
                    SECTION 8: ACTION BUTTONS
                ============================ --}}
                <div class="form-actions">
                    <button type="button" id="cancel-button" class="btn btn-secondary" data-url="{{ url('receptionist/booking') }}">Cancel</button>
                    <button type="submit" class="btn btn-primary">Check-In Guest</button>
                </div>
            </form>

            {{-- ===========================
                ALERTS
            ============================ --}}
            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning mt-3">
                    {{ session('error') }}
                </div>
            @endif

        </div>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // ALERT AUTO-HIDE
    // =========================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => alert.style.display = 'none', 3500);
    });

    // =========================
    // FLATPICKR SETUP
    // =========================
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');

    const checkinPicker = flatpickr(checkinInput, {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: tomorrow,
        maxDate: new Date().fp_incr(1),
        defaultDate: tomorrow,
        onChange: function (selectedDates) {
            const checkinDate = selectedDates[0];
            if (checkinDate) {
                checkoutPicker.set('minDate', new Date(checkinDate).fp_incr(1));
                checkoutPicker.set('maxDate', new Date(checkinDate).fp_incr(32));
                checkoutPicker.open();
            }
        }
    });

    const checkoutPicker = flatpickr(checkoutInput, {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(2),
        maxDate: new Date().fp_incr(32),
    });

    // =========================
    // DATE VALIDATION
    // =========================
    function validateDates() {
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);
        if (checkout <= checkin) {
            alert('Checkout date must be after check-in date.');
            checkoutInput.value = '';
        }
    }
    checkinInput?.addEventListener('change', validateDates);
    checkoutInput?.addEventListener('change', validateDates);

    // =========================
    // ROOM QUANTITY & SUBTOTAL
    // =========================
    const roomCards = document.querySelectorAll('.card-item.card-room');
    const totalRoomPriceEl = document.getElementById('total-room-price');

    function updateRoomSubtotal(card) {
        const qtyInput = card.querySelector('input[type="number"]');
        const price = parseFloat(card.dataset.price) || 0;
        const quantity = parseInt(qtyInput.value || 0);
        const subtotal = price * quantity;
        card.querySelector('.room-subtotal span').textContent = subtotal.toFixed(2);
        updateTotalRoomPrice();
    }

    function updateTotalRoomPrice() {
        let total = 0;
        roomCards.forEach(card => {
            const qtyInput = card.querySelector('input[type="number"]');
            const price = parseFloat(card.dataset.price) || 0;
            const quantity = parseInt(qtyInput.value || 0);
            total += price * quantity;
        });
        totalRoomPriceEl.textContent = `₱${total.toFixed(2)}`;
    }

    roomCards.forEach(card => {
        const decreaseBtn = card.querySelector('.decrease');
        const increaseBtn = card.querySelector('.increase');
        const qtyInput = card.querySelector('input[type="number"]');

        decreaseBtn?.addEventListener('click', () => {
            let val = parseInt(qtyInput.value || 0);
            if (val > 0) qtyInput.value = val - 1;
            updateRoomSubtotal(card);
        });

        increaseBtn?.addEventListener('click', () => {
            let val = parseInt(qtyInput.value || 0);
            qtyInput.value = val + 1;
            updateRoomSubtotal(card);
        });

        qtyInput?.addEventListener('input', () => {
            if (parseInt(qtyInput.value) < 0) qtyInput.value = 0;
            updateRoomSubtotal(card);
        });

        updateRoomSubtotal(card);
    });

    // =========================
    // GUEST COUNT
    // =========================
    const adultGuest = document.getElementById('adult');
    const childGuest = document.getElementById('child');
    const totalGuest = document.getElementById('guestamount');

    function updateTotalGuests() {
        const adults = parseInt(adultGuest?.value || 0);
        const children = parseInt(childGuest?.value || 0);
        if (totalGuest) totalGuest.value = adults + children;
    }
    [adultGuest, childGuest].forEach(i => i?.addEventListener('input', updateTotalGuests));
    updateTotalGuests();

    // =========================
    // CARD TOGGLE FOR CHECKBOXES
    // =========================
    const checkboxCards = document.querySelectorAll('.card-item input[type="checkbox"]');
    checkboxCards.forEach(checkbox => {
        const card = checkbox.closest('.card-item');
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT') return;
            checkbox.checked = !checkbox.checked;
            card.classList.toggle('selected', checkbox.checked);
        });
    });

    // =========================
    // IMAGE PREVIEW
    // =========================
    function setupImagePreview(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            let existingImg = this.nextElementSibling;
            if (existingImg && existingImg.tagName === 'IMG') existingImg.remove();
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '100px';
            img.style.marginTop = '0.5rem';
            this.insertAdjacentElement('afterend', img);
        });
    }
    setupImagePreview('validID');
    setupImagePreview('avatar');

    // =========================
    // PASSWORD VALIDATION
    // =========================
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    function validatePasswordMatch() {
        if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match.');
        } else confirmPassword.setCustomValidity('');
    }
    [password, confirmPassword].forEach(i => i?.addEventListener('input', validatePasswordMatch));

    // =========================
    // CANCEL BUTTON
    // =========================
    const cancelBTN = document.getElementById('cancel-button');
    cancelBTN?.addEventListener('click', function () {
        window.location.href = this.dataset.url;
    });

    // =========================
    // RETURNING GUEST TOGGLE
    // =========================
    const returningBtn = document.getElementById('alreadyLogin');
    const guestExtra = document.getElementById('guest-extra');
    returningBtn?.addEventListener('click', () => {
        const hidden = guestExtra.style.display === 'none';
        guestExtra.style.display = hidden ? '' : 'none';
        ['contactnum','email','gender','birthday','validID'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (hidden) el.setAttribute('required','required');
            else el.removeAttribute('required');
        });
        returningBtn.textContent = hidden
            ? 'Returning Guest? Click to Skip Details'
            : 'Show Personal Details';
    });

    // =========================
    // ACCOUNT TOGGLE
    // =========================
    const toggleAccount = document.getElementById('toggleAccount');
    const accountSection = document.getElementById('account-section');
    toggleAccount?.addEventListener('click', () => {
        const hidden = accountSection.style.display === 'none';
        accountSection.style.display = hidden ? '' : 'none';
        ['username','password','password_confirmation','avatar'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (hidden) el.setAttribute('required','required');
            else el.removeAttribute('required');
        });
        toggleAccount.textContent = hidden
            ? 'Skip Account Creation'
            : 'Show Account Information';
    });

    // =========================
    // NAME SUGGESTIONS
    // =========================
    function setupSuggestion(inputId, suggestionId) {
        const input = document.getElementById(inputId);
        const suggestionBox = document.getElementById(suggestionId);
        if (!input || !suggestionBox) return;

        input.addEventListener('input', async function () {
            const query = this.value.trim();
            suggestionBox.innerHTML = '';
            suggestionBox.style.display = 'none';
            if (query.length < 2) return;
            try {
                const response = await fetch(`{{ route('receptionist.guestSuggestions') }}?q=${encodeURIComponent(query)}`);
                const guests = await response.json();
                if (guests.length > 0) {
                    guests.forEach(g => {
                        const li = document.createElement('li');
                        li.textContent = `${g.firstname} ${g.lastname}`;
                        li.addEventListener('click', () => {
                            document.getElementById('firstname').value = g.firstname;
                            document.getElementById('lastname').value = g.lastname;
                            document.getElementById('contactnum').value = g.contactnum || '';
                            document.getElementById('email').value = g.email || '';
                            document.getElementById('gender').value = g.gender || '';
                            document.getElementById('birthday').value = g.birthday || '';
                            suggestionBox.innerHTML = '';
                            suggestionBox.style.display = 'none';
                        });
                        suggestionBox.appendChild(li);
                    });
                    suggestionBox.style.display = 'block';
                }
            } catch (err) {
                console.error('Suggestion fetch error:', err);
            }
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
                suggestionBox.innerHTML = '';
                suggestionBox.style.display = 'none';
            }
        });
    }
    setupSuggestion('firstname', 'firstname-suggestions');
    setupSuggestion('lastname', 'lastname-suggestions');

    // =========================
    // AVAILABILITY CHECK
    // =========================
    async function checkAvailability() {
        const checkin = checkinInput.value;
        const checkout = checkoutInput.value;
        if (!checkin || !checkout) return;

        try {
            const response = await fetch(`{{ route('receptionist.checkAvailability') }}?checkin=${checkin}&checkout=${checkout}`);
            const data = await response.json();

            hideBookedItems('.card-item.card-room', data.bookedRooms, 'room[]');
            hideBookedItems('.card-item', data.bookedCottages, 'cottage[]');
        } catch (err) {
            console.error('Error checking availability:', err);
        }
    }

    function hideBookedItems(selector, bookedIDs, checkboxName) {
        document.querySelectorAll(selector).forEach(container => {
            const checkbox = container.querySelector(`input[name="${checkboxName}"]`);
            if (checkbox) {
                const itemID = parseInt(checkbox.value);
                if (bookedIDs.includes(itemID)) {
                    container.style.opacity = '0.4';
                    container.style.pointerEvents = 'none';
                } else {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            }
        });
    }

    checkinInput?.addEventListener('change', checkAvailability);
    checkoutInput?.addEventListener('change', checkAvailability);

});
</script>

</body>
</html>