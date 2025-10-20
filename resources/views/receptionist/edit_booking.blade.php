<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort - Edit Booking</title>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            display: flex;
        }

        /* Sidebar inclusion */
        #main-layout {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            margin-left:15rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .section h2 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }

        .scroll-container {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .card {
            flex: 0 0 200px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .card p {
            margin: 0.25rem 0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        .card input[type="checkbox"] {
            display: none;
        }

        .card.selected {
            border: 2px solid #007bff;
            background: #e7f1ff;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        label {
            margin-bottom: 0.3rem;
            font-weight: 500;
        }

        input,
        select {
            padding: 0.6rem;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 0.9rem;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #007bff;
        }

        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Scroll buttons */
        .scroll-btn {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .scroll-btn:hover {
            background: #007bff;
            color: white;
        }

        .alert {
            padding: 1rem;
            background: #ffe5e5;
            color: #d10000;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        @media(max-width: 768px) {
            .scroll-container {
                gap: 0.5rem;
            }

            .card {
                flex: 0 0 150px;
            }
        }
    </style>
</head>

<body>
    @include('components.receptionist_sidebar')

    <div id="main-layout">
        <h1>Edit Booking #{{ $bookingData->bookingID }}</h1>

        @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
        @endif

        <form action="{{ url('receptionist/update_booking/' . $bookingData->bookingID) }}" method="POST">
            @csrf

            <!-- Booking Info -->
            <div class="section">
                <h2>Booking Information</h2>
                <div class="form-group">
                    <label>Check-in</label>
                    <input id="checkin" type="date" name="checkin"
                        value="{{ old('checkin', \Carbon\Carbon::parse($bookingData->checkin)->format('Y-m-d')) }}"
                        required>
                </div>
                <div class="form-group">
                    <label>Check-out</label>
                    <input id="checkout" type="date" name="checkout"
                        value="{{ old('checkout', \Carbon\Carbon::parse($bookingData->checkout)->format('Y-m-d')) }}"
                        required>
                </div>
                 <div class="form-group">
                    <label>Total Guests</label>
                    <input type="number" name="guestamount" value="{{ old('guestamount', $bookingData->guestamount) }}"
                        required>
                </div>
                <div class="form-group">
                    <label>Adults</label>
                    <input type="number" name="adultguest" value="{{ old('adultguest', $bookingData->adultguest) }}"
                        required>
                </div>
                <div class="form-group">
                    <label>Children</label>
                    <input type="number" name="childguest" value="{{ old('childguest', $bookingData->childguest) }}"
                        required>
                </div>
            </div>

            <!-- Rooms -->
            <div class="section">
                <h2>Rooms</h2>
                <div class="scroll-container">
                    @foreach($rooms as $room)
                    <label class="card {{ in_array($room->roomID, $bookingData->rooms) ? 'selected' : '' }}">
                        <input type="checkbox" name="room[]" value="{{ $room->roomID }}"
                            {{ in_array($room->roomID, $bookingData->rooms) ? 'checked' : '' }}>
                        <img src="{{ asset('storage/' . $room->image) }}" alt="Room {{ $room->roomnum }}">
                        <p>Room {{ $room->roomnum }}</p>
                        <p>₱{{ number_format($room->price, 2) }}</p>
                        @if(isset($inclusionsByRoom[$room->roomnum]) && $inclusionsByRoom[$room->roomnum]->count())
                        <p><strong>Inclusions:</strong></p>
                        <ul style="padding-left:1rem;">
                            @foreach($inclusionsByRoom[$room->roomnum] as $inc)
                            <li>{{ $inc }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Cottages -->
            <div class="section">
                <h2>Cottages</h2>
                <div class="scroll-container">
                    @foreach($cottages as $cottage)
                    <label class="card {{ in_array($cottage->cottageID, $bookingData->cottages) ? 'selected' : '' }}">
                        <input type="checkbox" name="cottage[]" value="{{ $cottage->cottageID }}"
                            {{ in_array($cottage->cottageID, $bookingData->cottages) ? 'checked' : '' }}>
                        <img src="{{ asset('storage/' . $cottage->image) }}" alt="{{ $cottage->cottagename }}">
                        <p>{{ $cottage->cottagename }}</p>
                        <p>₱{{ number_format($cottage->price, 2) }}</p>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Amenities -->
            <div class="section">
                <h2>Extras</h2>
                <div class="scroll-container">
                    @foreach($amenities as $amenity)
                    <label class="card {{ in_array($amenity->amenityID, $bookingData->amenities) ? 'selected' : '' }}">
                        <input type="checkbox" name="amenity[]" value="{{ $amenity->amenityID }}"
                            {{ in_array($amenity->amenityID, $bookingData->amenities) ? 'checked' : '' }}>
                        <img src="{{ asset('storage/' . $amenity->image) }}" alt="{{ $amenity->amenityname }}">
                        <p>{{ $amenity->amenityname }}</p>
                        <p>Adult: ₱{{ $amenity->adultprice }}<br>Child: ₱{{ $amenity->childprice }}</p>
                    </label>
                    @endforeach
                </div>
            </div>

            

            <!-- Guest Info -->
            <div class="section">
                <h2>Guest Information</h2>
                <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" name="firstname" value="{{ old('firstname', $bookingData->firstname) }}" required>
                </div>
                <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" name="lastname" value="{{ old('lastname', $bookingData->lastname) }}" required>
                </div>
               
            </div>

            <div class="button-container">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Go Back</button>
                <button type="submit" class="btn btn-primary">Update Booking</button>
            </div>

        </form>
    </div>

    <script>
        // Highlight selected cards
        document.querySelectorAll('.card input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', e => {
                const card = e.target.closest('.card');
                if (e.target.checked) card.classList.add('selected');
                else card.classList.remove('selected');
            });
        });
    </script>
</body>

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

            // ====== Date Validation ======
            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');
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

            // ====== Flatpickr Setup for Check-in and Check-out ======
            flatpickr("#checkin", {
                altInput: true,
                altFormat: "F j, Y",        // Example: "October 18, 2025"
                dateFormat: "Y-m-d",        // Database-friendly format: YYYY/MM/DD
                minDate: new Date().fp_incr(1), // Tomorrow
                maxDate: new Date().fp_incr(31), // 1 month ahead
                onChange: function (selectedDates) {
                    const checkinDate = selectedDates[0];
                    if (checkinDate) {
                        const checkoutPicker = flatpickr("#checkout", {
                            altInput: true,
                            altFormat: "F j, Y",
                            dateFormat: "Y-m-d",
                            minDate: new Date(checkinDate).fp_incr(1), // 1 day after checkin
                            maxDate: new Date(checkinDate).fp_incr(31),
                        });
                        // Auto-open checkout when checkin selected
                        checkoutPicker.open();
                    }
                }
            });

            // Checkout initialized (default state)
            flatpickr("#checkout", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",         // Database-friendly format: YYYY/MM/DD
                minDate: new Date().fp_incr(2), // At least 2 days from today by default
                maxDate: new Date().fp_incr(32),
            });

            // ====== Availability Check ======
            async function checkAvailability() {
                const checkin = checkinInput.value;
                const checkout = checkoutInput.value;
                if (!checkin || !checkout) return;

                try {
                    const response = await fetch(`{{ route('receptionist.checkAvailability') }}?checkin=${checkin}&checkout=${checkout}`);
                    const data = await response.json();

                    hideBookedItems('.card', data.bookedRooms, 'room[]');
                    hideBookedItems('.card', data.bookedCottages, 'cottage[]');
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
