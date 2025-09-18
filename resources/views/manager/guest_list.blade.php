<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Guest List</h1>

                <div class="button-group">
                        <div id="add-container" data-url="{{ url('manager/add_guest') }}">
                            <h2 id="add-text">Add Guest</h2>
                            <i id="add-user" class="fas fa-plus-circle fa-3x"  style="cursor:pointer;"></i>
                        </div>
                    <div class="search-container">
                        <form action="{{ route('manager.search_guest') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_guest') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
            
            <div class="table-container">
                <table id="guest-table">
                    <theader>
                        <th>#</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Mobilenum</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Type</th>
                        <th>Avatar</th>
                        <th>Action</th>
                    </theader >

                    <tbody id="guest-tbody">
                        @php $count = 1; @endphp
                        @foreach($guest as $guests)
                        <tr class="{{ $loop->iteration % 2 === 0 ? 'even-row' : 'odd-row' }}">
                            <td>{{ ($guest->currentPage() - 1) * $guest->perPage() + $loop->iteration }}</td>
                            <td>{{($guests->firstname)}}</td>
                            <td>{{($guests->lastname)}}</td>
                            <td>{{($guests->mobilenum)}}</td>
                            <td>{{($guests->email)}}</td>
                            <td>{{($guests->gender)}}</td>
                            <td>{{($guests->birthday)}}</td>
                           @if($guests->role == 'Guest')
                                <td>Hotel Guest</td>
                            @else
                                <td>Day Tour Guest</td>
                            @endif
                            <td>
                                <img src="{{ asset('storage/' . $guests->avatar) }}"/>
                            </td>
                            <td style="cursor: pointer;" >
                                <div class="view" data-url="{{ route('manager.view_guest', ['guestID' => $guests->guestID])}}">
                                    <span>View</span>
                                    <i class="fa-regular fa-eye fa-lg"></i>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="page-container">
                {{ $guest->links() }}
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
   #guest {
        color: #F78A21;
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
    #guest-table {
        width: 100%;
        font-size: .7rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    #guest-table th, 
    #guest-table td {
        padding: 10px;
        text-align: center;
    }
    #guest-table th {
        background-color: #F78A21;
        color: #fff;
    }
    #guest-table img {
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
        margin-top: 1rem;
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
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .dropdown-content div {
        display: flex;  
        background: #e6e6e6;
        align-items: center;
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
        min-height: 10rem;
        max-height: 30rem;
        min-width: 20rem;
        max-width: 90vw;
        background: #fff;
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
    const addGuest = document.getElementById('add-container');
    const viewGuests = document.querySelectorAll('.view');

    viewGuests.forEach(viewGuest => {
        viewGuest.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });

    addGuest.addEventListener('click', function(){
        window.location.href = this.dataset.url;
    });
</script>
