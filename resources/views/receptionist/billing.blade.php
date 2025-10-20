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
    #billingside{color:orange !important;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
        width:100%;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:15rem;
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
    .search-container .reset-btn {
        padding: 10px 15px;
        background-color: #e53935;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        margin-left: 10px;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    .search-container .reset-btn:hover {
        background-color: #b71c1c;
    }

    .button-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    #add-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease;
    }
    #add-container:hover {
        color: #F78A21;
    }
    #add-text {
        opacity: 1;
        visibility: visible;
        width: auto;
        margin-left: 0.5rem;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-content: center;
        margin: 15px 0;
    }

    .search-container form {
        display: flex;
        align-items: center;
    }

    .search-container input[type="text"] {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 25px 0 0 25px;
        outline: none;
        width: 250px;
        font-size: 14px;
    }

    .search-container button {
        padding: 10px 15px;
        border-left: none;
        background-color: #000000;
        color: white;
        border-radius: 0 25px 25px 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-container button:hover {
        background-color: #F78A21;
        border: 1px solid #F78A21;
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
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        width: 420px;
        max-width: 90%;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        padding: 1.5rem;
        animation: fadeIn 0.3s ease-in-out;
        position: relative;
    }

    .modal-content h2 {
        margin-bottom: 1rem;
        color: #F78A21;
        text-align: center;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 1.4rem;
        cursor: pointer;
        color: #555;
        transition: 0.2s;
    }

    .close-btn:hover {
        color: #F78A21;
    }
    #add-container{
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1rem;
    }
    .add-action{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .table-wrapper table th,
    .table-wrapper table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-wrapper table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .table-wrapper table tbody tr:hover {
        background-color: #fef3c7; /* light orange */
    }

    .view-billing-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        transition: color 0.2s ease-in-out;
    }

    .view-billing-btn:hover {
        color: #c2410c; /* darker orange */
    }

    .rounded-full {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 500;
    }
</style>

<body>
    <div id="layout">
        @include('components.receptionist_sidebar')
        <div id="main-layout">
            <div id="layout-header">
                <h1>Billing</h1>
                <div class="button-group">
                    <div class="add-action" data-url="{{ url('receptionist/revenue_pdf') }}">
                        <i class="fa-solid fa-print fa-2x"></i>
                        <small>Revenue Report</small>
                    </div>
                    <div class="search-container">
                        <form action="{{ route('receptionist.search_billing') }}" method="GET">
                            <input type="text" name="search" placeholder="Search.." value="{{ request('search') }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request()->has('search') && request('search') !== '')
                                <a href="{{ route('receptionist.search_billing') }}" class="reset-btn">Clear Search</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="billing-container p-4">
    <div class="table-wrapper overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Billing #</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-right">Amount Tendered</th>
                    <th class="px-4 py-2 text-right">Balance Remaining</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0 ?>
                @foreach($payments as $bill)
                    <?php $count++ ?>
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="px-4 py-2">{{ $count }}</td>
                        <td class="px-4 py-2">{{ $bill->guestname }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($bill->created_at)->format('M d, Y') }}</td>
                        <td class="px-4 py-2">
                            @if($bill->totalamount - $bill->totaltender <= 0)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Paid</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">₱ {{ number_format($bill->totaltender, 2) }}</td>
                        <td class="px-4 py-2 text-right">₱ {{ number_format($bill->totalamount - $bill->totaltender, 2) }}</td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="view-billing-btn text-orange-600 hover:text-orange-800 font-semibold"
                                data-billing-no="{{ $loop->iteration }}"
                                data-guest-name="{{ $bill->guestname }}"
                                data-tender="{{ $bill->totaltender }}"
                                data-total="{{ $bill->totalamount }}"
                                data-amenity="{{ $bill->amenity_total }}"
                                data-menu="{{ $bill->menu_total }}"
                                data-room="{{ $bill->room_total }}"
                                data-cottage="{{ $bill->cottage_total }}"
                                data-additional="{{ $bill->additional_total }}">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="page-container" class="mt-4 flex justify-center">
        {{ $payments->links() }}
    </div>
</div>
            <div id="billingModal" class="modal-overlay" style="display:none;">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <h2>Billing Details</h2>
                    <div id="billingDetails">
                        <p><strong>Billing #:</strong> <span id="modalBillingNo"></span></p>
                        <p><strong>Guest Name:</strong> <span id="modalGuestName"></span></p>
                        <p><strong>Amount Tendered:</strong> ₱<span id="modalTenderTop"></span></p>
                        <p><strong>Remaining Amount:</strong> ₱<span id="modalRemaining"></span></p>
                        <hr>
                        <div id="modalBreakdown">
                            <p><strong>Amenity Total:</strong> ₱<span id="modalAmenity"></span></p>
                            <p><strong>Menu Total:</strong> ₱<span id="modalMenu"></span></p>
                            <p><strong>Room Total:</strong> ₱<span id="modalRoom"></span></p>
                            <p><strong>Cottage Total:</strong> ₱<span id="modalCottage"></span></p>
                            <p><strong>Additional Charges:</strong> ₱<span id="modalAdditional"></span></p> 
                            <hr>
                            <p><strong>Grand Total:</strong> ₱<span id="modalGrandTotal"></span></p>
                            <p><strong>Amount Tendered:</strong> ₱<span id="modalTenderBottom"></span></p>
                            <p><strong>Balance:</strong> ₱<span id="modalBalance"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('billingModal');
        const closeBtn = document.querySelector('.close-btn');

        document.querySelectorAll('.add-action').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                if (url) {
                    window.open(url, '_blank');
                }
            });
        });

        document.querySelectorAll('.view-billing-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const billingNo = this.dataset.billingNo || '';
                const guestName = this.dataset.guestName || '';
                const tender = parseFloat(this.dataset.tender || 0);
                const total = parseFloat(this.dataset.total || 0);
                const amenity = parseFloat(this.dataset.amenity || 0);
                const menu = parseFloat(this.dataset.menu || 0);
                const room = parseFloat(this.dataset.room || 0);
                const cottage = parseFloat(this.dataset.cottage || 0);
                const additional = parseFloat(this.dataset.additional || 0);

                const grandTotal = amenity + menu + room + cottage + additional;
                const balance = grandTotal - tender;

                document.getElementById('modalBillingNo').textContent = billingNo;
                document.getElementById('modalGuestName').textContent = guestName;
                document.getElementById('modalAmenity').textContent = amenity.toFixed(2);
                document.getElementById('modalMenu').textContent = menu.toFixed(2);
                document.getElementById('modalRoom').textContent = room.toFixed(2);
                document.getElementById('modalCottage').textContent = cottage.toFixed(2);
                document.getElementById('modalAdditional').textContent = additional.toFixed(2);
                document.getElementById('modalGrandTotal').textContent = grandTotal.toFixed(2);
                document.getElementById('modalTenderTop').textContent = tender.toFixed(2);
                document.getElementById('modalTenderBottom').textContent = tender.toFixed(2);
                document.getElementById('modalRemaining').textContent = balance.toFixed(2);
                document.getElementById('modalBalance').textContent = balance.toFixed(2);

                modal.style.display = 'flex';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
