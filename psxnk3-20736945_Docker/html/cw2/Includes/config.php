<?php
// MySQL database information

$servername = "mariadb"; 
$username = "root";
$password = "rootpwd";     
$dbname = "hospital_database";      

// 1. Create the connection 
$conn = new mysqli($servername, $username, $password, $dbname);

// 2. Check if the connection worked
if ($conn->connect_error) {
    // Manually trigger a 500 error state
    http_response_code(500); 
    // Load the error page and stop
    require_once(__DIR__ . '/../error.php'); 
    exit();
}


?>