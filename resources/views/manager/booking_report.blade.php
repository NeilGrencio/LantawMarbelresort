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
                <div id="pdf-container" data-url="{{ url('manager/export_pdf') }}">
                    <h2 id="add-text">Download</h2>
                    <i id="add-menu" class="fa-solid fa-file-lines fa-3x" style="cursor:pointer;"></i>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="report-filter">
                <div>
                    <div><small>Specific Date Range</small></div>
                    <div>
                        <input class="date-selector" id="from" type="date" value="{{ request('from') }}">
                        <input class="date-selector" id="to" type="date" value="{{ request('to') }}">
                    </div>
                    <div style="margin-top:.5rem; display:flex; gap:.5rem;">
                        <select id="roomFilter" class="filter-select">
                            <option value="">All Rooms</option>
                            @foreach($roomSelect as $room)
                                <option value="{{ $room->roomID }}" {{ request('room') == $room->roomID ? 'selected' : '' }}>
                                    Room {{ $room->roomnum }}
                                </option>
                            @endforeach
                        </select>

                        <select id="cottageFilter" class="filter-select">
                            <option value="">All Cottages</option>
                            @foreach($cottageSelect as $cottage)
                                <option value="{{ $cottage->cottageID }}" {{ request('cottage') == $cottage->cottageID ? 'selected' : '' }}>
                                    {{ $cottage->cottagename }}
                                </option>
                            @endforeach
                        </select>

                        <select id="amenityFilter" class="filter-select"> 
                            <option value="">All Amenities</option>
                            @foreach($amenitySelect as $amenity)
                                <option value="{{ $amenity->amenityID }}" {{ request('amenity') == $amenity->amenityID ? 'selected' : '' }}>
                                    {{ $amenity->amenityname }}
                                </option>
                            @endforeach
                        </select>
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

            <!-- REPORT CONTENT -->
            <div class="report-container">
                <div class="date">
                    <h3>LANTAW MARBEL HOTEL AND REOSRT Booking Report</h3>
                    <h3 id="selected-daterange">All Time</h3>
                </div>

                <div class="totals">
                    <div class="total-card">
                        <p>Total Overall Bookings</p>
                        <p>{{ $totals['total_all'] }}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Hotel Bookings</p>
                        <p>{{ $totals['total_hotel'] }}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Cottage Bookings</p>
                        <p>{{ $totals['total_cottage'] }}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Amenity Bookings</p>
                        <p>{{ $totals['total_amenity'] }}</p>
                    </div>
                </div>

                <table>
                    <thead>
                        <th>Guest Count</th>
                        <th>Guest ID</th>
                        <th>Total Price</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Room</th>
                        <th>Amenity</th>
                        <th>Cottage</th>
                    </thead>
                    <tbody>
                        @if(!empty($bookings) && count($bookings) > 0)
                            @foreach($bookings as $b)
                            <tr>
                                <td>{{ $b->guestamount }}</td>
                                <td>{{ $b->guestID }}</td>
                                <td>{{ $b->totalprice }}</td>
                                <td>{{ $b->bookingstart}} </td>
                                <td>{{ $b->bookingend}} </td>
                                <td>{{ $b->room }}</td>
                                <td>{{ $b->amenityname }}</td>
                                <td>{{ $b->cottagename }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">No bookings found</td>
                            </tr>
                        @endif
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
    const urlParams = new URLSearchParams(window.location.search);
    const fromInput = document.getElementById('from');
    const toInput = document.getElementById('to');
    const roomFilter = document.getElementById('roomFilter');
    const cottageFilter = document.getElementById('cottageFilter');
    const amenityFilter = document.getElementById('amenityFilter');
    const dateHeading = document.getElementById('selected-daterange');

    const today = new Date();
    const pad = n => String(n).padStart(2, '0');
    const format = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const formatDate = str => {
        const [y, m, d] = str.split("-");
        return `${m}/${d}/${y}`;
    };

    // Show date heading
    const from = urlParams.get('from');
    const to = urlParams.get('to');
    if (from && to && dateHeading) {
        dateHeading.textContent = (from === to)
            ? `Date: ${formatDate(from)}`
            : `Date: ${formatDate(from)} - ${formatDate(to)}`;
    }

    // Dropdown filter handler
    function updateFilters() {
        const url = new URL(window.location.href);
        if (fromInput.value) url.searchParams.set('from', fromInput.value); else url.searchParams.delete('from');
        if (toInput.value) url.searchParams.set('to', toInput.value); else url.searchParams.delete('to');
        if (roomFilter.value) url.searchParams.set('room', roomFilter.value); else url.searchParams.delete('room');
        if (cottageFilter.value) url.searchParams.set('cottage', cottageFilter.value); else url.searchParams.delete('cottage');
        if (amenityFilter.value) url.searchParams.set('amenity', amenityFilter.value); else url.searchParams.delete('amenity');
        window.location.href = url.toString();
    }

    [fromInput, toInput, roomFilter, cottageFilter, amenityFilter].forEach(el => {
        if (el) el.addEventListener('change', updateFilters);
    });

    // Auto filter buttons
    document.querySelectorAll('.filter-card').forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            const url = new URL(window.location.href);
            let fromDate, toDate;

            if (filter === "today") {
                fromDate = toDate = format(today);
            } else if (filter === "week") {
                const lastWeek = new Date(today);
                lastWeek.setDate(today.getDate() - 7);
                fromDate = format(lastWeek);
                toDate = format(today);
            } else if (filter === "month") {
                fromDate = `${today.getFullYear()}-${pad(today.getMonth() + 1)}-01`;
                toDate = format(today);
            } else if (filter === "year") {
                fromDate = `${today.getFullYear()}-01-01`;
                toDate = format(today);
            } else if (filter === "all") {
                url.searchParams.delete('from');
                url.searchParams.delete('to');
            }

            if (fromDate && toDate) {
                url.searchParams.set('from', fromDate);
                url.searchParams.set('to', toDate);
            }
            url.searchParams.set('filter', filter);
            window.location.href = url.toString();
        });
    });

    // PDF export
    const pdfExportBtn = document.getElementById('pdf-container');
    if (pdfExportBtn) {
        pdfExportBtn.addEventListener('click', () => {
            const exportUrl = pdfExportBtn.dataset.url;
            const params = new URLSearchParams(window.location.search);
            window.open(`${exportUrl}?${params.toString()}`, '_blank');
        });
    }
});
</script>
