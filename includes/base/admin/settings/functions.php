<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Square submenu to the Payments Tab
 *
 */
function wpbs_square_settings_page_tab($tabs)
{

    $tabs['square'] = 'Square';
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_square_settings_page_tab', 1);

/**
 * Add the Square Settings to the Square Payments tab
 *
 */
function wpbs_square_settings_page_tab_square()
{
    $settings = get_option('wpbs_settings', array());
    $defaults = wpbs_square_settings_square_defaults();
    $square_api = get_option('wpbs_square_api', array());

    include 'views/view-payment-settings-square.php';
}
add_action('wpbs_submenu_page_payment_settings_tab_square', 'wpbs_square_settings_page_tab_square');

/**
 * Make strings translatable - add default strings
 *
 */
function wpbs_square_payment_default_strings($strings)
{
    $strings['cardholder_name'] = __('Cardholder Name', 'wp-booking-system-square');
    $strings['card_details']    = __('Card Details', 'wp-booking-system-square');
    $strings['payment_required_field']  = __('This field is required.', 'wp-booking-system-square');
    $strings['payment_submit']  = __('Submit', 'wp-booking-system-square');

    return $strings;
}
add_filter('wpbs_payment_default_strings', 'wpbs_square_payment_default_strings');

/**
 * Make strings translatable - add form fields strings
 *
 */
function wpbs_square_payment_default_strings_labels($strings)
{
    $strings['cardholder_name'] = array(
        'label' => __('Cardholder Name Label', 'wp-booking-system-square'),
        'tooltip' => __("The label for the Cardholder's Name in the payment form.", 'wp-booking-system-square'),
    );

    $strings['card_details'] = array(
        'label' => __('Card Details Label', 'wp-booking-system-square'),
        'tooltip' => __("The label for the Card Details in the payment form.", 'wp-booking-system-square'),
    );

    $strings['payment_required_field'] = array(
        'label' => __('Payment Required Field', 'wp-booking-system-square'),
        'tooltip' => __("The error message when a payment form field is empty.", 'wp-booking-system-square'),
    );

    $strings['payment_submit'] = array(
        'label' => __('Payment Submit Button Label', 'wp-booking-system-square'),
        'tooltip' => __("The button label when submitting a payment form.", 'wp-booking-system-square'),
    );

    return $strings;
}
add_filter('wpbs_payment_default_strings_labels', 'wpbs_square_payment_default_strings_labels');

/**
 * Save Square API Keys in a separate option field.
 *
 */
function wpbs_square_save_api_keys($option_name, $old_value, $value)
{
    // If wpbs_settings are being saved
    if ($option_name != 'wpbs_settings') {
        return false;
    }

    // If isset square api post data
    if (!isset($_POST['wpbs_square_api'])) {
        return false;
    }

    // Do the update
    update_option('wpbs_square_api', $_POST['wpbs_square_api']);

};
add_action('updated_option', 'wpbs_square_save_api_keys', 10, 3);
