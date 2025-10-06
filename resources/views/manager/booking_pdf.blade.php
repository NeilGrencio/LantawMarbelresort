<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Report Booking PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
    @page { margin: 20mm; }
    html, body {
      font-family: Arial, Helvetica, sans-serif;
      color: #222;
      font-size: 12px;
      -webkit-print-color-adjust: exact; 
    }

    .report-header {
      text-align: center;
      margin-bottom: 12px;
    }
    .report-title {
      font-size: 18px;
      font-weight: 700;
      letter-spacing: 0.6px;
      margin: 0 0 6px;
    }
    .report-subtitle {
      font-size: 13px;
      margin: 0;
      font-weight: 600;
    }
    .report-daterange {
      margin-top: 6px;
      font-size: 13px;
      font-weight: 600;
    }

     .totals-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        text-align: center;
        font-size: 13px;
    }
    .totals-table th {
        padding: 8px;
        font-weight: 600;
        color: #333;
    }
    .totals-table td {
        padding: 10px;
        font-size: 18px;
        font-weight: 700;
        color: #000;
    }
    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 6px;
      box-shadow: none;
    }

    .report-table thead th {
      background: #d38d34; 
      color: #fff;
      text-transform: uppercase;
      font-size: 12px;
      font-weight: 700;
      padding: 12px 14px;
      border: 1px solid rgba(0,0,0,0.08);
    }

    .report-table tbody td {
      padding: 12px 14px;
      border: 1px solid rgba(0,0,0,0.06);
      font-size: 12px;
      vertical-align: middle;
      color: #222;
    }

    .report-table tbody tr:nth-child(even) td {
      background: #fbfbfb;
    }

    .report-table tbody tr:hover td {
      background: #f5f5f5;
    }

    .col-id { width: 6%; text-align: center; }
    .col-count { width: 10%; text-align: center; }
    .col-guest { width: 28%; text-align: left; padding-left: 18px; }
    .col-price { width: 12%; text-align: right; padding-right: 18px; }
    .col-room { width: 10%; text-align: center; }
    .col-amenity { width: 16%; text-align: left; padding-left: 12px; }
    .col-cottage { width: 12%; text-align: left; padding-left: 12px; }

    tr, td, th { page-break-inside: avoid; page-break-after: auto; }

    @media (max-width: 720px) {
      .totals-grid { grid-template-columns: repeat(2,1fr); }
      .report-title { font-size: 16px; }
      .report-daterange { font-size: 12px; }
    }
  </style>
</head>
<body>
  @php
    use Carbon\Carbon;
    $fromLabel = isset($from) && $from ? Carbon::parse($from)->format('m/d/Y') : null;
    $toLabel   = isset($to) && $to   ? Carbon::parse($to)->format('m/d/Y') : null;
    if ($fromLabel && $toLabel) {
        $dateRangeText = $fromLabel === $toLabel ? "Date: {$fromLabel}" : "Date: {$fromLabel} - {$toLabel}";
    } else {
        $dateRangeText = 'Date: All';
    }
    $bookingsList = $bookings ?? collect();
    $tot = $totals ?? ['total_all'=>0,'total_hotel'=>0,'total_cottage'=>0,'total_amenity'=>0];
  @endphp

  <div class="report-header">
    <div class="report-title">LANTAW MARBEL HOTEL AND RESORT</div>
    <div class="report-subtitle">Booking Report</div>
    <div class="report-daterange">{{ $dateRangeText }}</div>
  </div>

  <div class="totals-row">
    <table class="totals-table">
      <tr>
        <th>Total Overall Bookings</th>
        <th>Total Hotel Bookings</th>
        <th>Total Cottage Bookings</th>
        <th>Total Amenity Bookings</th>
      </tr>
      <tr>
        <td>{{ $tot['total_all'] }}</td>
        <td>{{ $tot['total_hotel'] }}</td>
        <td>{{ $tot['total_cottage'] }}</td>
        <td>{{ $tot['total_amenity'] }}</td>
      </tr>
    </table>
  </div>

  <table class="report-table">
    <thead>
      <tr>
        <th class="col-count">Guest Count</th>
        <th class="col-guest">Guest</th>
        <th class="col-price">Total Price</th>
        <th class="col-room">Room</th>
        <th class="col-amenity">Amenity</th>
        <th class="col-cottage">Cottage</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bookingsList as $b)
        <tr>
          <td class="col-count">{{ $b->guestamount ?? ($b->guest_count ?? '-') }}</td>
          <td class="col-guest">{{ $b->guestname ?? $b->guestID ?? '-' }}</td>
          <td class="col-price">{{ number_format($b->totalprice ?? 0, 2) }}</td>
          <td class="col-room">{{ $b->room ?? ($b->roomnum ?? '-') }}</td>
          <td class="col-amenity">{{ $b->amenityname ?? '-' }}</td>
          <td class="col-cottage">{{ $b->cottagename ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="padding:20px; text-align:center;">No bookings found for the selected range.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            const formattedFrom = formatDate(from);
            const formattedTo = formatDate(to);

            if (from === to) {
                dateHeading.textContent = `Date: ${formattedFrom}`;
            } else {
                dateHeading.textContent = `Date Range: ${formattedFrom} - ${formattedTo}`;
            }
        }
    });
</script>
</html>
