<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $meeting_date = mysqli_real_escape_string($conn, $_POST['meeting_date']);
    $meeting_purpose = mysqli_real_escape_string($conn, $_POST['meeting_purpose']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

    $query = "INSERT INTO meetings (customer_id, meeting_date, meeting_purpose, notes, user_id)
              VALUES ('$customer_id', '$meeting_date', '$meeting_purpose', '$notes', '$admin_id')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Meeting scheduled successfully!');
            window.location.href = 'addmeeting.html';
        </script>";
    } else {
        echo "<script>
            alert('Error: Could not schedule the meeting. " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    header('Location: meetings.html');
    exit();
}
?>