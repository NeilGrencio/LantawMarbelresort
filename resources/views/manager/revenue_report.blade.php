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
                    <h2 id="add-text">Export To PDF</h2>
                    <i id="add-menu" class="fa-solid fa-file-lines fa-3x" style="cursor:pointer;"></i>
                </div>
            </div>
            <div class="report-filter">
                <div class="filter-card" data-filter="year">
                    <h3>This Year</h3>
                </div>
                <div class="filter-card" data-filter="week">
                    <h3>Last Week</h3>
                </div>
                <div class="filter-card" data-filter="today">
                    <h3>Today</h3>
                </div>
            </div>
            <div class="report-container">
                <div class="date">
                    <h2 id="selected-daterange">Date</h2>
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
                <h2>Bookings</h2>
                <table>
                    <thead>
                        <th>#</th>
                        <th>Booking Number</th>
                        <th>Guest Name</th>
                        <th>Amount</th>
                        <th>Total Tender</th>
                        <th>Total Change</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        @foreach($payments as $b)
                        <tr>
                            <td>{{$b->bookingID}}</td>
                            <td>{{$b->guestname}}</td>
                            <td>₱ {{$b->total}}</td>
                            <td>₱ {{$b->totaltender}}</td>
                            <td>{{$b->totalchange}} %</td>
                            <td>{{$b->datepayment}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h2>Orders</h2>
                <table>
                    <thead>
                        <th>#</th>
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
                            <td>{{$b->guestname}}</td>
                            <td>₱ {{$b->total}}</td>
                            <td>₱ {{$b->totaltender}}</td>
                            <td>{{$b->totalchange}} %</td>
                            <td>{{$b->datepayment}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h2>Amenities</h2>
                <table>
                    <thead>
                        <th>#</th>
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
                            <td>{{$b->guestname}}</td>
                            <td>₱ {{$b->total}}</td>
                            <td>₱ {{$b->totaltender}}</td>
                            <td>{{$b->totalchange}} %</td>
                            <td>{{$b->datepayment}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
    #layout-header {
        display: flex;
        align-items: center;
        width: 100%;
        height:5%;
        padding: 1rem 3rem 1rem 2rem;
        background: white; 
        border-radius: 2rem;
        font-size: 70%;
        gap: 1rem;
    }
    #pdf-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        gap:1rem;
        margin-left:auto;
        right:.5rem;
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

    #pdf-container:hover #add-text,
    #print-container:hover #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
    }
    .report-filter{
        margin-top:.5rem;
        width:100%;
        height:4rem;
        display:flex;
        flex-direction:row;
        gap:1rem;
        justify-content: end;
    }
    .filter-card{
        background:rgb(238, 238, 238);
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        display:flex;
        align-items:center;
        justify-content:center;
        width:7rem;
        border-radius:.7rem;
        transition:all .3s ease;
        cursor:pointer;
    }
    .filter-card:hover{
        background:black;
        color:white;
    }
    .report-container{
        margin-top: .5rem;
        background:white;
        border-radius:.5rem;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,0.2);
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
    table{
        text-align:center;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-card');
        const dateHeading = document.getElementById('selected-daterange');

        const urlParams = new URLSearchParams(window.location.search);
        const from = urlParams.get('from');
        const to = urlParams.get('to');
        const currentFilter = urlParams.get('filter');

        const today = new Date();
        const pad = n => String(n).padStart(2, '0');
        const format = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const formatDate = str => {
            const [y, m, d] = str.split("-");
            return `${m}/${d}/${y}`;
        };

        if (!from || !to || !currentFilter) {
            const todayFormatted = format(today);
            const url = new URL(window.location.href);
            url.searchParams.set('from', todayFormatted);
            url.searchParams.set('to', todayFormatted);
            url.searchParams.set('filter', 'today');
            window.location.href = url.toString();
            return;
        }

        if (from && to && dateHeading) {
            dateHeading.textContent = from === to
                ? `Date: ${formatDate(from)}`
                : `Date: ${formatDate(from)} - ${formatDate(to)}`;
        }

        if (currentFilter) {
            document.querySelectorAll('.filter-card').forEach(btn => {
                if (btn.dataset.filter === currentFilter) {
                    btn.classList.add('active-filter');
                }
            });
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                const filter = this.dataset.filter;
                let fromDate, toDate;

                if (filter === 'today') {
                    fromDate = toDate = format(today);
                } else if (filter === 'week') {
                    const lastWeek = new Date(today);
                    lastWeek.setDate(today.getDate() - 7);
                    fromDate = format(lastWeek);
                    toDate = format(today);
                } else if (filter === 'year') {
                    fromDate = `${today.getFullYear()}-01-01`;
                    toDate = format(today);
                }

                const url = new URL(window.location.href);
                url.searchParams.set('from', fromDate);
                url.searchParams.set('to', toDate);
                url.searchParams.set('filter', filter);
                window.location.href = url.toString();
            });
        });

        const pdfExportBtn = document.getElementById('pdf-container');
        if (pdfExportBtn) {
            pdfExportBtn.addEventListener('click', function () {
                const exportUrl = this.dataset.url;

                const from = urlParams.get('from');
                const to = urlParams.get('to');

                if (!from || !to) {
                    alert('Please select a date range before exporting.');
                    return;
                }

                const pdfUrl = `${exportUrl}?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`;
                window.open(pdfUrl, '_blank');
            });
        }
    });
</script>




