<?php
session_start();
require 'db_connection.php'; // Ensure database connection is available

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT Name, Email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Store user data in session
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;

// Redirect to dashboard.html
header("Location: dashboard.html");
exit();
?>
