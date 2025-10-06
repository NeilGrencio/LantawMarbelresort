<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Logs - Lantaw-Marbel Resort</title>
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 1rem;
        }

        .report-header {
            background: white;
            border-radius: 1rem;
            padding: 1rem 2rem;
            margin-bottom: 1rem;
            box-shadow: .1rem .1rem .5rem rgba(0,0,0,0.2);
            text-align: center;
        }

        .report-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
        }

        .report-subtitle {
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }

        .report-daterange {
            font-size: 13px;
            font-weight: 500;
            margin-top: 5px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: .1rem .1rem .5rem rgba(0,0,0,0.2);
        }

        #logs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        #logs-table th, #logs-table td {
            padding: 0.75rem 1rem;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        #logs-table th {
            background-color: #F78A21;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .even-row { background-color: #eaeaea; }
        .odd-row { background-color: #ffffff; }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            background-color: #ffffff;
            border: 2px solid #000000;
            border-radius:.7rem;
            color: #000000;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: #F78A21;
            color: #fff;
            border:1px solid #603308;
            box-shadow: .1rem .1rem 0 #603308;
            transform: scale(1.05);
        }

        #pdf-container {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            cursor: pointer;
            gap: 0.5rem;
            float: right;
        }

        #pdf-container:hover { color: #F78A21; }

        #add-text {
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div id="layout">
    <!-- Header -->
    <div class="report-header">
        <div class="report-title">LANTAW MARBEL HOTEL AND RESORT</div>
        <div class="report-subtitle">Session Logs</div>
        <div class="report-daterange" id="selected-daterange">
            @php
                use Carbon\Carbon;
                $fromLabel = request('from') ? Carbon::parse(request('from'))->format('m/d/Y') : null;
                $toLabel   = request('to') ? Carbon::parse(request('to'))->format('m/d/Y') : null;
            @endphp
            {{ $fromLabel && $toLabel ? ($fromLabel === $toLabel ? "Date: {$fromLabel}" : "Date: {$fromLabel} - {$toLabel}") : 'Date: All' }}
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table id="logs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Activity</th>
                    <th>Activity Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session as $s)
                <tr class="{{ $loop->even ? 'even-row' : 'odd-row' }}">
                    <td>{{ $loop->iteration + ($session->firstItem() - 1) }}</td>
                    <td>{{ $s->username }}</td>
                    <td>{{ $s->activity }}</td>
                    <td>{{ $s->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter Buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = this.dataset.filter;
            const today = new Date();
            const pad = n => String(n).padStart(2, '0');
            const format = date => `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}`;
            let fromDate, toDate;

            if(filter === 'today') { fromDate = toDate = format(today); }
            else if(filter === 'week') {
                const lastWeek = new Date(today);
                lastWeek.setDate(today.getDate() - 7);
                fromDate = format(lastWeek);
                toDate = format(today);
            } else if(filter === 'month') {
                fromDate = `${today.getFullYear()}-${pad(today.getMonth()+1)}-01`;
                toDate = format(today);
            } else if(filter === 'year') {
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

    // PDF Download
    const pdfBtn = document.getElementById('pdf-container');
    pdfBtn.addEventListener('click', function () {
        const baseUrl = this.dataset.url;
        const filter = this.dataset.filter;
        const url = new URL(baseUrl, window.location.origin);
        if(filter) url.searchParams.set('filter', filter);
        window.open(url.toString(), '_blank');
    });
});
</script>
</body>
</html>
