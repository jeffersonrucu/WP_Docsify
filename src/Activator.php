<?php

namespace WPDocsify;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Activator {

    public static function activate(): void {
        self::setDefaultOptions();
        self::copySampleDocs();
    }

    private static function setDefaultOptions(): void {
        if ( false === get_option( 'wp_docsify_options' ) ) {
            add_option( 'wp_docsify_options', [
                'is_restricted' => WPDOCSIFY_DEFAULT_IS_RESTRICTED,
                'allowed_roles' => WPDOCSIFY_DEFAULT_ALLOWED_ROLES,
                'theme_color'   => WPDOCSIFY_DEFAULT_THEME_COLOR,
                'repo_url'      => '',
            ] );
        }
    }

    private static function copySampleDocs(): void {
        $uploads  = wp_upload_dir();
        $dest_dir = $uploads['basedir'] . '/wp-docsify';

        if ( ! file_exists( $dest_dir ) ) {
            wp_mkdir_p( $dest_dir );
        }

        $src_docs = WPDOCSIFY_DIR . 'src/docs';

        foreach ( WPDOCSIFY_SUPPORTED_LANGUAGES as $locale ) {
            $src  = $src_docs . '/' . $locale;
            $dest = $dest_dir . '/' . $locale;

            if ( is_dir( $src ) && ! file_exists( $dest ) ) {
                self::copyDir( $src, $dest );
            }
        }
    }

    private static function copyDir( string $src, string $dest ): void {
        wp_mkdir_p( $dest );

        $items = scandir( $src );
        if ( ! $items ) {
            return;
        }

        foreach ( $items as $item ) {
            if ( $item === '.' || $item === '..' ) {
                continue;
            }

            $s = $src . '/' . $item;
            $d = $dest . '/' . $item;

            if ( is_dir( $s ) ) {
                self::copyDir( $s, $d );
            } else {
                copy( $s, $d );
            }
        }
    }
}
