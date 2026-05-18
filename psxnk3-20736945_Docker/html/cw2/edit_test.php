<?php
    session_start();
    $page_title = "Edit Test Details";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $message = "";
    $message_type = "";
    $test_id = "";
    $test_name = "";

    //Ensure user is logged in
    if (!isset($_SESSION['user_id'])){
       header("Location: index.php");
       exit();
    }

    // Save changes (When the user clicks "Update")
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $test_id = $_POST['testid'] ?? '';
        // trim() to remove accidental whitespace
        $test_name = trim($_POST['testname'] ?? '');

        if (empty($test_name)) {
            $message = "Test name cannot be empty.";
            $message_type = "error";
        } else {
            // Check for duplicates (same name, different ID)
            $check_stmt = $conn->prepare("SELECT testid FROM test WHERE testname = ? AND testid != ?");
            $check_stmt->bind_param("si", $test_name, $test_id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                // Duplicate found
                $message = "Error: The test name '" . htmlspecialchars($test_name) . "' already exists.";
                $message_type = "error";
                $check_stmt->close(); 
            } else {
                $check_stmt->close();

                // 2. Proceed with update
                $stmt = $conn->prepare("UPDATE test SET testname = ? WHERE testid = ?");
                $stmt->bind_param("si", $test_name, $test_id);

                $audit = [
                    'action' => 'UPDATE_TEST', 
                    'target' => $test_id, 
                    'desc'   => "Renamed test ID $test_id to '$test_name'"
                ];

                if (executeAndLog($conn, $stmt, $audit)) {
                    $message = "Test updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error updating test database.";
                    $message_type = "error";
                }
                $stmt->close();
            }
        }
    }

    // Load data (When the user first arrives)
    if (isset($_GET['id'])) {
        $test_id = $_GET['id'];
    } elseif (isset($_POST['testid'])) {
        $test_id = $_POST['testid'];
    }

    // Fetch from DB if there's no error 
    if (!empty($test_id) && $message_type !== 'error') {
        $stmt = $conn->prepare("SELECT testname FROM test WHERE testid = ?");
        $stmt->bind_param("i", $test_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $test_name = $row['testname'];
        } else {
            if ($message_type !== 'success') {
                $message = "Test not found.";
                $message_type = "error";
            }
        }
        $stmt->close();
    } elseif (empty($test_id)) {
        header("Location: test_lookup.php");
        exit();
    }
?>

<?php 
    require_once('Includes/header.php'); 
    require_once('Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Edit Test Details</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 border-0" style="max-width: 600px;">
            <div class="card-body p-4 bg-light rounded">
                
                <form method="POST">
                    <input type="hidden" name="testid" value="<?php echo htmlspecialchars($test_id); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Test ID</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($test_id); ?>" disabled 
                               style="background-color: #e9ecef;">
                        <div class="form-text">Internal ID cannot be changed.</div>
                    </div>

                    <div class="mb-3">
                        <label for="testname" class="form-label fw-bold">Test Name</label>
                        <input type="text" class="form-control" name="testname" id="testname" 
                               value="<?php echo htmlspecialchars($test_name); ?>" required>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                        
                        <a href="test_lookup.php" class="btn btn-outline-secondary ms-2">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</main>

<?php require_once('Includes/footer.php'); ?>