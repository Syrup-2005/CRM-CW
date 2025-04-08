<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

require "db_connection.php"; // Include database connection

$user_id = $_SESSION["user_id"];
$query = $conn->prepare("SELECT Name, Company, Email, PhoneNumber, Address, profile_picture FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(["error" => "User not found"]);
}
?>