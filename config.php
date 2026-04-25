<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'WPDOCSIFY_VERSION', '2.0.0' );
define( 'WPDOCSIFY_NAME', 'WP Docsify' );
define( 'WPDOCSIFY_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPDOCSIFY_URL', plugin_dir_url( __FILE__ ) );
define( 'WPDOCSIFY_SUPPORTED_LANGUAGES', [ 'pt_BR', 'en_US' ] );

/** Default values — overridden by admin settings stored in wp_options. */
define( 'WPDOCSIFY_DEFAULT_THEME_COLOR', '#2674D9' );
define( 'WPDOCSIFY_DEFAULT_IS_RESTRICTED', true );
define( 'WPDOCSIFY_DEFAULT_ALLOWED_ROLES', [ 'administrator' ] );
