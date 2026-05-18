<?php 
// Resume the session to know who is logged in

session_start();

require_once(__DIR__ . '/Includes/config.php');
require_once(__DIR__ . '/Includes/audit_trail.php');

// Checking if the user is actually logged in before login them out

if(isset($_SESSION['user_id'])){
    $current_user = $_SESSION['user_id'];

    logActivity(
            $conn, 
            $current_user, 
            'LOGOUT', 
            'System', 
            "User logged out successfully"
        );
}
$_SESSION = array();
session_destroy();

// Redirecting to the login page
header("Location: index.php?msg=loggedout");
exit();
?>