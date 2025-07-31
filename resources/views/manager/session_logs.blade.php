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
            </div>
            <table>
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
                    <tr>
                        <td>{{$s->sessionID}}</td>
                        <td>{{$s->username}}</td>
                        <td>{{$s->useragent}}</td>
                        <td>{{$s->loginstatus}}</td>
                        <td>{{$s->sessioncreated}}</td>
                        <td>{{$s->sessionexpired}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        margin-left:15rem;
        gap:1rem;
    }
    #layout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height:5%;
        padding: 1rem 3rem 1rem 2rem;
        background: white; 
        border-radius: 2rem;
        font-size: 70%;
        gap: 1rem;
    }
    table{
        background:white;
        box-shadow:.2rem .1rem 0 rgba(0,0,0,0.2);
        border-radius:.7rem;
    }
    thead{
        background:grey;
        color:white;
        width:100%;
    }
    tr{
        text-align: center;
        height:2rem;
        word-wrap: wrap;
        max-width:15rem;
    }
</style>