<?php
// ──────────────────────────────────────────────
// 1~2차 카테고리 전체 조회
// ──────────────────────────────────────────────
$sql = "
  SELECT f_idx, f_parent_idx, f_depth, f_name, f_thumbnail
  FROM df_site_category
  WHERE f_depth IN (1,2)
  AND f_use = 'Y'
  ORDER BY f_depth, f_order
";
$cats = $db->query($sql, [], PDO::FETCH_OBJ);

$nodes = [];
foreach ($cats as $c) {
    $nodes[$c->f_idx] = [
        'idx'      => $c->f_idx,
        'parent'   => $c->f_parent_idx,
        'depth'    => $c->f_depth,
        'name'     => $c->f_name,
        'thumbnail'     => $c->f_thumbnail,
        'children' => []
    ];
}

// depth2를 parent 아래에 붙이기
foreach ($nodes as $id => $node) {
    if ($node['depth'] === 2 && isset($nodes[$node['parent']])) {
        $nodes[$node['parent']]['children'][$id] = $node;
    }
}

// depth1 메뉴만 추출.
$menu = array_filter($nodes, function($n){
    return $n['depth'] === 1;
});

// PC용: depth1 1줄당 5개씩 2줄로
$menuItems  = array_values($menu);
$firstCount = 5;
$rows       = [
    array_slice($menuItems, 0, $firstCount),
    array_slice($menuItems, $firstCount),
];

unset($node);
?>
