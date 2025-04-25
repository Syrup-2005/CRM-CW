<?php
include 'db_connection.php';

$suggestions = [];

// Fetch recent words from calls
$result1 = $conn->query("SELECT notes FROM calls ORDER BY created_at DESC LIMIT 10");
while ($row = $result1->fetch_assoc()) {
    $words = preg_split('/[\s,.;:!?]+/', $row['notes']);
    $suggestions = array_merge($suggestions, $words);
}

// Fetch recent words from meetings
$result2 = $conn->query("SELECT notes FROM meetings ORDER BY meeting_date DESC LIMIT 10");
while ($row = $result2->fetch_assoc()) {
    $words = preg_split('/[\s,.;:!?]+/', $row['notes']);
    $suggestions = array_merge($suggestions, $words);
}

// Clean up and get top 10 unique keywords
$suggestions = array_unique(array_filter($suggestions, fn($word) => strlen($word) > 3));
$suggestions = array_slice($suggestions, 0, 10);

foreach ($suggestions as $keyword) {
    echo "<span class='suggestion-bubble'>" . htmlspecialchars($keyword) . "</span>";
}

$conn->close();
?>
