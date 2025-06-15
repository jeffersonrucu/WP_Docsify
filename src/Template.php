<?php

namespace WPDocsify;

class Template
{
    /**
     * Initializes all instances of the project to prepare it for execution.
     */
    public function run(): void
    {
        $this->actions();
        $this->filters();
    }

    private function actions(): void
    {
    }

    private function filters(): void
    {
        add_filter('page_template', [$this, 'renderTemplate']);
        add_filter('theme_page_templates', [$this, 'includeTemplate'], 10, 4);
    }

    public function includeTemplate($post_templates, $wp_theme, $post, $post_type)
    {
        $post_templates['template-wp-docsify.php'] = 'WP Docsify';

        return $post_templates;
    }

    public function renderTemplate($page_template)
    {
        if (get_page_template_slug() !== 'template-wp-docsify.php') {
            return $page_template;
        }

        $page_docsify = WPDOCSIFY_DIR . '/src/templates/wp-docsify.php';

        if (defined('WPDOCSIFY_IS_RESTRICTED') && !WPDOCSIFY_IS_RESTRICTED) {
            return $page_docsify;
        }

        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url(get_permalink()));
            exit;
        }

        if (!defined('WPDOCSIFY_ALLOWED_ROLES') || !is_array(WPDOCSIFY_ALLOWED_ROLES)) {
            wp_redirect(home_url());
            exit;
        }

        $user = wp_get_current_user();
        $hasAccess = array_intersect($user->roles, WPDOCSIFY_ALLOWED_ROLES);

        if (empty($hasAccess)) {
            return WPDOCSIFY_DIR . '/src/templates/access-denied.php';
        }

        return $page_docsify;
    }
}
