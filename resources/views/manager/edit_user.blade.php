@php
    // Determine if the user is a guest
    $isGuest = old('role', $guest->role ?? '') == 'Guest';
@endphp
<body>
    <div id="layout">
        @include('components.sidebar')
        <div id="main-layout">
            <h1>Edit User</h1>
                <form method="POST" action="{{ route('manager.edit_user', ['userID' => $user->userID]) }}" enctype="multipart/form-data">
                    @csrf
                    <div id="add_user-container">
                        <div id="form-header-1">
                            <h3>Personal Information</h3>
                        </div>

                        <div id="row1">
                            <div>
                                <label for="firstname">Firstname:</label>
                                <input id="txtfirstname" type="text" placeholder="Firstname.." name="firstname" readonly
                                    value="{{ old('firstname', $isGuest ? ($guest->firstname ?? '') : ($staff->firstname ?? '')) }}">
                            </div>
                            <div>
                                <label for="lastname">Lastname:</label>
                                <input id="txtalstname" type="text" placeholder="Lastname.." name="lastname" readonly
                                    value="{{ old('lastname', $isGuest ? ($guest->lastname ?? '') : ($staff->lastname ?? '')) }}" >
                            </div>
                        </div>

                        <div id="row2">
                            <div>
                                <label for="mobilenum">Contact #:</label>
                                <div style="display:flex; flex-direction: row;gap:.2rem;">
                                    <span style="display:flex; align-items:center; padding:.5rem; background: rgba(240, 240, 240, 0.822); border: 1px solid black; border-radius: .5rem .2rem .2rem .5rem; width:9%;">+63</span>
                                    <input id="txtmobilenum" type="text" maxlength="10" placeholder="912345678" name="mobilenum"
                                        style="border-radius: .2rem .5rem .5rem .2rem; width:90.3%"
                                        value="{{ old('mobilenum', $isGuest ? ($guest->mobilenum ?? '') : ($staff->mobilenum ?? '')) }}">
                                </div>
                            </div>
                            <div>
                                <label for="email">Email:</label>
                                <input id="txtemail" type="email" placeholder="@email.com.." name="email"
                                    value="{{ old('email', $isGuest ? ($guest->email ?? '') : ($staff->email ?? '')) }}">
                            </div>
                        </div>

                        <div id="row3">
                            <div>
                                <label for="gender">Gender:</label>
                                @php
                                    $genderValue = old('gender', $isGuest ? ($guest->gender ?? '') : ($staff->gender ?? ''));
                                @endphp
                                <select id="txtgender" name="gender" readonly style="pointer-events:none; background:#eee;">
                                    <option value="" disabled>Select Gender</option>
                                    <option value="Male" {{ $genderValue == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $genderValue == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Non_Binary" {{ $genderValue == 'Non_Binary' ? 'selected' : '' }}>Non-Binary</option>
                                    <option value="Trans_Female" {{ $genderValue == 'Trans_Female' ? 'selected' : '' }}>Transgender Female</option>
                                    <option value="Trans_Male" {{ $genderValue == 'Trans_Male' ? 'selected' : '' }}>Transgender Male</option>
                                    <option value="Genderqueer" {{ $genderValue == 'Genderqueer' ? 'selected' : '' }}>Genderqueer</option>
                                    <option value="Agender" {{ $genderValue == 'Agender' ? 'selected' : '' }}>Agender</option>
                                    <option value="Bigender" {{ $genderValue == 'Bigender' ? 'selected' : '' }}>Bigender</option>
                                    <option value="Genderfluid" {{ $genderValue == 'Genderfluid' ? 'selected' : '' }}>Genderfluid</option>
                                    <option value="Two_Spirit" {{ $genderValue == 'Two_Spirit' ? 'selected' : '' }}>Two-Spirit</option>
                                    <option value="Other" {{ $genderValue == 'Other' ? 'selected' : '' }}>Other</option>
                                    <option value="Prefer_not_to_say" {{ $genderValue == 'Prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                </select>
                            </div>
                            {{-- Birthday only for Guest --}}
                            @if($isGuest)
                            <div>
                                <label id="lblbirthday" for="birthday">Birthday:</label>
                                <input id="txtbirthday" type="date" name="birthday" readonly
                                    value="{{ old('birthday', $guest->birthday ?? '') }}">
                            </div>
                            @endif
                        </div>

                        {{-- Valid ID only for Guest --}}
                        @if($isGuest)
                        <div class="cl-validID" id="row4" style="display: flex;">
                            <label for="validid">Import Valid ID</label>
                            <div>
                                <img id="id-preview" src="{{ $guest->validID ? asset('storage/'.$guest->validID) : asset('images/photo.png') }}">   
                            </div>
                        </div>
                        @endif

                        <div id="form-header-2">
                        <h3>Account Information</h3>
                    </div>

                    <div id="row5">
                        <div>
                            <label for="username">Username:</label>
                            <input id="txtusername" type="text" placeholder="Username" name="username" readonly
                                value="{{ old('username', $isGuest ? ($user->username ?? '') : ($user->username ?? '')) }}">
                        </div>
                        <div>
                            <label for="role" >Role:</label>
                            <select id="txtrole" name="role" readonly style="pointer-events:none; background:#eee;">
                                <option value="" disabled {{ old('role', $isGuest ? 'Guest' : ($staff->role ?? '')) == '' ? 'selected' : '' }}>Select Role</option>
                                <option value="Manager" {{ old('role', $isGuest ? '' : ($staff->role ?? '')) == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Receptionist" {{ old('role', $isGuest ? '' : ($staff->role ?? '')) == 'Receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="Kitchen Staff" {{ old('role', $isGuest ? '' : ($staff->role ?? '')) == 'Kitchen Staff' ? 'selected' : '' }}>Kitchen Staff</option>
                                <option value="Amenity Staff" {{ old('role', $isGuest ? '' : ($staff->role ?? '')) == 'Amenity Staff' ? 'selected' : '' }}>Amenity Staff</option>
                                <option value="Guest" {{ old('role', $isGuest ? 'Guest' : ($staff->role ?? '')) == 'Guest' ? 'selected' : '' }}>Guest</option>
                            </select>
                        </div> 
                    </div>

                    <div id="row6">
                        <div>
                            <label for="password">Password:</label> 
                            <input id="txtpassword" type="text" placeholder="Password" name="password" >
                        </div> 
                        <div>
                            <label for="cpassword">Confirm Password:</label> 
                            <input id="txtcpassword" type="text" placeholder="Confirm Password" name="cpassword">
                            <small id="password-match-msg" style="color: red; display: none; margin-top:.5rem;"><i class="fas fa-info-circle"></i> Password does not match.</small>
                        </div> 
                    </div>

                    <div id="row7">
                        <div>
                            <label for="avatar">Select Avatar:</label>
                            <img id="pfp-preview"
                                src="@if($isGuest)
                                        {{ $guest->avatar ? asset('storage/'.$guest->avatar) : asset('images/profile.jpg') }}
                                     @else
                                        {{ $staff->avatar ? asset('storage/'.$staff->avatar) : asset('images/profile.jpg') }}
                                     @endif">
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
            </form>
        </div>
    </div>
</body>
<style>
    body{
        overflow-y:auto;
    }
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:15rem;
    }
    #add_user-container{
        display:flex;
        flex-direction: column;
        width:100%;
        height: auto;
        border-radius:2rem;
        box-shadow:1rem 0rem 2rem rgba(0,0,0,0.2);
        background:white;
        padding:1rem;
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
        object-fit: cover;
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
    input[readonly], select[readonly] {
        background: #dcdcdc;
        color: #b2b2b2;
        border: #b2b2b2 1px solid;
        pointer-events: none;   
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
    #user{
        color: #F78A21;
    }
</style>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const userTab = document.getElementById('user');
        const cancelBtn = document.getElementById('btncancel'); 
        const message = document.querySelector('.alert-message');
        const password = document.getElementById('txtpassword');
        const cpassword = document.getElementById('txtcpassword');
        const msg = document.getElementById('password-match-msg');
        
        
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        cpassword.addEventListener('input', function(){
            if (this.value === password.value) {
                cpassword.style.border = "1px solid green";
                cpassword.style.background = "white";
                msg.style.display = 'block';
                msg.style.color = "green";
                msg.innerHTML = "<i class='fas fa-info-circle'></i> Password matches!"
            } else {
                cpassword.style.border = "1px solid red";
                cpassword.style.background = "rgba(255,0,0,0.5)";
                msg.style.color = "red";
                msg.innerHTML = "<i class='fas fa-info-circle'></i> Password does not match!"
                msg.style.display = 'block';
            }
        });

        // Highlight current tab
        if (userTab) {
            userTab.style.color = "#F78A21";
        }

        cancelBtn.addEventListener('click', function() {
            const url = cancelBtn.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });


        // Changes non numerics to numerics
        function validateContactNumber(inputElement) {
            let cleaned = inputElement.value.replace(/[^0-9]/g, '');

            // Remove leading zero
            if (cleaned.startsWith('0')) {
                cleaned = cleaned.slice(1);
            }

            inputElement.value = cleaned;
        }

        txtcontactnum.addEventListener('input', function(){
            validateContactNumber(txtcontactnum);
        })

        window.addEventListener('load', function(){
            validateContactNumber(txtcontactnum);
        })

        txtcontactnum.addEventListener('blur', function(){
            validateContactNumber(txtcontactnum);
        })

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
    });

    const pfpInput = document.getElementById('txtavatar');
    const pfpPreview = document.getElementById('pfp-preview');

    pfpInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    pfpPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        
</script>