<?php
if(isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = $_SERVER['DOCUMENT_ROOT'].'/userfiles/inquiry/'.$file;
    
    if(file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file.'"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}
