<?php
    session_start();

    /* * Reference: Apache HTTP Server Project 'Custom Error Responses'.
     * Logic relies on the 'REDIRECT_STATUS' environment variable, which is automatically set by the Apache server when an ErrorDocument directive is triggered
     */
    // Get the error code from the server, or default to "Unknown"
   
    $status = $_SERVER['REDIRECT_STATUS'] ?? 500;
    
    // Customize title and message based on the code
    $codes = [
        403 => ['title'=> 'Access Denied', 'msg' => 'You do not have permission to view this page.'],
        500 => ['title' => 'System Error',   'msg' => 'Something went wrong on our end. Please try again later.'],
        400 => ['title'=>'Bad Request',    'msg' => 'Your browser sent a request that this server could not understand.']
    ];

    // Pick the message, or use a default 
    $details = $codes[$status] ?? ['title' => 'Error', 'msg' => 'An unexpected error occurred.'];

    $page_title = $details['title'];
    

    require_once(__DIR__ . '/Includes/config.php'); 
    require_once(__DIR__ . '/Includes/header.php'); 
    if (isset($_SESSION['user_id'])) {
        require_once(__DIR__ . '/Includes/left_menu.php');
    }
?>

<div class="container" style="text-align: center; padding-top: 50px;">
    
    <h1 style="font-size: 80px; margin: 0; color: #d9534f;">
        <?php echo $status; ?>
    </h1>
    
    <h2 style="color: #333;"><?php echo $details['title']; ?></h2>
    <p style="color: #666; font-size: 18px; max-width: 600px; margin: 0 auto;">
        <?php echo $details['msg']; ?>
    </p>

    <div style="margin-top: 40px;">
        <a href="dashboard.php" class="button">Return to Dashboard</a>
    </div>

</div>

<?php require_once(__DIR__ . '/Includes/footer.php'); ?>