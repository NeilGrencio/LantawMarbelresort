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
            <h1>Create Guest Information</h1>

             <form method="POST" action="{{ route('manager.add_guest') }}" enctype="multipart/form-data">
                @csrf
                <div id="add_user-container">
                    <div id="form-header-1">
                        <h3>Personal Information</h3>
                    </div>

                    <div id="row1">
                        <div>
                            <label for="txtfirstname">Firstname:</label>
                            <input id="txtfirstname" type="text" placeholder="Firstname.." name="firstname" value="{{$guest->firstname}}" readonly>
                        </div>

                        <div>
                            <label for="txtalstname">Lastname:</label> 
                            <input id="txtalstname" type="text" placeholder="Lastname.." name="lastname" value="{{$guest->lastname}}" readonly>
                        </div>
                    </div>

                    <div id="row2">
                        <div>
                            <label for="txtcontactnum">Contact #:</label>
                            <div style="display:flex; flex-direction: row;gap:.2rem;">
                                <span style="display:flex; align-items:center; padding:.5rem; background: rgba(240, 240, 240, 0.822); border: 1px solid black; border-radius: .5rem .2rem .2rem .5rem; width:9%;">+63</span>
                                <input id="txtcontactnum" type="text"  maxlength="10" placeholder="912345678" name="contactnum" style="border-radius: .2rem .5rem .5rem .2rem; width:90.3%" value="{{$guest->mobilenum}}" readonly>
                            </div>
                        </div>
                        <div>
                            <label for="txtemail">Email:</label> 
                            <input id="txtemail" type="email" placeholder="@email.com.." name="email" value="{{$guest->email}}" readonly>  
                        </div>
                    </div>

                    <div id="row3">
                        <div>
                            <label for="txtgender">Gender:</label>
                            <select id="txtgender"  name="gender" disabled>
                                <option value="" disabled>Select Gender</option>
                                    <option value="Male" {{ $guest->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $guest->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Non_Binary" {{ $guest->gender == 'Non_Binary' ? 'selected' : '' }}>Non-Binary</option>
                                    <option value="Trans_Female" {{ $guest->gender == 'Trans_Female' ? 'selected' : '' }}>Transgender Female</option>
                                    <option value="Trans_Male" {{ $guest->gender == 'Trans_Male' ? 'selected' : '' }}>Transgender Male</option>
                                    <option value="Genderqueer" {{ $guest->gender == 'Genderqueer' ? 'selected' : '' }}>Genderqueer</option>
                                    <option value="Agender" {{ $guest->gender == 'Agender' ? 'selected' : '' }}>Agender</option>
                                    <option value="Bigender" {{ $guest->gender == 'Bigender' ? 'selected' : '' }}>Bigender</option>
                                    <option value="Genderfluid" {{ $guest->gender == 'Genderfluid' ? 'selected' : '' }}>Genderfluid</option>
                                    <option value="Two_Spirit" {{ $guest->gender == 'Two_Spirit' ? 'selected' : '' }}>Two-Spirit</option>
                                    <option value="Other" {{ $guest->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                    <option value="Prefer_not_to_say" {{ $guest->gender == 'Prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>
                        <div> 
                            {{-- Birthday --}}
                        <label id="lblbirthday" for="txtbirthday">
                            Birthday:
                        </label>
                        <input id="txtbirthday" type="date" name="birthday"
                            value="{{ $guest->birthday }}" readonly>
                        </div>
                        <div>
                            <label for="role">Role:</label> 
                            <select id="txtrole" name="role" disabled>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Guest" {{ $guest->role == 'Guest' ? 'selected' : '' }}>Hotel Guest</option>
                                <option value="Day Tour Guest" {{ $guest->role == 'Day Tour Guest' ? 'selected' : '' }}>Day Tour Guest</option>
                            </select>  
                        </div>
                    </div>

                    {{-- Valid ID --}}
                     <div class="cl-validID" id="row4">
                        <label for="txtvalidid">Import Valid ID</label>
                        <div>
                            <img id="id-preview" src="{{ asset('storage/' . $guest->validID) }}">
                        </div>
                    </div>

                    <div id="form-header-2">
                        <h3>Account Information</h3>
                    </div>

                    <div id="row5" class="user-information">
                        <div>
                            <label for="txtusername">Username:</label> 
                            <input id="txtusername" type="text" placeholder="Username" name="username" value="{{$user->username}}">
                        </div>
                         
                    </div>

                    <div id="row6" class="user-information">
                        <div>
                            <label for="txtpassword">Password:</label> 
                            <input id="txtpassword" type="text" placeholder="Password" name="password">
                        </div> 
                        <div>
                            <label for="txtcpassword">Confirm Password:</label> 
                            <input id="txtcpassword" type="text" placeholder="Confirm Password" name="cpassword">
                            <small id="password-match-msg" style="color: red; display: none; margin-top:.5rem;"><i class="fas fa-info-circle"></i> Password does not match.</small>
                        </div> 
                    </div>

                    <div id="row7">
                        <div>
                            <label for="avatar">Select Avatar:</label>
                            <img id="pfp-preview" src="{{ asset('storage/' . $guest->avatar) }}">
                            <input id="txtavatar" type="file" accept=".png, .jpg, .jpeg, .webp" name="avatar">
                            @error('validAvatar')
                                <div class="error-message" id="avatar-error-message">{{ $message}}</div>
                            @enderror 
                        </div> 
                    </div>
                </div>

                <div id="button-container">
                    <div>
                        <button id="btncancel" type="button" data-url="{{ url('manager/manage_user')}}">Cancel</button>
                        <button id="btnsubmit" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>



        @if ($errors->any())
            <div class="alert-message" style="background: red; color: white;">
                <h2>There were some errors with your submission:</h2>
                <ul>
                    @foreach ($errors->all() as $error)
                        <h2>{{ $error }}</h2>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
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
</body>

<style>
    #guest{color:#F78A21;}
    body{
        overflow-y:auto;
    }
    #layout{
        display: flex;
        flex-direction: row;
        height: 100vh;
    }
    #main-layout{
        display: flex;
        flex-direction: column;
        padding: 1rem;
        width: 100%;
        transition: width 0.3s ease-in-out;
        margin-left: 12rem;
    }
    #add_user-container{
        display:flex;
        flex-direction: column;
        width:100%;
        height: auto;
        border-radius:.7rem;
        box-shadow:.1rem .1rem 0rem rgba(0,0,0,0.2);
        background:white;
        padding:.5rem;
    }
    #form-header-1, #form-header-2{
        width:100%;
        height:3.5rem;
        background:rgb(54, 54, 54);
        color:white;
        padding-left:1rem;
        border-radius:1rem;
    }
    #row1, #row2, #row3, #row4, #row5, #row6, #row7{
        margin:1rem;
        display: flex;
        flex-direction: row;
        width:100%;
        gap:2rem;
    }
    #row1 div, #row2 div, #row3 div, #row5 div, #row6 div, #row7 div{
        display: flex;
        flex-direction: column;
        width:30rem;
    }
    #row4 div{
        display:flex;
        height:20rem;
        width:auto;
        gap:2rem;
    }
    #row4 img{
        display: flex;
        height: auto;
        max-height: 80%;
        width:auto;
        min-width: 30%;
        object-fit: contain;
        align-content: center;
        justify-content: center;
        border:2px solid black;
        border-radius:2rem;
    }
    label{
        display: flex;
        font-size: 1.5rem;
        font-weight: bold;
        margin-left:.5rem;
    }
    input{
        height:2.5rem;
        width:30rem;
        font-size:1rem;
        border-radius: .5rem;
        padding:.5rem;
    }
    select{
        height:2.5rem;
        width:30rem;
        font-size:1rem;
        border-radius: .5rem;
        padding:.5rem; 
    }
    #pfp-preview{
        display: flex;
        height:10rem;
        width: 10rem;
        border-radius: 50%;
        object-fit: cover;
    }
    #button-container{
        display: flex;
        margin-top: 1rem;
        gap:1rem;
    }
    #button-container button, #button-container input{
        height:2.5rem;
        width:10rem;
        font-size:1rem;
        border-radius: .5rem;
        padding:.5rem;
    }
    #button-container button{
        background:grey;
    }
    #button-container input{
        background:orange;
    }
    .error-message{
        width:100%;
        background:red;
        color:white;
    }
    .alert-message{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: fixed;
        right: 50%;
        transform: translate(50%, 0);
        bottom: 1rem;
        height: fit-content;
        min-height: 10rem;
        max-height: 30rem;
        width: fit-content;
        min-width: 20rem;
        max-width: 90vw;
        background: rgb(255, 255, 255);
        z-index: 1000;
        border-radius: 1rem;
        box-shadow: 0 0 1rem rgba(0,0,0,0.5);
        margin: auto;
        padding: 1rem;
        flex-wrap: wrap;
        word-wrap: break-word;
    }
    #popup-resend{
        margin-top:1rem; 
        padding: .5rem 2rem;
        background: #ccc; 
        color: #333; 
        border: none;
        border-radius: .5rem; 
        font-size: 1rem;
        transition:background 0.2s ease;
    }
    #popup-resend:hover{
        background: rgb(77, 77, 77);
        color:white;
        cursor:pointer;
    }
</style>

<script>

    //Display OTP for test
    window.Laravel = {
        testOtp: @json(session('test_otp'))
    };

    function validateContactNumber(inputElement) {
        let cleaned = inputElement.value.replace(/[^0-9]/g, '');
        if (cleaned.startsWith('0')) {
            cleaned = cleaned.slice(1);
        }
        inputElement.value = cleaned;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const txtcontactnum = document.getElementById('txtcontactnum');
        const form = document.querySelector('form');
        const btnSubmit = document.getElementById('btnsubmit');
        const btnCancel = document.getElementById('btncancel');
        const message = document.querySelector('.alert-message');
        const password = document.getElementById('txtpassword');
        const cpassword = document.getElementById('txtcpassword');
        const msg = document.getElementById('password-match-msg');
        const userTab = document.getElementById('user');
        const txtRole = document.getElementById('txtrole');
        const txtBirthday = document.getElementById('txtbirthday');
        const lblBirthday = document.getElementById('lblbirthday');
        const validIDSection = document.querySelector('.cl-validID');
        const idInput = document.getElementById('txtvalidid');
        const idPreview = document.getElementById('id-preview');
        const pfpInput = document.getElementById('txtavatar');
        const pfpPreview = document.getElementById('pfp-preview');
        const userInformation = document.querySelectorAll('.user-information')

        function createPopup() {
            const existing = document.getElementById('custom-popup');
            if (existing) existing.remove();

            const popup = document.createElement('div');
            popup.id = 'custom-popup';
            popup.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2000;
            `;

            //Get otp
            const testOtp = window.Laravel.testOtp ?? 'N/A';

            popup.innerHTML = `
                <div style="
                    background: white;
                    padding: 2rem 2.5rem;
                    border-radius: 1rem;
                    box-shadow: 0 0 1rem rgba(0,0,0,0.3);
                    text-align: center;
                    min-width: 300px;
                ">
                    <h2>Enter your OTP Code</h2>
                    <div>
                        <label for="otpcode">OTP ${testOtp}</label>
                        <input id="otpcode" name="otpCode" type="text" placeholder="OTP Code" pattern="\\d{6}" minlength="6" maxlength="6" required>
                    </div>
                    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                        <button id="popup-cancel" style="padding: .5rem 2rem; background: #ccc; color: #333; border: none; border-radius: .5rem;">Cancel</button>
                        <button id="popup-confirm" style="padding: .5rem 2rem; background: #F78A21; color: white; border: none; border-radius: .5rem;">Send</button>
                    </div>
                </div>
            `;
            document.body.appendChild(popup);

            const otpInput = document.getElementById('otpcode');
            otpInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });

            document.getElementById('popup-cancel').onclick = () => popup.remove();

            document.getElementById('popup-confirm').onclick = () => {
                const hiddenOtpInput = document.createElement('input');
                hiddenOtpInput.type = 'hidden';
                hiddenOtpInput.name = 'otpCode';
                hiddenOtpInput.value = otpInput.value;
                form.appendChild(hiddenOtpInput);
                popup.remove();
                form.submit();
            };
        }

        if (btnSubmit && form) {
            btnSubmit.addEventListener('click', function (e) {
                e.preventDefault();
                createPopup();
            });
        }

        if (message) {
            setTimeout(() => message.style.display = 'none', 3000);
        }

        if (password && cpassword && msg) {
            cpassword.addEventListener('input', function () {
                if (this.value === password.value) {
                    this.style.border = "1px solid green";
                    this.style.background = "white";
                    msg.style.display = 'block';
                    msg.style.color = "green";
                    msg.innerHTML = "<i class='fas fa-info-circle'></i> Password matches!";
                } else {
                    this.style.border = "1px solid red";
                    this.style.background = "rgba(255,0,0,0.5)";
                    msg.style.display = 'block';
                    msg.style.color = "red";
                    msg.innerHTML = "<i class='fas fa-info-circle'></i> Password does not match!";
                }
            });
        }

        if (btnCancel) {
            btnCancel.addEventListener('click', function () {
                window.location.href = this.dataset.url;
            });
        }

        
        if (txtRole) {
            txtRole.addEventListener('change', function () {
                const isDGuest = this.value === 'Day Tour Guest';

                userInformation.forEach(el => {
                    el.style.display = isDGuest ? 'none' : 'flex';
                });
            });

            // Trigger once on load
            txtRole.dispatchEvent(new Event('change'));
        }

        if (txtcontactnum) {
            txtcontactnum.addEventListener('input', function () {
                validateContactNumber(this);
            });
            window.addEventListener('load', () => validateContactNumber(txtcontactnum));
            txtcontactnum.addEventListener('blur', function () {
                validateContactNumber(this);
            });
        }

        if (pfpInput && pfpPreview) {
            pfpInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => pfpPreview.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }

        if (idInput && idPreview) {
            idInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => idPreview.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }

        if (txtBirthday && txtBirthday.value) {
            txtBirthday.style.display = 'inline';
            lblBirthday.style.display = 'inline';
        }

        if (idInput && idInput.value) {
            validIDSection.style.display = 'flex';
        }
    });
</script>


