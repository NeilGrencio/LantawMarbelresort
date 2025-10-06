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
            <div id="layout-header">
                <h1>Booking Report</h1>
                <div id="pdf-container" data-url="{{ url('manager/export_revenuepdf') }}">
                    <h2 id="add-text">Download</h2>
                    <i id="add-menu" class="fa-solid fa-file-lines fa-3x" style="cursor:pointer;"></i>
                </div>
            </div>
            <div class="report-filter">
                <div>
                    <div><small>Specific Date Range</small></div>
                    <div>
                        <input class="date-selector" id="from" type="date" value="{{ request('from') }}">
                        <input class="date-selector" id="to" type="date" value="{{ request('to') }}">
                    </div>
                    <div style="margin-top:.5rem; display:flex; gap:.5rem;">
                        <button class="filter-card" data-filter="booking">Bookings</button>
                        <button class="filter-card" data-filter="orders">Orders</button>
                        <button class="filter-card" data-filter="daytour">Day Tour</button>
                    </div>
                </div>
                <!-- Automated Ranges -->
                <div>
                    <div><small>Automated Range</small></div>
                    <div class="report-auto-wrapper">
                        <div class="filter-card" data-filter="year"><h3>This Year</h3></div>
                        <div class="filter-card" data-filter="month"><h3>This Month</h3></div>
                        <div class="filter-card" data-filter="week"><h3>This Week</h3></div>
                        <div class="filter-card" data-filter="today"><h3>Today</h3></div>
                        <div class="filter-card" data-filter="all"><h3>All</h3></div>
                    </div>
                </div>
            </div>
            <div class="report-container">
                <div class="date">
                    <h3>LANTAW MARBEL HOTEL AND RESORT Revenue Report</h3>
                    <h2 id="selected-daterange">All Time</h2>
                </div>

                <div class="totals">
                    <div class="total-card">
                        <p>Total Overall Revenue</p>
                        <p>₱ {{ $totals['all']}}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Booking Revenue</p>
                        <p>₱ {{ $totals['booking']}}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Order Revenue</p>
                        <p>₱ {{ $totals['order']}}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Amenity Revenue</p>
                        <p>₱ {{ $totals['amenity']}}</p>
                    </div>
                </div>

                <!-- Booking Table -->
                <div class="report-table" data-type="booking">
                    <table>
                        <thead><th colspan="6">Bookings</th></thead>
                        <thead>
                            <th>Booking Number</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>Amount Tendered</th>
                            <th>Total Change</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            @foreach($payments as $b)
                            <tr>
                                <td>{{$b->bookingID}}</td>
                                <td>{{$b->guestID}}</td>
                                <td>₱ {{$b->total}}</td>
                                <td>₱ {{$b->totaltender}}</td>
                                <td>₱ {{$b->totalchange}}</td>
                                <td>{{$b->datepayment}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Orders Table -->
                <div class="report-table" data-type="orders">
                    <table>
                        <thead><th colspan="6">Orders</th></thead>
                        <thead>
                            <th>Order Number</th>
                            <th>Guest Name</th>
                            <th>Amount</th>
                            <th>Total Tender</th>
                            <th>Total Change</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            @foreach($payments as $b)
                            <tr>
                                <td>{{$b->orderID}}</td>
                                <td>{{$b->guestID}}</td>
                                <td>₱ {{$b->total}}</td>
                                <td>₱ {{$b->totaltender}}</td>
                                <td>₱ {{$b->totalchange}}</td>
                                <td>{{$b->datepayment}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Day Tour Table -->
                <div class="report-table" data-type="daytour">
                    <table>
                        <thead><th colspan="6">Day Tour</th></thead>
                        <thead>
                            <th>Amenity Number</th>
                            <th>Guest Name</th>
                            <th>Amount</th>
                            <th>Total Tender</th>
                            <th>Total Change</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            @foreach($payments as $b)
                            <tr>
                                <td>{{$b->amenityID}}</td>
                                <td>{{$b->guestID}}</td>
                                <td>₱ {{$b->total}}</td>
                                <td>₱ {{$b->totaltender}}</td>
                                <td>₱ {{$b->totalchange}}</td>
                                <td>{{$b->datepayment}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        margin-left:12rem;
    }
    #layout-header {
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
    #pdf-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        gap:.5rem;
        margin-left:auto;
        right:.5rem;
        cursor: pointer;
        transition:all .2s ease;
    }
    #pdf-container:hover{
        transform:scale(1.05);
        color:orange;
    }

    #add-text {
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }
    .report-filter{
        margin-top:.5rem;
        width:100%;
        height:6rem;
        display:flex;
        flex-direction:row;
        background: white;
        padding:.5rem;
        border-radius: .7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        gap:1rem;
        justify-content: space-between;
    }
    .report-auto-wrapper{
        display: flex;
        height:2rem;
        gap:.5rem;
        flex-direction: row;
    }
    .date-selector{
        font-size:.7rem;
        border-radius:.4rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        padding:.3rem;
        width:10rem;
    }
    .filter-select{
        font-size:.6rem;
        border-radius:.4rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        padding:.3rem;
    }

    .filter-card{
        background:rgb(255, 255, 255);
        box-shadow:.1rem .1rem 0 rgba(0,0,0);
        border:solid 1px black;
        font-size:.7rem;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:.5rem;
        border-radius:.5rem;
        transition:all .3s ease;
        cursor:pointer;
    }
    .filter-card:hover{
        background:black;
        color:white;
        transform:scale(1.05);
    }
    .date{
        text-align: center;
    }
    
    .report-container{
        margin-top: .5rem;
        background:white;
        border-radius:.5rem;
        box-shadow:.1rem .1rem 0 rgba(0,0,0);
        border:1px solid black;
        display:flex;
        flex-direction:column;
        padding:1rem;
        gap:.5rem;
    }
    .totals{
        display:flex;
        flex-direction:row;
        width:100%;
        align-content: center;
        gap:1rem;
    }
    .total-card{
        display:flex;
        flex-direction: column;
        width:12rem;
        height:5rem;
        align-items:center;
        justify-content: center;
    }
    .filter-card.active-filter {
        background-color: orange;
        color: white;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-family: Arial, sans-serif;
        font-size: 14px;
        text-align: left;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }

    thead {
        background-color: #db8d34;
        color: #fff;
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    th {
        font-weight: bold;
        text-transform: uppercase;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-card');
        const tables = document.querySelectorAll('.report-table');
        const dateHeading = document.getElementById('selected-daterange');

        const urlParams = new URLSearchParams(window.location.search);
        const from = urlParams.get('from');
        const to = urlParams.get('to');
        const tableFilter = urlParams.get('table') || 'all';
        const rangeFilter = urlParams.get('range') || 'all';

        const today = new Date();
        const pad = n => String(n).padStart(2, '0');
        const format = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const formatDate = str => {
            const [y, m, d] = str.split("-");
            return `${m}/${d}/${y}`;
        };

        if (from && to && dateHeading) {
            dateHeading.textContent = from === to
                ? `Date: ${formatDate(from)}`
                : `Date: ${formatDate(from)} - ${formatDate(to)}`;
        } else {
            dateHeading.textContent = "All Time";
        }
        tables.forEach(t => {
            t.style.display = (tableFilter === 'all' || t.dataset.type === tableFilter) ? 'block' : 'none';
        });

        document.querySelectorAll('.filter-card').forEach(btn => {
            if (btn.dataset.filter === tableFilter || btn.dataset.filter === rangeFilter) {
                btn.classList.add('active-filter');
            }
        });

        document.querySelectorAll('[data-filter="booking"], [data-filter="orders"], [data-filter="daytour"]').forEach(button => {
            button.addEventListener('click', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('table', this.dataset.filter);
                url.searchParams.set('range', rangeFilter);
                if (from) url.searchParams.set('from', from);
                if (to) url.searchParams.set('to', to);
                window.location.href = url.toString();
            });
        });

        document.querySelectorAll('.report-auto-wrapper .filter-card').forEach(button => {
            button.addEventListener('click', function () {
                const filter = this.dataset.filter;
                let fromDate, toDate;

                if (filter === 'today') {
                    fromDate = toDate = format(today);
                } 
                else if (filter === 'week') {
                    const lastWeek = new Date(today);
                    lastWeek.setDate(today.getDate() - 6);
                    fromDate = format(lastWeek);
                    toDate = format(today);
                } 
                else if (filter === 'month') {
                    fromDate = `${today.getFullYear()}-${pad(today.getMonth() + 1)}-01`;
                    toDate = format(today);
                } 
                else if (filter === 'year') {
                    fromDate = `${today.getFullYear()}-01-01`;
                    toDate = format(today);
                } 
                else if (filter === 'all') {
                    fromDate = '';
                    toDate = '';
                }

                const url = new URL(window.location.href);
                url.searchParams.set('range', filter);
                url.searchParams.set('table', tableFilter);

                if (fromDate && toDate) {
                    url.searchParams.set('from', fromDate);
                    url.searchParams.set('to', toDate);
                } else {
                    url.searchParams.delete('from');
                    url.searchParams.delete('to');
                }

                window.location.href = url.toString();
            });
        });

        const pdfExportBtn = document.getElementById('pdf-container');
        if (pdfExportBtn) {
            pdfExportBtn.addEventListener('click', function () {
                const exportUrl = this.dataset.url;
                const pdfUrl = `${exportUrl}?from=${encodeURIComponent(from || '')}&to=${encodeURIComponent(to || '')}&table=${tableFilter}&range=${rangeFilter}`;
                window.open(pdfUrl, '_blank');
            });
        }
    });
</script>




