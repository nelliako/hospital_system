<?php

/* * Reference: Fox, J. et al. (2025) 'PHP: The Right Way'. 
     * Adopted the action first code structure: handling logic/database actions 
     * at the top of the file before generating any HTML output to prevent header errors.
     */
    

    session_start();
    $page_title = "Prescribe Test";

    require_once(__DIR__ . '/Includes/audit_trail.php');
    require_once(__DIR__ . '/Includes/config.php');

    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $message_type = "";
    $current_user = $_SESSION['user_id']; 

    // Initializing variables for edit mode
    $is_edit_mode = false;
    $pid_val = "";
    $tid_val = "";
    $date_val = date('Y-m-d'); 

    $orig_pid = "";
    $orig_tid = "";
    $orig_date = "";


    // 1. Handle Delete 
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {


        $del_pid  = $_GET['pid'] ?? '';
        $del_tid  = $_GET['tid'] ?? '';
        $del_date = $_GET['date'] ?? '';

        if ($del_pid && $del_tid && $del_date) {
            $stmt_del = $conn->prepare("DELETE FROM patient_test WHERE pid=? AND testid=? AND date=?");
            $stmt_del->bind_param("sis", $del_pid, $del_tid, $del_date);

            $audit_package = [
                'action' => 'DELETE_PRESCRIPTION',
                'target' => $del_pid,
                'desc'   => "Deleted prescription: Patient $del_pid, Test $del_tid on $del_date"
            ];

            try {
                if (executeAndLog($conn, $stmt_del, $audit_package)) {
                    header("Location: prescribe.php?msg=deleted");
                    exit();
                } 
            } catch (mysqli_sql_exception $e) {
                /* * Ref: MariaDB Knowledge Base (2025) 'Foreign Keys'.
                 * Implemented specific catch for Error Code 1451 (Constraint Violation) 
                 * to provide a user-friendly message when deletion is blocked by child records.
                 */
                // Check for fk error (1451) 
                if ($e->getCode() == 1451) {
                    $message = "<strong>Action Blocked:</strong> Cannot delete this prescription.<br>";
                    $message_type = "error";
                } else {
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                }
            }
            $stmt_del->close();
        }
    }

    // Handle Redirect Messages
    if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
        $message = "Prescription deleted successfully.";
        $message_type = "success";
    }

    // Handle edit mode setup
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        $is_edit_mode = true;
        $pid_val = $_GET['pid'];
        $tid_val = $_GET['tid'];
        $date_val = $_GET['date'];
        
        $orig_pid = $pid_val;
        $orig_tid = $tid_val;
        $orig_date = $date_val;
    }

   
    // Handle Form Submission (Create & Update)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $input_pid = $_POST['NHSno'] ?? '';
        $input_tid = $_POST['testid'] ?? '';
        $form_mode = $_POST['mode'] ?? 'create';

        // Check if Patient Exists
        $check_p = $conn->prepare("SELECT NHSno FROM patient WHERE NHSno = ?"); 
        $check_p->bind_param("s", $input_pid);
        $check_p->execute();
        $p_exists = $check_p->get_result()->num_rows > 0;
        $check_p->close();

        // Check if Test Exists
        $check_t = $conn->prepare("SELECT testid FROM test WHERE testid = ?");
        $check_t->bind_param("i", $input_tid); 
        $check_t->execute();
        $t_exists = $check_t->get_result()->num_rows > 0;
        $check_t->close();

        if (!$p_exists) {
            // Using htmlspecialchars to prevent security issues with the user input
            $safe_pid = htmlspecialchars($input_pid);
            $message = "Patient ID '<strong>$safe_pid</strong>' not found. " . 
                    "<a href='add_patient.php' class='btn btn-sm btn-primary ms-2'>Add Patient to Database</a>";
        } elseif (!$t_exists) {
            // Using htmlspecialchars to prevent security issues with the user input
            $safe_tid = htmlspecialchars($input_tid);
            
            $message = "Test ID '<strong>$safe_tid</strong>' not found. " .
                    "<a href='add_test.php' class='btn btn-sm btn-primary ms-2'>Add Test to Database</a>";
            
            $message_type = "error";
        } else {
            
            if ($form_mode === 'update') {
                // Update Logic
                $o_pid = $_POST['orig_pid'];
                $o_tid = $_POST['orig_tid'];
                $o_date = $_POST['orig_date'];

                $sql = "UPDATE patient_test SET pid=?, testid=?, doctorid=? WHERE pid=? AND testid=? AND date=?";
                $stmt = $conn->prepare($sql);
                // We use $current_user here. If they are an Admin, the DB might complain (see catch block).
                $stmt->bind_param("sissss", $input_pid, $input_tid, $current_user, $o_pid, $o_tid, $o_date);

                $audit_package = [
                    'action' => 'UPDATE_PRESCRIPTION',
                    'target' => $input_pid,
                    'desc'   => "Updated prescription. Changed from ($o_pid/$o_tid) to ($input_pid/$input_tid)"
                ];

                try {
                    if (executeAndLog($conn, $stmt, $audit_package)) {
                        $message = "Record updated successfully!";
                        $message_type = "success";
                        $is_edit_mode = false;
                        $pid_val = ""; $tid_val = "";
                    }
                } catch (mysqli_sql_exception $e) {

                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                    
                }
                $stmt->close();

            } else {
                // Create Logic
                $current_date = date('Y-m-d');
                $stmt = $conn->prepare("INSERT INTO patient_test (pid, testid, date, doctorid) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siss", $input_pid, $input_tid, $current_date, $current_user);

                $audit_package = [
                    'action' => 'PRESCRIBE_TEST',
                    'target' => $input_pid,
                    'desc'   => "Prescribed Test ID $input_tid to Patient $input_pid"
                ];

                try {
                    if (executeAndLog($conn, $stmt, $audit_package)) {
                        $message = "Prescription created successfully!";
                        $message_type = "success";
                    }
                } catch (mysqli_sql_exception $e) {
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                    
                }
                $stmt->close();
            }
        } 
    }

    // Fetch data for list
    $patients_result = $conn->query("SELECT NHSno, firstname, lastname FROM patient ORDER BY lastname ASC");
    $tests_result = $conn->query("SELECT testid, testname FROM test ORDER BY testname ASC");

    // Fetch History
    $history_sql = "
        SELECT pt.pid, pt.testid, pt.date, pt.doctorid, 
               p.firstname AS p_first, p.lastname AS p_last, 
               t.testname,
               d.firstname AS doc_first, d.lastname AS doc_last
        FROM patient_test pt
        LEFT JOIN patient p ON pt.pid = p.NHSno
        LEFT JOIN test t ON pt.testid = t.testid
        LEFT JOIN doctor d ON pt.doctorid = d.staffno
        ORDER BY pt.date DESC, pt.pid ASC
    ";
    $history_res = $conn->query($history_sql);
?>

<?php 
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4"><?php echo $is_edit_mode ? "Edit Prescription" : "Prescribe a Test"; ?></h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="card-title text-primary fw-bold">
                    <i class="bi bi-prescription"></i> Details
                </h5>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom">
                
                <form method="POST" action="prescribe.php" class="row g-3">
                    <input type="hidden" name="mode" value="<?php echo $is_edit_mode ? 'update' : 'create'; ?>">
                    
                    <?php if($is_edit_mode): ?>
                        <input type="hidden" name="orig_pid" value="<?php echo htmlspecialchars($orig_pid); ?>">
                        <input type="hidden" name="orig_tid" value="<?php echo htmlspecialchars($orig_tid); ?>">
                        <input type="hidden" name="orig_date" value="<?php echo htmlspecialchars($orig_date); ?>">
                    <?php endif; ?>

                    <div class="col-md-6">
                        <label for="patient_input" class="form-label fw-bold">Select Patient</label>
                        <input list="patient_list" name="NHSno" id="patient_input" class="form-control" 
                               value="<?php echo htmlspecialchars($pid_val); ?>"
                               placeholder="Type to search patient..." required autocomplete="off">
                        <datalist id="patient_list">
                            <?php 
                            $patients_result->data_seek(0);
                            while ($p = $patients_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($p['NHSno']); ?>">
                                    <?php echo htmlspecialchars($p['firstname'] . ' ' . $p['lastname']); ?> (<?php echo htmlspecialchars($p['NHSno']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </datalist>
                    </div>

                    <div class="col-md-6">
                        <label for="test_input" class="form-label fw-bold">Select Test</label>
                        <input list="test_list" name="testid" id="test_input" class="form-control" 
                               value="<?php echo htmlspecialchars($tid_val); ?>"
                               placeholder="Type to search test..." required autocomplete="off">
                        <datalist id="test_list">
                            <?php 
                            $tests_result->data_seek(0);
                            while ($t = $tests_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($t['testid']); ?>">
                                    <?php echo htmlspecialchars($t['testname']); ?>
                                </option>
                            <?php endwhile; ?>
                        </datalist>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $is_edit_mode ? "Update Prescription" : "Prescribe Test"; ?>
                        </button>

                        <?php if($is_edit_mode): ?>
                            <a href="prescribe.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <h3 class="mb-3 h5 text-muted text-uppercase fw-bold">Prescription History</h3>
        
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Patient Name</th>
                                <th>Test Prescribed</th>
                                <th>Prescribed By</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($history_res->num_rows > 0): ?>
                                <?php while($row = $history_res->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-nowrap"><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['p_first'] . ' ' . $row['p_last']); ?></div>
                                        <div class="small text-muted">ID: <?php echo htmlspecialchars($row['pid']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($row['testname']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            if (!empty($row['doc_first'])) {
                                                echo htmlspecialchars("Dr. " . $row['doc_first'] . " " . $row['doc_last']);
                                            } else {
                                                echo '<span class="text-muted">ID: ' . htmlspecialchars($row['doctorid']) . '</span>';
                                            }
                                        ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="prescribe.php?action=edit&pid=<?php echo $row['pid']; ?>&tid=<?php echo $row['testid']; ?>&date=<?php echo $row['date']; ?>" 
                                               class="btn btn-outline-primary">
                                                Edit
                                            </a>
                                            <a href="prescribe.php?action=delete&pid=<?php echo $row['pid']; ?>&tid=<?php echo $row['testid']; ?>&date=<?php echo $row['date']; ?>" 
                                               class="btn btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this prescription?');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No prescriptions found. Use the form above to add one.
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

<?php require_once('Includes/footer.php'); ?>