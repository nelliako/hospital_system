<?php
    session_start();
    $page_title = "Patient Search";


    require_once(__DIR__ . '/Includes/audit_trail.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    $search_result = null; 
    $message = "";
    $message_type = "";

    // Handle "Deleted" redirect message
    if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
        $message = "Patient record was deleted successfully.";
        $message_type = "success";
    }
/* * Reference: W3Schools (2025) 'PHP Forms'.
     * Implemented logic to check for 'query' parameter before executing database actions.
     * This follows the check first protocol to prevent unnecessary SQL execution
     */

    // Handle Search Query
    if (isset($_GET['query'])){
        $search_term = mysqli_real_escape_string($conn, $_GET['query']);
        $current_user = $_SESSION['user_id'] ?? 'Unknown User';
        
        logActivity($conn, $current_user, 'SEARCH', 'Patient Table', "User searched for: " . $search_term);

        $sql = "SELECT * FROM patient WHERE firstname LIKE '%$search_term%' OR lastname LIKE '%$search_term%' OR NHSno LIKE '%$search_term%'";
        $search_result = mysqli_query($conn, $sql);
    }

    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php');
?>

<style>
    /* Small history boxes */
    .history-scroll {
        max-height: 150px;
        overflow-y: auto;
    }
    /* Divider for history items */
    .history-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
        margin-bottom: 8px;
    }
    .history-item:last-child { border-bottom: none; }
</style>

<main class="main-content">
    <div class="container-fluid p-5">

        <h1 class="mb-4">Search for Patient</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 border-0">
            <div class="card-body p-4 bg-light rounded">
                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="search" class="form-label fw-bold">Find a Patient:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">🔍</span>
                            <input type="search" class="form-control border-start-0 ps-0" id="search" name="query" 
                                   value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>" 
                                   placeholder="Type patient's name or NHS number..." required>
                            <button type="submit" class="btn btn-nhs">Search</button>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="add_patient.php" class="btn btn-outline-primary">
                            <span class="me-1">➕</span> Add New Patient
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['query']) && $search_result): ?>
            
            <div class="results-section">
                <?php if (mysqli_num_rows($search_result) > 0): ?>
                    
                    <p class="text-muted mb-4">Found <strong><?php echo mysqli_num_rows($search_result); ?></strong> patient(s):</p>
                    
                    <?php while ($row = mysqli_fetch_assoc($search_result)): ?>
                        <?php 
            
                            // Data fetching logic
                            $nhs_no = $row['NHSno']; 
                            $clean_nhs = htmlspecialchars($row['NHSno']);
                            $full_name = htmlspecialchars($row['firstname'] . " " . $row['lastname']);
                            $phone = htmlspecialchars($row['phone']);
                            $address = htmlspecialchars($row['address']);

                            // Admission
                            $adm_sql = "SELECT w.date, w.consultantid, w.status, w.wardid, d.firstname, d.lastname, wd.wardname AS ward_name  
                                        FROM ward_patient_admission w
                                        LEFT JOIN doctor d ON w.consultantid = d.staffno
                                        LEFT JOIN ward wd ON w.wardid = wd.wardid
                                        WHERE w.pid = '$nhs_no' 
                                        ORDER BY w.date DESC, w.time DESC LIMIT 1";
                            $adm_res = $conn->query($adm_sql);
                            $adm_data = ($adm_res && $adm_res->num_rows > 0) ? $adm_res->fetch_assoc() : null;

                            // Exams
                            $exam_sql = "SELECT pe.date, pe.time, pe.doctorid, d.firstname, d.lastname 
                                         FROM patient_examination pe
                                         LEFT JOIN doctor d ON pe.doctorid = d.staffno
                                         WHERE pe.patientid = '$nhs_no' 
                                         ORDER BY pe.date DESC, pe.time DESC";
                            $exam_res = $conn->query($exam_sql);

                            // Tests
                            $test_sql = "SELECT pt.testid, pt.date, pt.report, t.testname 
                                         FROM patient_test pt 
                                         LEFT JOIN test t ON pt.testid = t.testid
                                         WHERE pt.pid = '$nhs_no' 
                                         ORDER BY pt.date DESC LIMIT 5";
                            $test_res = $conn->query($test_sql);
                        ?>
                        
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-header bg-white p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="h5 mb-1">
                                            <a href="patient_profile_and_edit.php?id=<?php echo $clean_nhs; ?>" class="text-decoration-none fw-bold text-dark stretched-link">
                                                <?php echo $full_name; ?>
                                            </a>
                                        </h2>
                                        <div class="text-muted small">
                                            <span class="me-3"><strong>NHS No:</strong> <?php echo $clean_nhs; ?></span>
                                            <span class="me-3"><strong>Phone:</strong> <?php echo $phone; ?></span>
                                            <span><i class="bi bi-geo-alt"></i> <?php echo $address; ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-light text-dark border">View Profile &rarr;</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="row g-0">
                                    
                                    <div class="col-md-4 p-4 border-end">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Latest Admission</h6>
                                        <?php if($adm_data): ?>
                                            <ul class="list-unstyled small mb-0">
                                                <li class="mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($adm_data['date']); ?></li>
                                                <li class="mb-2"><strong>Ward:</strong> 
                                                    <?php 
                                                        if (!empty($adm_data['ward_name'])) echo htmlspecialchars($adm_data['ward_name']);
                                                        elseif (!empty($adm_data['wardid'])) echo htmlspecialchars($adm_data['wardid']);
                                                        else echo "N/A";
                                                    ?>
                                                </li>
                                                <li class="mb-2"><strong>Consultant:</strong> 
                                                    <?php 
                                                        if (!empty($adm_data['firstname'])) echo htmlspecialchars("Dr. " . $adm_data['firstname'] . " " . $adm_data['lastname']);
                                                        else echo htmlspecialchars($adm_data['consultantid']);
                                                    ?>
                                                </li>
                                                <li class="mt-3">
                                                    <?php echo ($adm_data['status'] == 1) 
                                                        ? '<span class="badge bg-success">Current Inpatient</span>' 
                                                        : '<span class="badge bg-secondary">Discharged</span>'; ?>
                                                </li>
                                            </ul>
                                        <?php else: ?>
                                            <em class="text-muted small">No admission history found.</em>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-4 p-4 border-end bg-light">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Exam History</h6>
                                        <div class="history-scroll pe-2">
                                            <?php if($exam_res && $exam_res->num_rows > 0): ?>
                                                <?php while($ex = $exam_res->fetch_assoc()): ?>
                                                    <?php 
                                                        if (!empty($ex['firstname'])) $doc = "Dr. " . htmlspecialchars($ex['firstname'] . " " . $ex['lastname']);
                                                        else $doc = "ID: " . htmlspecialchars($ex['doctorid']);
                                                    ?>
                                                    <div class="history-item small">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($ex['date']); ?> <span class="text-muted fw-normal">(<?php echo htmlspecialchars($ex['time'] ?? '--:--'); ?>)</span></div>
                                                        <div class="text-primary"><?php echo $doc; ?></div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <em class="text-muted small">No examinations found.</em>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4 p-4">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Recent Tests</h6>
                                        <div class="history-scroll pe-2">
                                            <?php if($test_res && $test_res->num_rows > 0): ?>
                                                <?php while($t = $test_res->fetch_assoc()): ?>
                                                    <div class="history-item d-flex justify-content-between align-items-start small">
                                                        <div>
                                                            <span class="badge bg-info text-dark mb-1">
                                                                <?php echo !empty($t['testname']) ? htmlspecialchars($t['testname']) : "ID: " . htmlspecialchars($t['testid']); ?>
                                                            </span>
                                                            <div class="text-muted" style="font-size:0.85em;">
                                                                Result: <?php echo !empty($t['report']) ? htmlspecialchars($t['report']) : '<span class="text-warning">Pending</span>'; ?>
                                                            </div>
                                                        </div>
                                                        <span class="text-muted" style="font-size:0.85em;"><?php echo $t['date']; ?></span>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <em class="text-muted small">No tests found.</em>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div> </div> </div> <?php endwhile; ?>

                <?php else: ?>
                    <div class="card text-center p-5 shadow-sm border-0">
                        <div class="card-body">
                            <div class="display-1 text-muted mb-3"></div>
                            <h3 class="h5">No patients found</h3>
                            <p class="text-muted">We couldn't find any records for "<strong><?php echo htmlspecialchars($_GET['query']); ?></strong>".</p>
                            <a href="add_patient.php" class="btn btn-nhs mt-3">Add New Patient</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>