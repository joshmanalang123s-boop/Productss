<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

// Stripe Configuration
return [
    'secret_key' => $_ENV['STRIPE_SECRET_KEY'],
    'publishable_key' => $_ENV['STRIPE_PUBLISHABLE_KEY'],
    'app_url' => $_ENV['APP_URL']
];
