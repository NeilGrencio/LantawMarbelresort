<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    <style>
    #booking{color:orange;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:black 1px solid;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .9rem;
    }
    #add-container{
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1rem;
    }
    #add-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        gap:1rem;
    }
    #add-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }

    #add-container:hover #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
    }

</style>

<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Billing</h1>
            </div>
            <div class="billing-container">
                <div class="filter-wrapper">
                    <div class="filter-card" data-filter="all">
                        <strong>All</strong>
                    </div>
                    <div class="filter-card" data-filter="booking">
                        <strong>Booking</strong>
                    </div>
                    <div class="filter-card">
                </div>

                <!--Table-->
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Amount Tendered</th>
                                <th>Balance Remaining</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $bill)
                            <tr>
                                <td>{{ $bill->guestname }}</td>
                                <td>{{ $bill->totaltender }}</td>
                                <td>{{ $bill->totalamount }}</td>
                                <td>
                                    <!-- Add any action buttons/links here -->
                                    <a href="#">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const message = document.querySelector('.alert-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }
    });
</script>