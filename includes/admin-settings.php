<?php
add_action('admin_menu', 'remita_plugin_menu');
function remita_plugin_menu() {
    add_options_page(
        'Remita Inline Checkout Settings', 
        'Remita Inline Settings', 
        'manage_options', 
        'remita-inline-checkout', 
        'remita_plugin_settings_page'
    );
}

function remita_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Remita Inline Checkout Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('remita_plugin_options_group');
            do_settings_sections('remita_plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'remita_plugin_settings');
function remita_plugin_settings() {
    register_setting('remita_plugin_options_group', 'remita_merchant_id');
    register_setting('remita_plugin_options_group', 'remita_service_type_id');
    register_setting('remita_plugin_options_group', 'remita_api_key');
    register_setting('remita_plugin_options_group', 'remita_api_token');
    register_setting('remita_plugin_options_group', 'remita_public_key');

    add_settings_section('remita_plugin_section', 'Remita Credentials', null, 'remita_plugin');

    add_settings_field('remita_merchant_id', 'Merchant ID', 'remita_merchant_id_field', 'remita_plugin', 'remita_plugin_section');
    add_settings_field('remita_service_type_id', 'Service Type ID', 'remita_service_type_id_field', 'remita_plugin', 'remita_plugin_section');
    add_settings_field('remita_api_key', 'API Key', 'remita_api_key_field', 'remita_plugin', 'remita_plugin_section');
    add_settings_field('remita_api_token', 'API Token', 'remita_api_token_field', 'remita_plugin', 'remita_plugin_section');
    add_settings_field('remita_public_key', 'Public Key', 'remita_public_key_field', 'remita_plugin', 'remita_plugin_section');
}

function remita_merchant_id_field() {
    $value = esc_attr(get_option('remita_merchant_id'));
    echo '<input type="text" name="remita_merchant_id" value="' . $value . '" class="regular-text">';
}
function remita_service_type_id_field() {
    $value = esc_attr(get_option('remita_service_type_id'));
    echo '<input type="text" name="remita_service_type_id" value="' . $value . '" class="regular-text">';
}
function remita_api_key_field() {
    $value = esc_attr(get_option('remita_api_key'));
    echo '<input type="text" name="remita_api_key" value="' . $value . '" class="regular-text">';
}
function remita_api_token_field() {
    $value = esc_attr(get_option('remita_api_token'));
    echo '<input type="text" name="remita_api_token" value="' . $value . '" class="regular-text">';
}
function remita_public_key_field() {
    $value = esc_attr(get_option('remita_public_key'));
    echo '<input type="text" name="remita_public_key" value="' . $value . '" class="regular-text">';
}
?>
