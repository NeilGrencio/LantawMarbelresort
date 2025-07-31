<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt with Dynamic Updates</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .receipt-wrapper{
            display: flex;
            flex-direction: row;
            position:relative;
            width:100%;
            height:100%;
            gap:.5rem;
        }
        .receipt{
            background:white;
            display:flex;
            flex-direction: column;
            position:relative;
            height:97.3%;
            width:100%;
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
            border:1px solid black;
            padding:1rem;
            font-size:.8rem;
            overflow-y:auto;
        }
        hr {
            border: 1px solid black;  
            margin: 1rem 0;  
            width: 100%;
        }
        .no-selection{
            color:red;
            font-weight:bold;
        }
        .receipt span{
            font-weight:bold;
        }
        .label-container{
            display: flex;
            flex-direction: row;
            margin-bottom: 1rem;
            background:black;
            width: 100%;
            height:3rem;
            justify-content: space-between;
            align-items: center;
            padding:.5rem;
            font-size:.7rem;
            color:white;
            border-radius:.7rem;
        }
        .payment{
            display:flex;
            flex-direction: column;
            position:absolute;
            width:69.5%;
            height:90%;
            background:white;   
            border-radius:.7rem;
            box-shadow:.1rem .1rem 0 black;
            border:1px solid black;
            padding:1rem;
            font-size:.8rem;
            gap:.5rem;
        }
        .payment-type-wrapper{
            display:flex;
            flex-direction: row;
            gap:.5rem;
            margin-bottom: 1rem;
        }
        .payment-type-selection{
            display:flex;
            height:4rem;
            width:8rem;
            border-radius:.7rem;
            justify-content:center;
            align-items:center;
            border:1px solid black;
            box-shadow:.1rem .1rem 0 black;
            gap:.5rem;
            cursor:pointer;
            transition:all .2s ease;
            background: #f0f0f0;
        }
        .payment-type-selection:hover{
            background:orange;
            color:white;
            scale:1.05;
        }
        .payment-selection-wrapper{
            display:flex;
            flex-direction: row;
            gap:.5rem;
        }
        .payment-selection{
            display:flex;
            height:5rem;
            width:7rem;
            border-radius:.7rem;
            justify-content:center;
            align-items:center;
            border:1px solid black;
            box-shadow:.1rem .1rem 0 black;
            gap:.5rem;
            cursor:pointer;
            transition:all .2s ease;
        }
        .payment-selection:hover{
            background:orange;
            color:white;
            scale:1.1;
        }
        .input{
            display:flex;
            width:100%;
            background: white;
            border:1px solid black;
            border-radius:.5rem;
            padding:.5rem;
            font-size: .8rem;
        }
        .button-container {
            position:absolute;
            display: flex;
            flex-direction: row;
            margin-top: auto;
            bottom:1rem;
        }
        .form-button{
            background: rgb(255, 255, 255);
            color: rgb(0, 0, 0);
            border: none;
            padding: .5rem 1rem;
            border-radius: .5rem;
            cursor: pointer;
            font-size: .8rem;
            margin-right: .5rem;
            transition: all .2s ease-in-out;
            border:rgb(0, 0, 0) solid 1px;
            box-shadow: .1rem .1rem 0 rgb(0, 0, 0);
            margin-bottom: 1rem;
        }
        .form-button:hover{
            background: orange;
            color: black;
            transform: translateY(-.1rem);
        }  

        .alert-message{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: fixed;
            right: 50%;
            transform: translate(50%, 0);
            bottom: 1rem;
            height: fit-content;
            min-height: 10rem;
            max-height: 30rem;
            width: fit-content;
            min-width: 20rem;
            max-width: 90vw;
            background: rgb(255, 255, 255);
            z-index: 1000;
            border-radius: 1rem;
            box-shadow: 0 0 1rem rgba(0,0,0,0.5);
            margin: auto;
            padding: 1rem;
            flex-wrap: wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="receipt-wrapper">
        <div class="receipt">
            <h2>Booking Information</h2>
                <p><span>Guest: </span><span id="guest-name-receipt">N/A</span></p>
                <p><span>Guest Count: </span><span id="guest-amount-receipt">0</span></p>
                <hr/>
                <p>Selected Amenity:</p>
                <ul id="selected-amenities-list">
                    <li class="no-selection">No amenities selected</li>
                </ul>
                <hr/>
            <h2>Total</h2>
                <p><span>Adult Total: </span><span id="adult-total-receipt">₱ 0.00</span></p>
                <p><span>Child Total: </span><span id="child-total-receipt">₱ 0.00</span></p>
                <p><span>Amenity Total: </span><span id="amenity-total-receipt">₱ 0.00</span></p>
                
                <p><span>SubTotal: </span><span id="subtotal-receipt">₱ 0.00</span></p>
                <p><span>Discount: </span><span id="discount-receipt">0%</span></p>
                <p><span>Total: </span><span id="total-receipt">₱ 0.00</span></p>
                <p><span>Amount Due: </span><span id="amount-due-receipt">₱ 0.00</span></p>

            <h2>Payment</h2>
                <p><span>Amount Tendered: </span><span id="amount-tendered">₱ 0.00</span></p>
                <p><span>Total Change:</span><span id="change-receipt">₱ 0.00</span></p>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all form elements
        const firstNameInput = document.getElementById('txtfirstname');
        const lastNameInput = document.getElementById('txtalstname');
        const guestAmountInput = document.getElementById('guestamount');
        const adultGuestInput = document.querySelector('input[name="amenity_adult_guest"]');
        const childGuestInput = document.querySelector('input[name="amenity_child_guest"]');
        const amountPaidInput = document.getElementById('cash-amount');
        const discountSelect = document.getElementById('discount');
        const cashRadio = document.getElementById('cash');
        const gcashRadio = document.getElementById('gcash');
        const fullPaymentRadio = document.getElementById('full-payment');
        const downpaymentRadio = document.getElementById('downpayment');
        const amenityCheckboxes = document.querySelectorAll('.amenity-checkbox');

        // Get all receipt elements
        const guestNameField = document.getElementById('guest-name-receipt');
        const guestAmountField = document.getElementById('guest-amount-receipt');
        const selectedAmenitiesList = document.getElementById('selected-amenities-list');
        const adultTotalField = document.getElementById('adult-total-receipt');
        const childTotalField = document.getElementById('child-total-receipt');
        const amenityTotalField = document.getElementById('amenity-total-receipt');
        const subtotalField = document.getElementById('subtotal-receipt');
        const discountField = document.getElementById('discount-receipt');
        const totalPriceField = document.getElementById('total-receipt');
        const amountDueField = document.getElementById('amount-due-receipt');
        const paymentTypeField = document.getElementById('payment-type-receipt');
        const paymentMethodField = document.getElementById('payment-method-receipt');
        const amountTenderedField = document.getElementById('amount-tendered');
        const changeField = document.getElementById('change-receipt');
        const cashAmountWrapper = document.getElementById('cash-amount-wrapper');

        const amenitiesData = @json('amenities');

        let subtotal = 0;
        let discountAmount = 0;
        let totalAmount = 0;
        let amountDue = 0;

        function updateGuestName() {
            const firstName = firstNameInput ? firstNameInput.value.trim() : '';
            const lastName = lastNameInput ? lastNameInput.value.trim() : '';
            const fullName = `${firstName} ${lastName}`.trim();
            if (guestNameField) {
                guestNameField.textContent = fullName || 'N/A';
            }
        }

        function updateGuestAmount() {
            const guestAmount = guestAmountInput ? guestAmountInput.value : '0';
            if (guestAmountField) {
                guestAmountField.textContent = guestAmount || '0';
            }
        }

        function updateSelectedAmenities() {
            const selectedAmenities = [];
            amenityCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const amenityName = checkbox.getAttribute('data-name');
                    const amenityPrice = parseFloat(checkbox.getAttribute('data-price')) || 0;
                    selectedAmenities.push({ name: amenityName, price: amenityPrice });
                }
            });

            if (selectedAmenitiesList) {
                selectedAmenitiesList.innerHTML = '';
                if (selectedAmenities.length === 0) {
                    selectedAmenitiesList.innerHTML = '<li class="no-selection">No amenities selected</li>';
                } else {
                    selectedAmenities.forEach(amenity => {
                        const li = document.createElement('li');
                        li.textContent = `${amenity.name}`;
                        selectedAmenitiesList.appendChild(li);
                    });
                }
            }
        }

        // Function to calculate totals
        function calculateTotals() {
            const adultCount = parseInt(adultGuestInput ? adultGuestInput.value : '0') || 0;
            const childCount = parseInt(childGuestInput ? childGuestInput.value : '0') || 0;

            let totalAdultPrice = 0;
            let totalChildPrice = 0;
            let totalAmenityPrice = 0;

            // Calculate selected amenities total
            amenityCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    // Find the amenity data
                    const amenityId = parseInt(checkbox.value);
                    const amenity = amenitiesData.find(a => a.amenityID === amenityId);
                    if (amenity) {
                        totalAdultPrice += amenity.adult-price * adultCount;
                        totalChildPrice += amenity.child-price * childCount;
                        totalAmenityPrice += amenity.adult-price * adultCount + amenity.child-price * childCount;
                    }
                }
            });

            // Update individual totals
            if (adultTotalField) adultTotalField.textContent = '₱ ' + totalAdultPrice.toFixed(2);
            if (childTotalField) childTotalField.textContent = '₱ ' + totalChildPrice.toFixed(2);
            if (amenityTotalField) amenityTotalField.textContent = '₱ ' + totalAmenityPrice.toFixed(2);

            // Calculate subtotal
            subtotal = totalAmenityPrice;
            if (subtotalField) subtotalField.textContent = '₱ ' + subtotal.toFixed(2);

            // Calculate discount
            if (discountSelect) {
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const discountDecimal = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
                const discountPercentage = discountDecimal * 100;
                discountAmount = discountDecimal * subtotal;
                if (discountField) discountField.textContent = discountPercentage + '%';
            }

            // Calculate total after discount
            totalAmount = subtotal - discountAmount;
            if (totalPriceField) totalPriceField.textContent = '₱ ' + totalAmount.toFixed(2);

            // Calculate amount due based on payment type
            if (downpaymentRadio && downpaymentRadio.checked) {
                amountDue = totalAmount * 0.5; // 50% downpayment
            } else {
                amountDue = totalAmount; // Full payment
            }
            if (amountDueField) amountDueField.textContent = '₱ ' + amountDue.toFixed(2);
        }

        // Function to update payment information
        function updatePaymentInfo() {
            // Update payment type
            if (paymentTypeField) {
                if (fullPaymentRadio && fullPaymentRadio.checked) {
                    paymentTypeField.textContent = 'Full Payment';
                } else if (downpaymentRadio && downpaymentRadio.checked) {
                    paymentTypeField.textContent = '50% Downpayment';
                } else {
                    paymentTypeField.textContent = 'N/A';
                }
            }

            // Update payment method
            if (paymentMethodField) {
                if (cashRadio && cashRadio.checked) {
                    paymentMethodField.textContent = 'Cash';
                } else if (gcashRadio && gcashRadio.checked) {
                    paymentMethodField.textContent = 'GCash';
                } else {
                    paymentMethodField.textContent = 'N/A';
                }
            }

            // Update amount tendered and change
            if (cashRadio && cashRadio.checked) {
                if (cashAmountWrapper) cashAmountWrapper.style.display = 'block';
                const amountPaid = parseFloat(amountPaidInput ? amountPaidInput.value : '0') || 0;
                if (amountTenderedField) amountTenderedField.textContent = '₱ ' + amountPaid.toFixed(2);
                const change = amountPaid - amountDue;
                if (changeField) changeField.textContent = '₱ ' + (change >= 0 ? change.toFixed(2) : '0.00');
                if (amountPaidInput) amountPaidInput.style.borderColor = amountPaid < amountDue ? 'red' : '';
            } else {
                if (cashAmountWrapper) cashAmountWrapper.style.display = 'none';
                if (amountTenderedField) amountTenderedField.textContent = '₱ ' + amountDue.toFixed(2);
                if (changeField) changeField.textContent = '₱ 0.00';
            }
        }

        // Main function to update entire receipt
        function updateReceipt() {
            updateGuestName();
            updateGuestAmount();
            updateSelectedAmenities();
            calculateTotals();
            updatePaymentInfo();
        }

        // Add event listeners to all form fields
        
        // Personal information fields
        if (firstNameInput) firstNameInput.addEventListener('blur', updateReceipt);
        if (lastNameInput) lastNameInput.addEventListener('blur', updateReceipt);
        if (guestAmountInput) guestAmountInput.addEventListener('blur', updateReceipt);
        if (adultGuestInput) adultGuestInput.addEventListener('blur', updateReceipt);
        if (childGuestInput) childGuestInput.addEventListener('blur', updateReceipt);

        // Amenity checkboxes
        amenityCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateReceipt);
        });

        // Payment fields
        if (amountPaidInput) {
            amountPaidInput.addEventListener('blur', updateReceipt);
            amountPaidInput.addEventListener('input', updateReceipt); // Also update on input for real-time feedback
        }
        if (discountSelect) discountSelect.addEventListener('change', updateReceipt);
        if (cashRadio) cashRadio.addEventListener('change', updateReceipt);
        if (gcashRadio) gcashRadio.addEventListener('change', updateReceipt);
        if (fullPaymentRadio) fullPaymentRadio.addEventListener('change', updateReceipt);
        if (downpaymentRadio) downpaymentRadio.addEventListener('change', updateReceipt);

        // Also add change listeners for number inputs to ensure they update on value change
        if (adultGuestInput) adultGuestInput.addEventListener('change', updateReceipt);
        if (childGuestInput) childGuestInput.addEventListener('change', updateReceipt);
        if (guestAmountInput) guestAmountInput.addEventListener('change', updateReceipt);

        // Initialize the receipt
        updateReceipt();
    });
</script>
</body>
</html>