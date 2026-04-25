=== WP Docsify ===
Contributors:      jeffersonrucu, studiostg
Tags:              documentation, docsify, markdown, docs, knowledge-base
Requires at least: 5.9
Tested up to:      6.7
Requires PHP:      7.4
Stable tag:        2.0.0
License:           GPL-2.0+
License URI:       https://www.gnu.org/licenses/gpl-2.0.txt

Integrate Docsify documentation into WordPress using a custom page template with role-based access control and multilingual support.

== Description ==

WP Docsify embeds the [Docsify](https://docsify.js.org) documentation generator into your WordPress site as a custom page template. It renders Markdown (.md) files stored in your uploads directory — no database required.

**Features**

* Custom page template — select "WP Docsify" on any WordPress page
* Role-based access control configured from the admin panel
* Multilingual support (en_US, pt_BR)
* Admin settings panel under Settings > WP Docsify
* Docsify plugins included: full-text search, pagination, copy code, collapsible sidebar, Mermaid diagrams (via ESM)
* Documentation files stored in `wp-content/uploads/wp-docsify/` — survives plugin updates
* Sample documentation copied to uploads on activation
* No Composer required — built-in PSR-4 autoloader

== Third-Party Services ==

This plugin loads scripts and stylesheets from the following external CDN services.
These resources are loaded **only on pages that use the "WP Docsify" template**.

* **jsDelivr** (https://cdn.jsdelivr.net) — Docsify core library, Vue CSS theme, search plugin, sidebar collapse plugin, D3, Mermaid ESM
  Privacy policy: https://www.jsdelivr.com/terms/privacy-policy-jsdelivr-net
* **unpkg** (https://unpkg.com) — docsify-pagination, docsify-copy-code, docsify-mermaid, docsify-mermaid-zoom
  Privacy policy: https://www.npmjs.com/policies/privacy

Review the privacy policies of these CDN services before using this plugin on sites subject to GDPR or similar regulations.

== Installation ==

1. Upload the `wp-docsify` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings > WP Docsify** to configure access control, theme color, and repository URL.
4. Create a WordPress page and set its template to **WP Docsify** in the Page Attributes panel.
5. Publish the page — sample documentation is already in `wp-content/uploads/wp-docsify/`.

== Frequently Asked Questions ==

= Where do I put my documentation files? =

After activation, sample docs are copied to `wp-content/uploads/wp-docsify/en_US/` and `wp-content/uploads/wp-docsify/pt_BR/`. Replace or extend those Markdown files. `README.md` is always the home page.

= How do I restrict access? =

Go to **Settings > WP Docsify**, enable **Enable Restriction**, and check the roles that should have access. Logged-out users are redirected to the WordPress login page automatically.

= Does this plugin require Composer? =

No. Version 2.0.0 replaced the Composer autoloader with a built-in PSR-4 loader. No `composer install` is needed.

= Can I use a custom theme? =

Yes. Replace the Docsify Vue theme URL in `src/templates/wp-docsify.php` with any other Docsify theme CDN URL, or enqueue your own stylesheet.

= Will my documentation survive a plugin update? =

Yes. Documentation files are stored in `wp-content/uploads/wp-docsify/`, which is never touched by plugin updates.

== Screenshots ==

1. WP Docsify documentation page rendered inside WordPress.
2. Access denied page shown to unauthorized users.
3. Admin settings panel under Settings > WP Docsify.

== Changelog ==

= 2.0.0 =
* Added admin settings page (access control, theme color, repository URL).
* Documentation files now stored in `wp-content/uploads/wp-docsify/` (survives plugin updates).
* Replaced Composer autoloader with built-in PSR-4 autoloader.
* Scripts and styles now registered via `wp_enqueue_script` / `wp_enqueue_style`.
* Added `wp_head()` and `wp_footer()` to all templates.
* Fixed text domain to match plugin slug (`wp-docsify`).
* Added `Requires at least`, `Requires PHP`, and `Domain Path` headers.
* Sample documentation copied to uploads directory on activation.
* Options cleaned up on plugin uninstall.
* Removed Google Fonts CDN from access-denied template (uses system fonts).
* Added direct-access guards to all PHP class files.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 2.0.0 =
Documentation files have moved from the plugin directory to `wp-content/uploads/wp-docsify/`. Sample files are copied there automatically on activation. If you had custom docs in the plugin folder, move them to the new uploads path.
