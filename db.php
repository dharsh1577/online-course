<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session for user authentication
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login page if user is not logged in and the current file is not 'login.php'
if (basename($_SERVER['PHP_SELF']) != 'login.php' && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
