<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); 
}




$file = __DIR__ . '/user_manual.pdf';


if (!file_exists($file)) {
    die("Error: File not found at " . $file);
}


if(ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', '0');
}


while (ob_get_level()) {
    ob_end_clean();
}


header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="user_manual.pdf"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

readfile($file);
exit;
?>
