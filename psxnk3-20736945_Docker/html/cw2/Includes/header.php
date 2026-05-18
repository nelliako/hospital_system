<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="Css/global.css">

    <title>
      <?php 
        if(isset($page_title)){
          echo $page_title;
        } else {
          echo "Hospital Management System"; 
        }
      ?>
    </title>
</head>

<body>

    <header class="nhs-header d-flex align-items-center">
        <div class="container-fluid d-flex justify-content-between align-items-center px-4">
            
            <div class="header-title">
                <h3 class="mb-0 text-white"></h3>
            </div>

            <a href="dashboard.php" class="text-decoration-none d-flex align-items-center bg-white p-1 rounded">
                <img src="nhs.png" alt="NHS Logo" height="40" class="me-2"> 
                <span class="text-black fw-bold pe-2">QMC Hospital</span>
            </a>

        </div>
    </header>
