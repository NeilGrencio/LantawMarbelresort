@extends('layouts.app')

@section('content')
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <div id="layout-header">
                <h1>Edit Booking #{{ $bookingData->bookingID }}</h1>
            </div>

            @if (session('success'))
                <div class="alert-message">{{ session('success') }}</div>
            @endif

            <form action="{{ url('receptionist/update_booking/' . $bookingData->bookingID) }}" method="POST">
                @csrf
                <div id="form-container">

                    <!-- Rooms Selection -->
                    <div id="room-content" class="room-selection-wrapper">
                        <button type="button" class="scroll-btn left-btn">&#9664;</button>
                        <div id="room-selection">
                            @foreach ($rooms as $room)
                                @php
                                    $selectedRooms = $bookingData->rooms; // already an array of roomIDs
                                @endphp
                                <div class="room">
                                    <label id="room-name" for="room-{{ $room->roomID }}">
                                        Room {{ $room->roomnum }}
                                        <input type="checkbox" id="room-{{ $room->roomID }}" name="room[]"
                                            value="{{ $room->roomID }}"
                                            {{ in_array($room->roomID, $selectedRooms) ? 'checked' : '' }}>
                                        <div
                                            class="room-card {{ in_array($room->roomID, $selectedRooms) ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $room->image) }}" alt="Room Image">
                                            <p>Price: ₱{{ $room->price }}</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="scroll-btn right-btn">&#9654;</button>
                    </div>

                    <!-- Cottages Selection -->
                    <div id="cottage-content" class="cottage-selection-wrapper">
                        <button type="button" class="scroll-btn left-btn">&#9664;</button>
                        <div id="cottage-selection">
                            @foreach ($cottages as $cottage)
                                @php
                                    $selectedCottages = $bookingData->cottages; // already an array of cottageIDs
                                @endphp
                                <div class="cottage">
                                    <label id="cottage-name" for="cottage-{{ $cottage->cottageID }}">
                                        Cottage {{ $cottage->cottagename }}
                                        <input type="checkbox" id="cottage-{{ $cottage->cottageID }}" name="cottage[]"
                                            value="{{ $cottage->cottageID }}"
                                            {{ in_array($cottage->cottageID, $selectedCottages) ? 'checked' : '' }}>
                                        <div
                                            class="cottage-card {{ in_array($cottage->cottageID, $selectedCottages) ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $cottage->image) }}" alt="Cottage Image">
                                            <p>Price: ₱{{ $cottage->price }}</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="scroll-btn right-btn">&#9654;</button>
                    </div>

                    <!-- Amenities Selection -->
                    <div id="amenity-content" class="amenity-selection-wrapper">
                        @foreach ($amenities as $amenity)
                            @php
                                $selectedAmenities = $bookingData->amenities; // already an array of amenityIDs
                            @endphp
                            <div class="amenity">
                                <label for="amenity-{{ $amenity->amenityID }}">
                                    {{ $amenity->amenityname }}
                                    <input type="checkbox" id="amenity-{{ $amenity->amenityID }}" name="amenity[]"
                                        value="{{ $amenity->amenityID }}"
                                        {{ in_array($amenity->amenityID, $selectedAmenities) ? 'checked' : '' }}>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    {{-- Booking Information --}}
                    <div class="label-container">
                        <h2>Booking Information</h2>
                    </div>
                    <label>Check-in:
                        <input type="date" name="checkin"
                            value="{{ old('checkin', \Carbon\Carbon::parse($bookingData->checkin)->format('Y-m-d')) }}"
                            required>
                    </label>
                    <label>Check-out:
                        <input type="date" name="checkout"
                            value="{{ old('checkout', \Carbon\Carbon::parse($bookingData->checkout)->format('Y-m-d')) }}"
                            required>
                    </label>
                    {{-- Guest Info --}}
                    <div class="label-container">
                        <h2>Guest Information</h2>
                    </div>
                    <label>Firstname:
                        <input type="text" name="firstname" value="{{ old('firstname', $bookingData->firstname) }}"
                            required>
                    </label>
                    <label>Lastname:
                        <input type="text" name="lastname" value="{{ old('lastname', $bookingData->lastname) }}"
                            required>
                    </label>
                    <label>Total Guests:
                        <input type="number" name="guestamount"
                            value="{{ old('guestamount', $bookingData->guestamount) }}" required>
                    </label>
                    <label>Adults:
                        <input type="number" name="adultguest" value="{{ old('adultguest', $bookingData->adultguest) }}"
                            required>
                    </label>
                    <label>Children:
                        <input type="number" name="childguest" value="{{ old('childguest', $bookingData->childguest) }}"
                            required>
                    </label>

                </div>

                <div class="button-container">
                    <button type="button" class="form-button" onclick="window.history.back()">Go Back</button>
                    <button type="submit" class="form-button">Update Booking</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Add your toggle and scroll functionality here (same as in your existing blade)
    </script>
@endsection
