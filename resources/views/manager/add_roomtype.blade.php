<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Add Room | Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<div id="layout">
    @include('components.sidebar')

    <main id="main">
        <div class="page-header">
            <h1><i class="fa-solid fa-bed"></i> Add Room</h1>
        </div>

        <form action="{{ url('manager/add_roomtype') }}" method="POST" enctype="multipart/form-data" class="edit-room-form">
            @csrf

            <section class="card preview-card">
                <h3><i class="fa-solid fa-eye"></i> Room Preview</h3>
                <div class="preview-content">
                    <div class="preview-image">
                        <img id="imagePRV" src="{{ old('image') ? old('image') : asset('images/photo.png') }}" alt="Room Preview">
                    </div>
                    <div class="preview-details">
                        <h4 id="roomnumPRV">Room {{ old('roomnum') }}</h4>
                        <p id="descriptionPRV">{{ old('description') ?: 'Description will appear here...' }}</p>
                        <div class="price-status">
                            <span id="pricingPRV">₱ {{ old('price') ?: '0.00' }}</span>
                            <span id="statusPRV" class="status-tag">{{ old('status') ?: 'Status' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h3><i class="fa-solid fa-pen-to-square"></i> Room Details</h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="roomtype">Room Type</label>
                        <input type="text" id="roomtype" name="roomtype" value="{{ old('roomtype') }}" required>
                    </div>

                    <div class="form-group {{ $errors->has('basecapacity') ? 'error-field' : '' }}">
                        <label for="basecapacity">Base Capacity</label>
                        <input type="text" id="basecapacity" name="basecapacity" placeholder="1" value="{{ old('basecapacity') }}">
                    </div>

                    <div class="form-group {{ $errors->has('maxcapacity') ? 'error-field' : '' }}">
                        <label for="roomcapacity">Maximum Capacity</label>
                        <input type="text" id="roomcapacity" name="maxcapacity" placeholder="3" value="{{ old('maxcapacity') }}">
                    </div>

                    <div class="form-group {{ $errors->has('price') ? 'error-field' : '' }}">
                        <label for="price">Original Price</label>
                        <input type="text" id="price" name="price" placeholder="₱ 4500.00" value="{{ old('price') }}">
                    </div>

                    <div class="form-group {{ $errors->has('extra') ? 'error-field' : '' }}">
                        <label for="extra">Extra Price</label>
                        <input type="text" id="extra" name="extra" placeholder="₱ 500.00" value="{{ old('extra') }}">
                    </div>

                    <div class="form-group">
                        <label for="discount">Promo Discount</label>
                        <select id="discount" name="discountID">
                            <option value="" selected>None</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->discountID }}" 
                                    {{ old('discountID') == $discount->discountID ? 'selected' : '' }}>
                                    {{ $discount->name }} - ₱{{ number_format($discount->flatamount, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group full-width {{ $errors->has('description') ? 'error-field' : '' }}">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Enter room description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Room Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
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
                                                {{ in_array($amenity->amenityID, old('amenities', [])) ? 'checked' : '' }}>
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
                                                {{ in_array($item->menuID, old('menu', [])) ? 'checked' : '' }}>
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
                </div>

                <div class="button-container">
                    <button type="button" id="cancelBtn" data-url="{{ url('manager/room_list') }}" class="btn cancel">Cancel</button>
                    <button type="submit" class="btn save">Save Room</button>
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

        @if ($errors->any())
            <div class="alert-message error">
                <i class="fa-solid fa-triangle-exclamation"></i> Please fix the following errors:
                <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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

    body {
        font-family: "Poppins", sans-serif;
        background-color: #fafafa;
        color: #222;
        overflow-y: auto;
        margin: 0;
    }

    #layout {
        min-height: 100vh;
        width: 100%;
    }

    #main {
        margin-left: 14rem;
        margin-top: -3rem;
        width: calc(100% - 14rem);
        padding: 2rem 3rem;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
    }

    .card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #ddd;
        width:auto;
    }

    .card h3 {
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .preview-content {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        align-items: flex-start;
    }

    .preview-image img {
        width: 20rem;
        height: 15rem;
        border-radius: 0.8rem;
        object-fit: cover;
        border: 1px solid #ccc;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    label {
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    input, textarea, select {
        padding: 0.8rem 1rem;
        border: 1px solid #aaa;
        border-radius: 0.6rem;
        font-size: 1rem;
        width: 100%;
        box-sizing: border-box;
    }

    textarea { resize: none; }

    .checkbox-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f5f5f5;
        padding: 0.5rem 0.8rem;
        border-radius: 0.6rem;
        cursor: pointer;
        border: 1px solid #ccc;
        transition: 0.3s;
    }

    .checkbox-item:hover {
        background: #ffe0c2;
    }

    .checkbox-item img {
        width: 40px;
        height: 40px;
        border-radius: 0.3rem;
        object-fit: cover;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        gap: 1.2rem;
        margin-top: 1.5rem;
    }

    .btn {
        padding: 0.9rem 1.8rem;
        font-size: 1rem;
        border-radius: 0.6rem;
        cursor: pointer;
        border: none;
        font-weight: 600;
    }

    .btn.save {
        background: #F78A21;
        color: white;
    }

    .btn.cancel {
        background: #fff;
        border: 2px solid #ccc;
        color: #333;
    }

    .alert-message {
        position: fixed;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        padding: 1rem 2rem;
        z-index: 9999;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        #main { margin-left: 0; padding: 1rem; }
        .form-grid { grid-template-columns: 1fr; }
        .button-container { flex-direction: column; }
    }

    .inclusion-strip {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        width: 100%;
    }

    .inclusion-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 100%;
    }

    .inclusion-group h4 {
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .checkbox-strip {
        display: flex;
        justify-content: flex-start;
        align-items: stretch;
        flex-wrap: nowrap;
        gap: 1rem;
        overflow-x: auto;
        width: 100%;
        padding: 1rem 0.5rem;
        box-sizing: border-box;
        scroll-behavior: smooth;
    }

    .checkbox-strip::-webkit-scrollbar {
        height: 8px;
    }

    .checkbox-strip::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .checkbox-strip::-webkit-scrollbar-thumb:hover {
        background-color: #aaa;
    }

    .checkbox-card {
        position: relative;
        flex: 0 0 180px;
        border: 2px solid #ddd;
        border-radius: 0.75rem;
        background: #fff;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .checkbox-card:hover {
        border-color: #F78A21;
        box-shadow: 0 0 8px rgba(247, 138, 33, 0.2);
    }

    .checkbox-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .checkbox-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        text-align: center;
        width: 100%;
    }

    .checkbox-content img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #eee;
        margin-bottom: 0.5rem;
    }

    .checkbox-content span {
        font-size: 0.9rem;
        color: #333;
        word-break: break-word;
    }

    .checkbox-card input:checked + .checkbox-content {
        background: rgba(247, 138, 33, 0.1);
        border-color: #F78A21;
    }

    .checkbox-card input:checked + .checkbox-content span {
        color: #F78A21;
        font-weight: 600;
    }

    .error-field input,
    .error-field textarea,
    .error-field select {
        border-color: #e74c3c !important;
        box-shadow: 0 0 6px rgba(231, 76, 60, 0.5);
        animation: shake 0.25s ease-in-out 0s 2;
    }

    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        50% { transform: translateX(4px); }
        75% { transform: translateX(-4px); }
        100% { transform: translateX(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomtypeDropdown = document.getElementById('roomtype');
        const discountDropdown = document.getElementById('discount');

        const fields = {
            basecapacity: document.getElementById('basecapacity'),
            roomcapacity: document.getElementById('roomcapacity'),
            price: document.getElementById('price'),
            extra: document.getElementById('extra'),
            description: document.getElementById('description'),
        };

        const preview = {
            image: document.getElementById('imagePRV'),
            num: document.getElementById('roomnumPRV'),
            desc: document.getElementById('descriptionPRV'),
            price: document.getElementById('pricingPRV'),
        };

        // When a room type is selected
        roomtypeDropdown.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (!selected) return;

            // Fill the form fields from the data attributes
            fields.basecapacity.value = selected.dataset.basecapacity || '';
            fields.roomcapacity.value = selected.dataset.maxcapacity || '';
            fields.price.value = selected.dataset.price || '';
            fields.extra.value = selected.dataset.extra || '';
            fields.description.value = selected.dataset.description || '';

            // Update preview content
            preview.desc.textContent = fields.description.value || 'Description will appear here...';
            preview.price.textContent = '₱ ' + parseFloat(fields.price.value || 0).toFixed(2);
            if (selected.dataset.imageUrl) preview.image.src = selected.dataset.imageUrl;

            // Auto-select the corresponding discount
            const discountName = selected.dataset.discountName;
            if (discountName) {
                [...discountDropdown.options].forEach(opt => {
                    if (opt.textContent.includes(discountName)) {
                        discountDropdown.value = opt.value;
                    }
                });
            } else {
                discountDropdown.value = '';
            }
        });
    });
</script>