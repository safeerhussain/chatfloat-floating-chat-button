<?php
/*
Plugin Name: ChatFloat – Floating Chat Button
Description: A simple and lightweight plugin to add a floating WhatsApp button on your website. Fully customizable via admin settings. Let your visitors chat instantly.
Version: 1.1.1
Author: Digital Eggheads
Author URI: https://digitaleggheads.com
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: chatfloat-floating-chat-button
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue frontend styles
function chatfloat_enqueue_styles() {
    wp_enqueue_style(
        'chatfloat-style',
        plugin_dir_url(__FILE__) . 'assets/css/style.css',
        [],
        '1.1.3'
    );
}
add_action('wp_enqueue_scripts', 'chatfloat_enqueue_styles');

// Add admin menu for plugin settings
function chatfloat_add_admin_menu() {
    add_menu_page(
        __('Chat Float - Settings!', 'chatfloat-floating-chat-button'), // Page title
        __('Chat Float', 'chatfloat-floating-chat-button'),          // Menu title
        'manage_options',                           // Capability
        'chatfloat-settings',                       // Menu slug
        'chatfloat_settings_page',                  // Callback function to render page
        'dashicons-whatsapp',                       // Icon
        25                                          // Menu position
    );
}
add_action('admin_menu', 'chatfloat_add_admin_menu');

// Render plugin settings page
function chatfloat_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Chat Float - Settings', 'chatfloat-floating-chat-button'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('chatfloat_settings_group');
            do_settings_sections('chatfloat-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function chatfloat_register_settings() {
    // Sanitize WhatsApp number
    register_setting('chatfloat_settings_group', 'chatfloat_number', 'sanitize_text_field');

    // Sanitize button text
    register_setting('chatfloat_settings_group', 'chatfloat_text', 'sanitize_text_field');

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
}
add_action('admin_init', 'chatfloat_register_settings');



// Settings section intro text
function chatfloat_section_callback() {
    echo '<p>' . esc_html_e('Configure your WhatsApp button settings below.', 'chatfloat-floating-chat-button') . '</p>';
}

// WhatsApp number input field
function chatfloat_number_field_callback() {
    $number = get_option('chatfloat_number');
    echo '<input type="text" name="chatfloat_number" value="' . esc_attr($number) . '" placeholder="+1234567890">';
}

// Button text input field
function chatfloat_text_field_callback() {
    $text = get_option('chatfloat_text');
    echo '<input type="text" name="chatfloat_text" value="' . esc_attr($text) . '" placeholder="' . esc_attr__('Chat with us', 'chatfloat-floating-chat-button') . '">';
}

// Output floating button in footer
function chatfloat_render_button() {
    $number = get_option('chatfloat_number');
    $text = get_option('chatfloat_text');

    if (!$number) {
        return; // Do not show button if number is not set
    }

    // Create clean WhatsApp link
    $wa_link = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $number);
    $icon_url = plugin_dir_url(__FILE__) . 'assets/images/Whatsapp-Icon.svg';

   
    
    echo '<div class="chatfloat-container">
    <a href="' . esc_url($wa_link) . '" target="_blank" class="whatsapp-float-link">
        <div class="whatsapp-icon" aria-hidden="true"></div>
    </a>
    <a href="' . esc_url($wa_link) . '" target="_blank" class="chatfloat-text"><p><span>' . esc_html($text) . '</span></p></a>
</div>';


}
add_action('wp_footer', 'chatfloat_render_button');

?>
