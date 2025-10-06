<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lantaw-Marbel Revenue Report PDF</title>
    <style>
        @page { margin: 20mm; }
        html, body {
            font-family: "DejaVu Sans", sans-serif;
            color: #222;
            font-size: 12px;
        }
        .report-header { text-align: center; margin-bottom: 12px; }
        .report-title { font-size: 18px; font-weight: 700; }
        .report-subtitle { font-size: 13px; font-weight: 600; }
        .report-daterange { margin-top: 6px; font-size: 13px; font-weight: 600; }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            text-align: center;
            font-size: 13px;
        }
        .totals-table th, .totals-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .totals-table td {
            font-size: 16px;
            font-weight: 700;
        }

        .section-title { font-size: 14px; font-weight: 700; margin-top: 20px; margin-bottom: 8px; }
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th, .report-table td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        .report-table th { background: #d38d34; color: #fff; }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    $fromLabel = $from ? Carbon::parse($from)->format('m/d/Y') : null;
    $toLabel   = $to ? Carbon::parse($to)->format('m/d/Y') : null;
    $dateRangeText = $fromLabel && $toLabel
        ? ($fromLabel === $toLabel ? "Date: {$fromLabel}" : "Date: {$fromLabel} - {$toLabel}")
        : 'Date: All';
@endphp

<div class="report-header">
    <div class="report-title">LANTAW MARBEL HOTEL AND RESORT</div>
    <div class="report-subtitle">Revenue Report</div>
    <div class="report-daterange">{{ $dateRangeText }}</div>
</div>

<!-- Totals -->
<table class="totals-table">
    <tr>
        <th>Total Overall Revenue</th>
        <th>Total Booking Revenue</th>
        <th>Total Order Revenue</th>
        <th>Total Amenity Revenue</th>
    </tr>
    <tr>
        <td>₱ {{ number_format($totals->overall ?? 0, 2) }}</td>
        <td>₱ {{ number_format($totals->booking ?? 0, 2) }}</td>
        <td>₱ {{ number_format($totals->orders ?? 0, 2) }}</td>
        <td>₱ {{ number_format($totals->amenities ?? 0, 2) }}</td>
    </tr>
</table>

<!-- Booking Transactions -->
@if(count($grouped['booking']) > 0)
<div class="section-title">Booking Transactions</div>
<table class="report-table">
    <thead>
        <tr>
            <th>#</th><th>Type</th><th>Guest</th><th>Amount</th><th>Tender</th><th>Change</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grouped['booking'] as $p)
        <tr>
            <td>{{ $p->billingID }}</td>
            <td>{{ $p->payment_type }}</td>
            <td>{{ $p->guestname }}</td>
            <td>₱ {{ number_format($p->total ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totaltender ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totalchange ?? 0, 2) }}</td>
            <td>{{ $p->datepayment ? Carbon::parse($p->datepayment)->format('m/d/Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- Order Transactions -->
@if(count($grouped['order']) > 0)
<div class="section-title">Order Transactions</div>
<table class="report-table">
    <thead>
        <tr>
            <th>#</th><th>Type</th><th>Guest</th><th>Amount</th><th>Tender</th><th>Change</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grouped['order'] as $p)
        <tr>
            <td>{{ $p->billingID }}</td>
            <td>{{ $p->payment_type }}</td>
            <td>{{ $p->guestname }}</td>
            <td>₱ {{ number_format($p->total ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totaltender ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totalchange ?? 0, 2) }}</td>
            <td>{{ $p->datepayment ? Carbon::parse($p->datepayment)->format('m/d/Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- Amenity Transactions -->
@if(count($grouped['amenity']) > 0)
<div class="section-title">Amenity & Day Tour Transactions</div>
<table class="report-table">
    <thead>
        <tr>
            <th>#</th><th>Type</th><th>Guest</th><th>Amount</th><th>Tender</th><th>Change</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grouped['amenity'] as $p)
        <tr>
            <td>{{ $p->billingID }}</td>
            <td>{{ $p->payment_type }}</td>
            <td>{{ $p->guestname }}</td>
            <td>₱ {{ number_format($p->total ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totaltender ?? 0, 2) }}</td>
            <td>₱ {{ number_format($p->totalchange ?? 0, 2) }}</td>
            <td>{{ $p->datepayment ? Carbon::parse($p->datepayment)->format('m/d/Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
</body>

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