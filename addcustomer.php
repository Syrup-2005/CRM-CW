<?php
session_start();
require 'db_connection.php';

try {
    // Get the latest customer ID
    $stmt = $conn->query("SELECT customer_id FROM customers ORDER BY customer_id DESC LIMIT 1");
    $lastID = $stmt->fetch_assoc()['customer_id'] ?? null;

    if ($lastID) {
        $newID = str_pad((int)$lastID + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newID = '0001';
    }

    // Get form input
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $company = $_POST['company'] ?? '';
    $address = $_POST['address'] ?? '';
    $type_of_work = $_POST['typeofwork'] ?? '';
    $admin_id = $_POST['admin_id'] ?? '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO customers (customer_id, customer_name, email, phone_no, company, address, type_of_work, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $newID, $name, $email, $phone, $company, $address, $type_of_work, $admin_id);

    if ($stmt->execute()) {
        header("Location: customer.html");
        exit(); 
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
?>
