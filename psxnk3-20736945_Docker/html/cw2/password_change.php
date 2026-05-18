<?php
    session_start();
    $page_title = "Change Password";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $message = "";
    $message_type = "";

    // Only logged in users should access this
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    // Handle Form Submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username    = trim($_POST['username']);
        $oldpassword = $_POST['password'];
        $newpassword = $_POST['newpassword'];

        if (empty($username) || empty($oldpassword) || empty($newpassword)) {
            $message = "All fields are required.";
            $message_type = "error";
        } else {
            // Check if old credentials are correct
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $oldpassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt->close();
                
                // Update to new password
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username= ?");
                $update_stmt->bind_param("ss", $newpassword, $username);
                
                try {
                    $audit_package = [
                        'action' => 'CHANGE_PASSWORD', 
                        'target' => $username, 
                        'desc'   => "User changed their password"
                    ];

                    if (executeAndLog($conn, $update_stmt, $audit_package)) {
                        // Success! Redirect to clear POST data
                        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=changed");
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
                $message = "Invalid username or current password.";
                $message_type = "error";
                $stmt->close();
            }
        }
    }

    // Handle Success Message from Redirect
    if (isset($_GET['msg']) && $_GET['msg'] == 'changed') {
        $message = "Password changed successfully.";
        $message_type = "success";
    }

    require_once('Includes/header.php');
    require_once('Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Change Password</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0" style="max-width: 500px;">
            <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                <h5 class="card-title text-primary fw-bold">
                    <i class="bi bi-shield-lock"></i> Security Credentials
                </h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                               placeholder="Enter your username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Current Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter current password" required>
                    </div>

                    <div class="mb-4">
                        <label for="newpassword" class="form-label fw-bold">New Password</label>
                        <input type="password" class="form-control" id="newpassword" name="newpassword" 
                               placeholder="Enter new password" required>
                        <div class="form-text">Make sure it's secure and memorable.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<?php require_once('Includes/footer.php'); ?>