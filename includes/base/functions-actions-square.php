<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ignore reCaptcha on confirmation screen
 *
 */
function wpbs_validate_recaptcha_payment_confirmation_square($response, $form_data)
{
    if (isset($form_data['wpbs-square-confirmation-loaded']) && $form_data['wpbs-square-confirmation-loaded'] == '1') {
        return true;
    }
    return $response;
}
add_filter('wpbs_validate_recaptcha_payment_confirmation', 'wpbs_validate_recaptcha_payment_confirmation_square', 10, 2);

/**
 * Show the payment confirmation page after submitting the form
 *
 */
function wpbs_square_submit_form_payment_confirmation($response, $post_data, $form, $form_args, $form_fields, $calendar_id)
{
    // Check if another payment method was already found
    if ($response !== false) {
        return $response;
    }

    $payment_found = false;

    // Check if payment method is enabled.
    foreach ($form_fields as $form_field) {
        if ($form_field['type'] == 'payment_method' && $form_field['user_value'] == 'square') {
            $payment_found = true;
            break;
        }
    }

    if ($payment_found === false) {
        return false;
    }

    // Include Square API
    include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/vendor/square-api.php';

    $api = WPBS_Square_API::keys();

    // Check for API Keys
    if (empty($api['application_id']) || empty($api['access_token'])) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<p class="wpbs-form-general-error">' . __("Please add your API keys in the plugin's Settings Page.", 'wp-booking-system-square') . '</p>',
            )
        );
    }

    // Parse POST data
    parse_str($post_data['form_data'], $form_data);

    // Check if the payment screen was shown
    if (isset($form_data['wpbs-square-confirmation-loaded']) && $form_data['wpbs-square-confirmation-loaded'] == '1') {
        return false;
    }

    // Add a field to the input so we can check if the payment screen was already shown
    add_filter('wpbs_form_outputter_form_fields_after', function () {
        return '<input type="hidden" name="wpbs-square-confirmation-loaded" value="1" />';
    });

    // Generate form
    $form_outputter = new WPBS_Form_Outputter($form, $form_args, $form_fields, $calendar_id);

    // Check if post data exists and matches form values
    if (wpbs_validate_payment_form_consistency($form_fields) === false) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<strong>' . __('Something went wrong. Please refresh the page and try again.', 'wp-booking-system-square') . '</strong>',
            )
        );
    }

    // Get price
    $payment = new WPBS_Payment;
    $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    $total = $payment->get_total();

    // Check if part payments are used
    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $total = $payment->get_total_first_payment();
    }

    // Check if price is greater than the minimum allowed, 0.5;
    if ($total <= 0.5) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<p class="wpbs-form-general-error">' . __("The minimum payable amount is 0.50$", 'wp-booking-system-square') . '</p>',
            )
        );
    }

    // Get plugin settings
    $settings = get_option('wpbs_settings', array());


    $invoice_item_description = (!empty($settings['payment_square_invoice_name_translation_' . $form_outputter->get_language()])) ? $settings['payment_square_invoice_name_translation_' . $form_outputter->get_language()] : (!empty($settings['payment_square_invoice_name']) ? $settings['payment_square_invoice_name'] : get_bloginfo('name') . ' Booking');


    //TODO: Change the squareupsandbox to just squareup
    /**
     * Prepare Response
     *
     */
    $square_output = '';
    if ($api['environment'] == 'sandbox') {
        $square_output .= '<p class="wpbs-payment-test-mode-enabled">' . __('Square Test mode is enabled.', 'wp-booking-system-square') . '</p>';
    }

    $square_output .= '<div class="wpbs-payment-confirmation-square-form" id="form-container">';

    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $square_output .= '<label>' . wpbs_get_payment_default_string('amount_billed', $form_outputter->get_language()) . '</label><input class="wpbs-payment-confirmation-square-input" type="text" value="' . wpbs_get_formatted_price($total, $payment->get_currency()) . '" readonly>';
    }

    $square_output .= '
        <div id="sq-card-number"></div>
        <div class="third" id="sq-expiration-date"></div>
        <div class="third" id="sq-cvv"></div>
        <div class="third" id="sq-postal-code"></div>
        <button id="sq-creditcard" class="button-credit-card" onclick="onGetCardNonce(event)">Pay ' . wpbs_get_formatted_price($total, $payment->get_currency()) . '</button>
    </div>
    <script>

    wpbs_lazy_load_script("https://js.squareupsandbox.com/v2/paymentform",wpbs_init_square);

    function wpbs_init_square(){

        const idempotency_key = uuidv4();
        console.log("' . $api['location_id'] . '")
        const paymentForm = new SqPaymentForm({
            applicationId: "' . $api['application_id'] . '",
            inputClass: "sq-input",
            autoBuild: false,
            inputStyles: [{
                fontSize: "16px",
                lineHeight: "24px",
                padding: "16px",
                placeholderColor: "#a0a0a0",
                backgroundColor: "transparent",
            }],
            // Initialize the credit card placeholders
            cardNumber: {
                elementId: "sq-card-number",
                placeholder: "Card Number"
            },
            cvv: {
                elementId: "sq-cvv",
                placeholder: "CVV"
            },
            expirationDate: {
                elementId: "sq-expiration-date",
                placeholder: "MM/YY"
            },
            postalCode: {
                elementId: "sq-postal-code",
                placeholder: "Postal"
            },
            callbacks: {
                /*
                * callback function: cardNonceResponseReceived
                * Triggered when: SqPaymentForm completes a card nonce request
                */
                cardNonceResponseReceived: function (errors, nonce, cardData) {
                if (errors) {
                    // Log errors from nonce generation to the browser developer console.
                    console.error("Encountered errors:");
                    errors.forEach(function (error) {
                        console.error("  " + error.message);
                    });
                    alert("Encountered errors, check browser developer console for more details");
                    return;
                }
                jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . '").append("<h4>' . wpbs_get_payment_default_string('processing_payment', $form_outputter->get_language()) . '</h4>");

                fetch(wpbs_ajaxurl, {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/x-www-form-urlencoded",
                      "Cache-Control": "no-cache"
                    },
                    body: new URLSearchParams({
                        action: "payment_request",
                        nonce: nonce,
                        wp_nonce: "'. wp_create_nonce('payment_request_nonce') . '",
                        idempotency_key: idempotency_key,
                        total: ' .  $total . ',
                        currency: "' . $payment->get_currency() . '",
                        description: "' . $invoice_item_description . '",


                    })   
                  })
                  .catch(err => {
                    alert("Network error: " + err);
                  })
                  .then(response => {
                    if (!response.ok) {
                      return response.json().then(
                        errorInfo => Promise.reject(errorInfo));
                    }
                    return response.json();
                  })
                  .then(data => {
                    if(!data.success){
                        console.log(data.errors)
                        jQuery(".wpbs-square-payment-confirmation-inner-' . $form_outputter->get_unique() . '").show();
                        jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . '").remove();
                        return;
                    }
                 
                    jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . '").parents(".wpbs-main-wrapper").find(".wpbs-calendar").append("<div class=\"wpbs-overlay\"><div class=\"wpbs-overlay-spinner\"><div class=\"wpbs-overlay-bounce1\"></div><div class=\"wpbs-overlay-bounce2\"></div><div class=\"wpbs-overlay-bounce3\"></div></div></div>");
                    jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . '").parents(".wpbs-container").addClass("wpbs-is-loading");
                    jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . ' form").append("<input type=\"hidden\" name=\"wpbs-square-payment-id\" value=\""+data.id+"\" />")
                    jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . ' form").submit();
                  })
                  .catch(err => {
                    console.error(err);
                    jQuery(".wpbs-square-payment-confirmation-inner-' . $form_outputter->get_unique() . '").show();
                    jQuery(".wpbs-square-payment-confirmation-' . $form_outputter->get_unique() . '").remove();
                  });

               
            }
        }
        });

        paymentForm.build();

        function uuidv4() {
            return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == "x" ? r : (r & 0x3 | 0x8);
            return v.toString(16);
            });
        }

        function onGetCardNonce(event) {

            event.preventDefault();
            // Request a nonce from the SqPaymentForm object
            paymentForm.requestCardNonce();
        }
        window.onGetCardNonce = onGetCardNonce;
    }

    
    </script>
    ';

    $output = wpbs_form_payment_confirmation_screen($form_outputter, $payment, 'square', $square_output);

    return json_encode(
        array(
            'success' => false,
            'html' => $output,
        )
    );

}
add_filter('wpbs_submit_form_before', 'wpbs_square_submit_form_payment_confirmation', 10, 6);

/**
 * Save the order in the database and maybe capture the payment
 *
 */
function wpbs_square_action_save_payment_details($booking_id, $post_data, $form, $form_args, $form_fields)
{

    // Parse POST data
    parse_str($post_data['form_data'], $form_data);

    // Check if square is enabled
    if (!isset($form_data['wpbs-square-confirmation-loaded'])) {
        return false;
    }

    // Check if we got a payment id
    if (!isset($form_data['wpbs-square-payment-id'])) {
        return false;
    }

    $calendar_id = $post_data['calendar']['id'];

    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Include Square SDK
    include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/square-api/square-api.php';

    // Get price
    $payment = new WPBS_Payment;
    $details['price'] = $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $details['part_payments'] = array('deposit' => false, 'final_payment' => false);
    }
    
    // Generate form
    $form_outputter = new WPBS_Form_Outputter($form, $form_args, $form_fields, $calendar_id);

    // Authorization method
    if (isset($settings['payment_square_delayed_capture']) && $settings['payment_square_delayed_capture'] == 'on') {
        // Save temporary data, and capture the payment when the booking is accepted.
        $details['raw'] = array();
        $details['payment_intent_id'] = $form_data['wpbs-square-payment-intent-id'];

        if (isset($details['part_payments']['deposit'])) {
            $details['part_payments']['deposit'] = true;
        }

        $status = 'authorized';
        $id = 'N/A';
    } else {
        // Capture payment when booking.

        /**
         * Create the Payment Intent on Square
         *
         */
        $invoice_item_description = (!empty($settings['payment_square_invoice_name_translation_' . $form_outputter->get_language()])) ? $settings['payment_square_invoice_name_translation_' . $form_outputter->get_language()] : (!empty($settings['payment_square_invoice_name']) ? $settings['payment_square_invoice_name'] : get_bloginfo('name') . ' Booking');

        $order = WPBS_Square_PaymentIntent::getPaymentIntent($form_data['wpbs-square-payment-id']);

        // Get Order
        // $order = WPBS_Square_PaymentIntent::getPaymentIntent($form_data['wpbs-square-payment-intent-id']);

        if ($order->isSuccess()) {
            $details['raw'] = $order->getResult();

            if (isset($details['part_payments']['deposit'])) {
                $details['part_payments']['deposit'] = true;
            }

            $status = 'completed';
            // $result = $order->getResult()->getPayment()->getId();
            $id = $order->getResult()->getPayment()->getId();
        } else {

            $details['error'] = $order->getErrors();
            $status = 'error';
            $id = 'N/A';
        }
    }

    // Save Order
    wpbs_insert_payment(array(
        'booking_id' => $booking_id,
        'gateway' => 'square',
        'order_id' => $id,
        'order_status' => $status,
        'details' => $details,
        'date_created' => current_time('Y-m-d H:i:s'),
    ));

}
add_action('wpbs_submit_form_after', 'wpbs_square_action_save_payment_details', 10, 5);

/**
 * Capture the order if needed
 *
 * @param WPBS_Booking $booking
 *
 */
function wpbs_square_save_booking_data_accept_booking($booking)
{
    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Check if delayed capture is enabled
    if (!isset($settings['payment_square_delayed_capture']) || $settings['payment_square_delayed_capture'] != 'on') {
        return false;
    }

    // Get Payment
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (is_null($payments)) {
        return false;
    }

    $payment = array_shift($payments);

    if(is_null($payment)){
		return false;
	}

    // Exit if status is not "authorized"
    if ($payment->get('order_status') != 'authorized') {
        return false;
    }

    // Include Square SDK
    include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/vendor/square-api.php';

    // Get Order
    $details = $payment->get('details');

    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $details['part_payments'] = array('deposit' => false, 'final_payment' => false);
    }

    // Capture order
    $order = WPBS_Square_PaymentIntent::getPaymentIntent($details['payment_intent_id']);

    // Prepare details
    if ($order['success'] == true) {
        $details['raw'] = $order['data'];

        if (isset($details['part_payments']['deposit'])) {
            $details['part_payments']['deposit'] = true;
        }

        $status = 'completed';
        $id = $order['data']->id;
    } else {
        $details['error'] = $order['error'];
        $status = 'error';
        $id = 'N/A';
    }

    // Update payment with correct details
    wpbs_update_payment($payment->get('id'), array(
        'order_id' => $id,
        'order_status' => $status,
        'details' => $details,
    ));

}
add_action('wpbs_save_booking_data_accept_booking', 'wpbs_square_save_booking_data_accept_booking', 1, 10);

/**
 * Cancel the authorization if the booking is deleted
 *
 * @param int $booking_id
 *
 */
function wpbs_square_permanently_delete_booking($booking_id)
{

    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Check if delayed capture is enabled
    if (!isset($settings['payment_square_delayed_capture']) || $settings['payment_square_delayed_capture'] != 'on') {
        return false;
    }

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    // Get payment
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return false;
    }

    $payment = array_shift($payments);

    if (is_null($payment)) {
        return false;
    }

    // Exit if status is not "authorized"
    if ($payment->get('order_status') != 'authorized') {
        return false;
    }

    // Include Square SDK
    include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/vendor/square-api.php';

    // Get Order
    $details = $payment->get('details');

    // Cancel the order
    $order = WPBS_Square_PaymentIntent::cancelPayment($details['payment_intent_id']);

}
add_action('wpbs_permanently_delete_booking', 'wpbs_square_permanently_delete_booking', 1, 10);


function process_payment_request() {
    if(!isset($_POST['total'])|| !isset($_POST['currency']) || !isset($_POST['description']) || !isset($_POST['nonce'])) {
        return false;
    }

    // Include Square SDK
    include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/vendor/square-api.php';

    $order = WPBS_Square_PaymentIntent::createPaymentIntent($_POST['total'] * 100, $_POST['currency'], $_POST['description'], $_POST['nonce']);
   
        
    // Get Order
    // $order = WPBS_Square_PaymentIntent::getPaymentIntent($form_data['wpbs-square-payment-intent-id']);

    if ($order->isSuccess()) {
        $details['raw'] = $order->getResult();

        if (isset($details['part_payments']['deposit'])) {
            $details['part_payments']['deposit'] = true;
        }

        echo json_encode(
            array(
                'success' => true,
                'id' => $order->getResult()->getPayment()->getId(),
            )
        );
    } else {

        echo json_encode(
            array(
                'success' => false,
                'errors' => $order->getErrors(),
            )
        );

    }
    die();

 
}
add_action('wp_ajax_payment_request', 'process_payment_request');
add_action('wp_ajax_nopriv_payment_request', 'process_payment_request');