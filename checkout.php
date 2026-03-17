<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

// Initialize Stripe with API Key
\Stripe\Stripe::setApiKey($config['secret_key']);

$priceId = $_GET['priceId'] ?? null;
$productId = $_GET['productId'] ?? null;

if (!$priceId || !$productId) {
    header('Location: index.php');
    exit;
}

try {
    // Create Checkout Session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price' => $priceId,
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => $config['app_url'] . '/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $config['app_url'] . '/cancel.php',
    ]);

    // Redirect to Stripe Checkout
    header('Location: ' . $session->url);
    exit;

} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating checkout session: " . $e->getMessage();
}
