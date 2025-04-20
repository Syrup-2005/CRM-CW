<?php
// db_connection.php - Database connection
require 'db_connection.php';

// Query to get user IDs and usernames for admins
$sql = "SELECT user_id, Name FROM users";
$result = $conn->query($sql);

// Check if query succeeded
if ($result->num_rows > 0) {
    $admins = [];
    while ($row = $result->fetch_assoc()) {
        $admins[] = [
            'user_id' => $row['user_id'],
            'Name' => $row['Name']
        ];
    }
    echo json_encode($admins); // Return results as JSON
} else {
    echo json_encode(['error' => 'No admins found']);
}

$conn->close();
?>