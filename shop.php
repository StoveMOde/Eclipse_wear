<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$firstName = $_SESSION["first_name"] ?? "Customer";

// Toast messages (from login or add_to_cart)
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

// Cart count
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Eclipse Wear</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yellow-bg">

<!-- Toast Popup -->
<?php if ($success || $error): ?>
    <div id="toast" class="toast <?php echo $success ? 'toast-success show' : 'toast-error show'; ?>">
        <span id="toast-message"><?php echo htmlspecialchars($success ?: $error); ?></span>
    </div>
<?php endif; ?>

<header>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="shop.php" class="active">Shop</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="profile.php">My Profile</a></li>
        </ul>

        <div class="nav-actions">
            <span class="welcome-text">Welcome, <?php echo htmlspecialchars($firstName); ?>!</span>

            <a href="cart.php" class="btn btn-dark">
                Cart (<?php echo $cartCount; ?>)
            </a>

            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </nav>
</header>

<main class="shop-layout">

    <aside class="filters">
        <h3>Keywords</h3>
        <div class="tag">Spring ✕</div>
        <div class="tag">Smart ✕</div>
        <div class="tag">Modern ✕</div>

        <h3>New</h3>
        <label><input type="checkbox" checked> New In</label>
        <label><input type="checkbox" checked> Frame Shape</label>
        <label><input type="checkbox" checked> Lens Shape</label>

        <h3>Price Range</h3>
        <input type="range" min="0" max="100" value="30">

        <h3>Color</h3>
        <label><input type="checkbox" checked> Black</label>
        <label><input type="checkbox" checked> Red</label>
        <label><input type="checkbox" checked> Gray</label>

        <h3>Size</h3>
        <label><input type="checkbox" checked> S</label>
        <label><input type="checkbox" checked> M</label>
        <label><input type="checkbox" checked> XL</label>
    </aside>

    <section class="products">

        <div class="product-card">
            <img src="images/cat_eye.jpg" alt="Cat Eye">
            <p>Cat Eye</p>
            <a href="add_to_cart.php?id=1&name=Cat%20Eye" class="btn btn-dark">Add to Cart</a>
        </div>

        <div class="product-card">
            <img src="images/square_black.jpg" alt="Square Black">
            <p>Square Black</p>
            <a href="add_to_cart.php?id=2&name=Square%20Black" class="btn btn-dark">Add to Cart</a>
        </div>

        <div class="product-card">
            <img src="images/night_black.jpg" alt="Night Black">
            <p>Night Black</p>
            <a href="add_to_cart.php?id=3&name=Night%20Black" class="btn btn-dark">Add to Cart</a>
        </div>

        <div class="product-card">
            <img src="images/oval_black.jpg" alt="Oval Black">
            <p>Oval Black</p>
            <a href="add_to_cart.php?id=4&name=Oval%20Black" class="btn btn-dark">Add to Cart</a>
        </div>

        <div class="product-card">
            <img src="images/white_frames.jpg" alt="White Frames">
            <p>White Frames</p>
            <a href="add_to_cart.php?id=5&name=White%20Frames" class="btn btn-dark">Add to Cart</a>
        </div>

        <div class="product-card">
            <img src="images/wood_frames.jpg" alt="Wood Frames">
            <p>Wood Frames</p>
            <a href="add_to_cart.php?id=6&name=Wood%20Frames" class="btn btn-dark">Add to Cart</a>
        </div>

    </section>

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

