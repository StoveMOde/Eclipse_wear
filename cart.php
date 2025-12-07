<?php
session_start();

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$firstName = $_SESSION["first_name"] ?? "Customer";

// Handle cart updates (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action     = $_POST['action'] ?? '';
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === 'update' && $product_id !== null) {
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        if ($qty <= 0) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['flash_success'] = "Item removed from cart.";
        } else {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['qty'] = $qty;
                $_SESSION['flash_success'] = "Cart updated.";
            }
        }
    }

    if ($action === 'remove' && $product_id !== null) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['flash_success'] = "Item removed from cart.";
        }
    }

    // JS redirect back to cart to avoid header issues
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}

// Toast messages
$success = "";
$error   = "";

if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
if (isset($_SESSION['flash_error'])) {
    $error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}

// Cart data
$cart      = $_SESSION['cart'] ?? [];
$cartCount = count($cart);

// Calculate totals
$grandTotal = 0.0;
foreach ($cart as $item) {
    $grandTotal += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | Eclipse Wear</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yellow-bg">

<?php if ($success || $error): ?>
    <div id="toast" class="toast <?php 
        echo $success ? 'toast-success show' : 'toast-error show'; 
    ?>">
        <span id="toast-message">
            <?php echo htmlspecialchars($success ?: $error); ?>
        </span>
    </div>
<?php endif; ?>

<header>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="profile.php">My Profile</a></li>
        </ul>

        <div class="nav-actions">
            <span class="welcome-text">
                Welcome, <?php echo htmlspecialchars($firstName); ?>!
            </span>

            <a href="cart.php" class="btn btn-dark">
                Cart (<?php echo $cartCount; ?>)
            </a>

            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </nav>
</header>

<main class="form-page">
    <div class="form-container">
        <h2>My Cart</h2>

        <?php if ($cartCount === 0): ?>
            <p>Your cart is currently empty.</p>
            <p><a href="shop.php" class="btn btn-dark">Back to Shop</a></p>
        <?php else: ?>
            <div class="cart-list">
                <?php foreach ($cart as $product_id => $item): 
                    $price      = $item['price'];
                    $qty        = (int)$item['qty'];
                    $lineTotal  = $price * $qty;
                ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Item ID: <?php echo htmlspecialchars($product_id); ?></p>
                            <p>Price: $<?php echo number_format($price, 2); ?></p>
                            <p>Line Total: $<?php echo number_format($lineTotal, 2); ?></p>
                        </div>

                        <div class="cart-item-actions">
                            <!-- Update quantity -->
                            <form method="POST" class="cart-inline-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

                                <label>Qty:</label>
                                <input type="number" name="qty" min="1"
                                       value="<?php echo $qty; ?>">

                                <button type="submit" class="btn btn-dark">Update</button>
                            </form>

                            <!-- Remove item -->
                            <form method="POST" class="cart-inline-form">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

                                <button type="submit" class="btn btn-dark">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top: 20px;">
                <h3>Grand Total: $<?php echo number_format($grandTotal, 2); ?></h3>
                <br>
                <a href="shop.php" class="btn btn-dark">Continue Shopping</a>
                <button class="btn btn-dark" disabled>Checkout (Coming Soon)</button>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    © 2025 Eclipse Wear — All Rights Reserved.
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toast = document.getElementById('toast');
    if (toast && toast.classList.contains('show')) {
        setTimeout(function () {
            toast.classList.remove('show');
        }, 4000);
    }
});
</script>

</body>
</html>
