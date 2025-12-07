<?php
session_start();
require_once "db.php";

$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first            = trim($_POST['first_name'] ?? "");
    $last             = trim($_POST['last_name'] ?? "");
    $email            = trim($_POST['email'] ?? "");
    $password         = $_POST['password'] ?? "";
    $confirm_password = $_POST['confirm_password'] ?? "";

    // Basic validation
    if ($first === "" || $last === "" || $email === "" || $password === "" || $confirm_password === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "An account with that email already exists.";
        }
        $check->close();
    }

    if ($error === "") {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql  = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $first, $last, $email, $password_hashed);
            if ($stmt->execute()) {
                // Flash message for login page
                $_SESSION['flash_success'] = "Signup successful! You can now log in.";

                // Use JS redirect instead of header() to avoid blank page
                echo "<script>window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Something went wrong while creating your account.";
            }
            $stmt->close();
        } else {
            $error = "SQL Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>

        <form method="POST" action="signup.php">
            <label>First Name</label>
            <input type="text" name="first_name" required>

            <label>Last Name</label>
            <input type="text" name="last_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="btn btn-dark">Create Account</button>
        </form>

        <p style="margin-top: 10px;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</main>

<footer>
    © 2025 Eclipse Wear — All Rights Reserved.
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => toast.classList.remove('show'), 4000);
    }
});
</script>

</body>
</html>
