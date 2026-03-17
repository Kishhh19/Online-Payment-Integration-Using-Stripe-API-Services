<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();

return [

    'secret_key' => $_ENV['STRIPE_SECRET_KEY'],

    'publishable_key' => $_ENV['STRIPE_PUBLISHABLE_KEY']

];