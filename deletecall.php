<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST); // DEBUG LINE

    if (!isset($_POST['call_id'])) {
        echo "Call ID not provided.";
        exit;
    }

    $call_id = $_POST['call_id'];

    // Include the database connection file
    require 'db_connection.php'; 

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete specific call
    $delete_sql = "DELETE FROM calls WHERE call_id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $call_id);
        
        if ($stmt->execute()) {
            echo "Call deleted successfully.";
        } else {
            echo "Error deleting call: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing delete statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
