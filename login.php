<?php
session_start();
require_once "db.php";

// Toast messages
$success = "";
$error   = "";

// Grab flash message (e.g. after account deletion)
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter both email and password.";
    } else {
        // Look up user
        $sql = "SELECT id, first_name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $stmt->bind_result($id, $first_name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"]     = $id;
                $_SESSION["first_name"]  = $first_name;

                // Welcome toast on shop page
                $_SESSION['flash_success'] = "Welcome, $first_name!";

                header("Location: shop.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "No account exists with that email.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="yellow-bg">

<?php if ($success || $error): ?>
<div id="toast" class="toast <?php echo $success ? 'toast-success show' : 'toast-error show'; ?>">
    <span><?php echo htmlspecialchars($success ?: $error); ?></span>
</div>
<?php endif; ?>

<header>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.html">About</a></li>
        </ul>
    </nav>
</header>

<main class="form-page">
    <div class="form-container">
        <h2>Login</h2>

        <form method="POST" action="login.php">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn btn-dark">Sign In</button>
        </form>

        <p style="margin-top: 10px;">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </p>
    </div>
</main>

<footer>© 2025 Eclipse Wear — All Rights Reserved.</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var toast = document.getElementById('toast');
    if (toast) setTimeout(() => toast.classList.remove('show'), 4000);
});
</script>
</body>
</html>
