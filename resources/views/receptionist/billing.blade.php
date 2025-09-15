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
    .table-wrapper{
        display: flex;
        flex-direction: row;
        width: 100%;
        height: auto;
        padding: .5rem;
        border-radius: .7rem;
        margin-top: 1rem;
        align-items: center;
        align-content: center;
        background: white;
        box-shadow: .1rem .1rem 0 black;
        overflow-x: auto;
    }
    table{
        width: 100%;
        font-size:.7rem;
        border-collapse: collapse;
        transition: all 0.3s ease-in;
    }
    th, td{
        padding: 10px;
        text-align: center;
    }
    thead{
        background:orange;
        color:white;
        justify-content: center;
        align-items: center;
    }
    tbody{
        justify-content: center;
        align-items: center;
    }

    #page-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        background: transparent;
        align-items: center;
    }
    .page-item {
        display: flex;
        align-items: center;
    }
    .page-link,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        min-height: 2.5rem;
        padding: 0.5rem 0.75rem;
        background: #fff;
        color: #F78A21;
        text-decoration: none;
        border: 1.5px solid #F78A21;
        border-radius: 50%;
        font-size: 1.1rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, border 0.2s;
        margin: 0 0.15rem;
    }
    .page-item.active .page-link,
    .page-link:hover {
        background: #F78A21;
        color: #fff;
        border-color: #F78A21;
    }
    .page-item.disabled .page-link,
    .page-item.disabled span {
        color: #ccc;
        pointer-events: none;
        background: #f8f9fa;
        border-color: #eee;
    }
    .page-item.disabled {
        display: none !important;
    }
    .pagination .page-status {
        background: transparent;
        border: none;
        color: #333;
        font-size: 1rem;
        font-weight: 400;
        border-radius: 0;
        min-width: unset;
        min-height: unset;
        margin: 0 0.5rem;
        padding: 0;
    }
    .filter-wrapper{
        width:100%;
        height:3rem;
        display:flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap:.5rem;
    }
    .filter-card{
        background:white;
        padding:.5rem;
        border-radius:.4rem;
        box-shadow:.1rem .1rem 0 black;
        transition:all .2s ease;
    }
    .filter-card:hover{
        background:orange;
        cursor:pointer;
        transform: translateY(10);
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
                </div>

                <!--Table-->
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Billing #</th>
                                <th>Name</th>
                                <th>Amount Tendered</th>
                                <th>Balance Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0 ?>
                            @foreach($payments as $bill)
                                <?php $count++ ?>
                            <tr>
                                <td>{{ $count}}</td>
                                <td>{{ $bill->guestname }}</td>
                                <td>{{ $bill->totaltender }}</td>
                                <td>{{ $bill->totalamount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="page-container">
                    {{ $payments->links() }}
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
