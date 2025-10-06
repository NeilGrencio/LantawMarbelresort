<!doctype html>
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
                <h1>Guest Report</h1>
                <div id="pdf-container" data-url="{{ url('manager/export_guestpdf') }}">
                    <h2 id="add-text">Download</h2>
                    <i id="add-menu" class="fa-solid fa-file-lines fa-3x" style="cursor:pointer;"></i>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="report-filter">
                <div>
                    <div><small>Specific Date Range</small></div>
                    <div style="display:flex; gap:.5rem; align-items:center;">
                        <input class="date-selector" id="fromInput" name="from" type="date" value="{{ request('from') }}">
                        <input class="date-selector" id="toInput" name="to" type="date" value="{{ request('to') }}">
                        <button id="applyDateBtn" class="filter-card" style="padding:.4rem .6rem;">Apply Date</button>
                        <button id="clearDateBtn" class="filter-card" style="padding:.4rem .6rem;">Clear Date</button>
                    </div>
                    <div style="margin-top:.5rem; display:flex; gap:.5rem;">
                        <button class="filter-card" data-type="guest" data-filter="hotel">Hotel Guest</button>
                        <button class="filter-card" data-type="guest" data-filter="daytour">Day Tour</button>
                        <button class="filter-card" data-type="guest" data-filter="" title="Show all">All Guests</button>
                    </div>
                </div>

                <!-- Automated Ranges -->
               <div>
                    <div><small>Automated Range</small></div>
                    <div class="report-auto-wrapper">
                        <div class="filter-card" data-type="range" data-filter="year"><h3>This Year</h3></div>
                        <div class="filter-card" data-type="range" data-filter="month"><h3>This Month</h3></div>
                        <div class="filter-card" data-type="range" data-filter="week"><h3>This Week</h3></div>
                        <div class="filter-card" data-type="range" data-filter="today"><h3>Today</h3></div>
                        <div class="filter-card" data-type="range" data-filter="all"><h3>All</h3></div>
                    </div>
                </div>
            </div>

            <!-- REPORT CONTENT -->
            <div class="report-container">
                <div class="date">
                    <h3>LANTAW MARBEL HOTEL AND RESORT Guest Report</h3>
                    <h3 id="selected-daterange">All Time</h3>
                </div>

                <!-- TOTALS -->
                <div class="totals">
                    <div class="total-card">
                        <p>Total Guests</p>
                        <p>{{ $totals['total_all'] ?? 0 }}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Hotel Guests</p>
                        <p>{{ $totals['total_Hguest'] ?? 0 }}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Day-Tour Guests</p>
                        <p>{{ $totals['total_Dguest'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- TABLE -->
                <table>
                    <thead>
                        <th>Guest</th>
                        <th>Role</th>
                        <th>Total Returns</th>
                    </thead>
                    <tbody>
                        @forelse($guest as $g)
                            <tr>
                                <td>{{ $g->firstname }} {{ $g->lastname }}</td>
                                <td>{{ $g->role === 'Guest' ? 'Hotel Guest' : 'Day-Tour Guest' }}</td>
                                <td>{{ $g->guest_return_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No guests found for this range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
    const guestButtons = document.querySelectorAll('.filter-card[data-type="guest"]');
    const rangeButtons = document.querySelectorAll('.filter-card[data-type="range"]');
    const fromInput = document.getElementById('fromInput');
    const toInput = document.getElementById('toInput');
    const applyDateBtn = document.getElementById('applyDateBtn');
    const clearDateBtn = document.getElementById('clearDateBtn');
    const pdfBtn = document.getElementById('pdf-container');
    const selectedRangeHeading = document.getElementById('selected-daterange');

    const url = new URL(window.location.href);
    const params = url.searchParams;
    const currentFrom = params.get('from');
    const currentTo = params.get('to');
    const currentFilter = params.get('filter') || '';

    const today = new Date();
    const pad = n => String(n).padStart(2, '0');
    const format = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const formatDisplay = s => {
        if (!s) return 'All Time';
        const [y,m,d] = s.split('-'); return `${m}/${d}/${y}`;
    };

    if (currentFrom) fromInput.value = currentFrom;
    if (currentTo) toInput.value = currentTo;
    updateDateHeading();

    // highlight guest buttons
    guestButtons.forEach(b => {
        const f = b.dataset.filter || '';
        if ((f === '' && currentFilter === '') || (f !== '' && currentFilter === f)) {
            b.classList.add('active-filter');
        } else {
            b.classList.remove('active-filter');
        }
    });

    // highlight range buttons when from/to matches
    function getRangeDates(rangeKey) {
        const now = new Date();
        let from, to;
        if (rangeKey === 'today') {
            from = new Date(now);
            to = new Date(now);
        } else if (rangeKey === 'week') {
            const last = new Date(now);
            last.setDate(now.getDate() - 6);
            from = last; to = now;
        } else if (rangeKey === 'month') {
            from = new Date(now.getFullYear(), now.getMonth(), 1);
            to = now;
        } else if (rangeKey === 'year') {
            from = new Date(now.getFullYear(), 0, 1);
            to = now;
        } else if (rangeKey === 'all') {
            return { from: '', to: '' };
        } else {
            return null;
        }
        return { from: format(from), to: format(to) };
    }

    rangeButtons.forEach(rb => {
        const key = rb.dataset.filter;
        const r = getRangeDates(key);
        if (!r) return;
        if (r.from === (currentFrom || '') && r.to === (currentTo || '')) {
            rb.classList.add('active-filter');
        } else {
            rb.classList.remove('active-filter');
        }
    });

    // guest button click: set filter param and preserve existing from/to
    guestButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const guestType = this.dataset.filter || '';
            const newUrl = new URL(window.location.href);
            if (guestType === '') {
                newUrl.searchParams.delete('filter');
            } else {
                newUrl.searchParams.set('filter', guestType);
            }
            // preserve from/to if present
            if (fromInput.value) newUrl.searchParams.set('from', fromInput.value);
            if (toInput.value) newUrl.searchParams.set('to', toInput.value);
            window.location.href = newUrl.toString();
        });
    });

    // range button click: set from/to and preserve existing guest filter
    rangeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const key = this.dataset.filter;
            const r = getRangeDates(key);
            const newUrl = new URL(window.location.href);
            if (r.from && r.to) {
                newUrl.searchParams.set('from', r.from);
                newUrl.searchParams.set('to', r.to);
            } else {
                newUrl.searchParams.delete('from');
                newUrl.searchParams.delete('to');
            }
            // DO NOT overwrite guest filter; preserve it
            const existingFilter = params.get('filter');
            if (existingFilter) newUrl.searchParams.set('filter', existingFilter);
            else newUrl.searchParams.delete('filter');
            window.location.href = newUrl.toString();
        });
    });

    // Apply manual dates
    applyDateBtn.addEventListener('click', function () {
        const f = fromInput.value;
        const t = toInput.value;
        if (f && t && f > t) {
            alert('From date cannot be after To date.');
            return;
        }
        const newUrl = new URL(window.location.href);
        if (f) newUrl.searchParams.set('from', f); else newUrl.searchParams.delete('from');
        if (t) newUrl.searchParams.set('to', t); else newUrl.searchParams.delete('to');

        // preserve guest filter
        const existingFilter = params.get('filter');
        if (existingFilter) newUrl.searchParams.set('filter', existingFilter);

        window.location.href = newUrl.toString();
    });

    // Clear date inputs and remove from/to params
    clearDateBtn.addEventListener('click', function () {
        fromInput.value = '';
        toInput.value = '';
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.delete('from');
        newUrl.searchParams.delete('to');
        const existingFilter = params.get('filter');
        if (existingFilter) newUrl.searchParams.set('filter', existingFilter);
        window.location.href = newUrl.toString();
    });

    // PDF export — use current querystring so it matches the current filters
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function () {
            const exportUrl = this.dataset.url;
            const pdfUrl = `${exportUrl}${window.location.search}`;
            window.open(pdfUrl, '_blank');
        });
    }

    function updateDateHeading() {
        const currentFromVal = fromInput.value;
        const currentToVal = toInput.value;
        if (currentFromVal && currentToVal) {
            selectedRangeHeading.textContent = currentFromVal === currentToVal
                ? `Date: ${formatDisplay(currentFromVal)}`
                : `Date: ${formatDisplay(currentFromVal)} - ${formatDisplay(currentToVal)}`;
        } else {
            selectedRangeHeading.textContent = 'All Time';
        }
    }
});
</script>
</body>
</html>
