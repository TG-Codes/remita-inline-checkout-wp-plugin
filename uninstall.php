<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('remita_merchant_id');
delete_option('remita_service_type_id');
delete_option('remita_api_key');
delete_option('remita_api_token');
delete_option('remita_public_key');
?> 