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
        if (get_page_template_slug() == 'template-wp-docsify.php') {
            if(!is_user_logged_in() || !current_user_can('administrator')) {
                wp_redirect(home_url());
                exit;
            }

            $page_template = WPDocsify_DIR . '/src/template-wp-docsify.php';
        }

        return $page_template;
    }
}
