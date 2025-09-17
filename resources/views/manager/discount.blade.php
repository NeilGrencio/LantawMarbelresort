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
            <div id="layout-header">
                <h1>Discounts</h1>

                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_discount') }}">
                            <h2 id="add-text">Add a Discount</h2>
                            <i id="add-user" class="fas fa-plus-circle fa-3x" style="cursor:pointer;"></i>
                        </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_discount') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_discount') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
            <div class="table-container">
                <table id="discount-table">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($discount as $d)
                        @php
                            $rowClass = ($d->status == 'Available') ? 'available' : 'unavailable';
                            $textColor = ($d->status == 'Available') ? 'green' : 'red';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $d->discountID }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->percentage }}%</td>
                            <td style="color: {{ $textColor }}">{{ $d->status }}</td>
                            <td class="action-toggle">
                                Action <i class="fa-solid fa-chevron-down fa-lg"></i>
                            
                            <div class="action-dropdown">
                                <div data-url="{{url('manager/edit_discount/' . $d->discountID)}}">
                                    <h2>Update</h2>
                                    <i class="fa-solid fa-pencil fa-lg"></i>
                                </div>
                                @if($d->status == 'Available')
                                    <div style="color:red;" data-url="{{url('manager/deactivate_discount/' . $d->discountID)}}">
                                        <h2>Deactivate</h2>
                                        <i class="fa-solid fa-circle-xmark fa-lg"></i>
                                    </div>
                                @else
                                    <div style="color:green;" data-url="{{url('manager/activate_discount/' . $d->discountID)}}">
                                        <h2>Activate</h2>
                                        <i class="fa-solid fa-circle-check fa-lg"></i>
                                    </div>
                                @endif
                            </div>
                            </td>
                        </tr>
                        
                    @endforeach
                </tbody>
                </table>
                <div id="page-container">
                    {{ $discount->links() }}
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
            </div>
            
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
        display:flex;
        flex-direction: column;
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:12rem;
        gap:.5rem;
    }
    #layout-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 8%;
        padding: 1rem 3rem 1rem 2rem;
        background: white;
        border-radius: .7rem;
        font-size: .6rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    .search-container .reset-btn {
        padding: 10px 15px;
        background-color: #e53935;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        margin-left: 10px;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    .search-container .reset-btn:hover {
        background-color: #b71c1c;
    }

    .button-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
    }
    #add-container:hover {
        color: #F78A21;
    }
    #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
        margin-left: 0.5rem;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-content: center;
        margin: 15px 0;
    }

    .search-container form {
        display: flex;
        align-items: center;
    }

    .search-container input[type="text"] {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 25px 0 0 25px;
        outline: none;
        width: 250px;
        font-size: 14px;
    }

    .search-container button {
        padding: 10px 15px;
        border-left: none;
        background-color: #000000;
        color: white;
        border-radius: 0 25px 25px 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-container button:hover {
        background-color: #F78A21;
        border: 1px solid #F78A21;
    }
    .table-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        height: auto;
        padding: .5rem;
        border-radius: .7rem;
        margin-top: 1rem;
        align-items: center;
        background: white;
        box-shadow: .1rem .1rem 0 black;
        overflow-x: auto;
    }
    #discount-table{
        width: 100%;
        font-size: .8rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    thead{
       background-color: #F78A21;
        color: #fff;
        padding: 10px;
        text-align: center;
        height:1.7rem;
    }
    td{
        padding: 10px;
        text-align: center;
        height:1.7rem;
    }
    .action-toggle{
        cursor:pointer;
    }
    .action-dropdown{
        display:none;
        flex-direction:column;
        background:rgb(236, 236, 236);
        border:1px solid black;
        border-radius:.7rem;
        height:8.5rem;
        width:10rem;
        padding: .5rem;
        right:9rem;
        position: absolute;
        cursor:pointer;
        z-index:1;
    }
    .action-dropdown div{
        display:flex;
        flex-direction: row;
        width:100%;
        background:rgb(255, 255, 255);
        border:solid 1px black;
        border-radius:.5rem;
        cursor:pointer;
        font-size:.7rem;
        justify-content:space-evenly;
        align-items: center;
        margin-top:.5rem;
        transition:all .3s ease;
    }
    .action-dropdown div:hover {
        background: #F78A21;
        color:white;
    }
    .available{
        background-color:white;
    }
    .unavailable{
        background-color:rgb(228, 228, 228);
    }
    #page-container{
        display:flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        background: transparent;
        align-items: center;
    }
    .page-item {
        display: flex;
        align-items: center;
    }
    .page-link, .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        min-height: 2.5rem;
        padding: 0.5rem 0.75rem;
        background: #fff;
        color: #F78A21;
        text-decoration: none;
        border: 1.5px solid #F78A21;
        border-radius: 50%;
        font-size: 1.1rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, border 0.2s;
        margin: 0 0.15rem;
    }
    .page-item.active .page-link,
    .page-link:hover {
        background: #F78A21;
        color: #fff;
        border-color: #F78A21;
    }
    .page-item.disabled .page-link,
    .page-item.disabled span {
        color: #ccc;
        pointer-events: none;
        background: #f8f9fa;
        border-color: #eee;
    }
    .page-item.disabled { display: none !important; }

    /* Style for "Page X of Y" */
    .pagination .page-status {
        background: transparent;
        border: none;
        color: #333;
        font-size: 1rem;
        font-weight: 400;
        border-radius: 0;
        min-width: unset;
        min-height: unset;
        margin: 0 0.5rem;
        padding: 0;
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
        const toggles = document.querySelectorAll('.action-toggle');
        const addDiscount = document.getElementById('add-container');
        const message = document.querySelector('.alert-message');

        // Auto-hide success/error message
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        if (addDiscount) {
            addDiscount.addEventListener('click', function () {
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        }

        // Toggle action dropdown
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                // Close other dropdowns
                document.querySelectorAll('.action-dropdown').forEach(drop => {
                    if (drop !== this.querySelector('.action-dropdown')) {
                        drop.style.display = 'none';
                    }
                });

                const dropdown = this.querySelector('.action-dropdown');
                if (dropdown) {
                    dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
                }
                e.stopPropagation();
            });
        });

        // Handle dropdown action click (redirect)
        document.querySelectorAll('.action-dropdown div[data-url]').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Hide dropdowns when clicking outside
        document.addEventListener('click', function () {
            document.querySelectorAll('.action-dropdown').forEach(drop => {
                drop.style.display = 'none';
            });
        });
    });
</script>
