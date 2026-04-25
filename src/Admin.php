<?php

namespace WPDocsify;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Admin {

    public function run(): void {
        add_action( 'admin_menu', [ $this, 'addMenu' ] );
        add_action( 'admin_init', [ $this, 'registerSettings' ] );
    }

    public function addMenu(): void {
        add_options_page(
            __( 'WP Docsify Settings', 'wp-docsify' ),
            __( 'WP Docsify', 'wp-docsify' ),
            'manage_options',
            'wp-docsify',
            [ $this, 'renderPage' ]
        );
    }

    public function registerSettings(): void {
        register_setting(
            'wp_docsify_settings',
            'wp_docsify_options',
            [ 'sanitize_callback' => [ $this, 'sanitizeOptions' ] ]
        );

        add_settings_section( 'wp_docsify_access', __( 'Access Control', 'wp-docsify' ), null, 'wp-docsify' );

        add_settings_field(
            'is_restricted',
            __( 'Enable Restriction', 'wp-docsify' ),
            [ $this, 'renderIsRestricted' ],
            'wp-docsify',
            'wp_docsify_access'
        );

        add_settings_field(
            'allowed_roles',
            __( 'Allowed Roles', 'wp-docsify' ),
            [ $this, 'renderAllowedRoles' ],
            'wp-docsify',
            'wp_docsify_access'
        );

        add_settings_section( 'wp_docsify_appearance', __( 'Appearance', 'wp-docsify' ), null, 'wp-docsify' );

        add_settings_field(
            'theme_color',
            __( 'Theme Color', 'wp-docsify' ),
            [ $this, 'renderThemeColor' ],
            'wp-docsify',
            'wp_docsify_appearance'
        );

        add_settings_field(
            'repo_url',
            __( 'Repository URL', 'wp-docsify' ),
            [ $this, 'renderRepoUrl' ],
            'wp-docsify',
            'wp_docsify_appearance'
        );
    }

    /**
     * @param mixed $input
     */
    public function sanitizeOptions( $input ): array {
        $output = [];

        $output['is_restricted'] = ! empty( $input['is_restricted'] );

        $valid_roles = array_keys( wp_roles()->roles );
        if ( isset( $input['allowed_roles'] ) && is_array( $input['allowed_roles'] ) ) {
            $output['allowed_roles'] = array_values(
                array_intersect( $input['allowed_roles'], $valid_roles )
            );
        } else {
            $output['allowed_roles'] = [];
        }

        $color                = sanitize_hex_color( $input['theme_color'] ?? '' );
        $output['theme_color'] = $color ?: WPDOCSIFY_DEFAULT_THEME_COLOR;

        $output['repo_url'] = esc_url_raw( $input['repo_url'] ?? '' );

        return $output;
    }

    public function renderIsRestricted(): void {
        $options = get_option( 'wp_docsify_options', [] );
        $checked = isset( $options['is_restricted'] ) ? $options['is_restricted'] : WPDOCSIFY_DEFAULT_IS_RESTRICTED;
        ?>
        <label>
            <input type="checkbox" name="wp_docsify_options[is_restricted]" value="1" <?php checked( $checked ); ?>>
            <?php esc_html_e( 'Restrict access to logged-in users with specific roles', 'wp-docsify' ); ?>
        </label>
        <?php
    }

    public function renderAllowedRoles(): void {
        $options = get_option( 'wp_docsify_options', [] );
        $allowed = $options['allowed_roles'] ?? WPDOCSIFY_DEFAULT_ALLOWED_ROLES;
        $roles   = wp_roles()->roles;

        foreach ( $roles as $role_key => $role_data ) {
            $checked = in_array( $role_key, $allowed, true );
            ?>
            <label style="display:block;margin-bottom:5px;">
                <input type="checkbox"
                       name="wp_docsify_options[allowed_roles][]"
                       value="<?php echo esc_attr( $role_key ); ?>"
                    <?php checked( $checked ); ?>>
                <?php echo esc_html( translate_user_role( $role_data['name'] ) ); ?>
            </label>
            <?php
        }

        echo '<p class="description">' . esc_html__( 'Select which roles can view the documentation.', 'wp-docsify' ) . '</p>';
    }

    public function renderThemeColor(): void {
        $options = get_option( 'wp_docsify_options', [] );
        $color   = $options['theme_color'] ?? WPDOCSIFY_DEFAULT_THEME_COLOR;
        ?>
        <input type="color"
               name="wp_docsify_options[theme_color]"
               value="<?php echo esc_attr( $color ); ?>">
        <p class="description"><?php esc_html_e( 'Accent color used by Docsify.', 'wp-docsify' ); ?></p>
        <?php
    }

    public function renderRepoUrl(): void {
        $options = get_option( 'wp_docsify_options', [] );
        $url     = $options['repo_url'] ?? '';
        ?>
        <input type="url"
               name="wp_docsify_options[repo_url]"
               value="<?php echo esc_attr( $url ); ?>"
               class="regular-text"
               placeholder="https://github.com/username/repo">
        <p class="description"><?php esc_html_e( 'Optional. Adds a GitHub link in the Docsify toolbar.', 'wp-docsify' ); ?></p>
        <?php
    }

    public function renderPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $uploads  = wp_upload_dir();
        $docs_dir = $uploads['basedir'] . '/wp-docsify';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'wp_docsify_settings' );
                do_settings_sections( 'wp-docsify' );
                submit_button();
                ?>
            </form>

            <hr>

            <h2><?php esc_html_e( 'Documentation Files', 'wp-docsify' ); ?></h2>
            <p>
                <?php
                printf(
                    /* translators: %s: file path */
                    esc_html__( 'Place your Markdown (.md) files inside: %s', 'wp-docsify' ),
                    '<code>' . esc_html( $docs_dir ) . '/{locale}/</code>'
                );
                ?>
            </p>
            <p>
                <?php esc_html_e( 'Supported locales: en_US, pt_BR. README.md is the home page.', 'wp-docsify' ); ?>
            </p>

            <h2><?php esc_html_e( 'How to Use', 'wp-docsify' ); ?></h2>
            <ol>
                <li><?php esc_html_e( 'Create a WordPress page.', 'wp-docsify' ); ?></li>
                <li>
                    <?php
                    printf(
                        /* translators: %s: template name */
                        esc_html__( 'In the Page Attributes panel, set the template to %s.', 'wp-docsify' ),
                        '<strong>WP Docsify</strong>'
                    );
                    ?>
                </li>
                <li><?php esc_html_e( 'Publish the page and visit it to see your documentation.', 'wp-docsify' ); ?></li>
            </ol>
        </div>
        <?php
    }
}
