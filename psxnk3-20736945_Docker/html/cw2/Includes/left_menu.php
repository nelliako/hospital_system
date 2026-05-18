<nav class="nhs-sidebar d-flex flex-column justify-content-between">
    
    <div>
        <div class="sidebar-header">
            <h3>Medical System</h3>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="patient_lookup.php" class="nav-link">Search Patients</a>
            <a href="test_lookup.php" class="nav-link">Search Tests</a>
            <a href="prescribe.php" class="nav-link">Prescribe</a>
            <a href="parking_permit_doctor.php" class="nav-link">Parking Permit</a>
            <a href="password_change.php" class="nav-link">Change Password</a>
            <a href="user_manual.php" class="nav-link">User Manual</a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'doctor'): ?>
                <div class="menu-divider"></div> <a href="profile_doctor.php" class="nav-link">My Profile</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <div class="menu-divider"></div>
                <div class="menu-label">Admin Controls</div>
                <a href="add_doctor.php" class="nav-link">Add Doctor</a>
                <a href="audit_admin.php" class="nav-link">Audit Logs</a>
                <a href="parking_permit_admin.php" class="nav-link">Permit Requests</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn btn-logout w-100">Logout</a>
    </div>

</nav>