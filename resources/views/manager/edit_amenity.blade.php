<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Edit Amenity</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    #amenities {
        background: rgba(255,255,255,0.15);
        border-left: 4px solid #ff9100;
        color: white;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: white;
    }
    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
        width: 100%;
    }
    #main-layout {
        padding: 1.5rem;
        width: calc(100% - 14rem);
        overflow-x: auto;
    }

    /* Page Header */
    #title-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }
    #title-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #F78A21;
    }

    /* Form Container */
    .form-container {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 2rem;
        width: 100%;
        margin: auto;
    }

    .form-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 1.5rem;
    }

    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .form-section h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #F78A21;
        margin-bottom: 0.5rem;
    }

    label {
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 0.3rem;
    }

    input, select, textarea {
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 0.8rem;
        border: 1px solid #ccc;
        outline: none;
        font-size: 0.95rem;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #F78A21;
        box-shadow: 0 0 0 3px rgba(247,138,33,0.15);
    }

    textarea { min-height: 6rem; resize: vertical; }

    /* Image Upload */
    .image-loader {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        cursor: pointer;
        align-items: flex-start;
    }

    #imagePreview {
        width: 100%;
        max-width: 400px;
        height: 200px;
        border-radius: 1rem;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    #imagePreview:hover {
        transform: scale(1.02);
    }

    /* Buttons */
    #button-container {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    button {
        padding: 0.7rem 1.5rem;
        border-radius: 1rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        font-size: 1rem;
    }

    button[type="submit"] {
        background: #F78A21;
        color: #fff;
    }

    button[type="submit"]:hover {
        background: #e07b1f;
        transform: translateY(-2px);
    }

    #btnCancel {
        background: #e0e0e0;
        color: #333;
    }

    #btnCancel:hover {
        background: #ccc;
        transform: translateY(-2px);
    }

    /* Alerts */
    .alert-message {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 1rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        font-weight: 600;
        z-index: 1000;
        text-align: center;
        min-width: 250px;
    }

    @media (max-width: 768px) {
        #main-layout { margin-left: 0; padding: 1rem; }
        #imagePreview { max-width: 100%; height: 180px; }
        #button-container { flex-direction: column; align-items: stretch; }
        button { width: 100%; }
    }
</style>
</head>

<body>
<div id="layout">
    @include('components.sidebar')
    <div id="main-layout">
        <div id="title-header">
            <h1>Edit Amenity</h1>
        </div>

        <form action="{{ url('manager/edit_amenity/' . $amenity->amenityID) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-container">

                <!-- Amenity Details -->
                <div class="form-section">
                    <h2>Amenity Details</h2>
                    <label for="txtAmenityName">Amenity Name</label>
                    <input id="txtAmenityName" name="amenityname" type="text" placeholder="Amenity Name.." 
                        value="{{ old('amenityname', $amenity->amenityname) }}">

                    <label for="txtDescription">Amenity Description</label>
                    <textarea id="txtDescription" name="description" placeholder="Amenity Description..">{{ old('description', $amenity->description) }}</textarea>

                    <label for="txtAmenityCapacity">Amenity Capacity</label>
                    <input id="txtAmenityCapacity" name="amenitycapacity" type="text" placeholder="20"
                        value="{{ old('amenitycapacity', $amenity->capacity) }}">

                    <label class="image-loader" for="image">Amenity Image
                        <img id="imagePreview" src="{{ asset('storage/' . $amenity->image) }}">
                    </label>
                    <input id="image" name="image" type="file" accept="image/*">

                    <label for="status">Amenity Status</label>
                    <select id="status" name="status">
                        <option value="" disabled>Select Status</option>
                        <option value="Available" {{ old('status', $amenity->status) == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status', $amenity->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="Maintenance" {{ old('status', $amenity->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>

                    <label for="type">Amenity Type</label>
                    <select id="type" name="type">
                        <option value="" disabled>Select Type</option>
                        <option value="Facility" {{ old('type', $amenity->type) == 'Facility' ? 'selected' : '' }}>Facility</option>
                        <option value="Items" {{ old('type', $amenity->type) == 'Items' ? 'selected' : '' }}>Items</option>
                    </select>
                </div>

                <!-- Pricing Section -->
                <div class="form-section">
                    <h2>Amenity Pricing</h2>
                    <label for="txtchildprice">Children Price</label>
                    <input id="txtchildprice" type="text" name="childprice" placeholder="₱ 100.00" 
                        value="{{ old('childprice', $amenity->childprice) }}">

                    <label for="txtadultprice">Adult Price</label>
                    <input id="txtadultprice" type="text" name="adultprice" placeholder="₱ 150.00" 
                        value="{{ old('adultprice', $amenity->adultprice) }}">
                </div>

                <!-- Buttons -->
                <div id="button-container">
                    <button id="btnCancel" type="button" data-url="{{ url('manager/amenity_list') }}">Cancel</button>
                    <button type="submit">Save</button>
                </div>

            </div>
        </form>

        @if(session('success'))
        <div class="alert-message">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-message">{{ session('error') }}</div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const imagePreview = document.getElementById('imagePreview');
    const imageInput = document.getElementById('image');
    const cancel = document.getElementById('btnCancel');
    const message = document.querySelector('.alert-message');

    if(message){
        setTimeout(() => message.style.display = 'none', 2500);
    }

    cancel.addEventListener('click', function(){
        window.location.href = this.dataset.url;
    });

    imageInput.addEventListener('change', function(e){
        if(e.target.files && e.target.files[0]){
            const reader = new FileReader();
            reader.onload = function(event){
                imagePreview.src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>
</body>
</html>
