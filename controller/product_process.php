<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

if ($_GET['mode'] !== 'product_list') {
    http_response_code(400);
    exit('Invalid mode');
}

// 1) 파라미터
$category_idx = (int)($_GET['category_idx'] ?? 0);
$depth        = (int)($_GET['depth'] ?? 0);
$selected     = $_GET['selected'] ?? [];

// 페이징 관련
$page     = max(1, (int)($_GET['page']     ?? 1));
$per_page = max(1, (int)($_GET['per_page'] ?? 20));
$offset   = ($page - 1) * $per_page;

$bind = [];

// 2) 필터 조건 조립 (기존과 동일)
if (is_array($selected) && count($selected) > 0) {
    $ph = [];
    foreach ($selected as $i => $val) {
        
        $key = "sel{$i}";
        $ph[] = ":" . $key;
        $bind[$key] = (int)$val;
    }
    $where = "AND p.f_cat_idx IN(" . implode(',', $ph) . ")";
} else {
    if ($depth === 1) {
        $where = "AND c1.f_idx = :cat";
    } else {
        $where = "AND c2.f_idx = :cat";
    }
    $bind['cat'] = $category_idx;
}

// 3) 전체 개수 쿼리
$sqlCount = "
  SELECT COUNT(*) 
  FROM df_site_product AS p
    LEFT JOIN df_site_category AS c3 ON p.f_cat_idx      = c3.f_idx
    LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx  = c2.f_idx
    LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx  = c1.f_idx
  WHERE 1=1
    {$where}
";
$total = $db->single($sqlCount, $bind);

// 4) 페이지 데이터 쿼리
$sqlData = "
  SELECT p.f_idx, p.f_name, p.f_thumbnail
  FROM df_site_product AS p
    LEFT JOIN df_site_category AS c3 ON p.f_cat_idx      = c3.f_idx
    LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx  = c2.f_idx
    LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx  = c1.f_idx
  WHERE 1=1
    {$where}
  ORDER BY p.prior DESC
  LIMIT {$offset}, {$per_page}
";
$list = $db->query($sqlData, $bind, PDO::FETCH_ASSOC);

// 5) 결과 리턴
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'list'     => $list,
  'total'    => $total,
  'page'     => $page,
  'per_page' => $per_page,
]);
exit;
