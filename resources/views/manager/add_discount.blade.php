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
                <h1>Add Discount</h1>
            </div>
            <form method="post" action="{{url('manager/add_discount')}}">
                @csrf 
                <div class="form-container">
                    <label for="name">Discount Name</label>
                    <input id="name" name="name" type="text" placeholder="Senior Discount" required value="{{old('name')}}"/>
                    @error('name')
                        <p>{{ $message }}</p>
                    @enderror

                    <label for="amount">Discount Amount</label>
                    <input id="amount" name="amount" type="text" placeholder="0.20" required value="{{old('amount')}}"/>
                    @error('amount')
                        <p>{{ $message }}</p>
                    @enderror

                    <label for="status">Discount Status</label>
                    <select id="status" name="status">
                        <option value="" selected>Select Discount Status</option>
                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                    @error('status')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="button-container">
                    <button id="cancel-button" type="button" data-url="{{url('manager/discount')}}">Cancel</button>
                    <button type="submit">Save</button>
                </div>
            </form>
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
        </div>
    </div>
</body>
<style>
    #discount { color: #F78A21;}
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
        gap: 1rem;
    }
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    form{
        height:100%;
        width:100%;
    }
    .form-container{
        display:flex;
        flex-direction:column;
        width:100%;
        background:white;
        border-radius:.7rem;
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        gap:.5rem;
        padding:1rem;
    }
    input, select{
        width:100%;
        height:3rem;
        border-radius:.7rem;
        padding:.5rem;
        display:flex;
    }
    label{
        font-weight: bold;
        font-size:.9rem;
    }
    .button-contianer{
        margin-top:1rem;
    }
    button{
        margin-top:1rem;
        height:3rem;
        width:5rem;
        transition:all .3s ease;
        border-radius:.7rem;
        border:none;
        box-shadow:.2rem .3rem 0 rgba(0,0,0,.2);
    }
    button:hover{
        background:#F78A21;
        color:white;
        cursor: pointer;
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
        const amountInput = document.getElementById('amount');
        const cancelButton = document.getElementById('cancel-button');
        const alert = document.querySelector('.alert-message');

        if (alert) {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 2500);
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', function () {
                const url = this.dataset.url;
                if (url) window.location.href = url;
            });
        }

        if (amountInput) {
            // Restrict to digits, one dot, and allow %
            amountInput.addEventListener('input', function () {
                let val = this.value;

                // Allow only digits, dot, and percent
                val = val.replace(/[^0-9.%]/g, '');

                // Prevent more than one dot
                const parts = val.split('.');
                if (parts.length > 2) {
                    val = parts[0] + '.' + parts.slice(1).join('');
                }

                this.value = val;
            });

            // Format on blur
            amountInput.addEventListener('blur', function () {
                let val = this.value;

                // Handle % input
                if (val.includes('%')) {
                    val = val.replace('%', '');
                    const percent = parseFloat(val);
                    if (!isNaN(percent)) {
                        this.value = (percent / 100).toFixed(2);
                    } else {
                        this.value = '';
                    }
                } else {
                    const num = parseFloat(val);
                    if (!isNaN(num)) {
                        if (num % 1 === 0) {  
                            this.value = (num / 100).toFixed(2);  
                        } else {
                            this.value = num.toFixed(2);  
                        }
                    } else {
                        this.value = '';
                    }
                }
            });
        }
    });
</script>
