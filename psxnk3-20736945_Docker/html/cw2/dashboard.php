<?php

    /* * Dashboard layout references
     * * Grid System: Uses Bootstrap 5 'row-cols' classes for responsive layout.
     * Reference: Bootstrap 'Grid System'. https://getbootstrap.com/docs/5.3/layout/grid/
     * * Clickable Cards: Uses the 'stretched-link' utility to make entire cards clickable.
     * Reference: Bootstrap 'Stretched Link'. https://getbootstrap.com/docs/5.3/helpers/stretched-link/
     * * Hover Animation: Custom CSS added to lift cards on mouseover for better UI feedback.
     * Reference: W3Schools 'CSS Transitions'. https://www.w3schools.com/css/css3_transitions.asp
     */
     

    session_start();
    
    // Redirect to login if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }


    require_once(__DIR__ . '/Includes/header.php'); 
    require_once(__DIR__ . '/Includes/left_menu.php'); 
    require_once(__DIR__ . '/Includes/config.php');

    // Get current user details, default role - admin
    $role = $_SESSION['role'] ?? 'admin'; 
    $user_id = $_SESSION['user_id'];
?>

<main class="main-content">
    <div class="container-fluid p-5">
        
        <div class="mb-4">
            <h1 class="mb-2">Dashboard</h1>
            <p class="text-muted">
                Welcome back, <strong><?php echo htmlspecialchars($user_id); ?></strong>
            </p>
            <hr class="text-muted">
        </div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-4 g-4">

            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="display-4 mb-3">🔍</div>
                        <h3 class="h5 card-title fw-bold">Search Patients</h3>
                        <p class="card-text text-muted small">Find patient records by NHS number or Name.</p>
                        <a href="patient_lookup.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="display-4 mb-3">🧪</div>
                        <h3 class="h5 card-title fw-bold">Prescribe</h3>
                        <p class="card-text text-muted small">Add test to patient.</p>
                        <a href="prescribe.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <?php if ($role == 'admin'): ?>
                
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">👨‍⚕️</div>
                            <h3 class="h5 card-title fw-bold">Manage Doctors</h3>
                            <p class="card-text text-muted small">Add and edit medical staff accounts.</p>
                            <a href="add_doctor.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">🛡️</div>
                            <h3 class="h5 card-title fw-bold">Audit Logs</h3>
                            <p class="card-text text-muted small">View system security logs and user activity.</p>
                            <a href="audit_admin.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">🏎️</div>
                            <h3 class="h5 card-title fw-bold">Parking Permit</h3>
                            <p class="card-text text-muted small">Approve or reject requests.</p>
                            <a href="parking_permit_admin.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <?php if ($role == 'doctor'): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-success hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">👤</div>
                            <h3 class="h5 card-title fw-bold">My Profile</h3>
                            <p class="card-text text-muted small">View and update your details.</p>
                            <a href="profile_doctor.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">➕</div>
                            <h3 class="h5 card-title fw-bold">Add Patient</h3>
                            <p class="card-text text-muted small">Register a new patient into the system.</p>
                            <a href="add_patient.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="display-4 mb-3">🏎️</div>
                            <h3 class="h5 card-title fw-bold">Parking Permit</h3>
                            <p class="card-text text-muted small">Request parking permit and track updates.</p>
                            <a href="parking_permit_doctor.php" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="display-4 mb-3">🔬</div>
                        <h3 class="h5 card-title fw-bold">Add test</h3>
                        <p class="card-text text-muted small">Add test that is not in the system.</p>
                        <a href="add_test.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>

        </div> </div>
</main>

<style>
    .hover-effect { transition: transform 0.2s ease-in-out; }
    .hover-effect:hover { transform: translateY(-5px); background-color: #f8f9fa; }
</style>

<?php require_once(__DIR__ .'/Includes/footer.php'); ?>