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
        <form method="post" action="options.php">
            <?php
            settings_fields('chatfloat_settings_group');
            do_settings_sections('chatfloat-settings');
            submit_button();
            ?>
        </form>
    </div>

    <div class="wrap">
  <h1>Plugin Settings</h1>

  <!-- Tabs Navigation -->
  <h2 class="nav-tab-wrapper">
    <a href="#general" class="nav-tab nav-tab-active">General</a>
    <a href="#advanced" class="nav-tab">Advanced</a>
  </h2>

  <!-- Settings Form -->
  <form method="post" action="options.php">
    <?php settings_fields('your_plugin_options_group'); ?>
    <?php do_settings_sections('your_plugin_slug'); ?>

    <!-- General Section -->
    <div id="general" class="tab-content active">

      <h2>General Settings</h2>
      
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="setting_text">Text Field</label></th>
          <td><input type="text" id="setting_text" name="setting_text" value="" class="regular-text"></td>
        </tr>

        <tr valign="top">
          <th scope="row"><label for="setting_checkbox">Checkbox</label></th>
          <td><input type="checkbox" id="setting_checkbox" name="setting_checkbox" value="1"> Enable feature</td>
        </tr>

        <tr valign="top">
          <th scope="row"><label for="setting_select">Select Option</label></th>
          <td>
            <select id="setting_select" name="setting_select">
              <option value="option1">Option 1</option>
              <option value="option2">Option 2</option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><label for="setting_textarea">Textarea</label></th>
          <td><textarea id="setting_textarea" name="setting_textarea" rows="5" cols="50"></textarea></td>
        </tr>

        <tr valign="top">
          <th scope="row">Toggle Switch</th>
          <td>
            <label class="switch">
              <input type="checkbox" name="setting_toggle">
              <span class="slider round"></span>
            </label>
          </td>
        </tr>

      </table>

      <?php submit_button(); ?>
    </div>

    <!-- Advanced Section -->
    <div id="advanced" class="tab-content" style="display:none;">
      <h2>Advanced Settings</h2>

      <!-- Accordions -->
      <div class="accordion">
        <h3>Accordion Title 1</h3>
        <div class="accordion-content">
          <p>Content inside accordion 1.</p>
        </div>

        <h3>Accordion Title 2</h3>
        <div class="accordion-content">
          <p>Content inside accordion 2.</p>
        </div>
      </div>

    </div>

  </form>
</div>

<!-- Simple Styles and Scripts to Demo Tabs, Accordion, and Toggle -->
<style>
  .tab-content { margin-top: 20px; }
  .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
  .switch input { display: none; }
  .slider { position: absolute; cursor: pointer; background-color: #ccc; border-radius: 24px; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; }
  .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
  input:checked + .slider { background-color: #0073aa; }
  input:checked + .slider:before { transform: translateX(26px); }
  
  .accordion h3 { cursor: pointer; background: #f1f1f1; padding: 10px; margin: 0; }
  .accordion-content { display: none; padding: 10px; background: #fff; border: 1px solid #ddd; }
</style>

<script>
  // Tabs
  document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
      this.classList.add('nav-tab-active');

      document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
      document.querySelector(this.getAttribute('href')).style.display = 'block';
    });
  });

  // Accordions
  document.querySelectorAll('.accordion h3').forEach(h3 => {
    h3.addEventListener('click', function() {
      this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block';
    });
  });
</script>


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
function chatfloat_section_callback() {
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
