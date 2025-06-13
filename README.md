# WP Docsify

**WP Docsify** is a WordPress plugin that allows you to create and manage documentation using [Docsify](https://docsify.js.org/), leveraging `.md` files directly within your project. It's ideal for technical projects, user manuals, or any kind of versioned technical documentation.

## 📁 Project Structure

```
wp-docsify/
├── src/
│   ├── docs/                    # Where Markdown (.md) files should be created
│   └── template-wp-docsify.php  # Template file responsible for rendering Docsify
```

## 🧩 Features

- Direct integration of Docsify into WordPress
- Reads `.md` files from the `src/docs/` directory
- Automatic rendering via a custom template
- Simple and database-independent

## 🛠️ Installation

1. Clone this repository or place the plugin in your WordPress plugins directory:

```bash
wp-content/plugins/wp-docsify/
```

2. Activate the plugin through the WordPress admin panel.

3. Create a page in WordPress and select the **"WP Docsify"** template (template-wp-docsify.php).

## ✍️ How to Use

1. Add your documentation `.md` files inside the directory:

```
wp-content/plugins/wp-docsify/src/docs/
```

2. The `README.md` file will be used as the documentation's home page.

3. Customize Docsify behavior (menus, themes, etc.) directly in the `template-wp-docsify.php` file.

## ✅ Example

```markdown
# Welcome to WP Docsify

This is the initial documentation.

## Installation

Follow the steps to install and configure the plugin.
```

---

Developed by Jefferson Oliveira using the https://docsify.js.org library 🧑‍💻
