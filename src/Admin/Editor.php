<?php

namespace WPDocsify\Admin;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Editor {

    private FileManager $file_manager;
    private string $hook = '';

    public function __construct() {
        $this->file_manager = new FileManager();
    }

    public function setHook( string $hook ): void {
        $this->hook = $hook;
    }

    public function run(): void {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
        $this->registerAjax();
    }

    public function enqueueAssets( string $hook ): void {
        if ( $this->hook && $hook !== $this->hook ) {
            return;
        }

        $screen = get_current_screen();
        if ( ! $screen || strpos( $screen->id, 'wp-docsify-editor' ) === false ) {
            return;
        }

        wp_enqueue_style( 'easymde', 'https://unpkg.com/easymde/dist/easymde.min.css', [], null );
        wp_enqueue_style( 'wp-docsify-admin-editor', WPDOCSIFY_URL . 'src/assets/admin-editor.css', [ 'easymde' ], WPDOCSIFY_VERSION );

        wp_enqueue_script( 'easymde', 'https://unpkg.com/easymde/dist/easymde.min.js', [], null, true );
        wp_enqueue_script( 'wp-docsify-admin-editor', WPDOCSIFY_URL . 'src/assets/admin-editor.js', [ 'easymde' ], WPDOCSIFY_VERSION, true );

        wp_localize_script( 'wp-docsify-admin-editor', 'wpDocsifyEditor', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wp_docsify_editor' ),
            'i18n'    => [
                'newFileName'    => __( 'File name (e.g. guide.md):', 'wp-docsify' ),
                'newFolderName'  => __( 'Folder name:', 'wp-docsify' ),
                'confirmDelete'  => __( 'Delete this item? This cannot be undone.', 'wp-docsify' ),
                'saved'          => __( 'Saved!', 'wp-docsify' ),
                'saveError'      => __( 'Error saving file.', 'wp-docsify' ),
                'unsavedChanges' => __( 'You have unsaved changes. Discard them?', 'wp-docsify' ),
                'renamePrompt'   => __( 'New name:', 'wp-docsify' ),
                'selectFile'     => __( 'Select a file from the tree to start editing.', 'wp-docsify' ),
                'noFiles'        => __( 'No documentation files found.', 'wp-docsify' ),
                'onlyMd'         => __( 'Only .md files are supported.', 'wp-docsify' ),
            ],
        ] );
    }

    private function registerAjax(): void {
        $actions = [
            'wp_docsify_get_tree',
            'wp_docsify_get_file',
            'wp_docsify_save_file',
            'wp_docsify_create_file',
            'wp_docsify_delete_file',
            'wp_docsify_create_folder',
            'wp_docsify_delete_folder',
            'wp_docsify_rename_item',
        ];

        foreach ( $actions as $action ) {
            add_action( 'wp_ajax_' . $action, [ $this, 'handleAjax' ] );
        }
    }

    public function handleAjax(): void {
        check_ajax_referer( 'wp_docsify_editor', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'wp-docsify' ) ], 403 );
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above
        $action = sanitize_key( $_POST['action'] ?? '' );

        switch ( $action ) {
            case 'wp_docsify_get_tree':
                wp_send_json_success( $this->file_manager->getTree() );
                break;

            case 'wp_docsify_get_file':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path    = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                $content = $this->file_manager->readFile( $path );
                if ( $content === false ) {
                    wp_send_json_error( [ 'message' => __( 'File not found.', 'wp-docsify' ) ] );
                }
                wp_send_json_success( [ 'content' => $content ] );
                break;

            case 'wp_docsify_save_file':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path    = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- markdown content
                $content = wp_unslash( $_POST['content'] ?? '' );
                if ( $this->file_manager->saveFile( $path, $content ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not save file.', 'wp-docsify' ) ] );
                }
                break;

            case 'wp_docsify_create_file':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                if ( $this->file_manager->createFile( $path ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not create file. Check that the name ends in .md.', 'wp-docsify' ) ] );
                }
                break;

            case 'wp_docsify_delete_file':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                if ( $this->file_manager->deleteFile( $path ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not delete file.', 'wp-docsify' ) ] );
                }
                break;

            case 'wp_docsify_create_folder':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                if ( $this->file_manager->createFolder( $path ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not create folder.', 'wp-docsify' ) ] );
                }
                break;

            case 'wp_docsify_delete_folder':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                if ( $this->file_manager->deleteFolder( $path ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not delete folder.', 'wp-docsify' ) ] );
                }
                break;

            case 'wp_docsify_rename_item':
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $path     = sanitize_text_field( wp_unslash( $_POST['path'] ?? '' ) );
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $new_name = sanitize_file_name( wp_unslash( $_POST['new_name'] ?? '' ) );
                if ( $this->file_manager->renameItem( $path, $new_name ) ) {
                    wp_send_json_success();
                } else {
                    wp_send_json_error( [ 'message' => __( 'Could not rename item.', 'wp-docsify' ) ] );
                }
                break;

            default:
                wp_send_json_error( [ 'message' => __( 'Unknown action.', 'wp-docsify' ) ], 400 );
        }
    }

    public function renderPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap wp-docsify-editor-wrap">
            <div class="wp-docsify-editor-layout">

                <aside class="wp-docsify-filetree">
                    <div class="wp-docsify-filetree__header">
                        <strong><?php esc_html_e( 'Files', 'wp-docsify' ); ?></strong>
                        <div class="wp-docsify-filetree__btns">
                            <button id="btn-new-file" class="button button-small" title="<?php esc_attr_e( 'New File', 'wp-docsify' ); ?>">
                                + <?php esc_html_e( 'File', 'wp-docsify' ); ?>
                            </button>
                            <button id="btn-new-folder" class="button button-small" title="<?php esc_attr_e( 'New Folder', 'wp-docsify' ); ?>">
                                + <?php esc_html_e( 'Folder', 'wp-docsify' ); ?>
                            </button>
                        </div>
                    </div>
                    <div id="wp-docsify-tree" class="wp-docsify-filetree__tree"></div>
                </aside>

                <div class="wp-docsify-editor-pane">
                    <div class="wp-docsify-editor-pane__bar">
                        <span id="wp-docsify-current-file" class="wp-docsify-editor-pane__filename"></span>
                        <div class="wp-docsify-editor-pane__bar-right">
                            <span id="wp-docsify-save-status" class="wp-docsify-save-status" aria-live="polite"></span>
                            <button id="btn-save" class="button button-primary" disabled>
                                <?php esc_html_e( 'Save', 'wp-docsify' ); ?> <kbd>Ctrl+S</kbd>
                            </button>
                        </div>
                    </div>
                    <div id="wp-docsify-editor-container" class="wp-docsify-editor-container">
                        <div class="wp-docsify-editor-placeholder">
                            <p><?php esc_html_e( 'Select a file from the tree to start editing.', 'wp-docsify' ); ?></p>
                        </div>
                        <textarea id="wp-docsify-editor" style="display:none;"></textarea>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}
