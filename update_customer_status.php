<?php
header('Content-Type: application/json');
require_once "db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"] ?? null;
$status = $data["status"] ?? null;

if (!$id || !$status) {
    echo json_encode(["message" => "Invalid data", "debug" => $data]);
    exit;
}

$stmt = $conn->prepare("UPDATE customers SET status = ? WHERE customer_id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Status updated successfully"]);
} else {
    echo json_encode(["message" => "Failed to update status", "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
