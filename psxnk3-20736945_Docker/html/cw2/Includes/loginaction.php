<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/audit_trail.php'); 

// Checking the form values
$name = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $name, $password);
$stmt->execute();
$result = $stmt->get_result();

// Checking credentials
if ($result->num_rows > 0){
    $row = $result->fetch_assoc();

    session_start();
    $_SESSION['user_id'] = $row['username']; 
    $_SESSION['role'] = $row['role'];


    // Audit log - success
    // We log that the user successfully entered the system.
    logActivity(
        $conn, 
        $row['username'], // User who performed action
        'LOGIN', // Action type
        'Users Table', // Target
        'User logged in successfully' // Description
    );

    // Redirect logic
    if ($row['role'] == 'admin') {
        header("Location: ../dashboard.php");
    } else {
        header("Location: ../dashboard.php");
    }
    exit;
}
else {

    // This is to check if there were loging attempts
    logActivity(
        $conn, 
        $name . " (Unknown)", // Mark as unknown/unverified user
        'LOGIN_FAILED', // Action type
        'Users Table', // Target
        'Failed login attempt using username: ' . $name
    );

    $msg = "Invalid username or password";
    header("Location: ../index.php?error=" . urlencode($msg)); 
    exit;
}

$stmt->close();
?>