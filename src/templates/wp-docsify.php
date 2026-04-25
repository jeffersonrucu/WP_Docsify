<?php
/* Template Name: WP Docsify */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$options     = get_option( 'wp_docsify_options', [] );
$theme_color = $options['theme_color'] ?? WPDOCSIFY_DEFAULT_THEME_COLOR;
$repo_url    = $options['repo_url'] ?? '';

$uploads  = wp_upload_dir();
$docs_url = trailingslashit( $uploads['baseurl'] ) . 'wp-docsify/' . WPDOCSIFY_DEFINE_LANGUAGES;

// Styles
wp_enqueue_style( 'docsify-vue', 'https://cdn.jsdelivr.net/npm/docsify@4/lib/themes/vue.css', [], null );
wp_enqueue_style( 'wp-docsify', WPDOCSIFY_URL . 'src/assets/style.css', [ 'docsify-vue' ], WPDOCSIFY_VERSION );

// Config inline script — must run before docsify.js
$docsify_config = [
    'name'                => sprintf(
        /* translators: %s: site name */
        '%s - %s',
        __( 'Documentation', 'wp-docsify' ),
        get_bloginfo( 'name' )
    ),
    'logo'                => '_media/logo.svg',
    'repo'                => $repo_url,
    'loadSidebar'         => true,
    'loadNavbar'          => true,
    'basePath'            => $docs_url,
    'auto2top'            => false,
    'themeColor'          => $theme_color,
    'routerMode'          => 'hash',
    'sidebarDisplayLevel' => 0,
    'copyCode'            => [
        'buttonText'  => __( 'Copy', 'wp-docsify' ),
        'errorText'   => __( 'Error', 'wp-docsify' ),
        'successText' => __( 'Copied', 'wp-docsify' ),
    ],
    'pagination'          => [
        'previousText' => __( 'Previous', 'wp-docsify' ),
        'nextText'     => __( 'Next', 'wp-docsify' ),
    ],
    'search'              => [
        'placeholder' => __( 'Search...', 'wp-docsify' ),
        'noData'      => __( 'No results found', 'wp-docsify' ),
    ],
    'mermaidConfig'       => [ 'querySelector' => '.mermaid' ],
    'mermaidZoom'         => [
        'minimumScale' => 1,
        'maximumScale' => 5,
        'zoomPannel'   => true,
    ],
];

wp_register_script( 'docsify', 'https://cdn.jsdelivr.net/npm/docsify@4', [], null, true );
wp_add_inline_script( 'docsify', 'window.$docsify = ' . wp_json_encode( $docsify_config ) . ';', 'before' );
wp_enqueue_script( 'docsify' );

wp_enqueue_script( 'docsify-pagination', 'https://unpkg.com/docsify-pagination/dist/docsify-pagination.min.js', [ 'docsify' ], null, true );
wp_enqueue_script( 'docsify-search', 'https://cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js', [ 'docsify' ], null, true );
wp_enqueue_script( 'docsify-copy-code', 'https://unpkg.com/docsify-copy-code@2', [ 'docsify' ], null, true );
wp_enqueue_script( 'docsify-sidebar-collapse', 'https://cdn.jsdelivr.net/npm/docsify-sidebar-collapse/dist/docsify-sidebar-collapse.min.js', [ 'docsify' ], null, true );
wp_enqueue_script( 'd3', 'https://cdn.jsdelivr.net/npm/d3@7', [], null, true );
wp_enqueue_script( 'docsify-mermaid', 'https://unpkg.com/docsify-mermaid@2.0.1/dist/docsify-mermaid.js', [ 'docsify', 'd3' ], null, true );
wp_enqueue_script( 'docsify-mermaid-zoom', 'https://unpkg.com/docsify-mermaid-zoom/dist/docsify-mermaid-zoom.js', [ 'docsify-mermaid' ], null, true );

// Mermaid ESM requires type="module" — output inline via wp_footer
add_action( 'wp_footer', function () {
    echo '<script type="module">' . "\n";
    echo 'import mermaid from "https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs";' . "\n";
    echo 'mermaid.initialize({ startOnLoad: true });' . "\n";
    echo 'window.mermaid = mermaid;' . "\n";
    echo '</script>' . "\n";
}, 5 );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url( get_site_icon_url() ); ?>">
    <title><?php echo esc_html( sprintf( '%s - %s', __( 'Documentation', 'wp-docsify' ), get_bloginfo( 'name' ) ) ); ?></title>
    <?php wp_head(); ?>
</head>
<body>
    <main id="app"></main>
    <?php wp_footer(); ?>
</body>
</html>
