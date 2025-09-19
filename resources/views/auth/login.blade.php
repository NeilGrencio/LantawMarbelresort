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
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
      gap: 2rem;
    }

    /* Image on the left */
    .parent-container img {
      flex: 1;
      max-width: 60%;
      height: auto;
      object-fit: contain;
      border-radius: .7rem;
    }

    /* Login card on the right */
    .form-container {
      flex: 1;
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
      margin: 0 0 1rem 0;
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
      width: 100%;
    }

    button:hover {
      background: burlywood;
      color: black;
      transform: scale(1.05);
      cursor: pointer;
    }

    .error-message {
      color: red;
      font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .parent-container {
        flex-direction: column;
        padding: 1rem;
      }

      .parent-container img {
        max-width: 100%;
      }

      .form-container {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="parent-container">
    <!-- Left Image -->
    <img src="{{ asset('images/large_logo.png') }}" alt="Resort Logo" />

    <!-- Right Login Form -->
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

  <script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('password-toggle');

    togglePassword.addEventListener('click', function () {
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
