<?php
session_start();
require 'db_connection.php'; // Include database connection

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("405 Method Not Allowed");
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

// Validate inputs
if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: login.html");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: login.html");
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: login.html");
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "Email is already registered.";
    $stmt->close();
    header("Location: login.html");
    exit();
}

if (!$stmt->execute()) {
    error_log("MySQL Error: " . $stmt->error);
}

if (!$stmt) {
    error_log("Prepare statement failed: " . $conn->error);
    $_SESSION['error'] = "Something went wrong. Please try again.";
    header("Location: login.html");
    exit();
}

$stmt->close();

// Insert user into the database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['success'] = "Account created successfully! You can now log in.";
    $stmt->close();
    $conn->close();
    header("Location: login.html"); // Ensure this matches the actual login page
    exit();
} else {
    $_SESSION['error'] = "Something went wrong. Please try again.";
    $stmt->close();
    $conn->close();
    header("Location: login.html");
    exit();
}
?>
