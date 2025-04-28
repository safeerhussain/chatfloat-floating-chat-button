<?php
// chatfloat-button.php

function chatfloat_render_button_html() {
    $number = get_option('chatfloat_number');
    $text = get_option('chatfloat_text');
    $prefill_msg = get_option('chatfloat_prefill_message', '');
    $position = get_option('chatfloat_position', 'right');
    $bg_color = get_option('chatfloat_bg_color', '#000000'); // Default to black
    $text_color = get_option('chatfloat_text_color', '#ffffff'); // Default to white
    $display_desktop = get_option('chatfloat_display_desktop', 'yes');
    $display_mobile = get_option('chatfloat_display_mobile', 'yes');
    $dark_mode = get_option('chatfloat_darkmode');
    $top_margin = get_option('chatfloat_top_margin', 20); // Default to 20px
    $bottom_margin = get_option('chatfloat_bottom_margin', 20); // Default to 20px
    $horizontal_margin = get_option('chatfloat_horizontal_margin', 20); // Default to 20px

    
    if (!$number) {
        return; // Do not show button if number is not set
    }

    // Create clean WhatsApp link
    $wa_link = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $number);
    $icon_url = plugin_dir_url(__FILE__) . 'assets/images/Whatsapp-Icon.svg';

    $position_class = $position === 'left' ? 'position-left' : 'position-right';

    // Determine visibility classes
    $visibility_class = '';
    if ($display_desktop !== 'yes') {
        $visibility_class .= ' hide-on-desktop';
    }
    if ($display_mobile !== 'yes') {
        $visibility_class .= ' hide-on-mobile';
    }
    
    // Print out the custom CSS in the <head> to apply the background color dynamically
    echo '<style>
            .chatfloat-container {
                top: ' . esc_attr($top_margin) . 'px;
                bottom: ' . esc_attr($bottom_margin) . 'px;
                ' . ($position === 'left' ? 'left' : 'right') . ': ' . esc_attr($horizontal_margin) . 'px;
                position: fixed;
                display: flex;
                align-items: center;
                z-index: 1000;
                .chatfloat-container .chatfloat-text span {
                    background-color: ' . esc_attr($bg_color) . ';
                    color: ' . esc_attr($text_color) . ';
                    transition: background-color 0.5s ease, color 0.5s ease;
            }
            /* Position Classes */
            .chatfloat-container.position-right {
                right: ' . esc_attr($horizontal_margin) . 'px;
            }

            .chatfloat-container.position-left {
                left: ' . esc_attr($horizontal_margin) . 'px;
            }
            ';
            if ($dark_mode === 'yes'){
                
                echo '@media (prefers-color-scheme: dark) {
                .chatfloat-container .chatfloat-text span {
                    background-color: #25D366;
                    color: #FFFFFF;
                    transition: background-color 0.5s ease, color 0.5s ease;
                }
                }';
            }
            echo '}
          </style>';

    if (!empty($prefill_msg)) {
        $wa_link .= '?text=' . rawurlencode($prefill_msg);
    }

    echo '<div class="chatfloat-container ' . esc_attr($position_class) . esc_attr($visibility_class) . '">';
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
