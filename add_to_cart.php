<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Simple product catalog (id => [name, price])
$products = [
    1 => ["name" => "Cat Eye",       "price" => 79.00],
    2 => ["name" => "Square Black",  "price" => 85.00],
    3 => ["name" => "Night Black",   "price" => 92.00],
    4 => ["name" => "Oval Black",    "price" => 88.00],
    5 => ["name" => "White Frames",  "price" => 95.00],
    6 => ["name" => "Wood Frames",   "price" => 99.00],
];

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$product_id || !isset($products[$product_id])) {
    $_SESSION['flash_error'] = "Invalid product.";
    header("Location: shop.php");
    exit();
}

$product = $products[$product_id];
$name    = $product['name'];
$price   = $product['price'];

// If item already in cart, just bump qty
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['qty'] += 1;
    $_SESSION['flash_success'] = "Another $name added to cart!";
} else {
    $_SESSION['cart'][$product_id] = [
        "name"  => $name,
        "price" => $price,
        "qty"   => 1
    ];
    $_SESSION['flash_success'] = "$name added to cart!";
}

header("Location: shop.php");
exit();
?>
