<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name:       WP Docsify
 * Plugin URI:        https://github.com/jeffersonrucu/WP_Docsify
 * Description:       Integrate Docsify documentation into WordPress using a custom page template with role-based access control, multilingual support, and admin settings.
 * Version:           2.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            Jefferson Oliveira, Studio STG
 * Author URI:        https://github.com/jeffersonrucu
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-docsify
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

spl_autoload_register( function ( $class ) {
    $prefix   = 'WPDocsify\\';
    $base_dir = __DIR__ . '/src/';
    $len      = strlen( $prefix );

    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require $file;
    }
} );

require_once __DIR__ . '/config.php';

use WPDocsify\Activator;
use WPDocsify\Deactivator;
use WPDocsify\Core;

register_activation_hook( __FILE__, [ Activator::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ Deactivator::class, 'deactivate' ] );

add_action( 'plugins_loaded', function () {
    $plugin = new Core();
    $plugin->run();
} );
