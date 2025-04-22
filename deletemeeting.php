<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST); // DEBUG LINE

    $customer_id = $_POST['customer_id'];

    // Include the database connection file
    require 'db_connection.php'; 

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete related calls from the calls table
    $delete_meetings_sql = "DELETE FROM meetings WHERE customer_id = ?";
    if ($stmt_meetings = $conn->prepare($delete_meetings_sql)) {
        $stmt_meetings->bind_param("i", $customer_id);
        
        if ($stmt_meetings->execute()) {
            echo "Related meetings deleted successfully.<br>";
        } else {
            echo "Error deleting related meetings: " . $stmt_meetings->error . "<br>";
        }
        $stmt_meetings->close();
    } else {
        echo "Error preparing delete meetingss statement: " . $conn->error . "<br>";
    }

    // Close the database connection
    $conn->close();
}
?>
