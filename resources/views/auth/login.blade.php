<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<style>
    body{
        display:flex;
        flex-direction:row;
        background:linear-gradient(to top,rgb(189, 189, 189), rgb(228, 228, 228));
        padding:0;
        margin:0;
        font-family: Robot, sans-serif; 
    }
    *{box-sizing: border-box;}
    .logo-container{
        display:flex;
        width:50%;
        align-items:center;
        justify-content: center;
        font-size:3rem;
        font-weight:bold;
    }
    .form-container{
        display:flex;
        flex-direction: column;
        align-items: center;
        background: white;
        border-radius:1rem;
        height:100%;
        padding:7rem;
        width:100%;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,.2);
        gap:1rem;   
    }
    h2{
        font-size:3rem;
    }
    input{
        font-size:17px;
        border-radius:.7rem;
        padding:.5rem;
        width:100%;
    }
    label{
        display: flex;
        align-self:center;
        margin-right:auto;
        left:1;
        font-weight: bold;
        font-size:20px;
    }
    .label-cotainer{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        width:100%;
        align-items: center;
    }
    p{
        cursor:pointer;
        transition: all .2s ease;
    }
    p:hover{
        color:blue;
        transform:scale(1.2 );
    }
    .password-container{
        display: flex;
        flex-direction: row;
        width:100%;
        align-items: center;
        margin-top:-1rem;
        position: relative;
    }
    .password-container input{
        width:100rem;
        position: relative;
    }
    i{
        display:flex;
        margin-left:auto;
        right:1rem;
        position: absolute;
        cursor:pointer;
        color:grey;
        transition:all .3s ease;
    }
    i:hover{
        transform: scale(1.5);
        color:black;
    }
    button{
        display: flex;
        align-self: center;
        justify-content: center;
        align-items: center;
        font-size:15px;
        font-weight: bold;
        height:3rem;
        width:7rem;
        border-radius:.7rem;
        border:none;
        background:black;
        color:white;
        transition:all .3s ease;
    }
    button:hover{
        background: burlywood;
        color:black;
        cursor:pointer;
        transform: scale(1.2);
    }
    form{
        width:50%;
        height:100%;
        padding:10rem 3rem 10rem 3rem;
    }
</style>
<body>
    <div  class="logo-container">
        Welcome to Lantaw-Marbel Resort
    </div>
    <form action="{{url('login')}}" method='post'>
        @csrf
        <div class="form-container">
            <h2>Log In</h2>

            <label for="username">Username</label>
            <input id="username" name="username" placeholder="Username" required value="{{old('username')}}"/>

            @error('username')
                <div style="color:red;">{{ $message }}</div>
            @enderror

            <div class="label-cotainer">
                <label for="password">Password</label>
                <p data-url="{{url('forgot_password')}}">Forgot Password?</p>
            </div>
            
            <div class="password-container">
                <input id="password" name="password" type="password" placeholder="Password" required/>
                <i class="fa-solid fa-eye-slash fa-lg" id="password-toggle"></i>
            </div>
            @error('password')
                <div style="color:red;">{{ $message }}</div>
            @enderror
            <button type="submit">Log In</button>
        </div>
    </form>
</body>
<script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('password-toggle');

    togglePassword.addEventListener('click', function(){
        if(password.type === 'text'){
            password.type='password';
            togglePassword.className = 'fa-solid fa-eye-slash fa-lg';
        } else {
            password.type = 'text';
            togglePassword.className = 'fa-solid fa-eye fa-lg';
        }
    });

</script>
</html>