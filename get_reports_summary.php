<?php
include 'db_connection.php'; // Assuming the connection code is in this file

// Query to fetch the report summary
$query = "SELECT 
            (SELECT COUNT(*) FROM meetings) AS total_meetings,
            (SELECT COUNT(*) FROM meetings WHERE status = 'upcoming') AS upcoming_meetings,
            (SELECT COUNT(*) FROM meetings WHERE status = 'completed') AS completed_meetings,
            (SELECT COUNT(*) FROM calls) AS total_calls,
            (SELECT COUNT(*) FROM calls WHERE status = 'upcoming') AS upcoming_calls,
            (SELECT COUNT(*) FROM calls WHERE status = 'completed') AS completed_calls
          ";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>
