<?php
header('Content-Type: application/json');
include 'db_connection.php';

// SQL query for upcoming meetings
$query = "SELECT customer_id, meeting_date, meeting_purpose, notes 
          FROM meetings 
          WHERE meeting_date >= CURDATE()
          ORDER BY meeting_date ASC";

$result = mysqli_query($conn, $query);

$meetings = [];

while ($row = mysqli_fetch_assoc($result)) {
    $meetings[] = $row;
}

echo json_encode($meetings);
?>
