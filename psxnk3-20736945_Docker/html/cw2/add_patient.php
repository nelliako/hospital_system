<?php
    session_start();

    $page_title = "Add Patient";

    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    // Initialize variables
    $search_result = null; 
    $message = "";
    $message_type = "";

    // Session Check 
    if (!isset($_SESSION['user_id'])){
        $current_user_id = 'QM965'; // Dummy for testing
        $_SESSION['user_id'] = $current_user_id; 
   } else {
        $current_user_id = $_SESSION['user_id'];
    }

    // Form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        /* * Reference: W3Schools 'PHP Null Coalescing Operator'.
         * efficient handling of potential null values from the POST array
         */
        // Validate inputs
        $NHSno = $_POST['NHSno'] ?? '';
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $age = $_POST['age'] ?? '';
        $gender = $_POST['gender'] ?? ''; // This receives 'male' or 'female'
        $emergencyphone = $_POST['emergencyphone'] ?? '';

        // Logic: 1 = Female, 0 = Male
        $genderForDB = ($gender === 'female') ? 1 : 0;

        if (empty($NHSno) || empty($firstname) || empty($lastname) || empty($phone) || empty($age) || empty($gender) ){
            $message = "Please fill in all required fields (marked *).";
            $message_type = "error";
        } else {
            // SQL Preparation
            $stmt = $conn->prepare("INSERT INTO patient (NHSno, firstname, lastname, phone, address, age, gender, emergencyphone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssiss', $NHSno, $firstname, $lastname, $phone, $address, $age, $genderForDB, $emergencyphone);
            
            try {
                // AUdit - to keep changes logged
                $audit_package = [
                    'action' => 'ADD_PATIENT', 
                    'target' => $NHSno, 
                    'desc'   => "User added new patient: $firstname $lastname"
                ];

                // Run the function (audit)
                if (executeAndLog($conn, $stmt, $audit_package)) {
                    // Success! Redirect to self to prevent double submission
                    header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
                    exit();
                }

            } catch (mysqli_sql_exception $e) {
                $message = "Database Error: " . $e->getMessage();
                $message_type = "error";
            }
            $stmt->close();
        }   
    }

    // Check for success flag in URL
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        $message = "Patient added successfully!";
        $message_type = "success"; 
    }

    
    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Register New Patient</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php if($message_type == 'success'): ?>
                    <i class="bi bi-check-circle-fill me-2"></i>
                <?php else: ?>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php endif; ?>
                
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">Patient Details</h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    
                    <div class="col-md-6">
                        <label for="NHSno" class="form-label fw-bold">NHS Number*</label>
                        <input type="text" class="form-control" id="NHSno" name="NHSno" placeholder="e.g. W12435" required>
                    </div>

                    <div class="col-md-3">
                        <label for="age" class="form-label fw-bold">Age*</label>
                        <input type="number" class="form-control" id="age" name="age" required>
                    </div>

                    <div class="col-md-3">
                        <label for="gender" class="form-label fw-bold">Gender*</label>
                        <select id="gender" name="gender" class="form-select" required>
                            <option value="" selected disabled>Select...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="firstname" class="form-label fw-bold">First Name*</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>

                    <div class="col-md-6">
                        <label for="lastname" class="form-label fw-bold">Last Name*</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-bold">Phone Number*</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="col-md-6">
                        <label for="emergencyphone" class="form-label">Emergency Phone <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="text" class="form-control" id="emergencyphone" name="emergencyphone">
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label fw-bold">Address*</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>

                    <div class="col-12 mt-4">
                        <hr class="text-muted">
                        <button type="submit" class="btn btn-nhs btn-lg">Add Patient</button>
                        <a href="add_patient.php" class="btn btn-outline-secondary btn-lg ms-2">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<?php
    require_once(__DIR__ . '/Includes/footer.php');
?>