<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<style>
    #feedback{color:orange;}
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
            margin-left:12rem;
            margin-right:.7rem;
            overflow-y: hidden;
            overflow-x: hidden;
        } 
        #layout-header{
            display: flex;
            flex-direction: row;
            width: 100%;
            height:4rem;
            padding:1rem;
            background:white;
            border-radius: .7rem;
            border:1px solid black;
            box-shadow:.1rem .1rem 0 black;
            align-items: center;
            justify-content: space-between; 
            gap: 1rem;
            font-size: .9rem;
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
            margin-top:1rem;
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
        #feedback-table {
            width: 100%;
            font-size:.7rem;
            border-collapse: collapse;
            transition: all 0.3s ease-in;
        }
        #feedback-table th,
        #feedback-table td {
            padding: 10px;
            text-align: center;
        }
        #feedback-table th {
            background-color: #F78A21;
        }
        #feedback-table tr:hover {
            background: #a0a0a0;
        }
        #feedback-table img {
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
</style>
<body>
    <div id="layout">
        @include('components.sidebar')
            <div id="main-layout">
                <div id="layout-header">
                    <h1>Feedback List</h1>

                    <div class="search-container">
                        <form action="{{ route('manager.search_feedback') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('manager.search_feedback') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
                <div id="table-container">
            <table id="feedback-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Guest Name</th>
                        <th>Feedback</th>
                        <th>Date</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $index => $feedback)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $feedback->fullname }}</td>
                        <td>{{ $feedback->message }}</td>
                        <td>{{ \Carbon\Carbon::parse($feedback->date)->format('M d, Y') }}</td>
                        <td>
                            @for ($i = 1; $i <= 5; $i++)
                                @if($i <= $feedback->rating)
                                    <i class="fas fa-star" style="color: gold;"></i>
                                @else
                                    <i class="far fa-star" style="color: gold;"></i>
                                @endif
                            @endfor
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="page-container">
            {{ $feedbacks->links() }}
        </div>

    </div>
    </div>
</body>
