<?php

namespace WPDocsify\Admin;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class FileManager {

    private string $base_dir;

    public function __construct() {
        $uploads        = wp_upload_dir();
        $this->base_dir = trailingslashit( $uploads['basedir'] ) . 'wp-docsify';
    }

    public function getBaseDir(): string {
        return $this->base_dir;
    }

    public function getTree(): array {
        if ( ! is_dir( $this->base_dir ) ) {
            return [];
        }
        return $this->buildTree( $this->base_dir, '' );
    }

    private function buildTree( string $dir, string $relative ): array {
        $entries = scandir( $dir );
        if ( ! $entries ) {
            return [];
        }

        $items = [];

        foreach ( $entries as $entry ) {
            if ( $entry === '.' || $entry === '..' ) {
                continue;
            }

            $full_path = $dir . '/' . $entry;
            $rel_path  = $relative ? $relative . '/' . $entry : $entry;

            if ( is_dir( $full_path ) ) {
                $items[] = [
                    'name'     => $entry,
                    'type'     => 'folder',
                    'path'     => $rel_path,
                    'children' => $this->buildTree( $full_path, $rel_path ),
                ];
            } elseif ( pathinfo( $entry, PATHINFO_EXTENSION ) === 'md' ) {
                $items[] = [
                    'name' => $entry,
                    'type' => 'file',
                    'path' => $rel_path,
                ];
            }
        }

        usort( $items, function ( $a, $b ) {
            if ( $a['type'] !== $b['type'] ) {
                return $a['type'] === 'folder' ? -1 : 1;
            }
            return strcmp( $a['name'], $b['name'] );
        } );

        return $items;
    }

    /**
     * @return string|false
     */
    public function readFile( string $relative_path ) {
        $full = $this->resolvePath( $relative_path );
        if ( ! $full || ! is_file( $full ) ) {
            return false;
        }
        return file_get_contents( $full );
    }

    public function saveFile( string $relative_path, string $content ): bool {
        if ( ! $this->hasExtension( $relative_path, 'md' ) ) {
            return false;
        }
        $full = $this->resolvePath( $relative_path );
        if ( ! $full ) {
            return false;
        }

        $dir = dirname( $full );
        if ( ! is_dir( $dir ) ) {
            wp_mkdir_p( $dir );
        }

        return file_put_contents( $full, $content ) !== false;
    }

    public function createFile( string $relative_path ): bool {
        if ( ! $this->hasExtension( $relative_path, 'md' ) ) {
            return false;
        }
        $full = $this->resolvePath( $relative_path );
        if ( ! $full || file_exists( $full ) ) {
            return false;
        }

        $dir = dirname( $full );
        if ( ! is_dir( $dir ) ) {
            wp_mkdir_p( $dir );
        }

        return file_put_contents( $full, '' ) !== false;
    }

    public function deleteFile( string $relative_path ): bool {
        $full = $this->resolvePath( $relative_path );
        if ( ! $full || ! is_file( $full ) ) {
            return false;
        }
        return unlink( $full );
    }

    public function createFolder( string $relative_path ): bool {
        $full = $this->resolvePath( $relative_path );
        if ( ! $full || file_exists( $full ) ) {
            return false;
        }
        return wp_mkdir_p( $full );
    }

    public function deleteFolder( string $relative_path ): bool {
        $full = $this->resolvePath( $relative_path );
        if ( ! $full || ! is_dir( $full ) ) {
            return false;
        }
        return $this->removeDir( $full );
    }

    public function renameItem( string $old_path, string $new_name ): bool {
        $old_full = $this->resolvePath( $old_path );
        if ( ! $old_full || ! file_exists( $old_full ) ) {
            return false;
        }

        $new_rel  = dirname( $old_path ) . '/' . $new_name;
        $new_full = $this->resolvePath( $new_rel );
        if ( ! $new_full || file_exists( $new_full ) ) {
            return false;
        }

        return rename( $old_full, $new_full );
    }

    /**
     * Validates and resolves a relative path against the base directory.
     * Prevents directory traversal attacks.
     *
     * @return string|false
     */
    private function resolvePath( string $relative_path ) {
        $relative_path = ltrim( $relative_path, '/\\' );

        if ( preg_match( '/\.\./', $relative_path ) ) {
            return false;
        }

        $full = $this->base_dir . '/' . $relative_path;

        if ( file_exists( $full ) ) {
            $real = realpath( $full );
            $base = realpath( $this->base_dir );

            if ( $real === false || $base === false ) {
                return false;
            }
            if ( strpos( $real, $base . DIRECTORY_SEPARATOR ) !== 0 && $real !== $base ) {
                return false;
            }
            return $real;
        }

        $parent      = dirname( $full );
        $real_parent = file_exists( $parent ) ? realpath( $parent ) : false;
        $base        = realpath( $this->base_dir );

        if ( $real_parent && $base ) {
            if ( strpos( $real_parent, $base ) !== 0 ) {
                return false;
            }
        }

        return $full;
    }

    private function hasExtension( string $path, string $ext ): bool {
        return strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ) === $ext;
    }

    private function removeDir( string $dir ): bool {
        $items = scandir( $dir );
        if ( ! $items ) {
            return false;
        }

        foreach ( $items as $item ) {
            if ( $item === '.' || $item === '..' ) {
                continue;
            }
            $path = $dir . '/' . $item;
            if ( is_dir( $path ) ) {
                $this->removeDir( $path );
            } else {
                unlink( $path );
            }
        }

        return rmdir( $dir );
    }
}
