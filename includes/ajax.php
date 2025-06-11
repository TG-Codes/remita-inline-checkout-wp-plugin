<?php
add_action('wp_ajax_generate_rrr', 'remita_generate_rrr');
add_action('wp_ajax_nopriv_generate_rrr', 'remita_generate_rrr');

function remita_generate_rrr() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'remita_payment_nonce')) {
        wp_send_json_error('Security check failed. Please refresh the page and try again.');
        return;
    }

    // Validate and sanitize input
    if (!isset($_POST['amount']) || !is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
        wp_send_json_error('Invalid amount specified.');
        return;
    }

    if (!isset($_POST['customer_name']) || empty(trim($_POST['customer_name']))) {
        wp_send_json_error('Customer name is required.');
        return;
    }

    if (!isset($_POST['customer_email']) || !is_email($_POST['customer_email'])) {
        wp_send_json_error('Valid email address is required.');
        return;
    }

    $amount = number_format((float)$_POST['amount'], 2, '.', '');
    $payerName = sanitize_text_field($_POST['customer_name']);
    $payerEmail = sanitize_email($_POST['customer_email']);

    // Get credentials from plugin settings
    $merchantId = get_option('remita_merchant_id');
    $serviceTypeId = get_option('remita_service_type_id');
    $apiKey = get_option('remita_api_key');
    $apiToken = get_option('remita_api_token');

    // Check for missing credentials
    if (!$merchantId || !$serviceTypeId || !$apiKey || !$apiToken) {
        wp_send_json_error('Remita API credentials are not configured. Please contact the administrator.');
        return;
    }

    $orderId = uniqid();
    
    // Set base URL based on environment
    $is_demo = get_option('remita_is_demo', true);
    $base_url = $is_demo ? 'https://remitademo.net' : 'https://login.remita.net';
    $url = $base_url . "/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit";

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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlError) {
        wp_send_json_error('Connection error: ' . $curlError);
        return;
    }

    if ($httpCode !== 200) {
        wp_send_json_error('API request failed with status code: ' . $httpCode);
        return;
    }

    $response = json_decode($result, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Invalid response from payment gateway');
        return;
    }

    if (isset($response['RRR'])) {
        wp_send_json_success($response['RRR']);
    } else {
        $errorMessage = isset($response['message']) ? $response['message'] : 'RRR generation failed';
        wp_send_json_error($errorMessage);
    }
}
?>
