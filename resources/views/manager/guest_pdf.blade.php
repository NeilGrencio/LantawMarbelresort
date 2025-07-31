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
        <div id="main-layout">
            <div id="layout-header">
                <h1>Booking Report</h1>
            </div>
            <div class="report-container">
                <div class="totals">
                    <div class="total-card">
                        <p>Total Guest</p>
                        <p>{{ $totals['total_all']}}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Hotel Guest</p>
                        <p>{{ $totals['total_Hguest']}}</p>
                    </div>
                    <div class="total-card">
                        <p>Total Day-Tour Guest</p>
                        <p>{{ $totals['total_Dguest']}}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <th>#</th>
                        <th>Guest Name</th>
                        <th>Role</th>
                    </thead>
                    <tbody>
                        @foreach($guest as $g)
                        <tr>
                            <td>{{$g->guestID}}</td>
                            <td>{{$g->guestname}}</td>
                            <td>{{$g->role}}</td>
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
    body {
        font-family: sans-serif;
        font-size: 11px;
        margin: 0;
        padding: 1rem;
        overflow: visible; /* allow full content rendering in PDF */
    }

    #layout {
        display: block;
        width: 100%;
    }

    #main-layout {
        width: 100%;
        padding: 0.5rem;
    }

    #layout-header {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.5rem 1rem;
        background: white;
        border-radius: 1rem;
        font-size: 0.8rem;
        gap: 0.5rem;
    }

    #print-container,
    #pdf-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 0.5rem;
        margin-left: auto;
    }

    #add-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.2rem 0.5rem;
        margin-left: 0.3rem;
        border-radius: 4px;
    }

    #pdf-container:hover #add-text,
    #print-container:hover #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
    }

    .report-filter {
        margin-top: 0.5rem;
        width: 100%;
        height: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .filter-card {
        background: rgb(238, 238, 238);
        box-shadow: 0.1rem 0.1rem 0 rgba(0, 0, 0, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .filter-card:hover {
        background: black;
        color: white;
    }

    .filter-card.active-filter {
        background-color: orange;
        color: white;
        font-weight: bold;
    }

    .report-container {
        margin-top: 0.5rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0.1rem 0.2rem 0 rgba(0, 0, 0, 0.2);
        padding: 1rem;
        gap: 0.5rem;
    }

    .totals {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .total-card {
        width: 20%;
        min-width: 100px;
        text-align: center;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    table th,
    table td {
        border: 1px solid #444;
        padding: 4px 6px;
        text-align: center;
        font-size: 0.85rem;
    }

    table th {
        background: #f0f0f0;
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