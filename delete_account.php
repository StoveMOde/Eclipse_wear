<?php
session_start();
require 'db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete user from database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Clear user-related session data
unset($_SESSION['user_id'], $_SESSION['first_name'], $_SESSION['cart']);

// Set a one-time success message for the login page
$_SESSION['flash_success'] = "Your account has been deleted.";

// Redirect to login page
header("Location: login.php");
exit();
