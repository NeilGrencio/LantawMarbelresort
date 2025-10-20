<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort - View Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            margin: 0;
            display: flex;
        }
        main {
            flex: 1;
            padding: 2rem;
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
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 2px solid #eee;
            padding-bottom: .5rem;
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
        }
        .card-item img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .card-item h3 {
            font-size: 1rem;
            margin: 0.25rem 0;
        }
        .card-item p {
            font-size: 0.9rem;
            color: #555;
            margin: 0.25rem 0;
        }
        .inclusions {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            background: #f0f0f0;
            border-radius: 6px;
            padding: 0.5rem;
        }
        .info-group {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .info-group p {
            font-weight: 500;
            margin: 0.3rem 0;
        }
        .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .action-buttons button {
        padding: 0.6rem 1.4rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        color: #fff;
    }

    .action-buttons button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-back {
        background: #6c757d;
        color: #fff;
    }

    .btn-approve {
        background: #007bff;
    }

    .btn-decline {
        background: #ffc107;
        color: #000;
    }

    .btn-extend {
        background: #2c2c2c;
        color: #000;
    }

    .btn-cancel {
        background: #dc3545;
    }
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; top: 0; width: 100%; height: 100%; 
        overflow: auto; 
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover { color: black; }

    .btn-confirm {
        padding: 0.5rem 1rem;
        background: #F78A21;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
    }

        </style>
    </head>
    <body>
        @include('components.receptionist_sidebar')

        <main style="margin-left:15rem;">
            <div class="booking-page">
                <h2>Booking #{{ $bookingData->bookingID }}</h2>

                {{-- Booking Information --}}
                <section class="form-section">
                    <h3 class="section-title">Booking Information</h3>
                    <div class="info-group">
                        <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($bookingData->checkin)->format('M d, Y') }}</p>
                        <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($bookingData->checkout)->format('M d, Y') }}</p>
                        <p><strong>Guest Name:</strong> {{ $bookingData->firstname }} {{ $bookingData->lastname }}</p>
                        <p><strong>Total Guests:</strong> {{ $bookingData->guestamount }} (Adults: {{ $bookingData->adultguest }}, Children: {{ $bookingData->childguest }})</p>
                    </div>
                </section>

                {{-- Rooms --}}
                <section class="form-section">
                    <h3 class="section-title">Rooms</h3>
                    <div class="scroll-container">
                        @foreach($rooms as $room)
                            @if(in_array((int)$room->roomID, array_map('intval', $bookingData->rooms)))
                            <div class="card-item">
                                <img src="{{ asset('storage/' . $room->image) }}" alt="Room {{ $room->roomnum }}">
                                <h3>Room {{ $room->roomnum }}</h3>
                                <p>₱{{ number_format($room->price, 2) }}</p>
                                <div class="inclusions">
                                    <strong>Inclusions:</strong>
                                    @if(isset($inclusionsByRoom[$room->roomnum]) && $inclusionsByRoom[$room->roomnum]->count())
                                        <ul style="padding-left:1rem; margin:0;">
                                            @foreach($inclusionsByRoom[$room->roomnum] as $inc)
                                                <li>{{ $inc }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No inclusions</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>

                {{-- Cottages --}}
                <section class="form-section">
                    <h3 class="section-title">Cottages</h3>
                    <div class="scroll-container">
                        @foreach($cottages as $cottage)
                            @if(in_array((int)$cottage->cottageID, array_map('intval', $bookingData->cottages)))
                            <div class="card-item">
                                <img src="{{ asset('storage/' . $cottage->image) }}" alt="{{ $cottage->cottagename }}">
                                <h3>{{ $cottage->cottagename }}</h3>
                                <p>₱{{ number_format($cottage->price, 2) }}</p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>


                {{-- Amenities --}}
                <section class="form-section">
                    <h3 class="section-title">Amenities</h3>
                    <div class="scroll-container">
                        @foreach($amenities as $amenity)
                            @if(in_array($amenity->amenityID, $bookingData->amenities))
                            <div class="card-item">
                                <img src="{{ asset('storage/' . $amenity->image) }}" alt="{{ $amenity->amenityname }}">
                                <h3>{{ $amenity->amenityname }}</h3>
                                <p>Adult ₱{{ number_format($amenity->adultprice,2) }} / Child ₱{{ number_format($amenity->childprice,2) }}</p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>

                <div class="action-buttons">
                    <button type="button" class="btn-back" onclick="window.history.back()">Go Back</button>

                    @if($bookingData->status === 'Pending')
                        <button type="button" class="btn-approve" onclick="approveBooking({{ $bookingData->bookingID }})">Approve</button>
                        <button type="button" class="btn-decline" onclick="declineBooking({{ $bookingData->bookingID }})">Decline</button>
                    @endif
                    
                    <button type="button" class="btn-extend" id="btn-extend">Extend</button>

                    <button type="button" class="btn-cancel" onclick="cancelBooking({{ $bookingData->bookingID }})">Cancel</button>
                </div>

                <!-- Extend Modal -->
            <div id="extend-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeExtendModal()">&times;</span>
                    <h3>Extend Booking</h3>
                    <p>Current End Date: <strong>{{ $bookingData->checkout }}</strong></p>

                    <form method="POST" action="{{ url('receptionist/bookings_extend/' . $bookingData->bookingID) }}">
                        @csrf
                        <label for="extra_nights">Number of Nights to Extend:</label>
                        <input type="number" id="extra_nights" name="extra_nights" min="1" value="1" required>

                        <p>New Total: ₱<span id="new-total">{{ $bookingData->total }}</span></p>

                        <button type="submit" class="btn-confirm">Confirm</button>
                    </form>
                </div>
            </div>

            </div>
            @if (session('success'))
                <div class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="fixed bottom-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

        </div>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    // -----------------------------
    // Extend Booking Modal
    // -----------------------------
    const extendModal = document.getElementById('extend-modal');
    const nightsInput = document.getElementById('nights-extend');
    const newTotalSpan = document.getElementById('new-total');

    document.getElementById('btn-extend').addEventListener('click', () => {
        extendModal.style.display = 'block';
    });


    // Base values from server
    const baseTotal = {{ $bookingData->total ?? 0 }};
    const pricePerNight = {{ $bookingData->price ?? 0 }};

    function openExtendModal() {
        extendModal.style.display = 'block';
    }

    function closeExtendModal() {
        extendModal.style.display = 'none';
    }

    function updateExtendTotal() {
        const extraNights = parseInt(nightsInput.value) || 0;
        const newTotal = baseTotal + (extraNights * pricePerNight);
        newTotalSpan.textContent = newTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    nightsInput.addEventListener('input', updateExtendTotal);
    updateExtendTotal();

    window.openExtendModal = openExtendModal;
    window.closeExtendModal = closeExtendModal;

    function confirmExtend(bookingID) {
        const extraNights = parseInt(nightsInput.value);

        if (isNaN(extraNights) || extraNights < 1) {
            alert('Please enter a valid number of nights.');
            return;
        }

        fetch(`/receptionist/bookings_extend/${bookingID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ extra_nights: extraNights })
        })
        .then(res => {
            if (!res.ok) throw new Error("Network response was not OK");
            return res.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert(`Booking extended successfully!\nNew total: ₱${data.newTotal}`);
                location.reload(); // refresh to show new checkout and total
            } else {
                alert(data.message || 'Failed to extend booking.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong!');
        });
    }
    window.confirmExtend = confirmExtend;

    // -----------------------------
    // Booking Action Buttons
    // -----------------------------
    function bookingAction(url, confirmMessage) {
        if (!confirm(confirmMessage)) return;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Action completed.');
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong.');
        });
    }

    window.approveBooking = (id) => bookingAction("{{ url('receptionist/approve_booking') }}/" + id, "Are you sure you want to APPROVE this booking?");
    window.declineBooking = (id) => bookingAction("{{ url('receptionist/decline_booking') }}/" + id, "Are you sure you want to DECLINE this booking?");
    window.cancelBooking = (id) => bookingAction("{{ url('receptionist/cancel_booking') }}/" + id, "Are you sure you want to CANCEL this booking?");

    // -----------------------------
    // Close modal if clicked outside
    // -----------------------------
    window.addEventListener('click', function(event) {
        if(event.target === extendModal) {
            closeExtendModal();
        }
    });

});
</script>


</body>

</html>
