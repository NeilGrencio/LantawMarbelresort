<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
</head>
<style>
    *{box-sizing: border-box}
    .email-container{
        background:whitesmoke;
        width: 70%;
        display:flex;
        flex-direction: column; 
        margin:auto;
        font-family:'Roboto', Arial, sans-serif;
    }
    img{
        display:flex;
        align-self: flex-start;
        height:10%;
        width:10%;
    }
    #intro-text{
        color:orange;
        font-weight: 400;
    }
    .otp-container{
        display:flex;
        flex-direction: column;
        background:white;
        border:black 1px solid;
        margin-left:auto;
        margin-right:auto;
    }
    #req-text{
        color:rgb(127, 127, 127);
    }
    .otp-container h1{
        color:orange;
    }
</style>
<html>
    <body>
        <div class="email-container">
            <h2><span id="intro-text">Hello, {{ $username }}!</span></h2>
            <h3>We have received your request for an OTP. Here is the Lantaw-Marbel OTP code you need to reset your password:</h3>

            <div class="otp-container">
                <span id="req-text">Request Made On</span>
                <strong>{{ $datetime }}</strong>
                <h1>{{ $otp }}</h1>
            </div>

            <br/>
            <br/>
            <h2>If this wasn't you</h2>
            <p>The email was sent because someone is trying to reset the password of your Lantaw-Marbel account. The login attempt included your correct account username</p>

            <p>The OTP code contained in this email is required to reset your password.The OTP code is advisable to not be shared to other users.</p>
        </div>
        
        <p>This notification was sent to the email attached to your Lantaw-Marbel account</p>

        <p>This is an auto generated email please do not respond. If you need additional help please contact the Lantaw-Marbel Resort for more information</p>
    </body>
</html>