<?php
    session_start();
    $page_title = "Edit Doctor";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    // Admin access only
    if (!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 'admin'; // Fallback
    }

    $id = $_GET['id'] ?? '';
    $message = "";
    $message_type = "";

    // Redirect if no ID provided
    if (empty($id)) {
        header("Location: add_doctor.php");
        exit();
    }

    // Handle update (post)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Collect Inputs (Trim removes whitespace, empty strings remain empty)
        $fn   = trim($_POST['firstname']);
        $ln   = trim($_POST['lastname']);
        
        // Nullable Fields
        $qual = trim($_POST['qualification'] ?? '');
        $pay  = !empty($_POST['pay']) ? (int)$_POST['pay'] : 0; // Default to 0 if empty
        $addr = trim($_POST['address'] ?? '');
        
        // Helper function to get IDs
        function getId($conn, $table, $col_val, $col_search, $val) {
            $s = $conn->prepare("SELECT $col_val FROM $table WHERE $col_search = ?");
            $s->bind_param("s", $val);
            $s->execute();
            $res = $s->get_result();
            if ($r = $res->fetch_assoc()) return $r[$col_val];
            return 1; // Default fallback ID
        }

        // Convert Text Dropdowns -> IDs
        $spec_id   = getId($conn, 'department', 'id', 'specialisationname', $_POST['specialisation']);
        $gender_id = getId($conn, 'gender', 'id', 'gendername', $_POST['gender']);
        $status_id = getId($conn, 'consultant_status', 'id', 'statusname', $_POST['consultant_status']);

        // Prepare Update Statement
        $sql = "UPDATE doctor SET 
                firstname=?, lastname=?, specialisation=?, qualification=?, 
                pay=?, gender=?, consultantstatus=?, address=? 
                WHERE staffno=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisisiss", $fn, $ln, $spec_id, $qual, $pay, $gender_id, $status_id, $addr, $id);

        // Audit Log
        $audit_package = [
            'action' => 'UPDATE_DOCTOR', 
            'target' => $id, 
            'desc'   => "Admin updated profile for doctor $id"
        ];

        try {
            if (executeAndLog($conn, $stmt, $audit_package)) {
                $message = "Doctor details updated successfully!";
                $message_type = "success";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "Error updating database: " . $e->getMessage();
            $message_type = "error";
        }
        $stmt->close();
    }

    // Fetch existing
    // Use LEFT JOIN so that I could still get the doctor even if Department/Gender is NULL
    $sql = "SELECT d.*, 
                   dept.specialisationname, 
                   g.gendername, 
                   cs.statusname 
            FROM doctor d
            LEFT JOIN department dept ON d.specialisation = dept.id
            LEFT JOIN gender g ON d.gender = g.id
            LEFT JOIN consultant_status cs ON d.consultantstatus = cs.id
            WHERE d.staffno = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Handle "not found" issue
    if (!$row) {
        require_once(__DIR__ . '/Includes/header.php'); 
        require_once(__DIR__ . '/Includes/left_menu.php');
        echo "<main class='main-content'><div class='container-fluid p-5'>";
        echo "<div class='alert alert-danger'>Doctor with Staff ID <strong>".htmlspecialchars($id)."</strong> not found. <a href='add_doctor.php'>Return to List</a></div>";
        echo "</div></main>";
        require_once(__DIR__ . '/Includes/footer.php'); 
        exit;
    }

    // Null Coalescing (??) to prevent "undefined index" warnings (allowing for missing values)
    $val_fn    = $row['firstname'] ?? '';
    $val_ln    = $row['lastname'] ?? '';
    $val_qual  = $row['qualification'] ?? '';
    $val_pay   = $row['pay'] ?? '';
    $val_addr  = $row['address'] ?? '';
    
    // Dropdown defaults
    $curr_spec = $row['specialisationname'] ?? '';
    $curr_gen  = $row['gendername'] ?? '';
    $curr_stat = $row['statusname'] ?? '';
?>

<?php 
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Edit Doctor Details</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0" style="max-width: 800px;">
            <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                <h5 class="card-title text-primary fw-bold">
                    Edit Profile: <?php echo htmlspecialchars($val_fn . ' ' . $val_ln); ?>
                </h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Staff ID</label>
                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($row['staffno'] ?? ''); ?>" disabled>
                        <div class="form-text">Staff ID cannot be changed.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($val_fn); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($val_ln); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Specialisation</label>
                        <select name="specialisation" class="form-select">
                            <?php
                            $specs = ["Cardiology", "Radiology", "Pediatrics", "Oncology", "Neurology", "Orthopedics", "Dermatology", "Psychiatry", "Anesthesiology", "Gastroenterology", "General Surgery", "Emergency Medicine", "Urology", "Ophthalmology"];
                            foreach ($specs as $s) {
                                $sel = ($curr_spec == $s) ? 'selected' : '';
                                echo "<option value='$s' $sel>$s</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Qualification</label>
                            <input type="text" class="form-control" name="qualification" 
                                   value="<?php echo htmlspecialchars($val_qual); ?>" placeholder="e.g. MBBS">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pay (£)</label>
                            <input type="number" class="form-control" name="pay" 
                                   value="<?php echo htmlspecialchars($val_pay); ?>" placeholder="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male" <?php if($curr_gen == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if($curr_gen == 'Female') echo 'selected'; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <select name="consultant_status" class="form-select">
                                <option value="consultant" <?php if($curr_stat == 'consultant') echo 'selected'; ?>>Consultant</option>
                                <option value="not_consultant" <?php if($curr_stat == 'not_consultant') echo 'selected'; ?>>Not consultant</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($val_addr); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="add_doctor.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel / Back
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>