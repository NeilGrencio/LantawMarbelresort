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
                <h1>Session Logs</h1>

                <div class="button-group">
                    <div class="search-container">
                        <form action="{{ route('manager.search_logs') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_logs') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table id="logs-table">
                    <thead>
                        <th>#</th>
                        <th>username</th>
                        <th>useragent</th>
                        <th>login status</th>
                        <th>login time</th>
                        <th>login expire</th>
                    </thead>
                    <tbody>
                        @foreach($session as $s)
                        <tr class="{{ $loop->even ? 'even-row' : 'odd-row' }}">
                            <td>{{ $s->sessionID }}</td>
                            <td>{{ $s->username }}</td>
                            <td>{{ $s->useragent }}</td>
                            <td>{{ $s->loginstatus }}</td>
                            <td>{{ $s->sessioncreated }}</td>
                            <td>{{ $s->sessionexpired }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="page-container">
                {{ $session->links() }}
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
</body>
<style>
    #session { color: #F78A21;}
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
        gap:1rem;
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
        width: 100%;
        overflow-x: auto;
        background: white;
        border-radius: .7rem;
        margin-top: 1rem;
        box-shadow: .1rem .1rem 0 black;
        padding: .5rem;
    }

    #logs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    #logs-table th, 
    #logs-table td {
        padding: 0.75rem 1rem;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #logs-table th {
        background-color: #F78A21;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .even-row { background-color: #f9f9f9; }
    .odd-row { background-color: #ffffff; }


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
        word-wrap: break-word
    }
   
</style>
