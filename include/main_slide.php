<?php

$sql = "SELECT * FROM df_site_main_slide
        WHERE showset = 'Y'
        
        ORDER BY prior DESC";

$all_slide = $db->query($sql, null, PDO::FETCH_OBJ);

$main_slide = [];
$best_slide = [];

foreach ($all_slide as $slide) {
    if ($slide->code === 'main') {
        $main_slide[] = $slide;
    } elseif ($slide->code === 'best') {
        $best_slide[] = $slide;
    }
}


// $sql = "SELECT * FROM df_site_main_slide
//         WHERE showset = 'Y'
//         AND code = 'best'
//         ORDER BY prior DESC";

// $best_slide = $db->query($sql, null, PDO::FETCH_OBJ);
?>