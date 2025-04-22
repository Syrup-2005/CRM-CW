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
    $delete_calls_sql = "DELETE FROM calls WHERE customer_id = ?";
    if ($stmt_calls = $conn->prepare($delete_calls_sql)) {
        $stmt_calls->bind_param("i", $customer_id);
        
        if ($stmt_calls->execute()) {
            echo "Related calls deleted successfully.<br>";
        } else {
            echo "Error deleting related calls: " . $stmt_calls->error . "<br>";
        }
        $stmt_calls->close();
    } else {
        echo "Error preparing delete calls statement: " . $conn->error . "<br>";
    }

    // Delete related meetings from the meetings table
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
        echo "Error preparing delete meetings statement: " . $conn->error . "<br>";
    }

    // Delete the customer from the customers table
    $sql = "DELETE FROM customers WHERE customer_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $customer_id); // assuming `customer_id` is INT
        
        if ($stmt->execute()) {
            echo "Customer deleted successfully.";
        } else {
            echo "Error deleting customer: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the delete customer statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
