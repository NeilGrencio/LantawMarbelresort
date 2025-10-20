<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Add Cottage</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
        width: 100%;
    }

    #main-layout {
        width: calc(100% - 14rem);
        padding: 2rem;
        overflow-x: auto;
    }

    #title-container {
        margin-bottom: 2rem;
    }

    #title-container h1 {
        font-size: 2rem;
        color: #F78A21;
        font-weight: 700;
    }

    form {
        background: #fff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    label {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        color: #333;
    }

    input[type="text"], input[type="file"] {
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #ccc;
        outline: none;
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    input[type="text"]:focus {
        border-color: #F78A21;
        box-shadow: 0 0 5px rgba(247,138,33,0.3);
    }

    .image-loader {
        display: flex;
        flex-direction: column;
        cursor: pointer;
        align-items: center;
        gap: 0.5rem;
    }

    #cottage-preview {
        width: 250px;
        height: 200px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    #cottage-preview:hover {
        transform: scale(1.03);
    }

    .button-container {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    button {
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        border: none;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    button[type="submit"] {
        background-color: #F78A21;
        color: #fff;
    }

    button[type="submit"]:hover {
        background-color: #e36b0f;
    }

    #cancel {
        background-color: #ccc;
        color: #333;
    }

    #cancel:hover {
        background-color: #b3b3b3;
    }

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
</style>
</head>
<body>
<div id="layout">
    @include('components.sidebar')
    <div id="main-layout">
        <div id="title-container">
            <h1>Add Kiddy Pool Cottage</h1>
        </div>

        <form method="post" action="{{route('manager.submit_cottage')}}" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="cottagename">Cottage Name:</label>
                <input id="cottagename" type="text" name="cottagename" placeholder="November Rain Cottage" value="{{ old('cottagename') }}" required>
            </div>

            <div>
                <label for="capacity">Capacity:</label>
                <input id="capacity" type="text" name="capacity" placeholder="8" value="{{ old('capacity') }}" required>
            </div>

            <div>
                <label for="price">Price</label>
                <input id="price" type="text" name="price" placeholder="2300.00" value="{{ old('price') }}" required>
            </div>

            <div class="image-loader">
                <label for="image">Select an image:</label>
                <img id="cottage-preview" src="{{ asset('images/photo.png') }}" alt="Cottage Preview" />
                <input id="image" type="file" accept="image/jpg, image/png, image/webp, image/jpeg" name="image">
            </div>

            <div class="button-container">
                <button id="cancel" type="button" data-url="{{url('manager/cottage_list')}}">Cancel</button>
                <button type="submit">Save</button>
            </div>
        </form>

        @if (session('success'))
        <div class="alert-message">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert-message">{{ session('error') }}</div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const cancelBtn = document.getElementById('cancel');
    const cottagePreview = document.getElementById('cottage-preview');
    const imageInput = document.getElementById('image');
    const priceInput = document.getElementById('price');
    const message = document.querySelector('.alert-message');

    if (message) {
        setTimeout(() => message.style.display = 'none', 2500);
    }

    cancelBtn.addEventListener('click', () => {
        window.location.href = cancelBtn.dataset.url;
    });

    // Preview image
    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        if (file) cottagePreview.src = URL.createObjectURL(file);
    });

    // Numeric validation
    priceInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^\d.]/g, '').replace(/(\..*?)\..*/g, '$1');
    });

    priceInput.addEventListener('blur', function () {
        let val = parseFloat(this.value);
        this.value = isNaN(val) ? '' : val.toFixed(2);
    });
});
</script>
</body>
</html>
