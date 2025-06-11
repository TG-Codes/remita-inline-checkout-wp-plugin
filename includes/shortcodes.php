<?php
function remita_checkout_form() {
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/checkout-form.php';
    return ob_get_clean();
}
add_shortcode('remita_checkout', 'remita_checkout_form');
?>
