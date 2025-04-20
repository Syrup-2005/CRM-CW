<?php
header('Content-Type: application/json');
include 'db_connection.php';

//Change this according to db (reminder)
$query = "SELECT * FROM calls WHERE datetime >= NOW() ORDER BY datetime ASC";
$result = mysqli_query($conn, $query);

$calls = [];

while ($row = mysqli_fetch_assoc($result)) {
    $calls[] = $row;
}

echo json_encode($calls);
?>
