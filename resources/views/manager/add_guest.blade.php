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

            <form method="POST" action="{{ route('manager.save_guest') }}" enctype="multipart/form-data">
                @csrf
                <div id="add_user-container">
                    <div id="form-header-1">
                        <h3>Personal Information</h3>
                    </div>

                    <div id="row1">
                        <div>
                            <label for="txtfirstname">Firstname:</label>
                            <input id="txtfirstname" type="text" placeholder="Firstname.." name="firstname"
                                value="{{ old('firstname') }}">
                        </div>

                        <div>
                            <label for="txtalstname">Lastname:</label>
                            <input id="txtalstname" type="text" placeholder="Lastname.." name="lastname"
                                value="{{ old('lastname') }}">
                        </div>
                    </div>

                    <div id="row2">
                        <div>
                            <label for="txtcontactnum">Contact #:</label>
                            <div style="display:flex; flex-direction: row;gap:.2rem;">
                                <span style="display:flex; align-items:center; padding:.5rem; background: rgba(240, 240, 240, 0.822); border: 1px solid black; border-radius: .5rem .2rem .2rem .5rem; width:9%;">+63</span>
                                <input id="txtcontactnum" type="text" maxlength="10" placeholder="912345678" name="contactnum"
                                    style="border-radius: .2rem .5rem .5rem .2rem; width:90.3%"
                                    value="{{ old('contactnum') }}">
                            </div>
                        </div>
                        <div>
                            <label for="txtemail">Email:</label>
                            <input id="txtemail" type="email" placeholder="@email.com.." name="email"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    <div id="row3">
                        <div>
                            <label for="txtgender">Gender:</label>
                            <select id="txtgender" name="gender">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Prefer_not_to_say" {{ old('gender') == 'Prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>
                        <div>
                            <label id="lblbirthday" for="txtbirthday">Birthday:</label>
                            <input id="txtbirthday" type="date" name="birthday" value="{{ old('birthday') }}">
                        </div>
                        <div>
                            <label for="role">Role:</label>
                            <select id="txtrole" name="role">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                                <option value="Guest" {{ old('role') == 'Guest' ? 'selected' : '' }}>Hotel Guest</option>
                                <option value="Day Tour Guest" {{ old('role') == 'Day Tour Guest' ? 'selected' : '' }}>Day Tour Guest</option>
                            </select>
                        </div>
                    </div>

                    <div class="cl-validID" id="row4">
                        <label for="txtvalidid">Import Valid ID</label>
                        <div>
                            <img id="id-preview" src="{{ asset('images/photo.png') }}">
                            <input id="txtvalidid" type="file" accept=".png, .jpg, .jpeg, .webp" name="validID">
                        </div>
                    </div>

                    <div id="form-header-2">
                        <h3>Account Information</h3>
                    </div>

                    <div id="row5" class="user-information">
                        <div>
                            <label for="txtusername">Username:</label>
                            <input id="txtusername" type="text" placeholder="Username" name="username"
                                value="{{ old('username') }}">
                        </div>
                    </div>

                    <div id="row6" class="user-information">
                        <div>
                            <label for="txtpassword">Password:</label>
                            <input id="txtpassword" type="password" placeholder="Password" name="password">
                            <small>The password should contain 8 characters, one special, one capital, and one number* (Password123!) </small>
                        </div>
                        <div>
                            <label for="txtcpassword">Confirm Password:</label>
                            <input id="txtcpassword" type="password" placeholder="Confirm Password" name="cpassword">
                            <small id="password-match-msg" style="color: red; display: none; margin-top:.5rem;">
                                <i class="fas fa-info-circle"></i> Password does not match.
                            </small>
                        </div>
                    </div>

                    <div id="row7">
                        <div>
                            <label for="avatar">Select Avatar:</label>
                            <img id="pfp-preview" src="{{ asset('images/profile.jpg')}}">
                            <input id="txtavatar" type="file" accept=".png, .jpg, .jpeg, .webp" name="avatar">
                            @error('validAvatar')
                                <div class="error-message" id="avatar-error-message">{{ $message }}</div>
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
    body {
        overflow-y: auto;
        font-size: 0.85rem;
    }

    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
    }

    #main-layout {
        width: 100%;
        height: auto;
        padding: 0.75rem;
        margin-left: 12rem;
    }

    #add_user-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: auto;
        border-radius: 1rem;
        box-shadow: 0.5rem 0 1rem rgba(0, 0, 0, 0.1);
        background: white;
        padding: 0.75rem;
    }

    #form-header-1, #form-header-2 {
        width: 100%;
        height: 2.5rem;
        background: rgb(54, 54, 54);
        color: white;
        padding-left: 0.75rem;
        border-radius: 0.75rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    #row1, #row2, #row3, #row4, #row5, #row6, #row7 {
        margin: 0.75rem;
        display: flex;
        flex-direction: row;
        width: 100%;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    #row1 div, #row2 div, #row3 div, #row5 div, #row6 div, #row7 div {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 22rem;
    }

    #row4 div {
        display: flex;
        height: 15rem;
        width: auto;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    #row4 img {
        display: flex;
        height: auto;
        max-height: 80%;
        width: auto;
        min-width: 25%;
        object-fit: contain;
        border: 2px solid black;
        border-radius: 1.5rem;
    }

    label {
        display: flex;
        font-size: 0.9rem;
        font-weight: bold;
        margin-left: 0.25rem;
    }

    input, select {
        height: 2.2rem;
        width: 100%;
        max-width: 22rem;
        font-size: 0.85rem;
        border-radius: 0.4rem;
        padding: 0.4rem;
    }

    #pfp-preview {
        height: 8rem;
        width: 8rem;
        border-radius: 50%;
        object-fit: cover;
    }

    #button-container {
        display: flex;
        margin-top: 0.75rem;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    #button-container button, #button-container input {
        height: 2.2rem;
        width: 7rem;
        font-size: 0.85rem;
        border-radius: 0.4rem;
        padding: 0.4rem;
    }

    #button-container button {
        background: grey;
        color: white;
        border: none;
    }

    #button-container input {
        background: orange;
        color: white;
        border: none;
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

    #popup-resend {
        margin-top: 0.75rem;
        padding: 0.4rem 1.5rem;
        font-size: 0.85rem;
    }
</style>

<script>
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
