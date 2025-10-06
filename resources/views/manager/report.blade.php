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
            <div id="title-header">
                <h1>Select A Report</h1>
            </div>
            <div class="report-selection">
                <div class="report-card" id="booking-report" data-url="{{url('manager/booking_report')}}"> 
                    <div>
                        <i id="icon" class="fa-solid fa-clipboard-list fa-3x"></i>
                    </div>
                    <div>
                        <h2 id="report_title">Booking Report</h2>
                        <span>Auto Generated Report for Guest Booking</span><br/>
                        <small>Genereated on: {{$today}}</small> <br/><br/>
                        <button>View</button>
                    </div>
                </div>
                <div class="report-card" id="revenue-report" data-url="{{url('manager/revenue_report')}}">
                    <div>
                        <i id="icon" class="fa-solid fa-chart-simple fa-3x"></i>
                    </div>
                    <div>
                        <h2 id="report_title">Revenue Report</h2>
                        <span>Auto Generated Report of Resort Revenue</span><br/>
                        <small>Genereated on: {{$today}}</small> <br/><br/>
                        <button>View</button>
                    </div>
                </div>

                <div class="report-card" id="guest-report" data-url="{{url('manager/guest_report')}}">
                    <div>
                        <i id="icon" class="fa-solid fa-person fa-3x"></i>
                    </div>
                    <div>
                        <h2 id="report_title">Guest Report</h2>
                        <span>Auto Generated Report of Guest Total</span><br/>
                        <small>Genereated on: {{$today}}</small> <br/><br/>
                        <button>View</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    #report { color: #F78A21;}
    body{overflow-y: auto;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:12rem;
        gap:1rem;
    }
    #title-header{
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 8%;
        padding: 1rem 3rem 1rem 2rem;
        background: white;
        border-radius: .7rem;
        font-size: .6rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        gap: 1rem;
    }
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    .report-selection{
        width:100%;
        display:grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap:1rem;
        justify-content:center; 
    }
    .report-card{
        display:flex;
        flex-direction:row;
        height:10rem;
        width:100%;
        padding:.5rem;
        text-align: start;
        box-shadow:.1rem .1rem 0 rgba(0,0,0);
        font-size:.8rem;
        border:solid 1px black;
        background:white;
        border-radius:.7rem;
        gap:1rem;
    }
    #icon{
        padding:.5rem;
        font-size:8rem;
        border-right:solid 2px black;
    }
    #report_title{
        font-size:1.5rem;
        display:flex;
        align-content: flex-start;
        justify-content: flex-start;
    }
    button{
        height:2rem;
        padding:.5rem;
        width:5rem;
        background:#ccc;
        border:1px black solid;
        box-shadow:.1rem .1rem 0 rgba(0,0,0);
        border-radius:.7rem;
        margin-top:auto;
        bottom:1rem;
        transition:all .2s ease;
        cursor:pointer;
    }
    button:hover{
        color:white;
        background:rgb(249, 160, 72);
        transform:scale(1.1);
    }
</style>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const bookingReport = document.getElementById('booking-report');
        const checkReport = document.getElementById('check-report');
        const revenueReport = document.getElementById('revenue-report');
        const guestReport = document.getElementById('guest-report');

        if (bookingReport) {
            bookingReport.addEventListener('click', function () {
                const url = this.dataset.url;
                window.location.href = url;
            });
        }

        if (checkReport) {
            checkReport.addEventListener('click', function () {
                const url = this.dataset.url;
                window.location.href = url;
            });
        }

        if (revenueReport) {
            revenueReport.addEventListener('click', function () {
                const url = this.dataset.url;
                window.location.href = url;
            });
        }

        if (guestReport) {
            guestReport.addEventListener('click', function () {
                const url = this.dataset.url;
                window.location.href = url;
            });
        }
    });


</script>