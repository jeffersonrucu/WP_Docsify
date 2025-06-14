<?php

/**
 * Plugin metadata and configuration.
 */
define('WPDOCSIFY_VERSION', '1.0.0');
define('WPDOCSIFY_NAME', 'WP Docsify');
define('WPDOCSIFY_DIR', plugin_dir_path(__FILE__));
define('WPDOCSIFY_URL', plugin_dir_url(__FILE__));
define('WPDOCSIFY_REPOSITORY_URL', 'https://github.com/seu-usuario/wp-docsify');
define('WPDOCSIFY_THEME_COLOR', '#2674D9');
define('WPDOCSIFY_SUPPORTED_LANGUAGES', ['pt_BR', 'en_US']);

/**
 * Access control configuration.
 */
define('WPDOCSIFY_IS_RESTRICTED', true);
define('WPDOCSIFY_ALLOWED_ROLES', ['administrator']);
