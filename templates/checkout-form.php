<form id="payment-form">
    <div>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required min="1" step="0.01" />
    </div>
    <div>
        <label for="customer_name">Name:</label>
        <input type="text" id="customer_name" name="customer_name" required />
    </div>
    <div>
        <label for="customer_email">Email:</label>
        <input type="email" id="customer_email" name="customer_email" required />
    </div>
    <div id="payment-error" style="color: red; display: none;"></div>
    <input type="button" onclick="generateRRR()" value="Pay Now" />
</form>

<script>
function showError(message) {
    const errorDiv = document.getElementById('payment-error');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function hideError() {
    const errorDiv = document.getElementById('payment-error');
    errorDiv.style.display = 'none';
}

function generateRRR() {
    hideError();
    
    const amount = document.getElementById("amount").value;
    const customerName = document.getElementById("customer_name").value;
    const customerEmail = document.getElementById("customer_email").value;

    if (!amount || amount <= 0) {
        showError('Please enter a valid amount');
        return;
    }

    if (!customerName.trim()) {
        showError('Please enter your name');
        return;
    }

    if (!customerEmail.trim() || !customerEmail.includes('@')) {
        showError('Please enter a valid email address');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", remitaData.ajax_url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                makePayment(response.data);
            } else {
                showError(response.data || 'Failed to generate RRR. Please try again.');
            }
        } catch (e) {
            showError('An error occurred. Please try again.');
        }
    };
    xhr.onerror = function() {
        showError('Network error. Please check your connection and try again.');
    };
    xhr.send("action=generate_rrr&amount=" + encodeURIComponent(amount) + 
             "&customer_name=" + encodeURIComponent(customerName) + 
             "&customer_email=" + encodeURIComponent(customerEmail) +
             "&nonce=" + remitaData.nonce);
}

function makePayment(rrr) {
    const publicKey = remitaData.public_key;
    if (!publicKey) {
        showError('Payment configuration error. Please contact support.');
        return;
    }

    var paymentEngine = RmPaymentEngine.init({
        key: publicKey,
        processRrr: true,
        extendedData: {
            customFields: [
                { name: "rrr", value: rrr }
            ]
        },
        onSuccess: function(response) {
            console.log('Payment Successful', response);
            // You can add success handling here, like redirecting to a thank you page
            window.location.href = remitaData.success_url;
        },
        onError: function(response) {
            console.log('Payment Error', response);
            showError('Payment failed. Please try again.');
        },
        onClose: function() {
            console.log("Payment Widget Closed");
        }
    });
    paymentEngine.showPaymentWidget();
}
</script>
