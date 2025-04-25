<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $datetime = mysqli_real_escape_string($conn, $_POST['datetime']);
    $call_purpose = mysqli_real_escape_string($conn, $_POST['call_purpose']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $query = "INSERT INTO calls (customer_id, datetime, call_purpose, notes)
              VALUES ('$customer_id', '$datetime', '$call_purpose', '$notes')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Call scheduled successfully!');
            window.location.href = 'calls.html';
        </script>";
    } else {
        echo "<script>
            alert('Error: Could not schedule the call. " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    header('Location: calls.html');
    exit();
}
?>
