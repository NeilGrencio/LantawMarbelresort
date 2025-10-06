<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="title-container">
                <h1>Edit Room</h1>
            </div>

            <form action="{{ url('manager/edit_room/' . $room->roomID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-container">

                    <div id="form-header1">
                        <h3>Room Preview</h3>
                    </div>
                    <div class="room-card">
                        <div id="room-image">
                            <img id="imagePRV" src="{{ asset('storage/' . $room->image) }}" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                                        
                        <div id="room-details" style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%; postion:relatve;">
                            <h4 id="roomnumPRV">Room</h4>
                            <h4> Room Details</h4>
                            <p id="descriptionPRV">Description</p>
                            <h4 style="bottom:1px;margin-top:auto; display:flex;">Pricing</h4>
                            <h4 id="pricingPRV">₱ Price</h4>               
                            <h4 id="statusPRV">The room is currently in Status</h4>
                        </div>
                            
                    </div>

                    <div id="form-header1">
                        <h3>Room Details</h3>
                    </div>

                
                    <label for="roomnum">Room Number:</label>
                    <input type="text" id="roomnum" name="roomnum" value="{{ old('roomnum', $room->roomnum) }}" placeholder="101" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required>{{ old('description', $room->description) }}</textarea>

                    <label for="price">Room Price:</label>
                    <input type="text" id="price" name="price" value="{{ old('price', $room->price) }}" placeholder="₱ 4500.00" required>

                    <label for="roomtype">Room Type:</label>
                    <input type="text" id="roomtype" name="roomtype" value="{{ old('roomtype', $room->roomtype) }}" placeholder="Singles" required>

                    <label for="status">Room Availability:</label>
                    <select type="text" id="status" name="status" required>
                        <option value="" disabled>Select Status</option>
                        <option value="Available" {{ old('status', $room->status) == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status', $room->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="Maintenance" {{ old('status', $room->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>

                    <label for="image">Room Image:</label>
                    <input type="file" id="image" name="image" accept="image/jpg,image/jpeg,image/png,image/webp">

            </div>
            <div class="button-container">
                <button id="cancelBtn" type="button" data-url="{{ url('manager/room_list') }}">Cancel</button>
                <button type="submit">Save</button>
        </form>
        @if (session('error'))
        <div class="alert-message">
            <h2>{{ session('error') }}</h2>
        </div>
    @endif
    </div>
</body>
<style>
    body{
        overflow-y:auto;
    }
    textarea, input[type='text'], select{
        resize:none;
        width: 100%;
        padding: 0.5rem;
        border: 2px solid black;
        background: white;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-family: Roboto, Helvetica, sans-serif
    }
    textarea {
        min-height: 5rem;
        height: auto;
        overflow-y: hidden;
        resize: none;
    }
    #descriptionPRV{
        white-space:pre-line;
    }

    button{
        padding: 0.5rem 1rem;
        border: none;
        width: 10rem;
        height:3rem;
        border-radius: 0.5rem;
        background-color: #ffffff;
        border:2px solid black;
        color: black;
        font-size: 1rem;
        cursor: pointer;
        margin-top:1rem;
        transition: background-color 0.3s ease-in-out;
    }
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:12rem;
    }
    #title-container{
        display: flex;
        flex-direction: row;
        width: 100%;
        max-height:5rem;
        padding:1rem;
        border-radius: 2rem;
        align-content: center;
        align-items: center;
        gap: 1rem;

    }
    #title-container h1 {
        display: flex;
        align-items: center;
    }
    #title-container i {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        right: 1rem;
        cursor: pointer;
    }
    .form-container {
        display: flex;
        flex-direction: column;    
        max-height: auto;
        width: 100%;
        padding:1rem;
        border: 1px solid #ccc;
        border-radius: 1rem;
        padding: 1rem;
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        gap:1rem;
    }
    .room-card {
        display: flex;
        flex-direction: row;    
        max-height: auto;
        width: 100%;
        padding: 1rem;
        text-align: center;
    }
    #form-header1{
        padding-left: 0.5rem;
        border-radius: 0.5rem;
        position:relative;
        width:100%;
        background:rgb(55, 55, 55);
        color:white;
    }
    #room-image {
        background: rgb(100, 100, 100);
        height:18rem;
        width: 30%;
        margin-right: 16px;
        object-fit: cover;
    }
    #room-details {
        display: flex;
        flex-direction: column;
        gap:0.5rem;
        width: 70%;
        min-height:18rem;
        height:auto;
        position: relative;
    }
    #room-details *{
        margin: 0.2rem 0;
        align-items: self-start;
        text-align: left;   
        word-wrap: wrap;
        position: relative;
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const curTab = document.getElementById('rooms');   
        const roomnumInput = document.getElementById('roomnum');
        const descriptionInput = document.getElementById('description');
        const priceInput = document.getElementById('price');
        const roomtypeInput = document.getElementById('roomtype');
        const statusInput = document.getElementById('status');
        const imageInput = document.getElementById('image');
        const roomnumPRV = document.getElementById('roomnumPRV');
        const descriptionPRV = document.getElementById('descriptionPRV');
        const pricingPRV = document.getElementById('pricingPRV');
        const statusPRV = document.getElementById('statusPRV');
        const imagePRV = document.getElementById('imagePRV');
        const cancelBTN = document.getElementById('cancelBtn');
        const message = document.querySelector('alert-message');

        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 1500);
        }

        cancelBTN.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        })

        if (curTab) {
            curTab.style.color = "#F78A21";
        }

        function updatePreview() {
            roomnumPRV.textContent = 'Room ' + (roomnumInput.value || '');
            descriptionPRV.innerHTML = (descriptionInput.value || '');
            let price = parseFloat(priceInput.value);
            pricingPRV.textContent = '₱ ' + (isNaN(price) ? '' : price.toFixed(2));
            if (statusInput.value === 'Maintenance') {
                statusPRV.textContent = 'The room is currently in Maintenance';
            } else if (statusInput.value) {
                statusPRV.textContent = 'The room is currently ' + statusInput.value;
            } else {
                statusPRV.textContent = '';
            }
            // For image, only update if a file is selected (handled below)
        }

        // Initial preview update
        updatePreview();

        // Update preview fields on input/change
        roomnumInput.addEventListener('input', updatePreview);
        descriptionInput.addEventListener('input', updatePreview);
        priceInput.addEventListener('input', updatePreview);
        statusInput.addEventListener('change', updatePreview);

        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imagePRV.src = ev.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        if (descriptionInput) {
            // Function to auto-resize textarea
            function autoResizeTextarea() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            }
            descriptionInput.addEventListener('input', autoResizeTextarea);
            // Initial resize in case there's pre-filled text
            descriptionInput.style.height = 'auto';
            descriptionInput.style.height = (descriptionInput.scrollHeight) + 'px';
        }

        function valdateInputNumber(inputElement) {
            let cleaned = inputElement.value.replace(/[^0-9]/g, '');

            // Remove leading zero
            if (cleaned.startsWith('0')) {
                cleaned = cleaned.slice(1);
            }

            inputElement.value = cleaned;
        }

        roomnumInput.addEventListener('input', function(){
            valdateInputNumber(roomnumInput);
        })

        window.addEventListener('load', function(){
            valdateInputNumber(roomnumInput);
        })

        roomnumInput.addEventListener('blur', function(){
            valdateInputNumber(roomnumInput);
        })
       

    });
    
</script>