<?php
    /* Template Name: WP Docsify - Access Denied */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo esc_html__('Access Denied', 'wpdocsify') . ' - ' . get_bloginfo('name'); ?></title>

        <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo esc_url(WPDOCSIFY_URL); ?>/src/assets/style.css">
    </head>

    <body class="wp-docsify__body">
        <main class="wp-docsify__access-denied-container">
            <div class="wp-docsify__lock-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
                </svg>
            </div>

            <h1 class="wp-docsify__access-denied-title"><?php echo esc_html__('Access Denied', 'wpdocsify'); ?></h1>
            
            <p class="wp-docsify__access-denied-message">
                <?php echo esc_html__('Sorry, you do not have permission to access this documentation.', 'wpdocsify'); ?>
            </p>

            <div class="wp-docsify__access-denied-details">
                <p><?php echo esc_html__('Your account may not have the necessary permissions', 'wpdocsify'); ?></p>
            </div>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="wp-docsify__back-button">
                <svg viewBox="0 0 24 24">
                    <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/>
                </svg>
                <?php echo esc_html__('Back to Home', 'wpdocsify'); ?>
            </a>
        </main>
    </body>
</html>
