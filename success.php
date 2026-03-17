<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

// Initialize Stripe with API Key
\Stripe\Stripe::setApiKey($config['secret_key']);

$sessionId = $_GET['session_id'] ?? null;
$sessionData = null;

if ($sessionId) {
    try {
        $sessionData = \Stripe\Checkout\Session::retrieve($sessionId);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Error retrieving session: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Thank you for your purchase!</p>

    <?php if ($sessionData): ?>
        <p>Order ID: <?php echo htmlspecialchars($sessionData->id); ?></p>
        <p>Amount: <?php echo number_format($sessionData->amount_total / 100, 2); ?> <?php echo strtoupper($sessionData->currency); ?></p>
    <?php endif; ?>

    <a href="index.php">Continue Shopping</a>
</body>
</html>
