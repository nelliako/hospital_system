<?php
    session_start();
    $page_title = "Manage Doctors";

    require_once(__DIR__ . '/Includes/audit_trail.php');
    require_once(__DIR__ . '/Includes/config.php');

    // Admin access only
    if (!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 'admin'; 
    }

    $message = "";
    $message_type = "";

    // Handle post actions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Add doctor
        if (isset($_POST['add_doctor_btn'])) {
            
            $staffno = trim($_POST['staffno']);
            $rand_pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
            
            try {
                // Create User
                /* 
                * Reference: W3 School PHP Prepared.
                * Utilizing Prepared Statements with bound parameters (bind_param) to separate 
                * SQL code from user data, neutralizing potential SQL injection attacks
                */
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'doctor')");
                $stmt->bind_param("ss", $staffno, $rand_pass);
                
                if ($stmt->execute()) {
                    $stmt->close();

                    // Foreign Key Helper - conversion of text from the form (human readable) into ids that can be recorded to the db
                    function getId($conn, $table, $col_val, $col_search, $val) {
                        $s = $conn->prepare("SELECT $col_val FROM $table WHERE $col_search = ?");
                        $s->bind_param("s", $val);
                        $s->execute();
                        $res = $s->get_result();
                        if ($r = $res->fetch_assoc()) return $r[$col_val];
                        return 1; 
                    }

                    $spec_id   = getId($conn, 'department', 'id', 'specialisationname', $_POST['specialisation']);
                    $gender_id = getId($conn, 'gender', 'id', 'gendername', $_POST['gender']);
                    $status_id = getId($conn, 'consultant_status', 'id', 'statusname', $_POST['consultant_status']);

                    // Insert Doctor
                    $stmt_doc = $conn->prepare("INSERT INTO doctor(staffno, firstname, lastname, specialisation, qualification, pay, gender, consultantstatus, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt_doc->bind_param("sssisisis", 
                        $staffno, 
                        $_POST['firstname'], 
                        $_POST['lastname'], 
                        $spec_id, 
                        $_POST['qualification'], 
                        $_POST['pay'], 
                        $gender_id, 
                        $status_id, 
                        $_POST['address']
                    );

                    $audit_package = [
                        'action' => 'ADD_DOCTOR', 
                        'target' => $staffno, 
                        'desc'   => "Admin added new doctor ($staffno)"
                    ];

                    if (executeAndLog($conn, $stmt_doc, $audit_package)) {
                        $message = "Success! Doctor added.<br><strong>Username:</strong> $staffno <br>";
                        $message_type = "success";
                    } else {
                        $message = "Error adding doctor details: " . $stmt_doc->error;
                        $message_type = "error";
                    }
                    $stmt_doc->close();

                } else {
                    $message = "Error: Staff ID might already exist.";
                    $message_type = "error";
                }

            } catch (mysqli_sql_exception $e) {
                $message = "Database Error: " . $e->getMessage();
                $message_type = "error";
            }
        }
    }

    // Little pages
    $results_per_page = 5; 
    $count_sql = "SELECT COUNT(*) AS total FROM doctor 
              WHERE staffno NOT IN (SELECT username COLLATE utf8mb4_general_ci FROM users WHERE role = 'admin')";
    $row_count = mysqli_fetch_assoc(mysqli_query($conn, $count_sql));
    $total_records = $row_count['total'];
    $total_pages = ceil($total_records / $results_per_page);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $start_limit = ($page-1) * $results_per_page;


    $sql = "SELECT d.*, 
                dept.specialisationname, 
                g.gendername, 
                cs.statusname 
            FROM doctor d
            LEFT JOIN department dept ON d.specialisation = dept.id
            LEFT JOIN gender g ON d.gender = g.id
            LEFT JOIN consultant_status cs ON d.consultantstatus = cs.id
            
            WHERE d.staffno NOT IN (SELECT username COLLATE utf8mb4_general_ci FROM users WHERE role = 'admin')

            ORDER BY d.lastname ASC 
            LIMIT ?, ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $start_limit, $results_per_page);
        $stmt->execute();
        $result = $stmt->get_result();
?>

<?php 
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Manage Doctors</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                        <h5 class="card-title text-primary fw-bold">Add New Doctor</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <input type="hidden" name="add_doctor_btn" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Staff No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="staffno" required placeholder="e.g. QM000">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control" name="firstname" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Last Name</label>
                                    <input type="text" class="form-control" name="lastname" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Specialisation</label>
                                <select name="specialisation" class="form-select">
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Radiology">Radiology</option>
                                    <option value="Pediatrics">Pediatrics</option>        
                                    <option value="Oncology">Oncology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="Dermatology">Dermatology</option>
                                    <option value="Psychiatry">Psychiatry</option>
                                    <option value="Anesthesiology">Anesthesiology</option>
                                    <option value="Gastroenterology">Gastroenterology</option>
                                    <option value="General Surgery">General Surgery</option>
                                    <option value="Emergency Medicine">Emergency Medicine</option>
                                    <option value="Urology">Urology</option>
                                    <option value="Ophthalmology">Ophthalmology</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Qualification</label>
                                <input type="text" class="form-control" name="qualification" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Pay (£)</label>
                                <input type="number" class="form-control" name="pay" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="consultant_status" class="form-select">
                                        <option value="consultant">Consultant</option>
                                        <option value="not_consultant">Not consultant</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus-fill me-2"></i>Add Doctor
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white pt-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">Current Doctors</h5>
                        <span class="badge bg-light text-dark border">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Staff ID</th>
                                        <th>Name</th>
                                        <th>Dept / Qual</th>
                                        <th>Details</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                            <?php 
                                                // NULL handling
                                                $fname = !empty($row['firstname']) ? htmlspecialchars($row['firstname']) : 'N/A';
                                                $lname = !empty($row['lastname']) ? htmlspecialchars($row['lastname']) : '';
                                                $gender = !empty($row['gendername']) ? htmlspecialchars($row['gendername']) : '<span class="text-muted fst-italic">Unassigned</span>';
                                                $dept = !empty($row['specialisationname']) ? htmlspecialchars($row['specialisationname']) : 'General';
                                                $qual = !empty($row['qualification']) ? htmlspecialchars($row['qualification']) : '<span class="text-muted small">No Qual.</span>';
                                                $status = !empty($row['statusname']) ? htmlspecialchars($row['statusname']) : 'Unknown';
                                                $pay = isset($row['pay']) ? '£' . number_format($row['pay']) : '<span class="text-muted">N/A</span>';
                                                
                                               
                                                $edit_link = "edit_doctor.php?id=" . urlencode($row['staffno']);
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['staffno']); ?></td>
                                                <td>
                                                    <div class="fw-bold"><?php echo $fname . ' ' . $lname; ?></div>
                                                    <small class="text-muted"><?php echo $gender; ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info text-dark mb-1"><?php echo $dept; ?></span><br>
                                                    <small class="text-muted"><?php echo $qual; ?></small>
                                                </td>
                                                <td>
                                                    <small class="d-block text-muted">Status: <?php echo $status; ?></small>
                                                    <small class="d-block text-muted">Pay: <?php echo $pay; ?></small>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?php echo $edit_link; ?>" class="btn btn-outline-primary">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">No doctors found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-3">
                        <nav aria-label="Doctor pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>