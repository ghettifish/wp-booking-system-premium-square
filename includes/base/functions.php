<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_square_include_files_base()
{
    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include Payment Ajax Functions
    if (file_exists($dir_path . 'functions-actions-square.php')) {
        include $dir_path . 'functions-actions-square.php';
    }

    // Include Part Payments Functions
    // if (file_exists($dir_path . 'functions-part-payments.php')) {
    //     include $dir_path . 'functions-part-payments.php';
    // }
}
add_action('wpbs_square_include_files', 'wpbs_square_include_files_base');

/**
 * Register Payment Method
 *
 * @param array
 *
 */
function wpbs_square_register_payment_method($payment_methods)
{
    $payment_methods['square'] = 'Square';
    return $payment_methods;
}
add_filter('wpbs_payment_methods', 'wpbs_square_register_payment_method');

/**
 * Default form values
 *
 */
function wpbs_square_settings_square_defaults()
{
    return array(
        'display_name' => __('Square', 'wp-booking-system-square'),
        'description' => __('Pay with your credit card using Square.', 'wp-booking-system-square'),
    );
}

/**
 * Check if payment method is enabled in settings page
 *
 */
function wpbs_square_form_outputter_payment_method_enabled_square($active)
{
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['payment_square_enable']) && $settings['payment_square_enable'] == 'on') {
        return true;
    }
    return false;
}
add_filter('wpbs_form_outputter_payment_method_enabled_square', 'wpbs_square_form_outputter_payment_method_enabled_square');

/**
 * Get the payment method's name
 *
 */
function wpbs_square_form_outputter_payment_method_name_square($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_square_name_translation_' . $language])) {
        return $settings['payment_square_name_translation_' . $language];
    }
    if (!empty($settings['payment_square_name'])) {
        return $settings['payment_square_name'];
    }
    return wpbs_square_settings_square_defaults()['display_name'];
}
add_filter('wpbs_form_outputter_payment_method_name_square', 'wpbs_square_form_outputter_payment_method_name_square', 10, 2);

/**
 * Get the payment method's name
 *
 */
function wpbs_square_form_outputter_payment_method_description_square($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_square_description_translation_' . $language])) {
        return $settings['payment_square_description_translation_' . $language];
    }
    if (!empty($settings['payment_square_description'])) {
        return $settings['payment_square_description'];
    }
    return wpbs_square_settings_square_defaults()['description'];
}
add_filter('wpbs_form_outputter_payment_method_description_square', 'wpbs_square_form_outputter_payment_method_description_square', 10, 2);
