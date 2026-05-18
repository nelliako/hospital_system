<?php
    session_start();
    $page_title = "Search Tests";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $search_result = null; 
    $message = "";
    $message_type = "";

    // 1. Handle Deletion (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete'){
        $delete_id = $_POST['testid'] ?? '';

        if (!empty($delete_id)) {
            $stmt = $conn->prepare("DELETE FROM test WHERE testid = ?");
            $stmt->bind_param("i", $delete_id);

            $audit = [
                'action' => 'DELETE_TEST',
                'target' => $delete_id,
                'desc'   => "Deleted test ID $delete_id via Search Page"
            ];

            try {

                if (executeAndLog($conn, $stmt, $audit)) {
                    $message = "Test deleted successfully.";
                    $message_type = "success";
                }
            } catch (mysqli_sql_exception $e) {
                // Catch the crash if it happens
                
                // Check specifically for Foreign Key Error (1451)
                if ($e->getCode() == 1451) {
                    $message = "<strong>Action Blocked:</strong> Cannot delete this test because it is currently assigned to patients.<br>To preserve medical history, you must remove this test from patient records first.";
                    $message_type = "error";
                } else {
                    // All other errors
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                }
            }
            $stmt->close();
        }
    }

    // 2. Handle Search (GET)
    if (isset($_GET['query'])){
        $search_term = mysqli_real_escape_string($conn, $_GET['query']);
        $current_user = $_SESSION['user_id'] ?? 'Unknown User'; 

        // Log the search action (assuming logActivity exists in audit_trail.php)
        logActivity($conn, $current_user, 'SEARCH', 'Test Table', "User searched for: " . $search_term);

        $sql = "SELECT * FROM test WHERE testname LIKE '%$search_term%'";
        $search_result = mysqli_query($conn, $sql);
    }

    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Search for Test</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; // Allow HTML for bold text ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 border-0">
            <div class="card-body p-4 bg-light rounded">
                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="search" class="form-label fw-bold">Find a Test:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">🔍</span>
                            <input type="search" class="form-control border-start-0 ps-0" id="search" name="query" 
                                   value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>" 
                                   placeholder="Type test name (e.g., Blood Count)..." required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="add_test.php" class="btn btn-outline-primary">
                            <span class="me-1">➕</span> Add New Test
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['query']) && $search_result): ?>
            
            <div class="results-section">
                
                <?php if (mysqli_num_rows($search_result) > 0): ?>
                    
                    <p class="text-muted mb-4">Found <strong><?php echo mysqli_num_rows($search_result); ?></strong> test(s):</p>
                    
                    <?php while ($row = mysqli_fetch_assoc($search_result)): ?>
                        <?php 
                            $testname = htmlspecialchars($row['testname']);
                            $testid = htmlspecialchars($row['testid']);
                        ?>
                        
                        <div class="card shadow-sm mb-3 border-0">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                
                                <div>
                                    <h5 class="mb-1 text-primary fw-bold"><?php echo $testname; ?></h5>
                                    <small class="text-muted">Test ID: #<?php echo $testid; ?></small>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="edit_test.php?id=<?php echo $testid; ?>" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>

                                    <form method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete this test?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="testid" value="<?php echo $testid; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>

                <?php else: ?>
                    
                    <div class="card text-center p-5 shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h5">No tests found</h3>
                            <p class="text-muted">We couldn't find any tests matching "<strong><?php echo htmlspecialchars($_GET['query']); ?></strong>".</p>
                            <a href="add_test.php" class="btn btn-primary mt-3">Add New Test</a>
                        </div>
                    </div>

                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php require_once('Includes/footer.php'); ?>