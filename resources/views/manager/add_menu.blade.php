<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{ asset('favico.ico') }}" />
  <title>Lantaw-Marbel Resort</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #e7e7e7;
      margin: 0;
      padding: 0;
      color: #222;
      overflow-x: hidden;
    }

    #layout {
      min-height: 100vh;
      width: 100%;
    }

    #main-layout {
      margin-left: 15rem;
      margin-top: -4.5rem;
      width: calc(100% - 15rem);
      padding: 2rem;
      overflow-y: auto;
      background: #e7e7e7;
    }

    #title-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    #title-header h1 {
      font-size: 1.6rem;
      font-weight: 600;
      color: #333;
    }

    form {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 2rem;
      max-width: 100%;
      margin: 0 auto;
    }

    #form-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    label {
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 0.3rem;
    }

    input, select {
      font-size: 1rem;
      padding: 0.7rem;
      border: 1px solid #ccc;
      border-radius: 0.6rem;
      outline: none;
      transition: border-color 0.2s;
      width: 100%;
    }

    input:focus, select:focus {
      border-color: #f78a21;
      box-shadow: 0 0 0 3px rgba(247,138,33,0.2);
    }

    /* Image upload */
    .image {
      width: 14rem;
      height: 14rem;
      border-radius: 0.7rem;
      object-fit: cover;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 1rem;
    }

    .image:hover {
      transform: scale(1.02);
      box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }

    #button-container {
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    #button-container button {
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 0.6rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    #cancel {
      background: #ccc;
      color: #333;
    }

    #cancel:hover {
      background: #999;
      color: white;
    }

    #button-container button[type="submit"] {
      background: #f78a21;
      color: white;
    }

    #button-container button[type="submit"]:hover {
      background: #e67d1d;
    }

    /* Alert Message */
    .alert-message {
      position: fixed;
      bottom: 1rem;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      padding: 1rem 2rem;
      border-radius: 0.7rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      color: #333;
      animation: fadeOut 2.5s forwards;
      z-index: 1000;
    }

    @keyframes fadeOut {
      0%, 90% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }

    .custom-dropdown {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .custom-dropdown input {
        border: 1px solid #ccc;
        padding: .5rem .7rem;
        border-radius: .7rem;
        font-size: 15px;
        outline: none;
        transition: all 0.2s ease;
    }

    .custom-dropdown input:focus {
        border-color: #F78A21;
        box-shadow: 0 0 5px rgba(247, 138, 33, 0.5);
    }

    .dropdown-list {
        position: absolute;
        top: 110%;
        left: 0;
        right: 0;
        max-height: 200px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: .7rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        overflow-y: auto;
        display: none;
        z-index: 10;
    }

    .dropdown-list div {
        padding: .5rem;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .dropdown-list div:hover {
        background: #F78A21;
        color: white;
    }

  </style>
</head>

<body>
<div id="layout">
  @include('components.sidebar')

  <div id="main-layout">
    <div id="title-header">
      <h1>Add Menu Item</h1>
    </div>

    <form action="{{ url('manager/add_menu') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div id="form-container">
        <label for="menuname">Menu Name</label>
        <input id="menuname" name="menuname" type="text" placeholder="Sinigang" value="{{ old('menuname') }}" required/>

        <label for="itemtype">Item Type</label>
        <div class="custom-dropdown">
            <input type="text" id="itemtype" name="itemtype" placeholder="Select or type menu type" 
                value="{{ old('itemtype') }}" autocomplete="off" />
            <div class="dropdown-list" id="itemtype-list">
                <div>Starter</div>
                <div>Pork</div>
                <div>Vegetables</div>
                <div>Sea Food</div>
                <div>Chicken</div>
                <div>Soup</div>
                <div>Noodles</div>
                <div>Beef</div>
                <div>Rice</div>
                <div>Pizza</div>
                <div>Salad</div>
                <div>Quenchers</div>
                <div>Canned Juices</div>
                <div>Smoothies</div>
                <div>Frappucino</div>
                <div>Fruit Tea</div>
                <div>Beer</div>
                <div>Rum</div>
                <div>Brandy</div>
                <div>White Wine</div>
                <div>Red Wine</div>
                <div>Gin</div>
                <div>Whisky</div>
                <div>Breakfast</div>
            </div>
        </div>

        <label for="image">Select Menu Image</label>
        <img id="image-preview" class="image" src="{{ asset('images/photo.png') }}" alt="Preview">
        <input id="image" name="image" type="file" accept="image/webp,image/png,image/jpeg,image/jpg"/>

        <label for="price">Menu Price</label>
        <input id="price" name="price" placeholder="75.00" type="text" value="{{ old('price') }}" required/>

        <label for="status">Menu Status</label>
        <select id="status" name="status">
          <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
          <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
      </div>

      <div id="button-container">
        <button id="cancel" type="button" data-url="{{ url('manager/menu_list') }}">Cancel</button>
        <button type="submit">Save</button>
      </div>
    </form>

    @if (session('error'))
      <div class="alert-message"><h2>{{ session('error') }}</h2></div>
    @endif
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const priceInput = document.getElementById('price');
  const imageInput = document.getElementById('image');
  const imagePreview = document.getElementById('image-preview');
  const cancelBtn = document.getElementById('cancel');
    const itemInput = document.getElementById('itemtype');
    const dropdownList = document.getElementById('itemtype-list');
    const allItems = Array.from(dropdownList.querySelectorAll('div'));
  const message = document.querySelector('.alert-message');

  if (message) setTimeout(() => (message.style.display = 'none'), 2500);

  // Cancel button
  cancelBtn?.addEventListener('click', () => {
    window.location.href = cancelBtn.dataset.url;
  });

  // Image preview
  imageInput?.addEventListener('change', () => {
    const file = imageInput.files[0];
    imagePreview.src = file ? URL.createObjectURL(file) : '{{ asset("images/photo.png") }}';
  });

  // Price input restriction
  priceInput.addEventListener('input', () => {
    priceInput.value = priceInput.value.replace(/[^0-9.]/g, '');
    const parts = priceInput.value.split('.');
    if (parts.length > 2) priceInput.value = parts[0] + '.' + parts[1];
  });

  priceInput.addEventListener('blur', () => {
    if (priceInput.value) priceInput.value = parseFloat(priceInput.value).toFixed(2);
  });

  itemInput.addEventListener('focus', () => {
        dropdownList.style.display = 'block';
        filterDropdown('');
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-dropdown')) {
            dropdownList.style.display = 'none';
        }
    });

    // Filter as you type
    itemInput.addEventListener('input', function () {
        const value = this.value.trim().toLowerCase();
        filterDropdown(value);
    });

    // When selecting from dropdown
    dropdownList.addEventListener('click', function (e) {
        if (e.target.tagName === 'DIV') {
            itemInput.value = e.target.textContent;
            dropdownList.style.display = 'none';
        }
    });

    // Function to filter dropdown items
    function filterDropdown(query) {
        let visibleCount = 0;
        allItems.forEach(item => {
            if (item.textContent.toLowerCase().includes(query)) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        dropdownList.style.display = visibleCount > 0 ? 'block' : 'none';
    }
});
</script>
</body>
</html>
