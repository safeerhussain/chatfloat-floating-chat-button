<?php
/*
Plugin Name: ChatFloat – Floating Chat Button
Description: A simple and lightweight plugin to add a floating WhatsApp button on your website. Fully customizable via admin settings. Let your visitors chat instantly.
Version: 1.2.1
Author: Digital Eggheads
Author URI: https://digitaleggheads.com
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: chatfloat-floating-chat-button
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue frontend styles
function chatfloat_enqueue_styles() {
    if (!is_admin()) {
        wp_enqueue_style(
            'chatfloat-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            '1.1.11'
        );
    }
    // Admin styles (only on plugin settings page if needed)
    if (is_admin()) {
        wp_enqueue_style(
            'chatfloat-admin-style',
            plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
            [],
            '1.1.1'
        );     
      
        // Enqueue WordPress color picker scripts and styles
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_add_inline_script('wp-color-picker', 'jQuery(document).ready(function($) { $(".my-color-field").wpColorPicker(); });');

        wp_enqueue_script('chatfloat-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin-js.js', array('jquery'), null, true);
 
    }
    
}

add_action('admin_enqueue_scripts', 'chatfloat_enqueue_styles');
add_action('wp_enqueue_scripts', 'chatfloat_enqueue_styles');


// Add admin menu for plugin settings
function chatfloat_add_admin_menu() {
    add_menu_page(
        __('Chat Float - Settings', 'chatfloat-floating-chat-button'), // Page title
        __('Chat Float', 'chatfloat-floating-chat-button'),          // Menu title
        'manage_options',                           // Capability
        'chatfloat-settings',                       // Menu slug
        'chatfloat_settings_page',                  // Callback function to render page
        'dashicons-whatsapp',                       // Icon
        25                                          // Menu position
    );
}

add_action('admin_menu', 'chatfloat_add_admin_menu');

// Add settings link on the Plugins list page
function chatfloat_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=chatfloat-settings">' . __('Settings', 'chatfloat-floating-chat-button') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'chatfloat_add_settings_link');


// Render plugin settings page
function chatfloat_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Chat Float - Settings', 'chatfloat-floating-chat-button'); ?></h1>
        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) : ?>
            <div class="updated notice is-dismissible">
                <p><strong>Settings saved successfully!</strong></p>
            </div>
        <?php endif; ?>
        <hr>

        <div class="settings-container">
            <!-- Main content -->
            <div class="settings-main">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('chatfloat_settings_group');
                    do_settings_sections('chatfloat-settings');

                    submit_button();
                    ?>
                </form>
            </div>

            <!-- Sticky Sidebar -->
            <div class="settings-sidebar">
              <div class="sidebar-inner">
                <div class="postbox">
                  <div class="inside">
                    <h2>🚀 About This Plugin</h2>
                    <p>ChatFloat is developed as an in-house project by <strong>Team Digital Eggheads</strong>.</p>

                    <hr>

                    <h3>🎯 What It Does</h3>
                    <p>A simple and lightweight plugin to add a floating WhatsApp button on your website. Fully customizable via admin settings. Let your visitors chat instantly!</p>

                    <hr>

                    <h3>🤝 Need Support?</h3>
                    <p>Comments, suggestions, feedback? Shoot an email:
                        <br> 
                        <a href="mailto:hello@digitaleggheads.com" style="text-decoration:none;">
                            📧 hello@digitaleggheads.com
                        </a>
                    </p>

                    <hr>

                    <h3>✨ About Digital Eggheads</h3>
                    <p>A futuristic digital marketing agency, helping you take your business to new heights of success with multi-channel services that add brilliance to your digital presence.
                        <br> 
                        <a href="https://digitaleggheads.com" target="_blank" style="text-decoration:none;">
                            🌐 Visit our website
                        </a>
                    </p>

                    <div style="margin-top:20px; font-size:12px; color:#777;">
                        Made with ❤️ by Digital Eggheads
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>


    <?php
}

// Register settings and fields
function chatfloat_register_settings() {
    // Sanitize WhatsApp number
    register_setting('chatfloat_settings_group', 'chatfloat_number', 'sanitize_text_field');

    // Sanitize button text
    register_setting('chatfloat_settings_group', 'chatfloat_text', 'sanitize_text_field');

    // Sanitize button postion
    register_setting('chatfloat_settings_group', 'chatfloat_position', 'sanitize_text_field');

    // Sanitize desktop/mobile visibility toggle  
    register_setting('chatfloat_settings_group', 'chatfloat_display_desktop', 'sanitize_text_field');
    register_setting('chatfloat_settings_group', 'chatfloat_display_mobile', 'sanitize_text_field');

    // Register BG color setting
    register_setting('chatfloat_settings_group', 'chatfloat_bg_color', 'sanitize_hex_color');

    // Register text color setting
    register_setting('chatfloat_settings_group', 'chatfloat_text_color', 'sanitize_hex_color');

    // Register pre filled setting message
    register_setting('chatfloat_settings_group', 'chatfloat_prefill_message', 'sanitize_text_field');

    // Register dark mode
    register_setting('chatfloat_settings_group', 'chatfloat_darkmode', 'sanitize_text_field');

    // Register margins for button
    register_setting('chatfloat_settings_group', 'chatfloat_top_margin', 'intval');
    register_setting('chatfloat_settings_group', 'chatfloat_bottom_margin', 'intval');
    register_setting('chatfloat_settings_group', 'chatfloat_horizontal_margin', 'intval');


    add_settings_section(
        'chatfloat_main_section',
        __('WhatsApp Settings', 'chatfloat-floating-chat-button'),
        'chatfloat_section_callback',
        'chatfloat-settings'
    );

    add_settings_field(
        'chatfloat_number_field',
        __('Enter Your WhatsApp Number:', 'chatfloat-floating-chat-button'),
        'chatfloat_number_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    add_settings_field(
        'chatfloat_text_field',
        __('Enter Button Text:', 'chatfloat-floating-chat-button'),
        'chatfloat_text_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    add_settings_field(
        'chatfloat_prefill_message',
        'Predefined WhatsApp Message',
        'chatfloat_prefill_message_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    add_settings_field(
        'chatfloat_position_field',
        __('Button Position:', 'chatfloat-floating-chat-button'),
        'chatfloat_position_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    add_settings_field(
        'chatfloat_display_field',
        __('Control Visibility:', 'chatfloat-floating-chat-button'),
        'chatfloat_display_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    add_settings_field(
        'chatfloat_darkmode_field',
        __('Dark Mode (beta):', 'chatfloat-floating-chat-button'),
        'chatfloat_darkmode_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    // Add BG color picker field to settings page
    add_settings_field(
        'chatfloat_bg_color_field',
        __('Set Background Color:', 'chatfloat-floating-chat-button'),
        'chatfloat_bg_color_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );

    // Add text color picker field to settings page
    add_settings_field(
        'chatfloat_text_color_field',
        __('Set Text Color:', 'chatfloat-floating-chat-button'),
        'chatfloat_text_color_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );
    add_settings_field(
        'chatfloat_margin_field',
        __('⚠️ Update Button Margins (dev.):', 'chatfloat-floating-chat-button'),
        'chatfloat_margin_field_callback',
        'chatfloat-settings',
        'chatfloat_main_section'
    );
}
add_action('admin_init', 'chatfloat_register_settings');




// Settings section intro text
function e() {
    echo '<p>' . esc_html_e('Configure your WhatsApp button settings below.', 'chatfloat-floating-chat-button') . '</p>';
}

// WhatsApp number input field
function chatfloat_number_field_callback() {
    $number = get_option('chatfloat_number');
    echo '<input type="text" name="chatfloat_number" value="' . esc_attr($number) . '" placeholder="+15485178490">';
}

// Button text input field
function chatfloat_text_field_callback() {
    $text = get_option('chatfloat_text');
    echo '<input type="text" name="chatfloat_text" value="' . esc_attr($text) . '" placeholder="' . esc_attr__('Chat with us', 'chatfloat-floating-chat-button') . '">';
    echo '<p class="description">This is the label text which will be displayed with WhatsApp icon. Recommended to keep it short.</p>';
}

function chatfloat_prefill_message_callback() {
    $message = get_option('chatfloat_prefill_message', '');
    echo '<textarea name="chatfloat_prefill_message" rows="3" cols="50" placeholder="e.g. Hello, I\'m interested in your services...">' . esc_textarea($message) . '</textarea>';
    echo '<p class="description">This message will be pre-filled when users open the WhatsApp chat.</p>';
}

// Hex color picker for text background
function chatfloat_bg_color_field_callback() {
    $bg_color = get_option('chatfloat_bg_color', '#000000'); // Default to black
    echo '<input type="color" name="chatfloat_bg_color" value="' . esc_attr($bg_color) . '" />';
}

// Hex color picker for text
function chatfloat_text_color_field_callback() {
    $bg_color = get_option('chatfloat_text_color', '#ffffff'); // Default to black
    echo '<input type="color" name="chatfloat_text_color" value="' . esc_attr($bg_color) . '" />';
}

// Button position radio field
function chatfloat_position_field_callback() {
    $position = get_option('chatfloat_position', 'right'); // Default to right
    ?>
    <label>
        <input type="radio" name="chatfloat_position" value="right" <?php checked('right', $position); ?> />
        <?php esc_html_e('Show on right', 'chatfloat-floating-chat-button'); ?>
    </label><br>
    <label>
        <input type="radio" name="chatfloat_position" value="left" <?php checked('left', $position); ?> />
        <?php esc_html_e('Show on left', 'chatfloat-floating-chat-button'); ?>
    </label>
    <?php
}

// Toggle visibility control
function chatfloat_display_field_callback() {
    $display_desktop = get_option('chatfloat_display_desktop', 'yes'); // Default to yes
    $display_mobile = get_option('chatfloat_display_mobile', 'yes');   // Default to yes
    ?>

    <label>
        <input type="checkbox" name="chatfloat_display_desktop" value="yes" <?php checked($display_desktop, 'yes'); ?>>
        <span>Display on desktop</span>
    </label>
    <br>
    <label>
        <input type="checkbox" name="chatfloat_display_mobile" value="yes" <?php checked($display_mobile, 'yes'); ?>>
        <span>Display on mobile</span>
    </label>
    <p class="description">Uncheck the box when you want to hide the button on specific devices.</p>
   
    <?php
}


// Dark mode option
function chatfloat_darkmode_field_callback() {
    $dark_mode = get_option('chatfloat_darkmode', 'no'); // Default to no
    ?>

    <label>
        <input type="checkbox" name="chatfloat_darkmode" value="yes" <?php checked($dark_mode, 'yes'); ?>>
        <span>Enable Dark Mode (beta)</span>
    </label>
    <p class="description">Dark mode functionality is currently in beta. May not work on all themes.</p>
   
    <?php
}

function chatfloat_margin_field_callback() {
    // Get margin values
    $top_margin = get_option('chatfloat_top_margin', 20); // Default to 20px
    $bottom_margin = get_option('chatfloat_bottom_margin', 20); // Default to 20px
    $horizontal_margin = get_option('chatfloat_horizontal_margin', 20); // Default to 20px

    echo '<input type="number" size="4" name="chatfloat_top_margin" value="' . esc_attr($top_margin) . '" placeholder="' . esc_attr__('20', 'chatfloat-floating-chat-button') . '">';
    echo '<p class="description">Set/update <strong>top margin</strong> of ChatFloat WhatsApp Button</p>';
    echo '<input type="number" size="4" name="chatfloat_bottom_margin" value="' . esc_attr($bottom_margin) . '" placeholder="' . esc_attr__('20', 'chatfloat-floating-chat-button') . '">';
    echo '<p class="description">Set/update <strong>bottom margin</strong> of ChatFloat WhatsApp Button</p>';
    echo '<input type="number" size="4" name="chatfloat_horizontal_margin" value="' . esc_attr($horizontal_margin) . '" placeholder="' . esc_attr__('20', 'chatfloat-floating-chat-button') . '">';
    echo '<p class="description">Set/update <strong>side margin</strong> of ChatFloat WhatsApp Button</p>';

}



// Main plugin file (e.g., functions.php or plugin main file)

function chatfloat_render_button() {

    include plugin_dir_path(__FILE__) . '/assets/functions/chatfloat-button.php';    
    chatfloat_render_button_html();
}

// Hook the function to render button in the footer
add_action('wp_footer', 'chatfloat_render_button');



?>
