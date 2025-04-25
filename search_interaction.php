<?php
header('Content-Type: application/json');
include 'db_connection.php';

$query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : "%";
$type = $_GET['type'] ?? 'all';
$purpose = isset($_GET['purpose']) ? "%" . $_GET['purpose'] . "%" : "%";

$sql = "";
$params = [];
$types = "";

// ALL interactions: use UNION
if ($type === 'all') {
    $sql = "
        SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_purpose, c.notes
        FROM calls c
        JOIN customers cu ON cu.customer_id = c.customer_id
        WHERE c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)
        AND (c.notes LIKE ? OR c.call_purpose LIKE ? OR cu.customer_name LIKE ? OR cu.email LIKE ? OR c.customer_id LIKE ? OR c.datetime LIKE ?)
        AND c.call_purpose LIKE ?

        UNION

        SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_purpose, m.notes
        FROM meetings m
        JOIN customers cu ON cu.customer_id = m.customer_id
        WHERE m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR)
        AND (m.notes LIKE ? OR m.meeting_purpose LIKE ? OR cu.customer_name LIKE ? OR cu.email LIKE ? OR m.customer_id LIKE ? OR m.meeting_date LIKE ?)
        AND m.meeting_purpose LIKE ?
    ";

    $params = [$query, $query, $query, $query, $query, $query, $purpose,
               $query, $query, $query, $query, $query, $query, $purpose];
    $types = str_repeat("s", count($params));
} else {
    // Specific type: call or meeting
    if ($type === 'call') {
        $sql = "
            SELECT 'Call' AS type, c.customer_id, cu.customer_name, cu.email, c.datetime AS date, c.call_purpose AS interaction_purpose, c.notes
            FROM calls c
            JOIN customers cu ON cu.customer_id = c.customer_id
            WHERE c.datetime >= DATE_SUB(NOW(), INTERVAL 10 YEAR)
            AND (c.notes LIKE ? OR c.call_purpose LIKE ? OR cu.customer_name LIKE ? OR cu.email LIKE ? OR c.customer_id LIKE ? OR c.datetime LIKE ?)
            AND c.call_purpose LIKE ?
        ";
    } else {
        $sql = "
            SELECT 'Meeting' AS type, m.customer_id, cu.customer_name, cu.email, m.meeting_date AS date, m.meeting_purpose AS interaction_purpose, m.notes
            FROM meetings m
            JOIN customers cu ON cu.customer_id = m.customer_id
            WHERE m.meeting_date >= DATE_SUB(NOW(), INTERVAL 10 YEAR)
            AND (m.notes LIKE ? OR m.meeting_purpose LIKE ? OR cu.customer_name LIKE ? OR cu.email LIKE ? OR m.customer_id LIKE ? OR m.meeting_date LIKE ?)
            AND m.meeting_purpose LIKE ?
        ";
    }

    $params = [$query, $query, $query, $query, $query, $query, $purpose];
    $types = str_repeat("s", count($params));
}

// Prepare, bind, and execute
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode([]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$interactions = [];

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

echo json_encode($interactions);
$conn->close();
?>
