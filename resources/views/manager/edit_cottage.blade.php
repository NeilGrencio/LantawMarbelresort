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
        @include ('components.sidebar')
        <div id="main-layout">
            <div id="title-header">
                <h1>Edit Kiddy Pool Cottage</h1>
            </div>

            <form action="{{ route('manager.edit_cottage', ['cottageID' => $cottage->cottageID]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="form-container">
                    <label for="cottagename">Cottage Name:</label>
                    <input id="cottagename" type="text" name="cottagename" placeholder="November Rain Cottage"
                        value="{{ old('cottagename', $cottage->cottagename ?? '') }}" required>

                    <label for="capacity">Capacity:</label>
                    <input id="capacity" type="text" name="capacity" placeholder="8"
                        value="{{ old('capacity', $cottage->capacity ?? '') }}" required>

                    <label for="price">Price</label>
                    <input id="price" type="text" name="price" placeholder="2300.00"
                        value="{{ old('price', $cottage->price ?? '') }}" required>

                    <label for="image">Select an Image</label>
                    <img id="cottage-preview"
                        src="{{ $cottage->image ? asset('storage/' . $cottage->image) : asset('images/photo.png') }}" />
                    <input id="image" type="file" accept="image/jpg, image/png, image/webp, image/jpeg" name="image">

                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option disabled {{ old('status', $cottage->status ?? '') == '' ? 'selected' : '' }}>Select Status</option>
                        <option value="Available" {{ old('status', $cottage->status ?? '') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status', $cottage->status ?? '') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="Maintenance" {{ old('status', $cottage->status ?? '') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <div class="button-container">
                    <button id="cancel" type="button" data-url="{{url('manager/cottage_list')}}">Cancel</button>
                    <button type="Submit">Save</button>
                </div>
            </form>
        </div>
        @if (session('success'))
            <div class="alert-message">
                <h2>{{ session('success')}}</h2>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-message">
                <h2>{{ session('error') }}</h2>
            </div>
        @endif
    </div>
</body>

<style>
body{overflow-y:auto;}
    #cottages { color: #F78A21;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:15rem;
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
    }
    #form-container{
        display:flex;
        flex-direction: column;
        background:white;
        padding:1rem;
        border-radius:2rem;
        gap:1rem;
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
    .image-loader{
        display: flex;
        flex-direction:column;
        cursor: pointer;
    }
    #cottage-preview{
        display:flex;
        height:20rem;
        width:20rem;
        object-fit: cover;
        box-shadow:.2rem .3rem 0 rgba(0,0,0,0.5);
        border-radius:.7rem;
    }
    .button-container{
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
        const cancelbtn = document.getElementById('cancel');
        const cottagepreview = document.getElementById('cottage-preview');
        const inptCottageImage = document.getElementById('image');
        const priceInput = document.getElementById('price');
        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        
        priceInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^\d.]/g, '')  
                                .replace(/(\..*?)\..*/g, '$1');
        });

        // Format to end with .00 if necessary when losing focus
        priceInput.addEventListener('blur', function () {
            let val = parseFloat(this.value);
            if (!isNaN(val)) {
                this.value = val.toFixed(2);
            } else {
                this.value = '';
            }
        });

        inptCottageImage.addEventListener('change', function(){
            const file = inptCottageImage.files[0];
            if (file) {
                cottagepreview.src = URL.createObjectURL(file);
            }
        });

        cancelbtn.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });
    });
</script>