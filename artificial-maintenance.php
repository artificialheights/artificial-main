<?php
/*
Plugin Name: Artificial Maintenance
Plugin URI: https://artificialheights.com/
Description: Maintenance mode plugin customized for Artificial Heights.
Version: 1.1.7
Author: Artificial Heights, LLC
Author URI: https://artificialheights.com
License: GPL2
*/

defined('ABSPATH') or die('No script kiddies please!');

// Admin menu
add_action('admin_menu', function() {
    add_menu_page('Artificial Maintenance', 'Artificial Maintenance', 'manage_options', 'artificial-maintenance', 'artificial_maintenance_settings_page');
});

// Register setting
add_action('admin_init', function() {
    register_setting('artificial_maintenance_settings', 'artificial_maintenance_enabled');
    register_setting('artificial_maintenance_settings', 'artificial_maintenance_html');
});

// Settings page
function artificial_maintenance_settings_page() {
    ?>
    <div class="wrap">
        <h1>Artificial Maintenance Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('artificial_maintenance_settings');
            do_settings_sections('artificial_maintenance_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Maintenance Mode</th>
                    <td><input type="checkbox" name="artificial_maintenance_enabled" value="1" <?php checked(1, get_option('artificial_maintenance_enabled'), true); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Custom HTML Block</th>
                    <td><textarea name="artificial_maintenance_html" rows="10" cols="50"><?php echo esc_textarea(get_option('artificial_maintenance_html')); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Show maintenance screen for non-admins
add_action('template_redirect', function () {
    if (!current_user_can('manage_options') && get_option('artificial_maintenance_enabled')) {
        status_header(503);
        header('Retry-After: 600');
        echo get_option('artificial_maintenance_html');
        exit;
    }
});

// Safe load of plugin update checker
$update_checker_path = plugin_dir_path(__FILE__) . 'update-checker/plugin-update-checker.php';

if (file_exists($update_checker_path)) {
    require_once $update_checker_path;

    if (class_exists('Puc_v4_Factory')) {
        $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
            'https://artificialheights.com/updates/metadata.json',
            __FILE__,
            'artificial-maintenance'
        );
    } else {
        error_log('Puc_v4_Factory class not found after including plugin-update-checker.php');
    }
} else {
    error_log('Update checker not found at: ' . $update_checker_path);
}
