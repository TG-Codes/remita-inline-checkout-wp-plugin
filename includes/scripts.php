<?php
function remita_enqueue_scripts() {
    wp_enqueue_script('remita-inline', plugins_url('/assets/remita-pay-inline.bundle.js', __FILE__), array(), null, true);
    wp_localize_script('remita-inline', 'remitaData', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'remita_enqueue_scripts');
?>
