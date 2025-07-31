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
            <div class="title-contianer">
                <p>Welcome Manager!</p>
            </div>
            <div class="card-container">
                <div class="card">
                    <p>Pending Room Reservation</p>
                    <div id="new-notification">1</div>
                </div>  
                
                <div class="card">
                    <p>Cancelled Room Reservation</p>
                    <div id="new-notification">1</div>
                </div>  

                <div class="card">
                    <p>Accepted Room Reservation</p>
                    <div id="new-notification">1</div>
                </div>  

                <div class="card">
                    <p>Pending Kiddy Pool Reservation</p>
                    <div id="new-notification">1</div>
                </div>  
                
                <div class="card">
                    <p>Cancelled Kiddy Pool Reservation</p>
                    <div id="new-notification">1</div>
                </div>  

                <div class="card">
                    <p>Accepted Kiddy Pool Reservation</p>
                    <div id="new-notification">1</div>
                </div>  

                <div class="card">
                    <p>Chat</p>
                    <div id="new-notification">1</div>
                </div>  
                
                <div class="card">
                    <p>Feedback</p>
                    <div id="new-notification">1</div>
                </div>  

                <div class="card">
                    <p>Available Discounts</p>
                    <div id="new-notification">1</div>
                </div>  
                 
            </div>
        </div>
    </div>
</body>

<style>
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        padding:1rem;
        margin-left:15rem;
    }
    .title-contianer{
        margin-top:1rem;
        font-size:3rem;
        font-weight: lighter;
    }
    .card-container{
        display:flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap:2rem;
    }
    .card{
        width:24rem;
        height:5rem;
        display:flex;
        position: relative;
        align-items: center;
        padding:1rem;
        font-size:.9rem;
        background:white;
        border-radius:.5rem;
        border:1px solid black;
        box-shadow:.1rem .2rem 0 black;
    }
    #new-notification{
        display:flex;
        position:absolute;
        height:2rem;
        width: 2rem;
        align-items: center;
        justify-content: center;
        top:-1rem;
        right:-1rem;
        margin-left:auto;
        background:red;
        color:white;
        border-radius:50%;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        font-size:1rem;
    }
</style>

<script>
    document.getElementById('dashboard').style = "color:#F78A21;"
</script>