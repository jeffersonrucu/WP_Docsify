<?php
    /* Template Name: WP Docsify */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsify@4/lib/themes/vue.css">
        <link rel="stylesheet" href="<?php echo esc_url(WPDOCSIFY_URL); ?>/src/assets/style.css">

        <title><?php echo esc_html__('Documentation', 'wpdocsify') . ' - ' . get_bloginfo('name'); ?></title>
    </head>

    <body>
        <main id="app"></main>

        <script>
            window.$docsify = {
                name: '<?php echo esc_js( sprintf( '%s - %s', __('Documentation', 'wpdocsify'), get_bloginfo('name') ) ); ?>',
                logo: '_media/logo.svg',
                repo: "<?php echo esc_js(WPDOCSIFY_REPOSITORY_URL); ?>",
                loadSidebar: true,
                loadNavbar: true,
                basePath: "<?php echo esc_js(WPDOCSIFY_URL . 'src/docs/' . WPDOCSIFY_DEFINE_LANGUAGES); ?>",
                auto2top: false,
                themeColor: "<?php echo esc_js(WPDOCSIFY_THEME_COLOR); ?>",
                routerMode: 'hash',
                sidebarDisplayLevel: 0,
                copyCode: {
                    buttonText: '<?php echo esc_js(__('Copy', 'wpdocsify')); ?>',
                    errorText: '<?php echo esc_js(__('Error', 'wpdocsify')); ?>',
                    successText: '<?php echo esc_js(__('Copied', 'wpdocsify')); ?>',
                },
                pagination: {
                    previousText: '<?php echo esc_js(__('Previous', 'wpdocsify')); ?>',
                    nextText: '<?php echo esc_js(__('Next', 'wpdocsify')); ?>',
                },
                search: {
                    placeholder: '<?php echo esc_js(__('Search...', 'wpdocsify')); ?>',
                    noData: '<?php echo esc_js(__('No results found', 'wpdocsify')); ?>',
                },
                mermaidConfig: {
                    querySelector: ".mermaid"
                },
                mermaidZoom: {
                    minimumScale: 1,
                    maximumScale: 5,
                    zoomPannel: true
                },
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/docsify@4"></script>
        <script src="https://unpkg.com/docsify-pagination/dist/docsify-pagination.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js"></script>
        <script src="https://unpkg.com/docsify-copy-code@2"></script>
        <script src="https://cdn.jsdelivr.net/npm/docsify-sidebar-collapse/dist/docsify-sidebar-collapse.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>

        <script type="module">
            import mermaid from "https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs";

            mermaid.initialize({ startOnLoad: true });
            window.mermaid = mermaid;
        </script>

        <script src="https://unpkg.com/docsify-mermaid@2.0.1/dist/docsify-mermaid.js"></script>

        <script src="https://unpkg.com/docsify-mermaid-zoom/dist/docsify-mermaid-zoom.js"></script>
    </body>
</html>
