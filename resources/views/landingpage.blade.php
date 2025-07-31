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
        /*background:rgb(255, 182, 47);*/
        background:white;
        font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, Helvetica, sans-serif;
    }
    #toolbar{
        display: flex;
        width:100%;
        height:3rem;
        padding:0 2rem 0 2rem;
        background:black;
        color:white;
        align-items:center;
        gap:2rem;
    }
    #toolbar img{
        object-fit: cover;
        height:100%;
    }
    #toolbar button{
        right: 1;
        margin-left: auto;
        height:2rem;
        width:6rem;
        border: solid 2px orange;
        background:none;
        color:white;
        border-radius:1rem;
        transition:background 0.2s ease-in;
    }
    #toolbar button:hover{
        cursor:pointer;
        background:white;
        color:black;
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
        right:1;
        margin-left:left;
    }
    #layout-container h1{
        margin:0;
        font-size: 90px;
        word-wrap:break-word;
        justify-content: center;
        color: white;
        text-shadow: 1rem 0px rgba(0, 0, 0, 0.5);
    }
    </style>
</head>
<body>
    <div id="toolbar">
        <img src="{{asset('images/logo.png')}}"/>
        <h3>Lantaw Marbel</h3>
        <button>Log In</button>
    </div>
    <div class="parent-container">
        <div id="layout-container">
            <img src="{{asset('images/logo.png')}}"/>

            <div>
                <h2> Welcome to the </h2>
                <h1>LANTAW MARBEL</h1> 
                <h1>ORMS</h1>
                <p>This is a resort management system built specifically for lantaw-marbel</p>
            </div>
        </div> 
    </div>
    <div class="parent-container" style="background:rgb(255, 182, 47);">
        <div>

        </div>
        <div>
            
        </div>
        <div>
            
        </div>
    </div>
    
</body>
</html>