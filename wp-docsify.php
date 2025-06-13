<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name: WP Docsify
 * Plugin URI: https://github.com/jeffersonrucu/WP_Docsify
 * Description: Docsify for WordPress in a simple and practical way using the entire WordPress structure.
 * Version:     1.0.0
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
    die("
        Vendor folder not found in <strong style='color:red;'>'src/wp-content/plugins/wp-docsify/vendor'</strong>,
        please run the command <strong>'composer install'</strong>
    ");
}

require_once __DIR__ . '/vendor/autoload.php';

use WPDocsify\Activator;
use WPDocsify\Deactivator;
use WPDocsify\Core;

// Currently plugin version.
define('WPDocsify_VERSION', '1.0.0');
define('WPDocsify_NAME', 'WP Docsify');
define('WPDocsify_DIR', plugin_dir_path(__FILE__));
define ('WPDocsify_URL', plugin_dir_url(__FILE__));

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
