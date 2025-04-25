<?php
header('Content-Type: application/json');
include 'db_connection.php';

$query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : "%";
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// SQL query to fetch matching interactions
$sql = "
    SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_type AS interaction_type, c.notes 
    FROM calls c
    JOIN customers cu ON cu.customer_id = c.customer_id
    WHERE c.notes LIKE ?";

if ($type == 'meeting' || $type == 'all') {
    $sql .= " 
    UNION 
    SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_type AS interaction_type, m.notes 
    FROM meetings m
    JOIN customers cu ON cu.customer_id = m.customer_id
    WHERE m.notes LIKE ?";
}

if ($type == 'email' || $type == 'all') {
    $sql .= "
    UNION 
    SELECT 'Email' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_type AS interaction_type, c.notes 
    FROM calls c
    JOIN customers cu ON cu.customer_id = c.customer_id
    WHERE c.notes LIKE ?";
}

$sql .= " ORDER BY date DESC";

// Prepare and bind
$stmt = $conn->prepare($sql);
if ($type == 'meeting' || $type == 'all') {
    $stmt->bind_param("ss", $query, $query);
} else {
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
