<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT Name, Email, Company, Position, Phone_number, Address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $company, $position, $phone_number, $address);
$stmt->fetch();
$stmt->close();

echo json_encode([
    "name" => $name,
    "email" => $email,
    "company" => $company,
    "position" => $position,
    "phone" => $phone_number,
    "address" => $address
]);
?>
