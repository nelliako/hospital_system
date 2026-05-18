<?php
    session_start();
    $page_title = "My Profile";

    require_once(__DIR__ . '/Includes/audit_trail.php');
    require_once(__DIR__ . '/Includes/config.php');

    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $current_user_id = $_SESSION['user_id'];
    $message = "";
    $message_type = "";

    // post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Collect inputs
        $fn = trim($_POST['firstname']);
        $ln = trim($_POST['lastname']);
        $qual = trim($_POST['qualification']);
        $addr = trim($_POST['address']);
        
        // Helper to get ID from Name (Prevent SQL Injection)
        function getId($conn, $table, $col_id, $col_name, $val) {
            $stmt = $conn->prepare("SELECT $col_id FROM $table WHERE $col_name = ?");
            $stmt->bind_param("s", $val);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                return $row[$col_id];
            }
            return null; 
        }

        // Convert Dropdowns Text -> ID
        $spec_id   = getId($conn, 'department', 'id', 'specialisationname', $_POST['specialisation']);
        $gender_id = getId($conn, 'gender', 'id', 'gendername', $_POST['gender']);
        $status_id = getId($conn, 'consultant_status', 'id', 'statusname', $_POST['consultant_status']);

        if (empty($fn) || empty($ln)) {
            $message = "First Name and Last Name cannot be empty.";
            $message_type = "error";
        } else {
            // Prepared Update Statement
            $sql = "UPDATE doctor SET 
                    firstname=?, lastname=?, specialisation=?, qualification=?, 
                    gender=?, consultantstatus=?, address=? 
                    WHERE staffno=?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisiiss", $fn, $ln, $spec_id, $qual, $gender_id, $status_id, $addr, $current_user_id);

            // Audit logic
            $audit_package = [
                'action' => 'UPDATE_PROFILE', 
                'target' => $current_user_id, 
                'desc'   => "User updated their own doctor profile"
            ];

            if (executeAndLog($conn, $stmt, $audit_package)) {
                $message = "Profile updated successfully.";
                $message_type = "success";
            } else {
                $message = "Error updating profile: " . $conn->error;
                $message_type = "error";
            }
            $stmt->close();
        }
    }

    // existing data fetching
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
    $stmt->bind_param("s", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Check if profile exists
    if (!$row) {
        // Just a safe fallback display if the ID isn't in the doctor table
        echo "<div class='container p-5'><h3>Profile not found</h3><p>User ID <strong>" . htmlspecialchars($current_user_id) . "</strong> not found in Doctor records.</p></div>";
        exit();
    }

    // Pre-selection variables
    $curr_spec = $row['specialisationname'];
    $curr_gen  = $row['gendername'];
    $curr_stat = $row['statusname'];

    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">My Profile</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0" style="max-width: 800px;">
            <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                <h5 class="card-title text-primary fw-bold">Edit Personal Details</h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST">
                    
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-bold">Staff No</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control-plaintext text-muted" value="<?php echo htmlspecialchars($row['staffno']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Qualification</label>
                            <input type="text" class="form-control" name="qualification" value="<?php echo htmlspecialchars($row['qualification']); ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-bold">Current Pay (£)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($row['pay']); ?>" disabled>
                            <div class="form-text">Please contact an administrator to update salary information.</div>
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
                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($row['address']); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg"></i> Update Profile
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php 
    require_once(__DIR__ . '/Includes/footer.php'); 
?>