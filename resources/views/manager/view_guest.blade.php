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
            <h1>Edit Guest Information</h1>

            <form method="POST" action="{{ route('manager.edit_guest', ['guestID' => $guest->guestID]) }}" enctype="multipart/form-data">
                @csrf
                <div id="add_user-container">

                    <div id="form-header-1">
                        <h3>Personal Information</h3>
                    </div>

                    <div id="row1">
                        <div>
                            <label for="txtfirstname">Firstname:</label>
                            <input id="txtfirstname" type="text" name="firstname" value="{{ $guest->firstname }}" readonly>
                        </div>
                        <div>
                            <label for="txtlastname">Lastname:</label>
                            <input id="txtlastname" type="text" name="lastname" value="{{ $guest->lastname }}" readonly>
                        </div>
                    </div>

                    <div id="row2">
                        <div>
                            <label for="txtcontactnum">Contact #:</label>
                            <div style="display:flex; flex-direction:row; gap:.2rem;">
                                <span style="display:flex; align-items:center; padding:.5rem; background:rgba(240,240,240,0.822); border:1px solid black; border-radius:.5rem .2rem .2rem .5rem; width:9%;">+63</span>
                                <input id="txtcontactnum" type="text" maxlength="10" name="contactnum" value="{{ $guest->mobilenum }}" style="border-radius:.2rem .5rem .5rem .2rem; width:90.3%" readonly>
                            </div>
                        </div>
                        <div>
                            <label for="txtemail">Email:</label>
                            <input id="txtemail" type="email" name="email" value="{{ $guest->email }}" readonly>
                        </div>
                    </div>

                    <div id="row3">
                        <div>
                            <label for="txtgender">Gender:</label>
                            <select id="txtgender" name="gender" disabled>
                                <option value="" disabled>Select Gender</option>
                                <option value="Male" {{ $guest->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $guest->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Prefer_not_to_say" {{ $guest->gender == 'Prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>
                        <div>
                            <label for="txtbirthday">Birthday:</label>
                            <input id="txtbirthday" type="date" name="birthday" value="{{ $guest->birthday }}" readonly>
                        </div>
                        <div>
                            <label for="txtrole">Role:</label>
                            <select id="txtrole" name="role" disabled>
                                <option value="" disabled>Select Role</option>
                                <option value="Guest" {{ $guest->role == 'Guest' ? 'selected' : '' }}>Hotel Guest</option>
                                <option value="Day Tour Guest" {{ $guest->role == 'Day Tour Guest' ? 'selected' : '' }}>Day Tour Guest</option>
                            </select>
                        </div>
                    </div>

                    <div class="cl-validID" id="row4">
                        <label for="txtvalidid">Valid ID:</label>
                        <div>
                            <img id="id-preview" src="{{ route('guestid.image', ['filename' => basename($guest->validID)]) }}" alt="{{ $user->username }}">
                            <input id="txtvalidid" type="file" accept=".png, .jpg, .jpeg, .webp" name="validID">
                        </div>
                    </div>

                    <div id="form-header-2">
                        <h3>Account Information</h3>
                    </div>

                    <div id="row5" class="user-information">
                        <div>
                            <label for="txtusername">Username:</label>
                            <input id="txtusername" type="text" name="username" value="{{ $user->username }}">
                        </div>
                    </div>

                    <div id="row6" class="user-information">
                        <div>
                            <label for="txtpassword">Password:</label>
                            <input id="txtpassword" type="password" name="password" placeholder="New Password (optional)">
                            <small>The password should contain 8 characters, one special, one capital, and one number* (e.g., Password123!)</small>
                        </div>
                        <div>
                            <label for="txtcpassword">Confirm Password:</label>
                            <input id="txtcpassword" type="password" name="cpassword" placeholder="Confirm Password">
                            <small id="password-match-msg" style="color: red; display: none; margin-top:.5rem;">
                                <i class="fas fa-info-circle"></i> Password does not match.
                            </small>
                        </div>
                    </div>

                    <div id="row7">
                        <div>
                            <label for="avatar">Select Avatar:</label>
                            <img id="pfp-preview" src="{{ route('guest.image', ['filename' => basename($guest->avatar)]) }}" alt="{{ $user->username }}">
                            <input id="txtavatar" type="file" accept=".png, .jpg, .jpeg, .webp" name="avatar">
                            @error('validAvatar')
                                <div class="error-message" id="avatar-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="button-container">
                    <div>
                        <button id="btncancel" type="button" data-url="{{ url('manager/guest_list') }}">Cancel</button>
                        <button id="btnsubmit" type="submit">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="alert-message" style="background:red; color:white;">
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

{{-- Same CSS as the “create” version --}}
<style>
    #guest{color:orange;}
    body { overflow-y:auto; font-size:0.85rem; }
    #layout { display:flex; flex-direction:row; height:100vh; }
    #main-layout { width:100%; height:auto; padding:0.75rem; margin-left:12rem; }
    #add_user-container { display:flex; flex-direction:column; width:100%; border-radius:1rem; box-shadow:0.5rem 0 1rem rgba(0,0,0,0.1); background:white; padding:0.75rem; }
    #form-header-1, #form-header-2 { width:100%; height:2.5rem; background:rgb(54,54,54); color:white; padding-left:0.75rem; border-radius:0.75rem; font-size:1rem; display:flex; align-items:center; }
    #row1, #row2, #row3, #row4, #row5, #row6, #row7 { margin:0.75rem; display:flex; flex-direction:row; width:100%; flex-wrap:wrap; gap:1.5rem; }
    #row1 div, #row2 div, #row3 div, #row5 div, #row6 div, #row7 div { display:flex; flex-direction:column; width:100%; max-width:22rem; }
    #row4 div { display:flex; height:15rem; width:auto; gap:1.5rem; flex-wrap:wrap; }
    #row4 img { display:flex; height:auto; max-height:80%; width:auto; min-width:25%; object-fit:contain; border:2px solid black; border-radius:1.5rem; }
    label { display:flex; font-size:0.9rem; font-weight:bold; margin-left:0.25rem; }
    input, select { height:2.2rem; width:100%; max-width:22rem; font-size:0.85rem; border-radius:0.4rem; padding:0.4rem; }
    #pfp-preview { height:8rem; width:8rem; border-radius:50%; object-fit:cover; }
    #button-container { display:flex; margin-top:0.75rem; gap:0.75rem; flex-wrap:wrap; }
    #button-container button { height:2.2rem; width:7rem; font-size:0.85rem; border-radius:0.4rem; padding:0.4rem; border:none; }
    #btncancel { background:grey; color:white; }
    #btnsubmit { background:orange; color:white; }
    .alert-message { display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; position:fixed; right:50%; transform:translate(50%,0); bottom:1rem; height:fit-content; min-height:10rem; max-height:30rem; width:fit-content; min-width:20rem; max-width:90vw; background:white; z-index:1000; border-radius:1rem; box-shadow:0 0 1rem rgba(0,0,0,0.5); margin:auto; padding:1rem; flex-wrap:wrap; word-wrap:break-word; }
</style>

{{-- Same JS for dynamic validation --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('txtpassword');
    const cpassword = document.getElementById('txtcpassword');
    const msg = document.getElementById('password-match-msg');
    const btnCancel = document.getElementById('btncancel');
    const pfpInput = document.getElementById('txtavatar');
    const pfpPreview = document.getElementById('pfp-preview');
    const idInput = document.getElementById('txtvalidid');
    const idPreview = document.getElementById('id-preview');
    const message = document.querySelector('.alert-message');

    if (message) setTimeout(() => message.style.display = 'none', 3000);

    if (cpassword && password && msg) {
        cpassword.addEventListener('input', function() {
            if (this.value === password.value) {
                this.style.border = "1px solid green";
                msg.style.color = "green";
                msg.innerHTML = "<i class='fas fa-info-circle'></i> Password matches!";
            } else {
                this.style.border = "1px solid red";
                msg.style.color = "red";
                msg.innerHTML = "<i class='fas fa-info-circle'></i> Password does not match!";
            }
            msg.style.display = 'block';
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', () => window.location.href = btnCancel.dataset.url);
    }

    if (pfpInput && pfpPreview) {
        pfpInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => pfpPreview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    }

    if (idInput && idPreview) {
        idInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => idPreview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
