<?php
    session_start();
    $page_title = "Reset Password";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $message = "";
    $message_type = "";

    // Handle Form Submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = trim($_POST['username'] ?? '');
        $newpassword = $_POST['newpassword'] ?? '';

        if (empty($username) || empty($newpassword)) {
            $message = "Please fill in all fields.";
            $message_type = "error";
        } else {
            // Check if user exists
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
            
            
            $stmt->bind_param("s", $username); 
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt->close();

                // Update the password
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $newpassword, $username);
                
                try {
                    $audit_package = [
                        'action' => 'RESET_PASSWORD', 
                        'target' => $username, 
                        'desc'   => "User reset password"
                    ];

                    if (executeAndLog($conn, $update_stmt, $audit_package)) {
                        // Success! Redirect to self
                        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=reset");
                        exit();
                    } else {
                        $message = "Error updating database.";
                        $message_type = "error";
                    }
                } catch (mysqli_sql_exception $e) {
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                }
                
                if (isset($update_stmt)) $update_stmt->close();

            } else {
                $message = "Invalid username.";
                $message_type = "error";
                $stmt->close();
            }
        }
    }

    if (isset($_GET['msg']) && $_GET['msg'] == 'reset') {
        $message = "Password reset successfully. You may now login.";
        $message_type = "success";
    }

    require_once('Includes/header.php');
    // Sidebar excluded to match Login page style
?>

<style>
    body {
        padding-left: 0; /* Remove sidebar gap */
        background-color: var(--nhs-grey-5); /* Force grey background */
    }

    
    .nhs-header,
    .nhs-footer {
        margin-left: 0;
        width: 100%;
    }


    .login-wrapper {
        min-height: 80vh; 
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .login-card {
        width: 100%;
        max-width: 450px; 
    }
</style>

<div class="login-wrapper">
    <div class="card shadow-sm login-card border-0">
        
        <div class="card-header bg-white text-center py-4 border-bottom-0">
             <h2 class="h3 fw-bold text-primary">Reset Password</h2>
             <p class="text-muted mb-0">Enter your username and new password</p>
        </div>

        <div class="card-body p-4">
            
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> d-flex align-items-center" role="alert">
                    <span class="me-2"><?php echo ($message_type == 'success') ? '' : '⚠️'; ?></span>
                    <div><?php echo htmlspecialchars($message); ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control form-control-lg" name="username" id="username" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="newpassword" class="form-label fw-bold">New Password</label>
                    <input type="password" class="form-control form-control-lg" name="newpassword" id="newpassword" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-nhs btn-lg">Update Password</button>
                    
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='index.php';">
                        Back to Login
                    </button>
                </div>

            </form>
        </div>
        
        <div class="card-footer bg-light text-center py-3">
            <small class="text-muted">Authorized access only</small>
        </div>
    </div>
</div>

<?php require_once('Includes/footer.php'); ?>