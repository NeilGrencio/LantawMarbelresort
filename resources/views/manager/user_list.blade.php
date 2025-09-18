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
                <h1 id="h2">User List</h1>
                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_user') }}">
                            <h2 id="add-text">Add User</h2>
                            <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                        </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_user') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_user') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                    
                    </div>
                </div>
            
            <div id="table-container">
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Avatar</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-tbody">
                        @php $count = 1; @endphp
                        @foreach($users as $user)                            
                            <tr class="{{ $count % 2 === 0 ? 'even-row' : 'odd-row' }}">
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $count }}</td>
                                <td>
                                    @if(isset($user->s_role) && $user->s_role)
                                        {{ $user->s_firstname ?? '' }} {{ $user->s_lastname ?? '' }}
                                    @elseif(isset($user->g_role) && $user->g_role)
                                        {{ $user->g_firstname ?? '' }} {{ $user->g_lastname ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>••••••••</td>
                                <td>
                                    @if(isset($user->s_role) && $user->s_role)
                                        <img alt="Profile photo of staff user" src="{{ asset('storage/' . $user->s_avatar) }}">
                                     @elseif(isset($user->g_role) && $user->g_role)
                                        <img alt="Profile photo of guest user" src="{{ asset('storage/' . $user->g_avatar) }}">
                                    @else
                                        <img src="{{asset('iamges/profile.jpg')}}"/>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($user->s_role) && $user->s_role)
                                        {{ $user->s_role }}
                                    @elseif(isset($user->g_role) && $user->g_role)
                                        {{ $user->g_role }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                @if ($user->status == 'Active')
                                    <td style="color: green;">
                                        {{ $user->status }}
                                    </td>
                                @else
                                    <td style="color: red;">
                                        {{ $user->status }}
                                    </td>
                                @endif
                                <td>
                                    Action <i class="fa-solid fa-chevron-down fa-lg" style="cursor: pointer;" onclick="toggleDropdown(this)"></i>
                                    <div class="dropdown-content">
                                        <div data-url="{{ route('manager.edit_user', ['userID' => $user->userID]) }}">
                                            <h4>Edit</h4>
                                             <i class="fas fa-pen fa-lg"></i>
                                        </div>
                                        @if($user->status == 'Active')
                                        <div data-url="{{ route('manager.deactivate_user', ['userID' => $user->userID]) }}" >
                                            <h4 >Deactivate</h4>
                                            <i class="fas fa-ban fa-lg" style="color:#d9534f;"></i>
                                        </div>
                                        @else
                                        <div data-url="{{ route('manager.activate_user', ['userID' => $user->userID]) }}">
                                            <h4>Activate</h4>
                                            <i class="fas fa-check-circle fa-lg" style="color:#5cb85c;"></i>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @php $count++; @endphp
                            @endforeach
                            
                    </tbody>
                </table>
            </div>
            <div id="page-container">
                {{ $users->links() }}
            </div>

            @if(session('success'))
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
    * {
        box-sizing: border-box;
    }
    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
    }
    #main-layout {
        display: flex;
        flex-direction: column;
        padding: 1rem;
        width: 100%;
        transition: width 0.3s ease-in-out;
        margin-left: 12rem;
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
    #table-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        height: auto;
        padding: .5rem;
        border-radius: .7rem;
        margin-top: 1rem;
        align-items: center;
        align-content: center;
        background: white;
        box-shadow: .1rem .1rem 0 black;
        overflow-x: auto;
    }
    #user-table {
        width: 100%;
        font-size:.7rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    #user-table th,
    #user-table td {
        padding: 10px;
        text-align: center;
    }
    #user-table th {
        background-color: #F78A21;
    }
    #user-table tr:hover {
        background: #a0a0a0;
    }
    #user-table img {
        border-radius: 50%;
        object-fit: contain;
        width: 40px;
        height: 40px;
        display: block;
        margin: 0 auto;
    }
    #page-container {
        display: flex;
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
    .page-link,
    .pagination span {
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
    .page-item.disabled {
        display: none !important;
    }
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
    .even-row {
        background-color: #e2e2e2;
    }
    .odd-row {
        background-color: #ffffff;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        max-width: 95vw;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        overflow-wrap: break-word;
        word-wrap: break-word;
        white-space: normal;
    }
    .dropdown-content div {
        display: flex;
        background: #e6e6e6;
        font-style: normal;
        align-items: center;
        text-align: center;
        justify-content: space-between;
        padding: 0.5rem;
        cursor: pointer;
    }
    .dropdown-content.show {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        padding: .5rem;
    }
    .alert-message {
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
    document.getElementById('user').style = "color:#F78A21;"
    const message = document.querySelector('.alert-message');
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 2500);
    }

    document.getElementById('add-container').addEventListener('click', function() {
        window.location.href = this.dataset.url;
    });



    function toggleDropdown(icon) {
        // Close all dropdowns first
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
        // Open the dropdown for the clicked icon
        const dropdown = icon.parentElement.querySelector('.dropdown-content');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        // Check if the click is inside a dropdown or on the chevron icon
        const isDropdown = event.target.closest('.dropdown-content');
        const isChevron = event.target.classList.contains('fa-chevron-down');
        if (!isDropdown && !isChevron) {
            document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }

        //  Handle clicks on dropdown-content h4
        if (event.target.matches('.dropdown-content div')) {
            const url = event.target.dataset.url;
            if (url) {
                window.location.href = url;
            }
        }
    });


</script>