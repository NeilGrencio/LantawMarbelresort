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
            <div id="title-header">
                <h1>Add Amenity</h1>
            </div>

            {{--form--}}
            <form action="{{ url('manager/edit_amenity/' . $amenity->amenityID) }}" method="post" enctype="multipart/form-data"> 
                @csrf
                <div class="form-container">
                    <div id="form-header">
                        <h2>Amenity Details</h2>
                    </div>

                    <label for="txtAmenityName">Amenity Name</label>
                    <input id="txtAmenityName" name="amenityname" type="text" placeholder="Amenity Name.." 
                        value="{{ old('amenityname', $amenity->amenityname) }}">

                    <label for="txtDescription">Amenity Description</label>
                    <textarea id="txtDescription" name="description" placeholder="Amenity Description..">{{ old('description', $amenity->description) }}</textarea>
                    
                    <label for="txtAmenityCapacity">Amenity Capacity</label>
                    <input id="txtAmenityCapacity" name="amenitycapacity" type="text" placeholder="20"
                        value="{{ old('amenitycapacity', $amenity->capacity) }}"> 

                    <label class="image-loader" for="image">Amenity image
                    <img class="image-loader" id="imagePreview" src="{{ asset('storage/' . $amenity->image) }}">
                    </label>
                    <input id="image" name="image" type="file" accept="image/webp, image/png, image/jpg, image/jpeg">

                    <label for="status">Amenity Status</label>
                    <select id="status" name="status">
                        <option value="" disabled>Select Status</option>
                        <option value="Available" {{ old('status', $amenity->status) == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status', $amenity->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="Maintenance" {{ old('status', $amenity->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>

                    <div id="form-header">
                        <h2>Amenity Pricing</h2>
                    </div>

                    <label for="txtchildprice">Children Price</label>
                    <input id="txtchildprice" type="text" name="childprice" placeholder="₱ 100.00" 
                        value="{{ old('childprice', $amenity->childprice) }}">

                    <label for="txtadultprice">Adult Price</label>
                    <input id="txtadultprice" type="text" name="adultprice" placeholder="₱ 150.00" 
                        value="{{ old('adultprice', $amenity->adultprice) }}">
                </div>

                <div id="button-container">
                    <button id="btnCancel" type="button" data-url={{url('manager/amenity_list')}}>Cancel</button>
                    <button type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
    @if (session('success'))
        <div class="alert-message">
            <h2>{{ session('success') }}</h2>
        </div>
    @endif
    @if (session('error'))
        <div class="alert-message">
            <h2>{{ session('error') }}</h2>
        </div>
    @endif
</body>
<style>
    #amenities { color: #F78A21;}
    body{overflow-y: auto;}
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
    #title-header{
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
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    .form-container{
        display:flex;
        flex-direction: column;
        background:white;
        padding:1rem;
        border-radius:2rem;
        gap:1rem;
    }
    #form-header{
        display: flex;  
        background:black;
        color:white;
        height:3rem;
        align-items:center;
        padding-left:1rem;
        border-radius:1rem;
    }
    label{
        font-size:15px;
        font-weight:bold;
    }
    input, select{
        display:flex;
        padding:.5rem;
        border-radius:.7rem;
        width:100%;
        padding:.5rem;
    }
    textarea{
        min-height: 5rem;
        height: auto;
        overflow-y: hidden;
        resize: auto;
        padding:.5rem;
        border-radius:.7rem;
    }
    .image-loader{
        display: flex;
        flex-direction:column;
        cursor: pointer;
    }
    #imagePreview{
        display: flex;
        height:20rem;
        width:30rem;
        object-fit: cover;
        border-radius:.7rem;
        box-shadow:.2rem .3rem 0 rgba(0,0,0,0.2);
    }
    #button-container{
        display:flex;
        flex-direction: row;
        height:5rem;
        padding:1rem;
        gap:1rem;
    }
    button{
        height:3rem;
        width:7rem;
        border:none;
        border-radius:.7rem;
        box-shadow:.2rem .3rem 0 rgba(0,0,0,0.2);
        transiton:all .3s ease;
    }
    button:hover{
        background:#F78A21;
        cursor:pointer;
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
    document.addEventListener('DOMContentLoaded', function(){
        const imagePreview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('image');
        const cancel = document.getElementById('btnCancel');
        const message = document.querySelector('.alert-message');

        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        cancel.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        })


        imageInput.addEventListener('change', function(e){
            if(e.target.files && e.target.files[0]){
                const filereader = new FileReader();
                filereader.onload = function(event){
                    imagePreview.src = event.target.result;
                };
                filereader.readAsDataURL(e.target.files[0]);
            }
        });
    });
</script>
