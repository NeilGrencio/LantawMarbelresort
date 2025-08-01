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
            <form action="{{url('manager/add_amenity')}}" method="post" enctype="multipart/form-data"> 
                @csrf
                <div class="form-container">
                    <div id="form-header">
                        <h2>Amenity Details</h2>
                    </div>
                    <label for="txtAmenityName">Amenity Name</label>
                    <input id="txtAmenityName" name="amenityname" type="text" placeholder="Amenity Name.."> 

                    <label for="txtDescription">Amenity Description</label>
                    <textarea id="txtDescription" oninput="autoResize(this)" name="description" placeholder="Amenity Descriptions.."></textarea>

                    <label class="image-loader" for="image">Amenity Image
                    <img class="image-loader" id="imagePreview" src="{{asset('images/photo.png')}}">
                    </label>
                    <input id="image" name="amenityimage" type="file" accept="image/webp, image/png, image/jpg, image/jpeg">
                    
                    <div id="form-header">
                    <h2>Amenity Pricing</h2>
                    </div>

                    <label for="txtchildprice">Children Price</label>
                    <input id="txtchildprice" type="text" name="childprice" placeholder="₱ 100.00">

                    <label for="txtadultprice">Adult Price</label>
                    <input id="txtadultprice" type="text" name="adultprice" placeholder="₱ 150.00">
                </div>
                <div id="button-container">
                    <button id="btnCancel" type="button" data-url="{{url('manager/amenity_list')}}">Cancel</button>
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
        font-size: .8rem;
        margin-bottom:-1rem;
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
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
        border:1px solid black;
        gap:1rem;
    }
    #form-header{
        display: flex;  
        background:black;
        color:white;
        height:2rem;
        font-size:.8rem;
        align-items:center;
        padding-left:1rem;
        border-radius:.7rem;
    }
    label{
        font-size:15px;
        font-weight:bold;
    }
    input{
        display:flex;
        padding:.5rem;
        border-radius:.4rem;
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
    #imagePreview{
        display: flex;
        height:20rem;
        width:30rem;
        object-fit: cover;
        border-radius:.7rem;
        box-shadow:.2rem .3rem 0 rgba(0,0,0,0.2);
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
        const imagePreview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('image');
        const cancel = document.getElementById('btnCancel');
        const message = document.querySelector('.alert-message');

        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 1500);
        }

        if (cancel) {
            cancel.addEventListener('click', function () {
                window.location.href = this.dataset.url;
            });
        }

        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const filereader = new FileReader();
                    filereader.onload = function (event) {
                        imagePreview.src = event.target.result;
                    };
                    filereader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px'; // Set new height based on content
        }

        document.querySelectorAll('textarea').forEach(textarea => {
            autoResize(textarea);

            textarea.addEventListener('input', function () {
                autoResize(this);
            });
        });
        const numericInputs = [
            document.getElementById('txtadultprice'), 
            document.getElementById('txtchildprice')
        ];

        numericInputs.forEach(input => {
            if (!input) return;

            // Restrict non-numeric input except for dot
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Prevent more than one decimal point
                const parts = this.value.split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
            });

            // Format to two decimal places on blur
            input.addEventListener('blur', function () {
                if (this.value === '') return;

                let num = parseFloat(this.value);
                this.value = isNaN(num) ? '' : num.toFixed(2);
            });
        });
    });

</script>