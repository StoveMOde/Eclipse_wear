<?php
session_start();
require 'db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$success = "";
$error   = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {

    $first  = trim($_POST['first_name'] ?? '');
    $last   = trim($_POST['last_name'] ?? '');
    $email  = trim($_POST['email'] ?? '');

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if ($first === "" || $last === "" || $email === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Email unique check (other users only)
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        if ($check) {
            // 2 variables => "si" (string, int)
            $check->bind_param("si", $email, $user_id);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $error = "Email already in use by another account.";
            }
            $check->close();
        } else {
            $error = "Error preparing email check.";
        }
    }

    // Password change logic
    $updatePassword = false;
    $passwordHash   = null;

    if ($error === "") {
        $wantsPasswordChange = ($new_password !== "" || $confirm_password !== "");

        if ($wantsPasswordChange) {
            if ($current_password === "") {
                $error = "Enter your current password to change it.";
            } else {
                // Get current password hash from DB
                $pw = $conn->prepare("SELECT password FROM users WHERE id = ?");
                if ($pw) {
                    $pw->bind_param("i", $user_id);
                    $pw->execute();
                    $pw->bind_result($db_hash);
                    $pw->fetch();
                    $pw->close();

                    if (!password_verify($current_password, $db_hash)) {
                        $error = "Your current password is incorrect.";
                    } elseif ($new_password !== $confirm_password) {
                        $error = "New password and confirmation do not match.";
                    } elseif (strlen($new_password) < 8) {
                        $error = "New password must be at least 8 characters.";
                    } else {
                        $updatePassword = true;
                        $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);
                    }
                } else {
                    $error = "Error preparing password check.";
                }
            }
        }
    }

    // Perform update if no errors
    if ($error === "") {
        if ($updatePassword) {
            $stmt = $conn->prepare(
                "UPDATE users SET first_name=?, last_name=?, email=?, password=? WHERE id=?"
            );
            // 5 variables => "ssssi"
            $stmt->bind_param("ssssi", $first, $last, $email, $passwordHash, $user_id);
        } else {
            $stmt = $conn->prepare(
                "UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?"
            );
            // 4 variables => "sssi"
            $stmt->bind_param("sssi", $first, $last, $email, $user_id);
        }

        if ($stmt && $stmt->execute()) {
            $success = $updatePassword
                ? "Profile and password updated!"
                : "Profile updated!";
            $_SESSION['first_name'] = $first;
        } else {
            $error = "An error occurred while updating your profile.";
        }

        if ($stmt) {
            $stmt->close();
        }
    }
}

// Load latest user data for display
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yellow-bg">

<!-- Toast -->
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
            <li><a href="profile.php" class="active">My Profile</a></li>
        </ul>

        <div class="nav-actions">
            <span class="welcome-text">
                Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!
            </span>
            <a href="cart.php" class="btn btn-dark">Cart</a>
            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </nav>
</header>

<main class="form-page">
    <div class="form-container">
        <h2>My Profile</h2>

        <form method="POST">
            <input type="hidden" name="update_profile" value="1">

            <label>First Name</label>
            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($first_name); ?>">

            <label>Last Name</label>
            <input type="text" name="last_name" required value="<?php echo htmlspecialchars($last_name); ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">

            <hr>
            <h3>Change Password (optional)</h3>

            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Required if changing password">

            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Leave blank to keep existing">

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">

            <button type="submit" class="btn btn-dark" style="margin-top: 10px;">Save Changes</button>
        </form>

        <hr style="margin-top:25px;">

        <h3>Danger Zone</h3>
        <p>Deleting your account is permanent.</p>

        <form method="POST" action="delete_account.php"
              onsubmit="return confirm('Are you sure you want to delete your account?');">
            <button class="btn btn-dark">Delete My Account</button>
        </form>
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
