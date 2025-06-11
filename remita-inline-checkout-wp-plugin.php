<?php
/*
Plugin Name: Remita Inline Checkout WP Plugin
Plugin URI: https://github.com/TG-Codes/remita-inline-checkout-wp-plugin
Description: Adds a Remita Inline Checkout form with RRR generation via a shortcode.
Version: 1.1
Author: TG-Codes
Author URI: https://github.com/TG-Codes
Text Domain: remita-inline-checkout
Domain Path: /languages
*/

defined('ABSPATH') or die('No script kiddies please!');

// Define plugin constants
define('REMITA_PLUGIN_VERSION', '1.1');
define('REMITA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('REMITA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('REMITA_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once REMITA_PLUGIN_DIR . 'includes/scripts.php';
require_once REMITA_PLUGIN_DIR . 'includes/shortcodes.php';
require_once REMITA_PLUGIN_DIR . 'includes/ajax.php';
require_once REMITA_PLUGIN_DIR . 'includes/admin-settings.php';

// Initialize plugin
function remita_plugin_init() {
    // Load plugin text domain
    load_plugin_textdomain('remita-inline-checkout', false, dirname(REMITA_PLUGIN_BASENAME) . '/languages');
}
add_action('init', 'remita_plugin_init');

// Add settings link to plugins page
function remita_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=remita-inline-checkout">' . __('Settings', 'remita-inline-checkout') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . REMITA_PLUGIN_BASENAME, 'remita_plugin_settings_link');

// Activation hook
function remita_plugin_activate() {
    // Add default options if they don't exist
    if (!get_option('remita_merchant_id')) {
        add_option('remita_merchant_id', '');
    }
    if (!get_option('remita_service_type_id')) {
        add_option('remita_service_type_id', '');
    }
    if (!get_option('remita_api_key')) {
        add_option('remita_api_key', '');
    }
    if (!get_option('remita_api_token')) {
        add_option('remita_api_token', '');
    }
    if (!get_option('remita_public_key')) {
        add_option('remita_public_key', '');
    }
}
register_activation_hook(__FILE__, 'remita_plugin_activate');

// Deactivation hook
function remita_plugin_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'remita_plugin_deactivate');
?>
