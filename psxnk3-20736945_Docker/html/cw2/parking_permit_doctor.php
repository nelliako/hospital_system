<?php
    session_start();
    $page_title = "Permit Request";

    require_once(__DIR__ . '/Includes/config.php');
    require_once(__DIR__ . '/Includes/audit_trail.php');

    // Default Session Logic
    if (!isset($_SESSION['user_id'])){
        $current_user_id = 'QM345';
        $_SESSION['user_id'] = $current_user_id; 
    } else {
        $current_user_id = $_SESSION['user_id'];
    }

    $status = 'not submitted'; 
    $user_request = null; 
    $row = null; 
    $message = ""; 
    $message_type = "";

    // Form submission
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
        $doctorid = $_POST['doctorid'] ?? '';
        $carregistrationnumber = trim($_POST['carregistrationnumber'] ?? '');
        $permitchoice = $_POST['permitchoice'] ?? '';

        if (empty($doctorid) || empty($carregistrationnumber) || empty($permitchoice)){
            $message = "Please fill in all the fields.";
            $message_type = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO parking_request (doctorid, carregistrationnumber, permitchoice, status) VALUES (?, ?, ?, 'submitted')");
            $stmt->bind_param('sss', $doctorid, $carregistrationnumber, $permitchoice);
            
            try {
                // Audit
                $audit_package = [
                    'action' => 'SUBMIT_REQUEST', 
                    'target' => $doctorid, 
                    'desc'   => "Doctor submitted a {$permitchoice} parking request"
                ];

                if (executeAndLog($conn, $stmt, $audit_package)) {
                    // Refresh page
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            } catch (mysqli_sql_exception $e) {
                // Foreign Key Error (User ID doesn't exist in doctor table)
                if ($e->getCode() == 1452) {
                    $message = "Error: The Staff ID '$doctorid' does not belong to any registered doctor.";
                    $message_type = "error";
                } else {
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                }
            }   
            $stmt->close();
        }
    }

    // Fetch current status
    $stmt = $conn->prepare("SELECT * FROM parking_request WHERE doctorid = ?");
    $stmt->bind_param("s", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_request = $result->fetch_assoc();
        $status = $user_request['status']; 
    }
    $stmt->close();

    // Helper: Calculate Fee
    function getFee($type){
        return ($type === 'yearly') ? '£200.00' : '£20.00';
    }

    // Fetch permit number
    $parkingid_final = "Pending"; 
    if ($status == 'approved') {
        $parkingid = $conn->prepare("SELECT parkingid FROM parking_permit WHERE doctorid = ?");
        $parkingid->bind_param("s", $current_user_id);
        $parkingid->execute();
        $parkingid_result = $parkingid->get_result();

        if ($parkingid_result->num_rows > 0) {
            $row = $parkingid_result->fetch_assoc();
            $parkingid_final = $row['parkingid']; 
        }
        $parkingid->close();
    }
?>

<?php 
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Parking Permit Request</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'error') ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($status == 'not submitted'): ?>
            
            <div class="card shadow-sm border-0" style="max-width: 600px;">
                <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                    <h5 class="card-title fw-bold text-primary">New Application</h5>
                    <p class="text-muted small mb-0">You can only submit one active request at a time.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        
                        <div class="mb-3">
                            <label for="doctorid" class="form-label fw-bold">Staff ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="doctorid" id="doctorid" 
                                   value="<?php echo htmlspecialchars($current_user_id); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="carreg" class="form-label fw-bold">Car Registration Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="carregistrationnumber" id="carreg" 
                                   placeholder="e.g. AB12 CDE" required>
                        </div>

                        <div class="mb-4">
                            <label for="permitchoice" class="form-label fw-bold">Permit Duration <span class="text-danger">*</span></label>
                            <select name="permitchoice" id="permitchoice" class="form-select">
                                <option value="monthly">Monthly (£20)</option>
                                <option value="yearly">Yearly (£200)</option>
                            </select>
                            <div class="form-text">Fees are payable in advance upon approval.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                    </form>
                </div>
            </div>

        <?php elseif ($status == 'submitted'): ?>
            
            <div class="card shadow-sm border-0 border-start border-4 border-warning">
                <div class="card-body p-5 text-center">
                    <div class="display-1 text-warning mb-3"><i class="bi bi-hourglass-split"></i></div>
                    <h2 class="h4">Request Pending Review</h2>
                    <p class="text-muted">You have applied for a <strong><?php echo htmlspecialchars($user_request['permitchoice']); ?></strong> permit.</p>
                    
                    <div class="alert alert-light border d-inline-block mt-2 text-start">
                        <ul class="mb-0 list-unstyled">
                            <li><strong>Fee:</strong> <?php echo getFee($user_request['permitchoice']); ?></li>
                            <li><strong>Status:</strong> <span class="badge bg-warning text-dark">Under Review</span></li>
                        </ul>
                    </div>
                    
                    <p class="mt-4 small text-muted">Your application is currently being reviewed by an administrator.<br>Please check back later.</p>
                </div>
            </div>

        <?php elseif ($status == 'rejected'): ?>
            
            <div class="card shadow-sm border-0 border-start border-4 border-danger">
                <div class="card-body p-5">
                    <h2 class="h4 text-danger"><i class="bi bi-x-circle-fill me-2"></i>Request Rejected</h2>
                    <p class="text-muted">Unfortunately, your parking permit request was declined.</p>
                    
                    <div class="bg-light p-3 rounded border mb-3">
                        <strong>Reason provided:</strong><br>
                        <em class="text-dark">"<?php echo htmlspecialchars($user_request['reasonifrejected'] ?? 'No specific reason provided.'); ?>"</em>
                    </div>

                    <p>Please contact the administrator for further assistance.</p>
                    </div>
            </div>

        <?php elseif ($status == 'approved'): ?>
            
            <div class="card shadow-sm border-0 border-start border-4 border-success">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="h4 text-success"><i class="bi bi-check-circle-fill me-2"></i>Request Approved</h2>
                            <p>Your <strong><?php echo htmlspecialchars($user_request['permitchoice']); ?></strong> permit is ready.</p>
                        </div>
                        <span class="badge bg-success fs-6">Active</span>
                    </div>

                    <div class="row mt-4 g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="text-uppercase text-muted small fw-bold">Permit Details</h6>
                                <div class="fs-4 fw-bold text-dark"><?php echo htmlspecialchars($parkingid_final); ?></div>
                                <small>Parking Permit Number</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100" style="border-left: 4px solid green !important;">
                                <h6 class="text-uppercase text-muted small fw-bold">Payment Required</h6>
                                <div class="fs-4 fw-bold text-success"><?php echo getFee($user_request['permitchoice']); ?></div>
                                <small>Please pay this amount to the administrator.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Ensure your permit is visible at all times while parked.
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php require_once('Includes/footer.php'); ?>