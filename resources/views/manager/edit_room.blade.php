<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Edit Room | Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<div id="layout">
    @include('components.sidebar')

    <main id="main">
        <div class="page-header">
            <h1><i class="fa-solid fa-pen-to-square"></i> Edit Room</h1>
        </div>

        <form action="{{ url('manager/edit_room/' . $room->roomID) }}" method="POST" enctype="multipart/form-data" class="edit-room-form">
            @csrf

            <section class="card preview-card">
                <h3><i class="fa-solid fa-eye"></i> Room Preview</h3>
                <div class="preview-content">
                    <div class="preview-image">
                        <img id="imagePRV" src="{{ asset('storage/' . $room->image) }}" alt="Room Preview">
                    </div>
                    <div class="preview-details">
                        <h4 id="roomnumPRV">Room {{ $room->roomnum }}</h4>
                        <p id="descriptionPRV">{{ $room->description ?: 'Description will appear here...' }}</p>
                        <div class="price-status">
                            <span id="pricingPRV">
                                @if($room->discountID)
                                    <span style="text-decoration: line-through; color:#888;">₱ {{ number_format($room->price,2) }}</span>
                                    <span style="color:#F78A21; margin-left:0.5rem;">
                                        ₱ {{ number_format($room->price - ($room->discountAmount ?? 0),2) }}
                                    </span>
                                @else
                                    ₱ {{ number_format($room->price,2) }}
                                @endif
                            </span>
                            <span id="statusPRV" class="status-tag">{{ $room->status }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h3><i class="fa-solid fa-bed"></i> Room Details</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="roomnum">Room Number</label>
                        <input type="text" id="roomnum" name="roomnum" value="{{ old('roomnum', $room->roomnum) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="roomtype">Room Type</label>
                        <input type="text" id="roomtype" name="roomtype" value="{{ old('roomtype', $room->roomtype) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Room Price</label>
                        <input type="text" id="price" name="price" value="{{ old('price', $room->price) }}" required>
                    </div>

                    <div class="form-group {{ $errors->has('basecapacity') ? 'error-field' : '' }}">
                        <label for="basecapacity">Base Capacity</label>
                        <input type="text" id="basecapacity" name="basecapacity" placeholder="1" 
                            value="{{ old('basecapacity', $room->basecapacity ?? '') }}">
                    </div>

                    <div class="form-group {{ $errors->has('maxcapacity') ? 'error-field' : '' }}">
                        <label for="roomcapacity">Maximum Capacity</label>
                        <input type="text" id="roomcapacity" name="maxcapacity" placeholder="3" 
                            value="{{ old('maxcapacity', $room->maxcapacity ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="discount">Promo Discount</label>
                        <select id="discount" name="discountID">
                            <option value="" selected>None</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->discountID }}" 
                                        data-amount="{{ $discount->flatamount }}"
                                        {{ old('discountID', $room->discountID) == $discount->discountID ? 'selected' : '' }}>
                                    {{ $discount->name }} - ₱{{ number_format($discount->flatamount, 2) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Room Status</label>
                        <select id="status" name="status" required>
                            <option disabled>Select Status</option>
                            <option value="Available" {{ $room->status == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Unavailable" {{ $room->status == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                            <option value="Maintenance" {{ $room->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required>{{ old('description', $room->description) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Room Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                </div>

                <section class="form-group full-width">
                    <h3><i class="fa-solid fa-list-check"></i> Room Inclusions</h3>
                    <div class="inclusion-strip">
                        <div class="inclusion-group">
                            <h4><i class="fa-solid fa-spa"></i> Amenities</h4>
                            <div class="checkbox-strip">
                                @foreach ($amenities as $amenity)
                                    <label class="checkbox-card">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity->amenityID }}"
                                            {{ in_array($amenity->amenityID, old('amenities', $selectedAmenities ?? [])) ? 'checked' : '' }}>
                                        <div class="checkbox-content">
                                            <img src="{{ $amenity->image_url }}" alt="{{ $amenity->amenityname }}">
                                            <span>{{ $amenity->amenityname }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="inclusion-group">
                            <h4><i class="fa-solid fa-utensils"></i> Breakfast Menu</h4>
                            <div class="checkbox-strip">
                                @foreach ($menu as $item)
                                    <label class="checkbox-card">
                                        <input type="checkbox" name="menu[]" value="{{ $item->menuID }}"
                                            {{ in_array($item->menuID, old('menu', $selectedMenu ?? [])) ? 'checked' : '' }}>
                                        <div class="checkbox-content">
                                            <img src="{{ $item->image_url }}" alt="{{ $item->menuname }}">
                                            <span>{{ $item->menuname }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <div class="button-container">
                    <button type="button" id="cancelBtn" data-url="{{ url('manager/room_list') }}" class="btn cancel">Cancel</button>
                    <button type="submit" class="btn save">Save Changes</button>
                </div>
            </section>
        </form>

        @if (session('success'))
            <div class="alert-message success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-message error">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif
    </main>
</div>
</body>

<style>
    #rooms {
        background: rgba(255,255,255,0.15);
        border-left: 4px solid #ff9100;
        color: white;
    }
    body { font-family: "Poppins", sans-serif; background-color: #fafafa; margin: 0; overflow-y: auto; }
    #layout { min-height: 100vh; width: 100%; }
    #main { margin-left: 14rem; padding: 2rem 3rem; width: calc(100% - 14rem); box-sizing: border-box; }
    .page-header h1 { font-size: 2rem; font-weight: 600; color: #333; }
    .card { background: #fff; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; border: 1px solid #ddd; box-shadow: 0 3px 10px rgba(0,0,0,0.08); }
    .card h3 { font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
    .preview-content { display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start; }
    .preview-image img { width: 20rem; height: 15rem; border-radius: 0.8rem; object-fit: cover; border: 1px solid #ccc; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
    .form-group.full-width { grid-column: 1 / -1; }
    label { font-weight: 600; margin-bottom: 0.4rem; }
    input, textarea, select { padding: 0.8rem 1rem; border: 1px solid #aaa; border-radius: 0.6rem; font-size: 1rem; width: 100%; box-sizing: border-box; }
    textarea { resize: none; }
    .button-container { display: flex; justify-content: flex-end; gap: 1.2rem; margin-top: 1.5rem; }
    .btn { padding: 0.9rem 1.8rem; font-size: 1rem; border-radius: 0.6rem; cursor: pointer; border: none; font-weight: 600; }
    .btn.save { background: #F78A21; color: white; }
    .btn.cancel { background: #fff; border: 2px solid #ccc; color: #333; }

    .inclusion-strip { display: flex; flex-wrap: wrap; gap: 2rem; }
    .inclusion-group { flex: 1; min-width: 300px; }
    .checkbox-strip { display: flex; flex-wrap: wrap; gap: 1rem; }
    .checkbox-card { display: flex; flex-direction: column; align-items: center; background: #f9f9f9; border: 1px solid #ddd; border-radius: 0.6rem; padding: 0.8rem; width: 8rem; cursor: pointer; transition: 0.2s; }
    .checkbox-card:hover { background: #fff7f0; border-color: #F78A21; }
    .checkbox-content img { width: 3rem; height: 3rem; border-radius: 50%; object-fit: cover; margin-bottom: 0.4rem; }
    .checkbox-content span { font-size: 0.9rem; text-align: center; font-weight: 500; }
    .checkbox-card input { display: none; }
    .checkbox-card input:checked + .checkbox-content { border: 2px solid #F78A21; border-radius: 0.6rem; background: #fff4e6; }

    .alert-message { position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%); background: white; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.3); padding: 1rem 2rem; z-index: 9999; font-weight: 500; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            window.location.href = cancelBtn.dataset.url;
        });
    }
    const inputs = {
        roomnum: document.getElementById('roomnum'),
        description: document.getElementById('description'),
        price: document.getElementById('price'),
        status: document.getElementById('status'),
        image: document.getElementById('image'),
        discount: document.getElementById('discount'),
        basecapacity: document.getElementById('basecapacity'),
        maxcapacity: document.getElementById('roomcapacity')
    };

    const preview = {
        num: document.getElementById('roomnumPRV'),
        desc: document.getElementById('descriptionPRV'),
        price: document.getElementById('pricingPRV'),
        status: document.getElementById('statusPRV'),
        image: document.getElementById('imagePRV'),
    };

    function parsePrice(value){ return parseFloat(value.replace(/[^0-9.]/g,''))||0;}
    function formatPrice(value){ return '₱ '+value.toFixed(2);}

    function getSelectedDiscountAmount() {
        const option = inputs.discount.selectedOptions[0];
        return option && option.dataset.amount ? parseFloat(option.dataset.amount) : 0;
    }

    function updatePreview() {
        preview.num.textContent = `Room ${inputs.roomnum.value||''}`;
        preview.desc.textContent = inputs.description.value||'Description will appear here...';
        const price = parsePrice(inputs.price.value);
        const discount = getSelectedDiscountAmount();

        if(discount > 0){
            preview.price.innerHTML = `<span style="text-decoration: line-through; color:#888;">${formatPrice(price)}</span>
                                       <span style="color:#F78A21; margin-left:0.5rem;">${formatPrice(price - discount)}</span>`;
        } else {
            preview.price.textContent = formatPrice(price);
        }

        preview.status.textContent = inputs.status.value||'Status';
    }

    // Attach input/change events
    for(let key in inputs){
        inputs[key].addEventListener('input', updatePreview);
        inputs[key].addEventListener('change', updatePreview);
    }

    // Image preview
    inputs.image.addEventListener('change',(e)=>{
        const f = e.target.files[0];
        if(f){
            const r = new FileReader();
            r.onload = ev => preview.image.src = ev.target.result;
            r.readAsDataURL(f);
        }
    });

    // Initial preview
    updatePreview();
});
</script>