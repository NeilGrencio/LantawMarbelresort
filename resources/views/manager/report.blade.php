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
                    <i class="fa-solid fa-clipboard-list fa-3x"></i>
                    <h2>Booking Report</h2>
                    <button>View</button>
                </div>
                <div class="report-card" id="check-report" data-url="{{url('manager/check_report')}}">
                    <i class="fa-solid fa-clock fa-3x"></i>
                    <h2>Check-in/Check-out Report</h2>
                    <button>View</button>
                </div>
                <div class="report-card" id="revenue-report" data-url="{{url('manager/revenue_report')}}">
                    <i class="fa-solid fa-money-bill fa-3x"></i>
                    <h2>Revenue Report</h2>
                    <button>View</button>
                </div>
                <div class="report-card" id="guest-report" data-url="{{url('manager/guest_report')}}">
                    <i class="fa-solid fa-person fa-3x"></i>
                    <h2>Guest Report</h2>
                    <button>View</button>
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
        width:100%;
        height: auto;
        padding:1rem;
        margin-left:15rem;
    }
    #title-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        max-height:5rem;
        padding:1rem;
        border-radius: 2rem;
        align-content: center;
        align-items: center;
        gap: 1rem;
    }
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    .report-selection{
        width:100%;
        height: 90%;
        display:flex;
        flex-direction:row;
        flex-wrap:wrap;
        gap:2rem;
        justify-content:center; 
    }
    .report-card{
        display:flex;
        flex-direction:column;
        height:17rem;
        width:20rem;
        padding:1rem;
        align-items:center;
        justify-content: center;
        text-align: center;
        box-shadow:.1rem .1rem 0 rgba(0,0,0,0.2);
        background:white;
        border-radius:.7rem;
        gap:1rem;
    }
    button{
        height:4rem;
        padding:.5rem;
        width:7rem;
        background:rgb(255, 146, 3);
        border:none;
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        border-radius:.7rem;
        margin-top:auto;
        bottom:1rem;
        transition:all .2s ease;
        cursor:pointer;
    }
    button:hover{
        color:white;
        background:grey;
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