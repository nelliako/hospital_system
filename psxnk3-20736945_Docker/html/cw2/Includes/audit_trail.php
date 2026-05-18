<?php

// This function writes to the changes_log table
function logActivity($conn, $userid, $action, $targetid, $details) {

     /* * Reference/Inspiration: OWASP (2025) 'Logging Cheat Sheet'.
    * i.e. capturing the 'Source IP' ($_SERVER['REMOTE_ADDR']) is a mandatory requirement 
    * for security logs to establish the 'where' in the 5 Ws of auditing.
    */
    // Capture IP just in case 
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO changes_log (userid, actiontype, targetid, details, ipaddress) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $userid, $action, $targetid, $details, $ip);
    $stmt->execute();
    $stmt->close();
}



/* * Reference: Refactoring.Guru (2025) 'Decorator Design Pattern'.
 * Implements a procedural variation of the decorator/wrapper pattern.
 * Similar to how the Decorator class in the reference wraps a Component::operation(),
 * this function wraps the $stmt->execute() method. It 'decorates' the core database action with an additional logging responsibility, ensuring separation of concerns.
 */

// The function that is used in the main code 
function executeAndLog($conn, $stmt, $audit_details){
    // Checking if the user is logged in
    $userid = $_SESSION['user_id'] ?? 'SYSTEM';

    // Trying to execute the main db change
    if ($stmt->execute()){

        // If successful, write it to the changes_log
        logActivity(
            $conn,
            $userid,
            $audit_details['action'],
            $audit_details['target'],
            $audit_details['desc']

        );

        // Return true so that the page knew it worked
        return True;
    }else{
        // If the db change failed - false
        return False;
    }
}
?>
