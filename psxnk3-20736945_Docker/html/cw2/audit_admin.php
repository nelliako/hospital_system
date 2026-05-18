<?php
    session_start();
    $page_title = "Audit Trail";

    require_once(__DIR__ . '/Includes/config.php');
    require_once(__DIR__ . '/Includes/header.php');
    require_once(__DIR__ . '/Includes/left_menu.php');

    
    /* * Reference: W3Schools 'PHP Ternary Operator'.
     * Efficiently checking if the filter parameter exists in the URL 
     * to prevent 'undefined index' notices.
     */

    // Get the current filter value

    $filter_user = isset($_GET['user']) ? trim($_GET['user']) : '';

    // Fetch distinct users for the autocomplete list
    $user_list_result = $conn->query("SELECT DISTINCT userid FROM changes_log ORDER BY userid ASC");

    // Build the main query
    $sql = "SELECT * FROM changes_log";
    $params = [];
    $types = "";

    // Only add the "where" clause if a user is actually typed in
    if (!empty($filter_user)) {
        $sql .= " WHERE userid = ?";
        $params[] = $filter_user;
        $types .= "s";
    }

    $sql .= " ORDER BY timestamp DESC";

    // Prep statement
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">System Audit Trail</h1>
            <span class="badge bg-secondary">Total Records: <?php echo $result->num_rows; ?></span>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light p-4 rounded">
                <form method="GET" action="" class="row g-3 align-items-end">
                    
                    <div class="col-md-5">
                        <label for="userInput" class="form-label fw-bold text-muted">Filter by User ID</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person-badge"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   name="user" 
                                   id="userInput" 
                                   list="userList" 
                                   placeholder="Type to search users..." 
                                   value="<?php echo htmlspecialchars($filter_user); ?>">
                        </div>
                        
                        <datalist id="userList">
                            <?php 
                            if($user_list_result->num_rows > 0) {
                                while($u = $user_list_result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($u['userid']) . '">';
                                }
                            }
                            ?>
                        </datalist>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-filter"></i> Apply Filter
                        </button>
                        
                        <?php if(!empty($filter_user)): ?>
                            <a href="audit_admin.php" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 200px;">Timestamp</th>
                                <th style="width: 150px;">User ID</th>
                                <th style="width: 200px;">Action Type</th>
                                <th>Description / Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-nowrap text-muted">
                                        <?php echo $row['timestamp']; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-person-circle me-1"></i>
                                            <?php echo htmlspecialchars($row['userid']); ?>
                                        </span>
                                    </td>
                                    <td>
                                <?php 
                                            $action = htmlspecialchars($row['actiontype']);
                                            $badgeClass = 'bg-secondary'; // Default Grey

                                            // 1. Red - possible danger in action
                                            if (strpos($action, 'DELETE') !== false || strpos($action, 'REJECT_PERMIT') !== false || strpos($action, 'LOGIN_FAILED') !== false) {
                                                $badgeClass = 'bg-danger';
                                            } 
                                            // 2. Yellow - updates and changes in general
                                            elseif (strpos($action, 'UPDATE') !== false || strpos($action, 'RESET_PASSWORD') !== false || strpos($action, 'CHANGE_PASSWORD') !== false || strpos($action, 'SUBMIT_REQUEST') !== false || strpos($action, 'PRESCRIBE_TEST') !== false){
                                                $badgeClass = 'bg-warning text-dark';
                                            } 
                                            // 3. Green - additions or success
                                            elseif (strpos($action, 'ADD') !== false || strpos($action, 'APPROVE_PERMIT') !== false || strpos($action, 'CREATE') !== false) {
                                                $badgeClass = 'bg-success';
                                            }
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $action; ?></span>
                                    </td>
                                    <td class="text-secondary">
                                        <?php echo htmlspecialchars($row['details']); ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                                        No logs found matching "<strong><?php echo htmlspecialchars($filter_user); ?></strong>".
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>