<?php
function remita_enqueue_scripts() {
    // Enqueue Remita payment script
    wp_enqueue_script(
        'remita-payment',
        'https://remitademo.net/payment/v1/remita-pay-inline.bundle.js',
        array(),
        REMITA_PLUGIN_VERSION,
        true
    );

    // Enqueue custom script
    wp_enqueue_script(
        'remita-custom',
        REMITA_PLUGIN_URL . 'assets/remita-pay-inline.bundle.js',
        array('remita-payment'),
        REMITA_PLUGIN_VERSION,
        true
    );

    // Localize script with necessary data
    wp_localize_script('remita-custom', 'remitaData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('remita_payment_nonce'),
        'public_key' => get_option('remita_public_key'),
        'success_url' => home_url('/payment-success/'),
        'error_url' => home_url('/payment-error/')
    ));
}
add_action('wp_enqueue_scripts', 'remita_enqueue_scripts');
?>
