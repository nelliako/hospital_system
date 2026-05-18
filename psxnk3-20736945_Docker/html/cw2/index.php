<?php 
    $page_title = "Login";
    require_once(__DIR__ . '/Includes/header.php'); 
?>


<style>
    body {
        padding-left: 0; 
        background-color: var(--nhs-grey-5); 
    }

    /* Fix header & footer alignment for this page only as this is a structurally different page*/
    .nhs-header,
    .nhs-footer {
        margin-left: 0;
        width: 100%;
    }

    /* * Reference: W3Schools 'CSS Flexbox'.
     * Used the Flexbox layout module (justify-content, align-items) to vertically and horizontally center the login card within the viewport.
     */
    .login-wrapper {
        min-height: 80vh; 
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .login-card {
        width: 100%;
        max-width: 450px; /* Preventing it from getting too wide */
    }
</style>

<div class="login-wrapper">
    <div class="card shadow-sm login-card border-0">
        
        <div class="card-header bg-white text-center py-4 border-bottom-0">
             <h2 class="h3 fw-bold text-primary">Medical System</h2>
             <p class="text-muted mb-0">Please login to continue</p>
        </div>

        <div class="card-body p-4">
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <span class="me-2"></span>
                    <div><?php echo htmlspecialchars($_GET['error']); ?></div>
                </div>
            <?php endif; ?>

            <form name="login" method="post" action="Includes/loginaction.php">
                
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control form-control-lg" name="username" id="username" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control form-control-lg" name="password" id="password" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-nhs btn-lg">Login</button>
                    
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='password_reset.php';">
                        Reset Password
                    </button>
                </div>

            </form>
            </div>
        
        <div class="card-footer bg-light text-center py-3">
            <small class="text-muted">Authorized access only.</small>
        </div>
    </div>
</div>

<?php 
    
    require_once(__DIR__ . '/Includes/footer.php'); 
?>