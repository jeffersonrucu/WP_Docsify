<?php
    /* Template Name: WP Docsify - Access Denied*/
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo esc_html__('Documentation', 'wpdocsify') . ' - ' . get_bloginfo('name'); ?></title>

        <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsify@4/lib/themes/vue.css">
        <link rel="stylesheet" href="<?php echo esc_url(WPDOCSIFY_URL); ?>/src/docs/_assets/style.css">
    </head>

    <body>
        <div class="wpdocsify-access-danied">
            
        </div>
    </body>
</html>
