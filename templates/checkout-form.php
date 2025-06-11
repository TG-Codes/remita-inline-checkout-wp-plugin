<form id="payment-form">
    <div>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required />
    </div>
    <div>
        <label for="customer_name">Name:</label>
        <input type="text" id="customer_name" name="customer_name" required />
    </div>
    <div>
        <label for="customer_email">Email:</label>
        <input type="email" id="customer_email" name="customer_email" required />
    </div>
    <input type="button" onclick="generateRRR()" value="Pay Now" />
</form>

<script>
function generateRRR() {
    const amount = document.getElementById("amount").value;
    const customerName = document.getElementById("customer_name").value;
    const customerEmail = document.getElementById("customer_email").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", remitaData.ajax_url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            makePayment(response.data);
        } else {
            alert("Failed to generate RRR: " + response.data);
        }
    };
    xhr.send("action=generate_rrr&amount=" + amount + "&customer_name=" + customerName + "&customer_email=" + customerEmail);
}

function makePayment(rrr) {
    var paymentEngine = RmPaymentEngine.init({
        key: "YOUR_PUBLIC_KEY_HERE", // Replace with your public key
        processRrr: true,
        extendedData: {
            customFields: [
                { name: "rrr", value: rrr }
            ]
        },
        onSuccess: function(response) {
            console.log('Payment Successful', response);
        },
        onError: function(response) {
            console.log('Payment Error', response);
        },
        onClose: function() {
            console.log("Payment Widget Closed");
        }
    });
    paymentEngine.showPaymentWidget();
}
</script>
