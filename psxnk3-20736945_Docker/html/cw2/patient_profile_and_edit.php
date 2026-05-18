<?php
    session_start();
    $page_title = "Patient Profile";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    // Security Check
    if (!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 'QM345'; // Fallback
    }

    $message = "";
    $message_type = "";
    
    // Get ID from URL
    $nhs_id = $_GET['id'] ?? '';

    if (empty($nhs_id)) {
        header("Location: patient_lookup.php");
        exit();
    }

    // Handle post requests
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Delete action - involved fk error catch
        if (isset($_POST['btn_delete'])) {
            $stmt = $conn->prepare("DELETE FROM patient WHERE NHSno = ?");
            $stmt->bind_param("s", $nhs_id);

            $audit = [
                'action' => 'DELETE_PATIENT', 
                'target' => $nhs_id, 
                'desc'   => "Deleted patient record"
            ];

            try {
                // Try to run the query
                if (executeAndLog($conn, $stmt, $audit)) {
                    // Success
                    header("Location: patient_lookup.php?msg=deleted");
                    exit();
                }
            } catch (mysqli_sql_exception $e) {
                // If it crashes, catching the error here
                
                // Check if it is the specific "Foreign Key" error (1451)
                if ($e->getCode() == 1451) {
                    $message = "<strong>Action Blocked:</strong> Cannot delete this patient because they have active medical records (Tests, Admissions, or Examinations).<br>To protect medical history, the database has prevented this deletion.";
                    $message_type = "error";
                } else {
                    // Any other crash
                    $message = "Database Error: " . $e->getMessage();
                    $message_type = "error";
                }
            }
            $stmt->close();
        }

        // Update action
        elseif (isset($_POST['btn_update'])) {
            $fname   = trim($_POST['firstname']);
            $lname   = trim($_POST['lastname']);
            $phone   = trim($_POST['phone']);
            $addr    = trim($_POST['address']);
            $age     = (int)$_POST['age'];
            $e_phone = trim($_POST['emergencyphone']);
            $gender_raw = $_POST['gender']; 
            
            $gender_db = ($gender_raw === 'Female') ? 1 : 0;

            $sql = "UPDATE patient SET firstname=?, lastname=?, phone=?, address=?, age=?, gender=?, emergencyphone=? WHERE NHSno=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiiss", $fname, $lname, $phone, $addr, $age, $gender_db, $e_phone, $nhs_id);

            $audit = [
                'action' => 'UPDATE_PATIENT', 
                'target' => $nhs_id, 
                'desc'   => "Updated details for $fname $lname"
            ];

            try {
                if (executeAndLog($conn, $stmt, $audit)) {
                    $message = "Patient details updated successfully!";
                    $message_type = "success";
                }
            } catch (mysqli_sql_exception $e) {
                $message = "Error updating patient: " . $e->getMessage();
                $message_type = "error";
            }
            $stmt->close();
        }
    }

    // Fetch data
    $stmt = $conn->prepare("SELECT * FROM patient WHERE NHSno = ?");
    $stmt->bind_param("s", $nhs_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
    $stmt->close();

    if (!$patient) {
        // 404
        require_once('Includes/header.php'); 
        require_once('Includes/left_menu.php');
        echo "<main class='main-content'><div class='container-fluid p-5'><div class='alert alert-danger'>Patient not found.</div></div></main>";
        require_once('Includes/footer.php');
        exit();
    }
?>

<?php 
    require_once('Includes/header.php'); 
    require_once('Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Patient Profile</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0" style="max-width: 800px;">
            <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                <h5 class="card-title text-primary fw-bold">Edit Patient Details</h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST">
                    
                    <input type="hidden" name="target_id" value="<?php echo htmlspecialchars($patient['NHSno']); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">NHS Number</label>
                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($patient['NHSno']); ?>" disabled>
                        <div class="form-text">Unique ID cannot be changed.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($patient['firstname']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($patient['lastname']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Emergency Phone</label>
                            <input type="text" class="form-control" name="emergencyphone" value="<?php echo htmlspecialchars($patient['emergencyphone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Age</label>
                            <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male"   <?php echo ($patient['gender'] == 0) ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($patient['gender'] == 1) ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        
                        <a href="patient_lookup.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Search
                        </a>

                        <div>
                             <button type="submit" name="btn_delete" class="btn btn-outline-danger me-2"
                                    onclick="return confirm('CRITICAL WARNING:\n\nAre you sure you want to PERMANENTLY delete this patient?');">
                                <i class="bi bi-trash"></i> Delete Patient
                            </button>

                            <button type="submit" name="btn_update" class="btn btn-success px-4">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once('Includes/footer.php'); ?>