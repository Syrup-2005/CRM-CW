<?php 
header('Content-Type: application/json');
include 'db_connection.php';

// SQL query for upcoming meetings, with customer name and meeting_id
$query = "
    SELECT 
        meetings.meeting_id, 
        meetings.customer_id, 
        customers.customer_name, 
        meetings.meeting_date, 
        meetings.meeting_purpose, 
        meetings.notes
    FROM meetings
    INNER JOIN customers ON meetings.customer_id = customers.customer_id
    WHERE meetings.meeting_date >= CURDATE()
    ORDER BY meetings.meeting_date ASC
";

$result = mysqli_query($conn, $query);

$meetings = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $meetings[] = $row;
    }
}

echo json_encode($meetings);
?>
