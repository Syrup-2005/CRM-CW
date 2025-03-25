<?php
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $_SESSION['error'] = "Email is required";
        header('Location: forgot_password.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header('Location: forgot_password.php');
        exit();
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "8518", "abb_robotics");

    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, generate token
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in a password_resets table (Better security)
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, email, reset_token, reset_expiry) VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE reset_token=?, reset_expiry=?");
        $stmt->bind_param("isssss", $user['user_id'], $email, $token, $expiry, $token, $expiry);
        $stmt->execute();

        // Send reset email (Replace with SMTP in production)
        $reset_link = "http://localhost/CRM/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n" . $reset_link;
        $headers = "From: no-reply@yourcrm.com\r\n";

        // Attempt to send email
        if (@mail($email, $subject, $message, $headers)) {
            $_SESSION['success'] = "Password reset link sent to your email.";
        } else {
            $_SESSION['error'] = "Failed to send reset email. Please check mail settings.";
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }
    
    // Clean up
    $stmt->close();
    $conn->close();

    // Redirect back
    header('Location: forgot_password.php');
    exit();
}
?>

