<?php
/*
    Template Name: WP Dicsify
*/

header( "Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self' 'unsafe-inline' 'unsafe-eval' "
    . "https://cdn.jsdelivr.net "
    . "https://unpkg.com "
    . "https://js-agent.newrelic.com; "
    . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
    . "img-src 'self' data:;"
);

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Documentação - WP Docsify</title>

        <link rel="icon" type="image/x-icon" href="<?php echo WPDocsify_URL ?>/src/docs/_media/favicon.svg">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsify@4/lib/themes/vue.css">
        <link rel="stylesheet" href="<?php echo WPDocsify_URL ?>/src/docs/_assets/style.css">
    </head>

    <body>
    <main id="app"></main>

    <script>
        window.$docsify = {
            name: 'Documentação Studio STG',
            logo: '_media/logo.svg',
            repo: 'https://github.com/seu-repositorio',
            loadSidebar: true,
            loadNavbar: true,
            basePath: "<?php echo WPDocsify_URL . 'src/docs'; ?>",
            auto2top: false,
            themeColor: '#2674D9',
            routerMode: 'hash',
            sidebarDisplayLevel: 0,
            copyCode: {
                buttonText: 'Copiar',
                errorText: 'Erro',
                successText: 'Copiado',
            },
            pagination: {
                previousText: 'Voltar',
                nextText: 'Próximo',
            },
            search: {
                placeholder: 'Pesquisar...',
                noData: 'Nada encontrado',
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
