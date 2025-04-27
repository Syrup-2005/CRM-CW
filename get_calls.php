<?php 
header('Content-Type: application/json');
include 'db_connection.php';

// SQL query for upcoming calls, with customer name and call_id
$query = "
    SELECT 
        calls.call_id, 
        calls.customer_id, 
        customers.customer_name, 
        calls.datetime, 
        calls.call_purpose, 
        calls.notes,
        calls.created_at
    FROM calls
    INNER JOIN customers ON calls.customer_id = customers.customer_id
    WHERE calls.datetime >= CURDATE()
    ORDER BY calls.datetime ASC
";

$result = mysqli_query($conn, $query);

$calls = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $calls[] = $row;
    }
}

echo json_encode($calls);
?>
