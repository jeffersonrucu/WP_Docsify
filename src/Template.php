<?php

namespace WPDocsify;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Template {

    public function run(): void {
        $this->filters();
    }

    private function filters(): void {
        add_filter( 'page_template', [ $this, 'renderTemplate' ] );
        add_filter( 'theme_page_templates', [ $this, 'includeTemplate' ], 10, 4 );
    }

    /**
     * @param array<string,string> $post_templates
     * @param mixed                $wp_theme
     * @param mixed                $post
     * @param mixed                $post_type
     * @return array<string,string>
     */
    public function includeTemplate( $post_templates, $wp_theme, $post, $post_type ): array {
        $post_templates['template-wp-docsify.php'] = 'WP Docsify';
        return $post_templates;
    }

    public function renderTemplate( string $page_template ): string {
        if ( get_page_template_slug() !== 'template-wp-docsify.php' ) {
            return $page_template;
        }

        $options       = get_option( 'wp_docsify_options', [] );
        $is_restricted = isset( $options['is_restricted'] ) ? $options['is_restricted'] : WPDOCSIFY_DEFAULT_IS_RESTRICTED;
        $allowed_roles = $options['allowed_roles'] ?? WPDOCSIFY_DEFAULT_ALLOWED_ROLES;

        if ( ! $is_restricted ) {
            return WPDOCSIFY_DIR . 'src/templates/wp-docsify.php';
        }

        if ( ! is_user_logged_in() ) {
            wp_redirect( wp_login_url( get_permalink() ) );
            exit;
        }

        if ( empty( $allowed_roles ) || ! is_array( $allowed_roles ) ) {
            wp_redirect( home_url() );
            exit;
        }

        $user = wp_get_current_user();

        if ( empty( array_intersect( $user->roles, $allowed_roles ) ) ) {
            return WPDOCSIFY_DIR . 'src/templates/access-denied.php';
        }

        return WPDOCSIFY_DIR . 'src/templates/wp-docsify.php';
    }
}
