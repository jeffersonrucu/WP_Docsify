<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name: WP Docsify
 * Plugin URI: https://github.com/jeffersonrucu/WP_Docsify
 * Description: Docsify for WordPress in a simple and practical way using the entire WordPress structure.
 * Version:     2.0.0
 * Author:      Jefferson Oliveira
 * Author URI:  https://github.com/jeffersonrucu
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: jeffersonrucu/wp_docsify
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// if this composer did not exist.
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    add_action( 'admin_notices', function() {
        ?>
            <div class="notice notice-error">
                <p>
                    <?php esc_html_e( 'WP Docsify plugin error: vendor folder missing. Please run composer install. The plugin was deactivated.', 'wp-docsify' ); ?>
                </p>
            </div>
        <?php
    } );

    deactivate_plugins(plugin_basename(__FILE__));

    return;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use WPDocsify\Activator;
use WPDocsify\Deactivator;
use WPDocsify\Core;

/**
 * The code that runs during plugin activation.
 */
register_activation_hook(__FILE__, [Activator::class, 'activate']);

/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

/**
 * Begins execution of the plugin.
 */
add_action('plugins_loaded', function () {
    $plugin = new Core();
    $plugin->run();
});
