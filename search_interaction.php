<?php
header('Content-Type: application/json');
include 'db_connection.php';

// Get the query and type parameters from the GET request
$query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : "%";
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Base SQL query to fetch interactions based on type
$sql = "";

if ($type == 'all') {
    // When the type is 'all', get results from all interactions (calls, meetings, emails)
    $sql = "
        SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_type, c.notes 
        FROM calls c
        JOIN customers cu ON cu.customer_id = c.customer_id
        WHERE c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";

    $sql .= " UNION ";
    
    $sql .= "
        SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_type, m.notes 
        FROM meetings m
        JOIN customers cu ON cu.customer_id = m.customer_id
        WHERE m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";

    $sql .= " UNION ";

    $sql .= "
        SELECT 'Email' AS type, e.customer_id, cu.customer_name, cu.email, e.datetime AS date, e.email_type AS interaction_type, e.notes 
        FROM emails e
        JOIN customers cu ON cu.customer_id = e.customer_id
        WHERE e.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
} else {
    // Filter based on the selected interaction type
    if ($type == 'call') {
        $sql = "
            SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_type, c.notes 
            FROM calls c
            JOIN customers cu ON cu.customer_id = c.customer_id
            WHERE c.notes LIKE ? AND c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
    } elseif ($type == 'meeting') {
        $sql = "
            SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_type, m.notes 
            FROM meetings m
            JOIN customers cu ON cu.customer_id = m.customer_id
            WHERE m.notes LIKE ? AND m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
    } elseif ($type == 'email') {
        $sql = "
            SELECT 'Email' AS type, e.customer_id, cu.customer_name, cu.email, e.datetime AS date, e.email_type AS interaction_type, e.notes 
            FROM emails e
            JOIN customers cu ON cu.customer_id = e.customer_id
            WHERE e.notes LIKE ? AND e.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)";
    }
}

// Prepare and bind the SQL query
$stmt = $conn->prepare($sql);

if ($type == 'all') {
    // No keyword binding for the 'all' case
    // Only filter by date if no keyword is provided
    // No binding needed since the query is handling all data types
} else {
    // Bind the query parameter for 'like' search
    $stmt->bind_param("s", $query);
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
            'interaction_type' => $row['interaction_type'],
            'notes' => $row['notes']
        ];
    }
}

echo json_encode($interactions);

$conn->close();
?>
