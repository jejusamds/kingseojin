<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';
exit;
$sql = "insert into df_site_category(f_parent_idx, f_depth, f_name, f_order) values (:f_parent_idx, :f_depth, :f_name, :f_order)";

$datas = [
'운반카(우드)',
'운반카(플라스틱)',
'운반카(데크)',
'운반카(스텐)',
'운반카(음식처리기)',
'우드운반카(스틸)',
];

$i = 1;
error_reporting(E_ALL);
ini_set("display_errors", 1);
foreach ($datas as $dt) {

    $parent_idx = 40;
    $f_depth = 3;


    $binds = [
        'f_parent_idx' => $parent_idx,
        'f_depth' => $f_depth,
        'f_name' => $dt,
        'f_order' => $i
    ];

    var_dump($binds);
    echo "<br>";echo "<br>";

    $db->query($sql, $binds);

    $i++;
}