<?php
add_action('wp_ajax_generate_rrr', 'remita_generate_rrr');
add_action('wp_ajax_nopriv_generate_rrr', 'remita_generate_rrr');

function remita_generate_rrr() {
    $amount = sanitize_text_field($_POST['amount']);
    $payerName = sanitize_text_field($_POST['customer_name']);
    $payerEmail = sanitize_email($_POST['customer_email']);

    // Get credentials from plugin settings
    $merchantId = get_option('remita_merchant_id');
    $serviceTypeId = get_option('remita_service_type_id');
    $apiKey = get_option('remita_api_key');
    $apiToken = get_option('remita_api_token');

    // Check for missing credentials
    if (!$merchantId || !$serviceTypeId || !$apiKey || !$apiToken) {
        wp_send_json_error('Remita API credentials are missing. Please configure them in plugin settings.');
        return;
    }

    $orderId = uniqid();
    $url = "https://remitademo.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit";

    $payload = array(
        'merchantId' => $merchantId,
        'serviceTypeId' => $serviceTypeId,
        'amount' => $amount,
        'orderId' => $orderId,
        'payerName' => $payerName,
        'payerEmail' => $payerEmail,
        'description' => 'Payment for ' . $payerName
    );

    $jsonPayload = json_encode($payload);
    $hash_string = $merchantId . $serviceTypeId . $orderId . $amount . $apiKey;
    $hash = hash('sha512', $hash_string);

    $headers = array(
        'Content-Type: application/json',
        'Authorization: remitaConsumerKey=' . $apiKey . ',remitaConsumerToken=' . $hash
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response['RRR'])) {
        wp_send_json_success($response['RRR']);
    } else {
        wp_send_json_error('RRR generation failed');
    }
}
?>
