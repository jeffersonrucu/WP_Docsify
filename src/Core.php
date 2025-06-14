<?php

namespace WPDocsify;

class Core
{
    /**
     * Initializes all instances of the project to prepare it for execution.
     */
    public function run(): void
    {
        $this->initTextDomain();
        $this->initLanguage();

        $template = new Template();
        $template->run();
    }

    /**
     * Loads the plugin's translation files based on the current locale.
     */
    private function initTextDomain(): void {
        load_plugin_textdomain('wpdocsify', false, basename(WPDOCSIFY_DIR) . '/languages');
    }

    /**
     * Sets the current language for the plugin based on supported locales.
     */
    private function initLanguage(): void
    {
        if (is_array(WPDOCSIFY_SUPPORTED_LANGUAGES)) {
            $is_support = in_array(get_locale(), WPDOCSIFY_SUPPORTED_LANGUAGES);

            if ($is_support) {
                define('WPDOCSIFY_DEFINE_LANGUAGES', get_locale());
                return;
            }
        }

        define('WPDOCSIFY_DEFINE_LANGUAGES', 'en_US');
    }
}
