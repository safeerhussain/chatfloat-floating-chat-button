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
        '1.1.7'
    );
    // Enqueue WordPress color picker scripts and styles
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_add_inline_script('wp-color-picker', 'jQuery(document).ready(function($) { $(".my-color-field").wpColorPicker(); });');

}

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

// Render plugin settings page
function chatfloat_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Chat Float - Settings', 'chatfloat-floating-chat-button'); ?></h1>
        <hr>
        <hr class="wp-header-end">

        <form method="post" action="options.php">
            <?php
            settings_fields('chatfloat_settings_group');
            do_settings_sections('chatfloat-settings');
            submit_button();
            ?>
        </form>
      
        <h2 class="title">General Settings</h2>
        
        


    
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

    // Register BG color setting
    register_setting('chatfloat_settings_group', 'chatfloat_bg_color', 'sanitize_hex_color');

    // Register text color setting
    register_setting('chatfloat_settings_group', 'chatfloat_text_color', 'sanitize_hex_color');

    // Register pre filled setting message
    register_setting('chatfloat_settings_group', 'chatfloat_prefill_message');

    register_setting('chatfloat_settings_group', 'chatfloat_hide_on_mobile');
    register_setting('chatfloat_settings_group', 'chatfloat_hide_on_desktop');


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


// Output floating button in footer
function chatfloat_render_button() {
    $number = get_option('chatfloat_number');
    $text = get_option('chatfloat_text');
    $prefill_msg = get_option('chatfloat_prefill_message', '');
    $position = get_option('chatfloat_position', 'right');
    $bg_color = get_option('chatfloat_bg_color', '#000000'); // Default to black
    $text_color = get_option('chatfloat_text_color', '#ffffff'); // Default to white



    if (!$number) {
        return; // Do not show button if number is not set
    }

    // Create clean WhatsApp link
    $wa_link = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $number);
    $icon_url = plugin_dir_url(__FILE__) . 'assets/images/Whatsapp-Icon.svg';

    $position_class = $position === 'left' ? 'position-left' : 'position-right';
    
     // Print out the custom CSS in the <head> to apply the background color dynamically
    echo '<style>
            .chatfloat-container .chatfloat-text span {
                background-color: ' . esc_attr($bg_color) . ';
                color: ' . esc_attr($text_color) . ';
            }
          </style>';


    if (!empty($prefill_msg)) {
        $wa_link .= '?text=' . rawurlencode($prefill_msg);
    }

   echo '<div class="chatfloat-container ' . esc_attr($position_class) . '">';
    if ($position === 'left') {
        echo '
            <a href="' . esc_url($wa_link) . '" target="_blank" class="whatsapp-float-link">
                <div class="whatsapp-icon" aria-hidden="true"></div>
            </a>
            <a href="' . esc_url($wa_link) . '" target="_blank" class="chatfloat-text"><p><span>' . esc_html($text) . '</span></p></a>
        </div>';
    } else {
        echo '
            <a href="' . esc_url($wa_link) . '" target="_blank" class="chatfloat-text"><p><span>' . esc_html($text) . '</span></p></a>
            <a href="' . esc_url($wa_link) . '" target="_blank" class="whatsapp-float-link">
                <div class="whatsapp-icon" aria-hidden="true"></div>
            </a>
        </div>';
    }

}
add_action('wp_footer', 'chatfloat_render_button');

?>
