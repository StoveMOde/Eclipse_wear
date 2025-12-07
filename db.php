<?php
$host = "localhost";
$user = "root";
$pass = "root"; // default for MAMP
$dbname = "eclipse_wear"; // make sure this matches phpMyAdmin database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
