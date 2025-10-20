<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lantaw-Marbel Resort - Add Discount</title>
<link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favico.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<div id="layout">
    @include('components.sidebar')

    <div id="main-layout">
        <div id="title-header">
            <h1>Add Discount</h1>
        </div>

        <form method="post" action="{{url('manager/edit_discount/' . $discount->discountID)}}">
            @csrf

            <div class="form-container">
                <label for="name">Discount Name</label>
                <input id="name" name="name" type="text" placeholder="Senior Discount" 
                    required value="{{ old('name', $discount->name) }}"/>
                @error('name') <p class="error">{{ $message }}</p> @enderror

                <label for="percent">Discount Percent Amount</label>
                <input id="percent" name="percent" type="text" placeholder="0.20" 
                    value="{{ old('percent', $discount->percentamount ?? '') }}"/>
                <label class="input-prompt">Only one amount can be entered. Fill either Percent OR Flat amount.</label>
                @error('percent') <p class="error">{{ $message }}</p> @enderror

                <label for="flat">Discount Flat Amount</label>
                <input id="flat" name="flat" type="text" placeholder="200" 
                    value="{{ old('flat', $discount->flatamount ?? '') }}"/>
                @error('flat') <p class="error">{{ $message }}</p> @enderror

                <label for="type">Discount Type</label>
                <select id="type" name="type" required>
                    <option value="" disabled>Select Discount Type</option>
                    <option value="Discount" {{ old('type', $discount->type) == 'Discount' ? 'selected' : '' }}>Discount</option>
                    <option value="Promo" {{ old('type', $discount->type) == 'Promo' ? 'selected' : '' }}>Promo</option>
                </select>
                @error('type') <p class="error">{{ $message }}</p> @enderror

                <label for="status">Discount Status</label>
                <select id="status" name="status" required>
                    <option value="" disabled>Select Discount Status</option>
                    <option value="Available" {{ old('status', $discount->status) == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Unavailable" {{ old('status', $discount->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('status') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="button-container">
                <button id="cancel-button" type="button" data-url="{{url('manager/discount')}}" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-submit">Save</button>
            </div>
        </form>

        @if (session('success'))
        <div class="alert-message success">
            <h2>{{ session('success') }}</h2>
        </div>
        @endif
        @if (session('error'))
        <div class="alert-message error">
            <h2>{{ session('error') }}</h2>
        </div>
        @endif
    </div>
</div>

<style>
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

#title-header h1 {
    font-size: 2rem;
    color: #333;
    font-weight: 600;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: #fff;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 100%;
}

label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

input, select {
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #ccc;
    font-size: 1rem;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #F78A21;
    outline: none;
    box-shadow: 0 0 5px rgba(247,138,33,0.3);
}

.error {
    color: #e53935;
    font-size: 0.85rem;
}

.button-container {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1rem;
}

button.btn {
    padding: 0.75rem 2rem;
    border-radius: 1rem;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    transition: 0.3s;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

button.btn:hover {
    transform: translateY(-2px);
}

.btn-cancel {
    background: #e0e0e0;
    color: #333;
}

.btn-cancel:hover {
    background: #ccc;
}

.btn-submit {
    background: #F78A21;
    color: #fff;
}

.btn-submit:hover {
    background: #FFB74D;
}

.alert-message {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    padding: 1rem 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 1000;
    font-weight: 600;
    animation: fadein 0.5s;
}

.alert-message.success {
    border-left: 5px solid green;
}

.alert-message.error {
    border-left: 5px solid red;
}

.input-prompt {
    font-size: 0.8rem;
    color: #666;
    margin-top: -0.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

@keyframes fadein {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media screen and (max-width: 768px) {
    #main-layout {
        margin-left: 0;
        padding: 1rem;
    }
    .form-container {
        padding: 1rem;
    }
    .button-container {
        flex-direction: column-reverse;
        align-items: stretch;
    }
    button.btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cancelButton = document.getElementById('cancel-button');
    const alert = document.querySelector('.alert-message');
    const percentInput = document.getElementById('percent');
    const flatInput = document.getElementById('flat');

    // Hide alert after 2.5 seconds
    if(alert) {
        setTimeout(() => { alert.style.display = 'none'; }, 2500);
    }

    // Cancel button redirects
    if(cancelButton) {
        cancelButton.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    }

    function showPrompt() {
        // Optional: could highlight inputs or show a message
        const prompt = document.querySelector('.input-prompt');
        if(prompt) {
            prompt.style.opacity = '1';
            setTimeout(() => { prompt.style.opacity = '0.7'; }, 2000);
        }
    }

    // Percent input logic
    percentInput.addEventListener('input', function() {
        // Clear flat input if typing here
        if(flatInput.value !== '') {
            flatInput.value = '';
            showPrompt();
        }

        // Allow only digits and one dot
        this.value = this.value.replace(/[^0-9.]/g,'');
        const parts = this.value.split('.');
        if(parts.length > 2){
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });

    percentInput.addEventListener('blur', function() {
        let val = parseFloat(this.value);
        if(!isNaN(val)){
            // Convert whole number to decimal
            if(val >= 1) val = val / 100;
            this.value = val.toFixed(2);
        } else {
            this.value = '';
        }
    });

    // Flat input logic
    flatInput.addEventListener('input', function() {
        // Clear percent input if typing here
        if(percentInput.value !== '') {
            percentInput.value = '';
            showPrompt();
        }

        // Allow only digits
        this.value = this.value.replace(/[^0-9]/g,'');
    });

    flatInput.addEventListener('blur', function() {
        // Optional: format flat amount if needed
        let val = parseFloat(this.value);
        this.value = !isNaN(val) ? val : '';
    });
});
</script>
</body>
</html>
