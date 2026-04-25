<?php

namespace WPDocsify;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Core {

    public function run(): void {
        $this->initTextDomain();
        $this->initLanguage();

        if ( is_admin() ) {
            $admin = new Admin();
            $admin->run();
        }

        $template = new Template();
        $template->run();
    }

    private function initTextDomain(): void {
        load_plugin_textdomain( 'wp-docsify', false, basename( WPDOCSIFY_DIR ) . '/languages' );
    }

    private function initLanguage(): void {
        if ( is_array( WPDOCSIFY_SUPPORTED_LANGUAGES ) &&
            in_array( get_locale(), WPDOCSIFY_SUPPORTED_LANGUAGES, true ) ) {
            define( 'WPDOCSIFY_DEFINE_LANGUAGES', get_locale() );
            return;
        }

        define( 'WPDOCSIFY_DEFINE_LANGUAGES', 'en_US' );
    }
}
