<?php

require_once 'config.php';

$config = require 'config.php';

$client = new \GuzzleHttp\Client();

try {

    $response = $client->request('GET', 'https://api.stripe.com/v1/products', [

        'headers' => [

            'Authorization' => 'Bearer ' . $config['secret_key'],

        ],

        'query' => [

            'expand[]' => 'data.default_price',

        ],

    ]);

    $body = $response->getBody();

    $data = json_decode($body, true);

    $products = $data['data'];

} catch (Exception $e) {

    die('Error fetching products: ' . $e->getMessage());

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stripe Store</title>

    <style>

        body {

            font-family: Arial, sans-serif;

            background-color: #f4f4f4;

            margin: 0;

            padding: 20px;

        }

        .container {

            max-width: 1200px;

            margin: 0 auto;

        }

        h1 {

            text-align: center;

            color: #333;

        }

        .products {

            display: grid;

            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));

            gap: 20px;

        }

        .product {

            background: white;

            border-radius: 8px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.1);

            padding: 20px;

            text-align: center;

        }

        .product img {

            max-width: 100%;

            height: 200px;

            object-fit: cover;

            border-radius: 4px;

        }

        .product h2 {

            color: #333;

            margin: 10px 0;

        }

        .product p {

            color: #666;

            margin: 10px 0;

        }

        .price {

            font-size: 1.2em;

            color: #007bff;

            margin: 10px 0;

        }

        .buy-btn {

            background: #28a745;

            color: white;

            border: none;

            padding: 10px 20px;

            border-radius: 4px;

            cursor: pointer;

            font-size: 1em;

        }

        .buy-btn:hover {

            background: #218838;

        }

    </style>

</head>

<body>

    <div class="container">

        <h1>Our Products</h1>

        <div class="products">

            <?php foreach ($products as $product): ?>

                <div class="product">

                    <?php if (!empty($product['images'])): ?>

                        <img src="<?php echo htmlspecialchars($product['images'][0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

                    <?php endif; ?>

                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>

                    <p><?php echo htmlspecialchars($product['description'] ?? 'No description'); ?></p>

                    <?php if (isset($product['default_price']) && $product['default_price']): ?>

                        <div class="price">$<?php echo number_format($product['default_price']['unit_amount'] / 100, 2); ?></div>

                    <?php endif; ?>

                    <form action="checkout.php" method="post">

                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                        <input type="hidden" name="price_id" value="<?php echo $product['default_price']['id'] ?? ''; ?>">

                        <button type="submit" class="buy-btn">Buy Now</button>

                    </form>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</body>

</html>