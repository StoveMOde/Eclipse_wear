<?php include 'auth-protect.php'; ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="yellow-bg">

<header>
<nav>
<ul class="nav-links">
<li><a href="logout.php" class="btn btn-dark">Logout</a></li>
</ul>
</nav>
</header>

<h2>Welcome, <?php echo $_SESSION['user']; ?>!</h2>
<p>You are now logged in.</p>

</body>
</html>
