<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

if (($_GET['mode'] ?? '') !== 'search') {
    http_response_code(400);
    exit('Invalid mode');
}

$keyword = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = max(1, (int)($_GET['per_page'] ?? 20));
$offset = ($page - 1) * $per_page;

$list = [];
$total = 0;

if ($keyword !== '') {
    $bind = ['kw' => "%{$keyword}%"];
    $sqlCount = "SELECT COUNT(*) FROM df_site_product WHERE f_name LIKE :kw";
    $total = $db->single($sqlCount, $bind);
    $sql = "SELECT f_idx, f_name, f_thumbnail FROM df_site_product
            WHERE f_name LIKE :kw
            ORDER BY prior DESC
            LIMIT {$offset}, {$per_page}";
    $list = $db->query($sql, $bind, PDO::FETCH_ASSOC);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'list' => $list,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page
]);
exit;
?>
