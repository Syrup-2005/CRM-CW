<?php
// Start the session
session_start();

// Include your DB connection file
include 'db_connection.php'; // Make sure this file connects to your MySQL database

// Get user ID from session (you can change this if using another method)
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("User not logged in.");
}

// Get POST data safely
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$company = trim($_POST['company']);
$position = trim($_POST['position']);
$phone_number = trim($_POST['phone_number']);
$address = trim($_POST['address']);

// Validate required fields
if (empty($name) || empty($email)) {
    die("Name and Email are required.");
}

// Prepare SQL statement
$sql = "UPDATE users SET 
            name = ?, 
            email = ?, 
            company = ?, 
            position = ?,
            phone_number = ?, 
            address = ?
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $name, $email, $company, $position, $phone_number, $address, $user_id);

// Execute and check
if ($stmt->execute()) {
    echo "Profile updated successfully.";
    header("Location: profile.html"); //redirect to profile.html after sucessfull update
} else {
    echo "Error updating profile: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
