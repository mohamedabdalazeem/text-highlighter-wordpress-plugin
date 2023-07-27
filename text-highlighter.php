<?php
/**
 * Plugin Name: Text Highlighter
 * Plugin URI: https://www.example.com/
 * Description: Allows users to save and highlight specific text within their WordPress website.
 * Version: 1.0
 * Author: Mohamed Abdalazeem
 * Author URI: https://github.com/mohamedabdalazeem
 */

// Enqueue the CSS styles
function text_highlighter_enqueue_styles() {
    wp_enqueue_style('text-highlighter-styles', plugin_dir_url(__FILE__) . 'css/text-highlighter-styles.css');
}
add_action('admin_enqueue_scripts', 'text_highlighter_enqueue_styles');

// Add an admin menu item for the plugin
function text_highlighter_menu_item() {
    add_submenu_page(
        'options-general.php',
        'Text Highlighter',
        'Text Highlighter',
        'manage_options',
        'text-highlighter',
        'text_highlighter_settings_page'
    );
}
add_action('admin_menu', 'text_highlighter_menu_item');

// Display the settings page in the WordPress admin area
function text_highlighter_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['text_highlighter_text'])) {
        $text = sanitize_text_field($_POST['text_highlighter_text']);
        update_option('text_highlighter_text', $text);
    }

    $text = get_option('text_highlighter_text', '');
    ?>

    <div class="wrap text-highlighter-wrap">
        <h1>Text Highlighter Settings</h1>

        <div class="plugin-description">
            <p>
                Text Highlighter is a plugin that allows you to save and highlight specific text within your WordPress website.
                Enter the desired text below, and it will be dynamically highlighted in the content of your website.
            </p>
            <p>
                Whenever the entered text is found within the content, it will be displayed in <strong>bold</strong> and have a yellow background.
            </p>
        </div>

        <form method="post" action="" class="text-highlighter-form">
            <label for="text_highlighter_text">Enter Text:</label>
            <input type="text" id="text_highlighter_text" name="text_highlighter_text" value="<?php echo esc_attr($text); ?>">
            <button class="button button-primary" type="submit">Save</button>
        </form>

        <div class="plugin-images text-highlighter-images">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'images/text-highlighter.png'; ?>" alt="Text Highlighter" class="plugin-image text-highlighter-image">
        </div>
    </div>

    <?php
}

// Modify the content to highlight matching text
function text_highlighter_modify_content($content) {
    $highlight_text = get_option('text_highlighter_text', '');

    if (!empty($highlight_text)) {
        $highlighted_content = preg_replace('/(' . preg_quote($highlight_text, '/') . ')/i', '<span style="background-color: yellow;"><strong>$1</strong></span>', $content);

        if ($highlighted_content) {
            return $highlighted_content;
        }
    }

    return $content;
}
add_filter('the_content', 'text_highlighter_modify_content');
