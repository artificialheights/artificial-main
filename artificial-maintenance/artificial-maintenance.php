<?php
/*
Plugin Name: Artificial Maintenance
Description: Maintenance mode with globe HTML, backgrounds, styled lock/login, and optional glowing title.
Version:     1.11.1
Author: You
*/
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ARTMAINT_VERSION', '1.11.1' );
define( 'ARTMAINT_DIR', plugin_dir_path(__FILE__) );
define( 'ARTMAINT_URL', plugin_dir_url(__FILE__) );

/* admin section */
if ( is_admin() ) require_once ARTMAINT_DIR . 'admin/settings.php';

/* front-end maintenance template */
add_action( 'template_redirect', function () {
    if ( ! get_option( 'artmaint_enabled', false ) ) return;
    if ( current_user_can( 'manage_options' ) )        return;
    status_header( 503 );
    nocache_headers();
    include ARTMAINT_DIR . 'templates/maintenance.php';
    exit;
} );

/* Settings link on Plugins page */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function ( $links ) {
    array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=artmaint_settings' ) . '">Settings</a>' );
    return $links;
});
/* Hide labels and apply Arial font on wp-login.php */


/* Force Rajdhani everywhere on wp-login.php and hide labels */
add_action( 'login_head', function () {
    echo '
        <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
        <style id="am-rajdhani-login">
            html, body.login, body.login *, body.login *::before, body.login *::after {
                font-family: "Rajdhani", sans-serif !important;
            }
            /* Keep username/password labels hidden */
            #loginform label {display:none !important;}
        </style>
    ';
} );


/* Force Rajdhani on the front-end maintenance splash as well */
add_action( 'wp_head', function () {
    echo '
        <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
        <style id="am-rajdhani-front">
            html, body, body *, body *::before, body *::after {
                font-family: "Rajdhani", sans-serif !important;
            }
        </style>
    ';
} );


?>