<?php
require 'db_connection.php';

// Fetch all customers
$sql = "SELECT customer_id, customer_name FROM customers";
$result = mysqli_query($conn, $sql);

$customers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $customers[] = $row;
}

echo json_encode($customers);
?>
