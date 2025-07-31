<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Guest List</h2>

                <div class="search-container">
                    <form action="{{ route('manager.search_user') }}" method="GET" class="search-form">
                        <input type="text" placeholder="Search.." name="search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            
                <i id="add-guest" class="fas fa-plus-circle fa-3x" data-url="{{ url('manager/add_guest') }}" style="curosr:pointer;"></i>
        
            </div> 
            
            <div class="table-contianer">
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
    #guest{color:#F78A21;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:15rem;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:auto;
        padding:1rem;
        background:white;
        border-radius: 2rem;
        align-items: center;
        justify-content: space-evenly; 
        gap: 1rem;
    }
    .search-container {
        display: flex;
        margin-left:auto;
        right:1rem;
        gap:0.5rem;
        margin-top:1rem;
        align-items: center;
        justify-content: center;
    }
    .search-container button {
        height:3.2rem;
        padding: 6px 10px;
        margin-right:1rem;
        background: #ddd;
        font-size: 17px;
        border: none;
        cursor: pointer;
    }
    .search-container button:hover   {
        background: #ccc;
    }
    input[type=text] {
        height:3rem;
        width:15rem;
        border: 1px solid #ccc;  
        padding:6px 10px;
    } 
    #add-guest{
        width:5rem;
        height:3rem;
        cursor: pointer;
    }
    .table-container{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:auto;
        padding:1rem;
        background:white;
        border-radius: 2rem;
        align-items: center;
        align-content: center;
        margin-top:1rem;
    }
    #guest-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    #guest-table th, #guest-table td {
        padding: 10px;
        text-align: center;
    }

    #guest-table th {
        background-color: #F78A21;
    }

    #guest-table img {
        border-radius: 50%;
        object-fit: cover;
        width: 40px;
        height: 40px;
        display: block;
        margin: 0 auto;
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
    .dropdown-content div{
        display: flex;  
        background: #e6e6e6;
        font-style: normal;
        align-items: center;
        text-align: center;
        justify-content: space-between;
        padding:0.5rem;
        cursor:pointer;
    }
    .dropdown-content.show{
        display: flex;
        flex-direction: column;
        gap:.5rem;
        padding:.5rem;
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
    const addGuest = document.getElementById('add-guest');
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