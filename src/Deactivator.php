<?php

namespace WPDocsify;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Deactivator {

    public static function deactivate(): void {
        // Options and docs are preserved on deactivation.
    }
}
