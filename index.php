<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

// Initialize Stripe with API Key
\Stripe\Stripe::setApiKey($config['secret_key']);

$productList = [];
$error = null;

try {
    // Fetch all products from Stripe
    $products = \Stripe\Product::all(['limit' => 100]);

    foreach ($products->data as $product) {
        // Fetch prices for each product
        $prices = \Stripe\Price::all(['product' => $product->id, 'limit' => 1]);

        if (!empty($prices->data)) {
            $price = $prices->data[0];
            $productList[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description ?? 'No description',
                'image' => $product->images[0] ?? null,
                'price' => $price->unit_amount ?? 0,
                'currency' => $price->currency ?? 'usd',
                'price_id' => $price->id ?? null
            ];
        }
    }

} catch (\Stripe\Exception\ApiErrorException $e) {
    $error = "Error fetching products: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Store - Browse Products</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .product { border: 1px solid #ccc; padding: 20px; margin: 10px; }
        .btn { background: #007bff; color: white; padding: 10px; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Stripe Store</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php foreach ($productList as $product): ?>
        <div class="product">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: $<?php echo number_format($product['price'] / 100, 2); ?></p>
            <?php if ($product['price_id']): ?>
                <a href="checkout.php?priceId=<?php echo urlencode($product['price_id']); ?>&productId=<?php echo urlencode($product['id']); ?>" class="btn">Buy Now</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
