<?php
header('Content-Type: application/json');
include 'db_connection.php';

$query = "SELECT * FROM customers ORDER BY customer_name ASC";
$result = mysqli_query($conn, $query);

$customers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $customers[] = $row;
}

echo json_encode($customers);
?>
