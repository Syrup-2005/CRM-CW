<?php
header('Content-Type: application/json');
include 'db_connection.php';

// Get the query, type, and purpose parameters from the GET request
$query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : "%";
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$purpose = isset($_GET['purpose']) ? "%" . $_GET['purpose'] . "%" : "%";

// Base SQL query to fetch interactions based on type and purpose
$sql = "";

// All interaction types
if ($type == 'all') {
    // Query for Calls
    $sql = "
        SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_purpose, c.notes 
        FROM calls c
        JOIN customers cu ON cu.customer_id = c.customer_id
        WHERE c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR) AND c.notes LIKE ? AND c.call_purpose LIKE ?";
    
    $sql .= " UNION ";
    
    // Query for Meetings
    $sql .= "
        SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_purpose, m.notes 
        FROM meetings m
        JOIN customers cu ON cu.customer_id = m.customer_id
        WHERE m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR) AND m.notes LIKE ? AND m.meeting_purpose LIKE ?";
} else {
    // Filter based on selected interaction type
    if ($type == 'call') {
        $sql = "
            SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_purpose, c.notes 
            FROM calls c
            JOIN customers cu ON cu.customer_id = c.customer_id
            WHERE c.notes LIKE ? AND c.call_purpose LIKE ? AND c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
    } elseif ($type == 'meeting') {
        $sql = "
            SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_purpose, m.notes 
            FROM meetings m
            JOIN customers cu ON cu.customer_id = m.customer_id
            WHERE m.notes LIKE ? AND m.meeting_purpose LIKE ? AND m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
    }
}

// Prepare and bind the SQL query
$stmt = $conn->prepare($sql);

// Bind the parameters
if ($type == 'all') {
    $stmt->bind_param("ssss", $query, $purpose, $query, $purpose);
} else {
    $stmt->bind_param("ss", $query, $purpose);
}

$stmt->execute();
$result = $stmt->get_result();

$interactions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $interactions[] = [
            'customer_id' => $row['customer_id'],
            'customer_name' => $row['customer_name'],
            'email' => $row['email'],
            'date' => $row['date'],
            'type' => $row['type'],
            'interaction_purpose' => $row['interaction_purpose'],
            'notes' => $row['notes']
        ];
    }
    
}

echo json_encode($interactions);

$conn->close();
?>
