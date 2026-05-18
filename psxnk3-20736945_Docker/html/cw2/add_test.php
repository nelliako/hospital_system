<?php
    session_start();
    $page_title = "Add Test";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $message = "";
    $message_type = "";

    // Session Logic
    if (!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 'QM345'; 
    }

    // Handle POST
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
        $testname = trim($_POST['testname'] ?? '');

        if (empty($testname)){
            $message = "Please fill in test name";
            $message_type = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO test (testname) VALUES (?)");
            $stmt->bind_param('s', $testname);
            
            try {
                $audit_package = [
                    'action' => 'ADD_TEST', 
                    'target' => $testname, 
                    'desc'   => "Doctor added a new test"
                ];

                if (executeAndLog($conn, $stmt, $audit_package)) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
                    exit();
                } else {
                    $message = "Error adding test.";
                    $message_type = "error";
                }

            } catch (mysqli_sql_exception $e) {
                $message = "Database Error: " . $e->getMessage();
                $message_type = "error";
            }
            $stmt->close();
        }   
    }

    // Handle Success Message
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        $message = "Test added successfully!";
        $message_type = "success"; 
    }

    require_once('Includes/header.php'); 
    require_once('Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Add New Test</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 border-0">
            <div class="card-body p-4 bg-light rounded">
                
                <form method="POST" style="max-width: 600px;">
                    <div class="mb-3">
                        <label for="testname" class="form-label fw-bold">Test Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="testname" id="testname" 
                               placeholder="E.g. Full Blood Count" required>
                        <div class="form-text">Enter the official name of the medical test.</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Test</button>
                        <a href="test_lookup.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</main>

<?php require_once('Includes/footer.php'); ?>