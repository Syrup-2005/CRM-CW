<?php
session_start();
require 'db_connection.php'; // Include database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: login.html");
        exit();
    }

    // Prepare SQL query to check user credentials
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Successful login: Store user session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to login page
    header("Location: login.html");
    exit();
}
?>

