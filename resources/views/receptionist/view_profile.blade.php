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
<body>
    <body>
    <div id="layout">
        @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>View Profile</h1>
        </div>
        <div class="profile-content">
            <div id="avatar-container">
                <img src={{asset('images/profile.jpg')}}/>
            </div>

            <div id="profile-info">
                <label for="name">Name:</label>
                <h3 name="name">{{$user->fullname}}</h3>

                <label for="name">Username:</label>
                <h3 name="name">{{$user->username}}</h3>

                <label for="name">Role:</label>
                <h3 name="name">{{$user->role}}</h3>
                
                <label for="gender">Gender:</label>
                <h3 name="name">{{$user->gender}}</h3>

                <label for="email">Email:</label>
                <h3 name="email">{{$user->email}}</h3>

                <label for="contact">Contact #:</label>
                <h3 name="contact">{{$user->mobilenum}}</h3>
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
    .profile-content{
        display:flex;
        flex-direction: row;
        margin:auto;
        padding:1rem;
        gap:1rem;
        height:70%;
        width:70%;
        background:white;
        border:1px solid black;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0 black;
    }
    #avatar-container{
        display:flex;
        height:70%;
        width:40%;
        background:maroon;
        border-radius:.5rem;
    }
    #avatar-container{
        object-fit:cover;
    }

    #profile-info{
        display:flex;
        flex-direction: column;
        margin-top:1rem;
    }

    .row-info{
        display: flex;
        flex-direction: column;
    }