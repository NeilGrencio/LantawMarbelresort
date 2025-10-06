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
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background: white;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .parent-container {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100vh;
      padding: 2rem;
      gap: 2rem;
    }

    .image-box img {
      object-fit: contain;
      height: 90%;
      width: 100%;
      max-width: 600px;
      border-radius: .7rem;
    }

    .form-container {
      width: 100%;
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
      font-size: 1.5rem;
      margin: 0;
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

    @media (max-width: 900px) {
      .parent-container {
        flex-direction: column;
        text-align: center;
        height: auto;
        padding: 1rem;
      }

      .image-box img {
        width: 80%;
        max-width: 400px;
      }

      .form-container {
        width: 100%;
      }
    }

    @keyframes spin {
      0% { transform: rotate(0deg);}
      100% { transform: rotate(360deg);}
    }
  </style>
</head>

<body>
  <div class="parent-container">
    <div class="image-box">
      <img src="{{ asset('images/large_logo.png') }}" alt="Logo" />
    </div>
    <div>
      <div class="form-container">
        <form action="{{ url('auth/login') }}" method="post">
          @csrf
          <h2>Log In</h2>

          <label for="username">
            Username
            <input id="username" name="username" placeholder="Username" required value="{{ old('username') }}" />
            @error('username')
            <div class="error-message">{{ $message }}</div>
            @enderror
          </label>

          <label for="password">
            Password
            <input id="password" name="password" type="password" placeholder="Password" required />
            <i class="fa-solid fa-eye-slash fa-lg" id="password-toggle"></i>
            @error('password')
            <div class="error-message">{{ $message }}</div>
            @enderror
          </label>

          <button type="submit">Log In</button>
          <button type="button" id="forgot-password-btn" style="background:none;border:none;color:#007bff;cursor:pointer;text-decoration:underline;margin-top:1rem;">
            Forgot Password?
          </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Password Reset Modal -->
  <div id="reset-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:1000; justify-content:center; align-items:center;">
    <div id="modal-loading" style="display:none; justify-content:center; align-items:center; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:200;">
      <div style="border: 4px solid #f3f3f3; border-top: 4px solid #007bff; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
    </div>
    <div style="background:white; padding:2rem; border-radius:1rem; max-width:350px; width:90%; box-shadow:0 0.5rem 1rem rgba(0,0,0,0.2); display:flex; flex-direction:column; gap:1rem; position:relative;">
      <span id="close-reset-modal" style="position:absolute; top:1rem; right:1rem; cursor:pointer; font-size:1.2rem;">&times;</span>
      <div id="step-username">
        <h3>Reset Password</h3>
        <label>
          Enter your username:
          <input id="reset-username" type="text" placeholder="Username" required />
        </label>
        <button id="send-otp-btn" type="button">Send OTP to Email</button>
        <div id="reset-error" style="color:red; font-size:0.9rem;"></div>
      </div>
      <div id="step-otp" style="display:none;">
        <label>
          Enter OTP:
          <input id="reset-otp" type="text" placeholder="OTP" required />
        </label>
        <button id="verify-otp-btn" type="button">Verify OTP</button>
        <div id="otp-error" style="color:red; font-size:0.9rem;"></div>
      </div>
      <div id="step-password" style="display:none;">
        <label>
          New Password:
          <div style="position:relative;">
            <input id="new-password" type="password" name="password" placeholder="New Password" required style="width:100%;" />
            <i class="fa-solid fa-eye-slash fa-lg" id="toggle-new-password" style="position:absolute; right:1rem; top:0.7rem;"></i>
          </div>
          <div id="password-strength" style="margin-top:0.3rem; font-size:0.9rem;"></div>
        </label>
        <label>
          Confirm New Password:
          <div style="position:relative;">
            <input id="confirm-password" type="password" name="password_confirmation" placeholder="Confirm Password" required style="width:100%;" />
            <i class="fa-solid fa-eye-slash fa-lg" id="toggle-confirm-password" style="position:absolute; right:1rem; top:0.7rem;"></i>
          </div>
        </label>
        <button id="save-password-btn" type="button">Save New Password</button>
        <div id="password-error" style="color:red; font-size:0.9rem;"></div>
        <div id="password-success" style="color:green; font-size:0.9rem;"></div>
      </div>
    </div>
  </div>
    <script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('password-toggle');
    togglePassword.addEventListener('click', function () {
      password.type = password.type === 'text' ? 'password' : 'text';
      togglePassword.className = password.type === 'text' ? 'fa-solid fa-eye fa-lg' : 'fa-solid fa-eye-slash fa-lg';
    });

    const forgotLink = document.getElementById('forgot-password-btn');
    const resetModal = document.getElementById('reset-modal');
    const closeResetModal = document.getElementById('close-reset-modal');
    const sendOtpBtn = document.getElementById('send-otp-btn');
    const verifyOtpBtn = document.getElementById('verify-otp-btn');
    const savePasswordBtn = document.getElementById('save-password-btn');
    const stepUsername = document.getElementById('step-username');
    const stepOtp = document.getElementById('step-otp');
    const stepPassword = document.getElementById('step-password');
    const resetError = document.getElementById('reset-error');
    const otpError = document.getElementById('otp-error');
    const passwordError = document.getElementById('password-error');
    const passwordSuccess = document.getElementById('password-success');
    const resetUsername = document.getElementById('reset-username');
    const resetOtp = document.getElementById('reset-otp');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    const passwordStrength = document.getElementById('password-strength');
    const toggleNewPassword = document.getElementById('toggle-new-password');
    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
    const modalLoading = document.getElementById('modal-loading');

    function showLoading() { modalLoading.style.display = 'flex'; }
    function hideLoading() { modalLoading.style.display = 'none'; }

    forgotLink.addEventListener('click', () => { resetModal.style.display = 'flex'; });
    closeResetModal.addEventListener('click', () => { resetModal.style.display = 'none'; });

    // Helper to safely parse JSON
    async function parseJsonSafe(res) {
      const text = await res.text();
      try { return JSON.parse(text); } catch { return { success: false, message: text }; }
    }

    // Send OTP
    sendOtpBtn.addEventListener('click', () => {
      const username = resetUsername.value.trim();
      if (!username) { resetError.textContent = 'Please enter your username.'; return; }
      resetError.textContent = ''; showLoading();
      fetch('{{ url("auth/send_OTP") }}', {
        method: 'post',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ username })
      })
      .then(parseJsonSafe).then(data => {
        hideLoading();
        if (data.success) { stepUsername.style.display = 'none'; stepOtp.style.display = ''; }
        else { resetError.textContent = data.message || 'Failed to send OTP.'; }
      }).catch(e => { hideLoading(); resetError.textContent = 'Error: ' + e.message; });
    });

    // Verify OTP
    verifyOtpBtn.addEventListener('click', () => {
      const username = resetUsername.value.trim();
      const otp = resetOtp.value.trim();
      if (!otp) { otpError.textContent = 'Please enter the OTP.'; return; }
      otpError.textContent = ''; showLoading();
      fetch('{{ url("auth/forgot_password") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ username, otp })
      })
      .then(parseJsonSafe).then(data => {
        hideLoading();
        if (data.success) { stepOtp.style.display = 'none'; stepPassword.style.display = ''; }
        else { otpError.textContent = data.message || 'Invalid OTP.'; }
      }).catch(e => { hideLoading(); otpError.textContent = 'Error: ' + e.message; });
    });

    // Save new password
    savePasswordBtn.addEventListener('click', () => {
      const username = resetUsername.value.trim();
      const otp = resetOtp.value.trim();
      const password = newPassword.value;
      const confirm = confirmPassword.value;
      passwordError.textContent = ''; passwordSuccess.textContent = '';
      if (!password || !confirm) { passwordError.textContent = 'Please enter and confirm your new password.'; return; }
      if (password !== confirm) { passwordError.textContent = 'Passwords do not match.'; return; }
      if (password.length < 8) { passwordError.textContent = 'Password is too weak.'; return; }
      showLoading();
      fetch('{{ url("auth/reset_password") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({
          username: resetUsername.value.trim(),
          otp: resetOtp.value.trim(),
          password: password,
          password_confirmation: confirm })
      })
      .then(parseJsonSafe).then(data => {
        hideLoading();
        if (data.success) {
          passwordSuccess.textContent = 'Password reset successful! You may now log in.';
          setTimeout(() => { resetModal.style.display = 'none'; }, 2000);
        } else { passwordError.textContent = data.message || 'Failed to reset password.'; }
      }).catch(e => { hideLoading(); passwordError.textContent = 'Error: ' + e.message; });
    });

    // Toggle password visibility inside modal
    toggleNewPassword.addEventListener('click', () => {
      newPassword.type = newPassword.type === 'text' ? 'password' : 'text';
      toggleNewPassword.className = newPassword.type === 'text' ? 'fa-solid fa-eye fa-lg' : 'fa-solid fa-eye-slash fa-lg';
    });
    toggleConfirmPassword.addEventListener('click', () => {
      confirmPassword.type = confirmPassword.type === 'text' ? 'password' : 'text';
      toggleConfirmPassword.className = confirmPassword.type === 'text' ? 'fa-solid fa-eye fa-lg' : 'fa-solid fa-eye-slash fa-lg';
    });
  </script>
</body>
</html>