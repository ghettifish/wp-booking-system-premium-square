<?php

use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;

include_once WPBS_SQUARE_PLUGIN_DIR . 'includes/libs/vendor/autoload.php';
use Square\SquareClient;

class WPBS_Square_API
{
    public static function keys()
    {
        $settings = get_option('wpbs_settings', array());
        $square_api = get_option('wpbs_square_api', array());

        if ((isset($settings['payment_square_test']) && $settings['payment_square_test'] == 'on')) {
            return array(
                'environment' => Environment::SANDBOX,
                'location_id' => isset($square_api['payment_square_location_id']) ? $square_api['payment_square_location_id'] : '',
                'application_id' => isset($square_api['payment_square_test_api_application_id']) ? $square_api['payment_square_test_api_application_id'] : '',
                'access_token' => isset($square_api['payment_square_test_api_access_token']) ? $square_api['payment_square_test_api_access_token'] : '',
            );
        } else {
            return array(
                'environment' => Environment::PRODUCTION,
                'location_id' => isset($square_api['payment_square_location_id']) ? $square_api['payment_square_location_id'] : '',
                'application_id' => isset($square_api['payment_square_live_api_application_id']) ? $square_api['payment_square_live_api_application_id'] : '',
                'access_token' => isset($square_api['payment_square_live_api_access_token']) ? $square_api['payment_square_live_api_access_token'] : '',
            );
        }
    }
}

class WPBS_Square_Client
{
    public static function client()
    {
        $api = WPBS_Square_API::keys();
        return new SquareClient([
            'accessToken' => $api['access_token'],
            'environment' => $api['environment']
        ]);

    }
}

class WPBS_Square_PaymentIntent
{
    public static function createPaymentIntent($amount, $currency, $description, $nonce)
    {

        $client = WPBS_Square_Client::client();
        $payments_api = $client->getPaymentsApi();

        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency($currency);
        $create_payment_request = new CreatePaymentRequest($nonce, uniqid(), $money);
        $create_payment_request->setNote($description);

        return $payments_api->createPayment($create_payment_request);
    }

    public static function getPaymentIntent($id)
    {

        $client = WPBS_Square_Client::client();
        $payments_api = $client->getPaymentsApi();

        $apiResponse = $payments_api->getPayment($id);


        return $apiResponse;
    }

    public static function cancelPayment($id)
    {

        $client = WPBS_Square_Client::client();
        $payments_api = $client->getPaymentsApi();

        $apiResponse = $payments_api->cancelPayment($id);

        if($apiResponse->isSuccess()){
            $response = [
                'success' => true,
                'data' => $apiResponse->getResult()
            ];
        } else {
            $response = array(
                'success' => false,
                'error' => $apiResponse->getErrors()
            );
        }

        return $response;
    }
}
