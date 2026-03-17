<?php

require_once 'config.php';

$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'], $_POST['price_id'])) {

    die('Invalid request');

}

$product_id = $_POST['product_id'];

$price_id = $_POST['price_id'];

$client = new \GuzzleHttp\Client();

try {

    $response = $client->request('POST', 'https://api.stripe.com/v1/checkout/sessions', [

        'headers' => [

            'Authorization' => 'Bearer ' . $config['secret_key'],

            'Content-Type' => 'application/x-www-form-urlencoded',

        ],

        'form_params' => [

            'line_items[0][price]' => $price_id,

            'line_items[0][quantity]' => 1,

            'mode' => 'payment',

            'success_url' => 'http://localhost/stripe_api/stripe-php-app/success.php',

            'cancel_url' => 'http://localhost/stripe_api/stripe-php-app/cancel.php',

        ],

    ]);

    $body = $response->getBody();

    $data = json_decode($body, true);

    if (isset($data['url'])) {

        header('Location: ' . $data['url']);

        exit;

    } else {

        die('Error creating checkout session');

    }

} catch (Exception $e) {

    die('Error: ' . $e->getMessage());

}

?>