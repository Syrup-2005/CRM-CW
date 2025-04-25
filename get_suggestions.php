<?php
include 'db_connection.php';

$suggestions = [];

// Fetch a mix of notes, names, emails, and IDs from both tables
$queries = [
    "SELECT c.notes, cu.customer_name, cu.email, c.customer_id
     FROM calls c
     JOIN customers cu ON cu.customer_id = c.customer_id
     ORDER BY RAND() LIMIT 10",

    "SELECT m.notes, cu.customer_name, cu.email, m.customer_id
     FROM meetings m
     JOIN customers cu ON cu.customer_id = m.customer_id
     ORDER BY RAND() LIMIT 10"
];

foreach ($queries as $sql) {
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        // Break notes into words
        $noteWords = preg_split('/[\s,.;:!?]+/', $row['notes'] ?? '');
        $suggestions = array_merge($suggestions, $noteWords);

        // Include other fields as whole phrases
        $suggestions[] = $row['customer_name'];
        $suggestions[] = $row['email'];
        $suggestions[] = $row['customer_id'];
    }
}

// Clean: remove empty/short words, keep unique, shuffle and limit to 10
$suggestions = array_unique(array_filter($suggestions, fn($word) => strlen($word) > 2));
shuffle($suggestions);
$suggestions = array_slice($suggestions, 0, 10);

foreach ($suggestions as $keyword) {
    echo "<span class='suggestion-bubble'>" . htmlspecialchars($keyword) . "</span>";
}

$conn->close();
?>
