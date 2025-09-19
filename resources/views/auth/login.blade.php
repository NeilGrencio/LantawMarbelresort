<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

     <style>
    *{box-sizing:border-box;}
    body{
        margin:0;
        padding:0;
        background:white;
        font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        overflow:hidden;
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
        height:2rem;
        width:auto;
    }
    #toolbar h3 {
        margin: 0;
    }
    #toolbar .spacer {
        flex:1;
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
        height:95%;
        padding:2rem;
    }
    #layout-container{
        display:flex;
        height:100%;
        width:100%;
        align-items: center;
    }
    #layout-container img{
        object-fit: contain;
        height: 90%;
        width:70%;
        border-radius:.7rem;
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
        color: orange;
    }
    @media (max-width: 1024px) {
        #layout-container {
            flex-direction: column;
            text-align: center;
        }
        #layout-container img {
            width: 80%;
            height: auto;
            margin-bottom: 1rem;
        }
        #layout-container h1 {
            font-size: 60px;
        }
        .parent-container {
            padding: 1rem;
        }
        #toolbar {
            padding: 0 1rem;
        }
    }
    @media (max-width: 600px) {
        #toolbar {
            flex-wrap: nowrap;
            gap: 0.5rem;
            height: auto;
        }
        #toolbar img {
            height: 1.5rem;
            width: auto;
        }
        #toolbar h3 {
            font-size: 14px;
        }
        #toolbar button {
            height: 1.8rem;
            font-size: 9px;
            width:auto;
            flex-shrink: 0;
            white-space: nowrap;
        }
        #layout-container h1 {
            font-size: 40px;
        }
        #layout-container img {
            width: 100%;
            height: auto;
            border-radius: .5rem;
        }
        .parent-container {
            padding: 0.5rem;
        }
    }

    </style>
    {{-- <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: url("{{ asset('images/large_logo.png') }}") center/cover no-repeat;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .form-container {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 420px;
            background: whitesmoke;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        h2 {
            text-align: center;
            font-size: 1.2rem;
        }

        label {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            font-size: 0.9rem;
            position: relative;
        }

        .password-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        input {
            font-size: 0.9rem;
            border-radius: 0.5rem;
            padding: 0.6rem;
            border: 1px solid #ccc;
            width: 100%;
        }

        small {
            cursor: pointer;
            color: gray;
            transition: all 0.2s ease;
        }

        small:hover {
            color: blue;
            transform: scale(1.05);
        }

        i {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            cursor: pointer;
            color: gray;
            transition: all 0.3s ease;
        }

        i:hover {
            transform: scale(1.3);
            color: black;
        }

        button {
            margin-top: 1rem;
            align-self: center;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 0.7rem;
            background: black;
            color: white;
            transition: all 0.3s ease;
        }

        button:hover {
            background: burlywood;
            color: black;
            transform: scale(1.1);
            cursor: pointer;
        }

        .error-message {
            color: red;
            font-size: 0.8rem;
        }
    </style> --}}
</head>

<body>
    <div class="parent-container">
        <div id="layout-container">
            <img src="{{ asset('images/large_logo.png') }}" />
            <div>
                <div class="form-container">
                    <form action="{{ url('auth/login') }}" method="post">
                        @csrf
                        <h2>Log In</h2>

                        <label for="username">
                            Username
                            <input id="username" name="username" placeholder="Username" required
                                value="{{ old('username') }}" />
                            @error('username')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </label>

                        <label for="password">
                            <div class="password-wrapper">
                                <span>Password</span>
                                <small data-url="{{ url('forgot_password') }}">Forgot Password?</small>
                            </div>
                            <input id="password" name="password" type="password" placeholder="Password" required />
                            <i class="fa-solid fa-eye-slash fa-lg" id="password-toggle"></i>
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </label>

                        <button type="submit">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const password = document.getElementById('password');
        const togglePassword = document.getElementById('password-toggle');

        togglePassword.addEventListener('click', function() {
            if (password.type === 'text') {
                password.type = 'password';
                togglePassword.className = 'fa-solid fa-eye-slash fa-lg';
            } else {
                password.type = 'text';
                togglePassword.className = 'fa-solid fa-eye fa-lg';
            }
        });
    </script>
</body>

</html>
