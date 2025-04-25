<?php
$conn = new mysqli("localhost", "root", "root", "abb_robotics");
if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}
?>
