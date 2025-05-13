<?php
/**
 * Plugin Name: ES Business Profile
 * Plugin URI: https://ethersense.com
 * Description: A plugin to manage business contact information including hours, address, phone numbers, and email addresses.
 * Version: 1.0.0
 * Author: Ethersense Development
 * Author URI: https://ethersense.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: business-profile
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Add menu item to WordPress admin
function business_profile_menu() {
    add_menu_page(
        'Business Profile',
        'Business Profile',
        'manage_options',
        'business-profile',
        'business_profile_page',
        'dashicons-store',
        30
    );
}
add_action('admin_menu', 'business_profile_menu');

// Register settings
function business_profile_settings_init() {
    register_setting('business_profile', 'business_profile_options');

    add_settings_section(
        'business_profile_section',
        'Business Information',
        'business_profile_section_callback',
        'business-profile'
    );

    // Business Hours
    add_settings_field(
        'business_hours',
        'Business Hours',
        'business_hours_callback',
        'business-profile',
        'business_profile_section'
    );

    // Address
    add_settings_field(
        'business_address',
        'Business Address',
        'business_address_callback',
        'business-profile',
        'business_profile_section'
    );

    // Phone Numbers
    add_settings_field(
        'business_phone',
        'Phone Numbers',
        'business_phone_callback',
        'business-profile',
        'business_profile_section'
    );

    // Email Addresses
    add_settings_field(
        'business_email',
        'Email Addresses',
        'business_email_callback',
        'business-profile',
        'business_profile_section'
    );
}
add_action('admin_init', 'business_profile_settings_init');

// Section callback
function business_profile_section_callback() {
    echo '<p>Enter your business information below:</p>';
}

// Business Hours callback
function business_hours_callback() {
    $options = get_option('business_profile_options');
    $hours = isset($options['business_hours']) ? $options['business_hours'] : '';
    ?>
    <textarea name="business_profile_options[business_hours]" rows="7" cols="50" class="large-text"><?php echo esc_textarea($hours); ?></textarea>
    <p class="description">Enter your business hours. Example:<br>
        Monday - Friday: 9:00 AM - 5:00 PM<br>
        Saturday: 10:00 AM - 2:00 PM<br>
        Sunday: Closed</p>
    <?php
}

// Business Address callback
function business_address_callback() {
    $options = get_option('business_profile_options');
    $address = isset($options['business_address']) ? $options['business_address'] : '';
    ?>
    <textarea name="business_profile_options[business_address]" rows="5" cols="50" class="large-text"><?php echo esc_textarea($address); ?></textarea>
    <p class="description">Enter your complete business address.</p>
    <?php
}

// Phone Numbers callback
function business_phone_callback() {
    $options = get_option('business_profile_options');
    $phone = isset($options['business_phone']) ? $options['business_phone'] : '';
    ?>
    <textarea name="business_profile_options[business_phone]" rows="3" cols="50" class="large-text"><?php echo esc_textarea($phone); ?></textarea>
    <p class="description">Enter your phone numbers, one per line.</p>
    <?php
}

// Email Addresses callback
function business_email_callback() {
    $options = get_option('business_profile_options');
    $email = isset($options['business_email']) ? $options['business_email'] : '';
    ?>
    <textarea name="business_profile_options[business_email]" rows="3" cols="50" class="large-text"><?php echo esc_textarea($email); ?></textarea>
    <p class="description">Enter your email addresses, one per line.</p>
    <?php
}

// Admin page callback
function business_profile_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('business_profile');
            do_settings_sections('business-profile');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Add shortcode to display business information
function business_profile_shortcode($atts) {
    $options = get_option('business_profile_options');
    $output = '<div class="business-profile">';

    if (!empty($options['business_hours'])) {
        $output .= '<div class="business-hours"><h3>Business Hours</h3>';
        $output .= '<p>' . nl2br(esc_html($options['business_hours'])) . '</p></div>';
    }

    if (!empty($options['business_address'])) {
        $output .= '<div class="business-address"><h3>Address</h3>';
        $output .= '<p>' . nl2br(esc_html($options['business_address'])) . '</p></div>';
    }

    if (!empty($options['business_phone'])) {
        $output .= '<div class="business-phone"><h3>Phone Numbers</h3>';
        $output .= '<p>' . nl2br(esc_html($options['business_phone'])) . '</p></div>';
    }

    if (!empty($options['business_email'])) {
        $output .= '<div class="business-email"><h3>Email Addresses</h3>';
        $output .= '<p>' . nl2br(esc_html($options['business_email'])) . '</p></div>';
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('business_profile', 'business_profile_shortcode');

// Add some basic CSS
function business_profile_styles() {
    ?>
    <style>
        .business-profile {
            max-width: 800px;
            margin: 20px 0;
        }
        .business-profile h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .business-profile p {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .business-hours,
        .business-address,
        .business-phone,
        .business-email {
            margin-bottom: 30px;
        }
    </style>
    <?php
}
add_action('wp_head', 'business_profile_styles');