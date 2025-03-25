<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "8518";
$dbname = "abb_robotics"; 

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
