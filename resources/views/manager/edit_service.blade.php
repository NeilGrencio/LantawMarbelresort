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
                <h1>Edit Service Item</h1>
            </div>
            <form action="{{url('manager/edit_service/' . $menu->menuID)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="form-container">
                    <label for="menuname">Service Name</label>
                    <input id="menuname" name="menuname" type="text" placeholder="Sinigang" value="{{ old('menuname', $menu->menuname ?? '') }}" required/>

                    <label class="image" for="image">Select Service image
                    <img class="image" id="image-preview"  src="{{ isset($menu->image) ? asset('storage/' . $menu->image) : asset('images/placeholder.png') }}""/>
                    </label>
                    <input id="image" name="image" type="file" accept="image/webp, image/png, image/jpeg, image/jpg"/>

                    <label for="price">Service Price</label>
                    <input id="price" name="price" placeholder="75.00" type="text" value="{{ old('price', $menu->price ?? '' )}}" required/>

                    <label for="status">Service Status</label>
                    <select id="status" name="status">
                        <option value="Available" {{ old('status', $menu->status ?? '') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status', $menu->status ?? '') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
                <div id="button-container">
                    <button id="cancel" type="button" data-url={{url('manager/services_list')}}>Cancel</button>
                    <button type="submit">Save</button>
                </div>
            </form>
            @if (session('error'))
                <div class="alert-message">
                    <h2>{{ session('error') }}</h2>
                </div>
            @endif
        </div>
    </div>
</body>
<style>
    body{overflow-y:auto;}
    #menu { color: #F78A21;}
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
        justify-content: center;
    }
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    #form-container{
        display:flex;
        flex-direction:column;
        background:white;
        width:100%;
        padding:2rem;
        gap:.5rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0rem rgba(0,0,0);
        border-radius:.7rem;
    }
    #form-container label{
        font-size:17px;
        font-weight: bold;
    }
    #form-container input, select{
        font-size:15px;
        padding:.5rem;
        border-radius:.7rem;
        display:flex;   
    }
    .image{
        height:15rem;
        width:15rem;
        object-fit:cover;
        border-radius:.7rem;
        display:flex;
        flex-direction: column;
        align-content: flex-start;
        margin-bottom:1rem;
    }
    #image-preview{
        box-shadow:.2rem .3rem 0 rgba(0,0,0,0.5);
    }
    #button-container{
        display: flex;
        gap:1rem;
        padding:1rem;
    }
    #button-container button{
        height: 4rem;
        width:7rem;
        border-radius:.7rem;
        border:none;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,0.5);
        transition:all .3s ease;
    }
    #button-container button:hover{
        background:#F78A21;
        color:white;
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
document.addEventListener('DOMContentLoaded', function () {
    const priceInput = document.getElementById('price');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const cancelbtn = document.getElementById('cancel');
    const message = document.querySelector('.alert-message');

    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 2500);
    }

    if (cancelbtn) {
        cancelbtn.addEventListener('click', function () {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    }

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            // Reset to default placeholder if no file selected
            imagePreview.src = '{{ asset("images/photo.png") }}';
        }
    });

    // Restrict non-numeric input (allows decimal)
    priceInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9.]/g, '');

        // Prevent multiple dots
        const parts = this.value.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts[1];
        }
    });

    // Format on blur (add .00 if needed)
    priceInput.addEventListener('blur', function () {
        let val = this.value;

        if (val !== '') {
            let floatVal = parseFloat(val);
            this.value = floatVal.toFixed(2);
        }
    });
});
</script>
