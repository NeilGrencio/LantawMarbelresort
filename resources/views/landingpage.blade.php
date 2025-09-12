<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw Marbel ORMS</title>
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <style>
    *{box-sizing:border-box;}
    body{
        margin:0;
        padding:0;
        background:white;
        font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    #toolbar{
        display: flex;
        width:100%;
        height:3rem;
        padding:0 2rem;
        background:black;
        color:white;
        align-items:center;
        gap:1rem;
    }
    #toolbar img{
        object-fit: cover;
        height:100%;
    }
    #toolbar h3 {
        margin: 0;
    }
    #toolbar .spacer {
        flex:1; /* pushes buttons to the right */
    }
    #toolbar button{
        height:2rem;
        padding:0 1rem;
        border-radius:1rem;
        background:none;
        color:white;
        font-weight:500;
        transition:all 0.2s ease-in;
    }
    #loginbutton {
        border: 2px solid orange;
    }
    #loginbutton:hover{
        background:orange;
        color:black;
        cursor:pointer;
    }
    #downloadbutton {
        border: 2px solid green;
    }
    #downloadbutton:hover {
        background:green;
        color:white;
        cursor:pointer;
    }
    .parent-container{
        display:flex;
        flex-direction: row;
        width:100%;
        height:70%;
        padding:5rem;
    }
    #layout-container{
        display:flex;
        height:30rem;
        width:100%;
        padding:0 4rem 0 0;
        align-items: center;
        background: rgb(198, 227, 250);
        border-radius:2rem;
        box-shadow: 1rem 1rem 0rem rgb(0, 167, 0);
    }
    #layout-container img{
        object-fit: contain;
        height: 80%;
        width:50%;
    }
    #layout-container div{
        display:flex;
        flex-direction: column;
        align-items:center;
        justify-content: center;
    }
    #layout-container h1{
        margin:0;
        font-size: 90px;
        word-wrap:break-word;
        text-align:center;
        color: white;
        text-shadow: 1rem 0px rgba(0, 0, 0, 0.5);
    }
    </style>
</head>
<body>
    <div id="toolbar">
        <img src="{{asset('images/logo.png')}}"/>
        <h3>Lantaw Marbel</h3>
        <div class="spacer"></div>
        <button id="downloadbutton" data-url="https://lantawmarbelresort.site/app-debug.apk">
            <span>Download App</span>
        </button>
        <button id="loginbutton" data-url="{{url('auth/login')}}">
            <span>Log In</span>
        </button>
    </div>
    <div class="parent-container">
        <div id="layout-container">
            <img src="{{asset('images/logo.png')}}"/>
            <div>
                <h2>Welcome to the</h2>
                <h1>LANTAW MARBEL</h1>
                <h1>ORMS</h1>
                <p>This is a resort management system built specifically for Lantaw-Marbel</p>
            </div>
        </div>
    </div>
</body>
<script>
    const buttonlogin = document.getElementById('loginbutton');
    if(buttonlogin){
        buttonlogin.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });
    }

    const buttondownload = document.getElementById('downloadbutton');
    if(buttondownload){
        buttondownload.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });
    }
</script>
</html>
