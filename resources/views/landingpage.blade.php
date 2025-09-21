<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw Marbel ORMS</title>
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{box-sizing:border-box;}
        body{
            margin:0;
            padding:0;
            background:rgb(255, 251, 251);
            font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            overflow:auto;
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
            white-space: nowrap;
        }
        #loginbutton { border: 2px solid orange; }
        #loginbutton:hover{ background:orange; color:black; cursor:pointer; }
        #downloadbutton { border: 2px solid green; }
        #downloadbutton:hover { background:green; color:white; cursor:pointer; }

        .parent-container{
            display:flex;
            flex-direction: column;
            width:100%;
            height:95%;
            padding:2rem;
        }
        #layout-container{
            display:flex;
            height:100%;
            width:100%;
            align-items: center;
            position: relative;
            color:white;
        }
        #layout-container img{
            object-fit: cover;
            height: 90%;
            width:100%;
            border-radius:.7rem;
            filter: brightness(80%);
        }
        #layout-container div{
            position:absolute;
            display:flex;
            flex-direction: column;
            right:3rem;
            font-size:.9rem;
            align-items:center;
            justify-content: center;
            text-align: center;
        }
        #layout-container h1{
            margin:0;
            font-size: 90px;
            word-wrap:break-word;
            text-align:center;
            color: orange;
        }
        #layout-container h2{
            margin:0;
            font-size: 28px;
            color: rgb(255, 255, 255);
        }
        #layout-container p{
            margin:0.3rem 0;
            word-wrap:break-word;
            text-align:center;
            color: rgb(255, 255, 255);
        }
        .description{
            margin-top:2rem;
            height:auto;
            width:100%;
            padding:1.5rem;
            background:white;
            border:1px solid black;
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
            text-align: justify; 
            z-index: 1;
        }
        #logo-image{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
        }
        #logo-image img{
            object-fit: cover;
            height:5rem;
            width:auto;
            margin-bottom:1rem;
        }
        #card{
            display:flex;
            flex-direction: row;
            gap:1rem;
            align-items: center;
            justify-content: space-between;
            margin-top:1rem;
            padding:1rem;
            background:white;
            border:1px solid black;
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
        }
        #room-img, #amenities-img, #dining-img{
            display:flex;
            object-fit: cover;
            background: maroon;
            height:15rem;
            width:23rem;
            border-radius:.7rem;
        }

        @media (max-width: 1024px) {
            #layout-container {
                flex-direction: column;
                text-align: center;
            }
            #layout-container img {
                width: 100%;
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
            #information-text {
                font-size: 10px;
                right:1rem;
                left:3rem;
                color:white;
            }
            #information-text span {
                margin-top:-1.4rem;
                font-size: 8px;
            }
            #layout-container h2{
                margin-top:1rem;
                font-size: 15px;
            }
            #layout-container h1 {
                font-size: 30px;
            }
            #layout-container img {
                width: 100%;
                height: auto;
                border-radius: .5rem;
            }
            .parent-container {
                padding: 0.5rem;
            }
            #about-us h2 {
                padding: 1rem;
                text-align:start;
            }
            #card {
                flex-direction: column;
                text-align: center;
            }
            #room-img, #amenities-img, #dining-img {
                width: 100%;
                height: 10rem;
            }
        }

    </style>
</head>
<body>
    <div id="toolbar">
        <img src="{{asset('images/logo.png')}}"/>
        <h3>Lantaw Marbel</h3>
        <div class="spacer"></div>
        <button id="downloadbutton" data-url="https://lantawmarbelresort.site/app-debug.apk">
            <span>Download App</span>
        </button>
        <button id="loginbutton" data-url="{{url('auth/login')}}">
            <span>Log In</span>
        </button>
    </div>
    <div class="parent-container">
        <div id="layout-container">
            <img src="{{asset('images/landing_page_image.jpg')}}"/>
            <div id="information-text">
                <h2>Welcome to the</h2>
                <h1>LANTAW MARBEL<br/>
                    RESORT</h1>
                <p>Download the mobile application for guest access!</p>
                <br/>
                <br/>
                <br/>
                <span>Visit our on site location:
                FRRJ+4J9 Purok kalikasan Brgy. Paraiso, Banga - Koronadal City Rd, matulas, Koronadal, 9506 South Cotabato</span>
            </div>
        </div>

        <div id="about-us">
            <div class="description" id="about-us-description">
                <div id="logo-image">
                    <img src="{{asset('images/logo.png')}}"/>
                </div>

                <h2>What we Offer at Lantaw-Marbel Resort:</h2>
                <div id="card">
                    <p>
                        Rooms: We offer a variety of comfortable and well-appointed rooms to suit your needs. Each room is designed with modern amenities to ensure a pleasant stay.
                    </p>
                    <img id="room-img" src="{{asset('images/rooms.jpg')}}"/><br/>
                </div>
                <div id="card">
                    <img id="amenities-img" src="{{asset('images/amenities.jpg')}}"/>
                    <p>
                        Amenities: Our resort features a range of amenities including a swimming pool, fitness center, spa services, and recreational areas to enhance your experience.
                    </p>
                    
                </div>
                <div id="card">
                    <p>
                        Dining: Enjoy a variety of dining options at our on-site restaurants and cafes.
                    </p>
                    <img id="dining-img" src="{{asset('images/dining.jpg')}}"/>
                </div>

                <br/>
                <br/>

                <h2>About Lantaw-Marbel Resort:</h2>
                <p>
                    Lantaw-Marbel Resort is a premier destination located in the heart of Koronadal City, South Cotabato. 
                    Nestled amidst lush greenery and serene surroundings, our resort offers a tranquil escape from the hustle and bustle of city life. 
                    With its picturesque landscapes, modern amenities, and warm hospitality, Lantaw Marbel Resort is the perfect place for relaxation, recreation, and celebration.
                </p>
                <p>
                    Our resort features a variety of accommodations to suit every traveler's needs, from cozy rooms to spacious villas. 
                    Each room is thoughtfully designed with comfort and convenience in mind, ensuring a restful stay for our guests. 
                    Whether you're here for a romantic getaway, a family vacation, or a corporate retreat, we have the perfect space for you.
                </p>
                <p>
                    At Lantaw Marbel Resort, we pride ourselves on our exceptional facilities and services. 
                    Our outdoor swimming pool is surrounded by lush gardens, providing a refreshing oasis for guests to unwind and soak up the sun. 
                    For those seeking adventure, we offer various recreational activities such as hiking trails, biking paths, and team-building exercises.
                </p>
                <p>
                    Our resort also boasts several dining options that showcase the rich flavors of South Cotabato cuisine. 
                    From traditional Filipino dishes to international favorites, our restaurants offer a diverse menu that caters to all tastes. 
                    Guests can enjoy their meals while taking in the stunning views of the surrounding landscape.
                </p>
                <p>
                    Whether you're planning a wedding, a corporate event, or a special celebration, Lantaw Marbel Resort provides versatile event spaces that can accommodate gatherings of all sizes. 
                    Our dedicated events team will work closely with you to ensure that every detail is taken care of, making your event truly memorable.
                </p>
                <p>
                    Experience the perfect blend of nature, comfort, and hospitality at Lantaw Marbel Resort. 
                    We invite you to discover the beauty of Koronadal City while enjoying the exceptional amenities and services that our resort has to offer. 
                    Book your stay with us today and create unforgettable memories that will last a lifetime.
                </p>

                <br/>
                <br/>

                <h2>To Contact Lantaw-Marbel Resort:</h2>
                <p>
                    For inquiries, reservations, or more information about Lantaw Marbel Resort, please feel free to contact us through the following channels:
                </p>
                <p>
                    <i class="fas fa-phone"></i> Phone: +63 123 456 7890
                </p>
                <p>
                    <i class="fas fa-mobile-alt"></i> Mobile Application: Available on Android, click the "Download App" button above to get the app.
                </p>
                <p>
                    <i class="fab fa-facebook"></i> Facebook: facebook.com/LantawMarbelResort
                </p>
            </div>
        </div>
    </div>
</body>
<script>
    const buttonlogin = document.getElementById('loginbutton');
    if(buttonlogin){
        buttonlogin.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });
    }

    const buttondownload = document.getElementById('downloadbutton');
    if(buttondownload){
        buttondownload.addEventListener('click', function(){
            window.location.href = this.dataset.url;
        });
    }
</script>

</html>
