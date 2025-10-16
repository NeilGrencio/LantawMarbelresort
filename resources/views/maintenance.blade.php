<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw Marbel ORMS</title>
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #333;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: black;
            color: white;
            padding: 0.8rem 2rem;
        }
        #toolbar img {
            height: 2rem;
            width: auto;
            object-fit: cover;
        }
        #toolbar h3 {
            margin: 0 0 0 0.5rem;
            font-weight: 600;
        }
        #content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        #content img {
            width: 280px;
            max-width: 80%;
            height: auto;
            margin-bottom: 1.5rem;
        }
        #content h1 {
            font-size: 2rem;
            color: orange;
            margin: 0.5rem 0;
        }
        #content p {
            font-size: 1rem;
            color: #444;
            margin: 0.3rem 0;
        }

        footer {
            text-align: center;
            padding: 0.8rem;
            background: #000;
            color: white;
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            #toolbar {
                padding: 0.5rem 1rem;
            }
            #toolbar h3 {
                font-size: 14px;
            }
            #content img {
                width: 200px;
            }
            #content h1 {
                font-size: 1.5rem;
            }
            #content p {
                font-size: 0.9rem;
            }
            footer {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div id="toolbar">
        <div style="display:flex;align-items:center;gap:0.5rem;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h3>Lantaw Marbel</h3>
        </div>
    </div>

    <div id="content">
        <img src="{{ asset('images/maintenance.webp') }}" alt="Under Maintenance">
        <h1>Oops! The Site is Under Renovation</h1>
        <p>We’re improving your experience. Please check back again soon!</p>
    </div>

    <footer>
        &copy; {{ date('Y') }} Lantaw Marbel Resort. All rights reserved.
    </footer>
</body>
</html>
