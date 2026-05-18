<?php
    session_start();
    $page_title = "Manage Permit Requests";

    require_once(__DIR__ . '/Includes/config.php');
    require_once(__DIR__ . '/Includes/audit_trail.php');

    // Default Admin Session
    if (!isset($_SESSION['user_id'])){
        $current_user_id = 'admin';
    } else {
        $current_user_id = $_SESSION['user_id'];
    }

    $message = ""; 
    $message_type = ""; 

    // Handle POST actions - reject/approve
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $action = $_POST['action'] ?? '';
        $doctorid = $_POST['doctorid'] ?? '';

        // Approve
        if($action == 'approve'){
            $stmt = $conn->prepare("SELECT * FROM parking_request WHERE doctorid = ?");
            $stmt->bind_param("s", $doctorid);
            $stmt -> execute();
            $req_data = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if($req_data){
                $conn->begin_transaction(); // Start Transaction

                try{
                    // Insert Permit 
                    $permitenddate = date('Y-m-d', strtotime('+1 year'));
                    $permitactivationdate = date('Y-m-d');
                    $amount = ($req_data['permitchoice'] == 'yearly') ? 200.00 : 20.00;

                    $stmt1 = $conn->prepare("INSERT INTO parking_permit (doctorid, carregistrationnumber, permitchoice, permitactivationdate, permitenddate, amount) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt1->bind_param("sssssd", $req_data['doctorid'], $req_data['carregistrationnumber'], $req_data['permitchoice'], $permitactivationdate, $permitenddate, $amount);
                    
                    if(!$stmt1->execute()){
                         throw new Exception("Failed to create permit record.");
                    }
                    $stmt1->close();

                    // Update Status 
                    $stmt2 = $conn->prepare("UPDATE parking_request SET status = 'approved' WHERE doctorid = ?");
                    $stmt2->bind_param("s", $doctorid);

                    $audit_package = [
                        'action' => 'APPROVE_PERMIT', 
                        'target' => $doctorid, 
                        'desc'   => "Admin approved parking permit for $doctorid"
                    ];

                    // If this fails, I throw exception to trigger rollback
                    if (!executeAndLog($conn, $stmt2, $audit_package)) {
                        throw new Exception("Failed to update request status.");
                    }
                    $stmt2->close();

                    $conn->commit(); // Save changes
                    $message = "Request approved & permit generated successfully.";
                    $message_type = "success";

                } catch (Exception $e){
                    $conn->rollback(); // Undo changes
                    $message = "Error: ". $e->getMessage();
                    $message_type = "error";
                }
            }

        // Reject logic
        } elseif ($action == 'reject'){
            
            $reason = trim($_POST['reasonifrejected'] ?? '');

            if (empty($reason)){
                $message = "Rejection reason is required.";
                $message_type = "error";
            } else {
                $stmt = $conn->prepare("UPDATE parking_request SET status = 'rejected', reasonifrejected = ? WHERE doctorid = ?");
                $stmt->bind_param("ss", $reason, $doctorid);

                // Audit note for rejection
                $audit_package = [
                    'action' => 'REJECT_PERMIT', 
                    'target' => $doctorid, 
                    'desc'   => "Admin rejected permit. Reason: $reason"
                ];

              
                if(executeAndLog($conn, $stmt, $audit_package)){
                    $message = "Request rejected successfully.";
                    $message_type = "success";
                } else {
                    $message = "Database error during rejection.";
                    $message_type = "error";
                }
                $stmt->close();
            }
        }  
    }
    // Fetching pending requests
    $pending_requests = [];
    $sql = "SELECT * FROM parking_request WHERE status = 'submitted'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $pending_requests[] = $row;
        }
    }
?>

<?php
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Manage Permit Requests</h1>
        
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            Note: Approving a request will automatically generate a permit number and doctor will see it in their account.
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary fw-bold mb-0">Pending Requests</h5>
                <span class="badge bg-secondary"><?php echo count($pending_requests); ?> Pending</span>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Doctor ID</th>
                                <th>Car Registration</th>
                                <th>Permit Type</th>
                                <th class="ps-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pending_requests)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No pending permit requests found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pending_requests as $req): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">
                                            <?php echo htmlspecialchars($req['doctorid']); ?>
                                        </td>
                                        <td>
                                            <span class="font-monospace bg-light border px-2 py-1 rounded">
                                                <?php echo htmlspecialchars($req['carregistrationnumber']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($req['permitchoice'] == 'yearly'): ?>
                                                <span class="badge bg-primary">Yearly (£200)</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">Monthly (£20)</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="ps-4" style="min-width: 350px;">
                                            <div class="d-flex flex-column gap-2 py-2">
                                                
                                                <form method="POST">
                                                    <input type="hidden" name="doctorid" value="<?php echo $req['doctorid']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-success btn-sm w-100 text-start" 
                                                            onclick="return confirm('Confirm Approval: This will generate a permit immediately.');">
                                                        <i class="bi bi-check-circle-fill me-2"></i> Approve Request
                                                    </button>
                                                </form>

                                                <form method="POST">
                                                    <input type="hidden" name="doctorid" value="<?php echo $req['doctorid']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="reasonifrejected" class="form-control" 
                                                               placeholder="Reason for rejection..." required>
                                                        <button class="btn btn-outline-danger" type="submit" 
                                                                onclick="return confirm('Are you sure you want to reject this request?');">
                                                            Reject
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>








    <!--Show all requests with status "submitted" from parking_request table
    On the right from each request -> 2 buttons either approve and then they would move to parking_permit table with automatic id enumeration but also still update the current parking_request table
    Or reject with a space to provide reason -> this would just update parking_request table (messsage and status section)-->
