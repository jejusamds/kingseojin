<?php

// 1) 파라미터 수신
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : '';
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$param = "page={$page}&search_type={$search_type}&keyword={$keyword}";

// 2) 기본 조건: code = 'notice'
$addSql = " WHERE code = :code and depno = 0 ";
$binds = [];

// 3) 검색 조건 추가
if ($keyword !== '') {
    $addSql .= " AND ( subject LIKE :keyword OR content LIKE :keyword2 ) ";
    $binds['keyword'] = "%{$keyword}%";
    $binds['keyword2'] = "%{$keyword}%";
}

// 4) 전체 건수 조회
$page_set = 10;  // 한 페이지에 보여줄 행 수
$sqlTotal = "SELECT COUNT(1) FROM df_site_bbs {$addSql}";

foreach ($binds as $key => $val) {
    $db->bind($key, $val);
}
$db->bind('code', $code);
$total = $db->single($sqlTotal);

// 5) 페이징 계산
$total_page = $total > 0 ? ceil($total / $page_set) : 1;
if ($page < 1)
    $page = 1;
if ($page > $total_page)
    $page = $total_page;
$offset = ($page - 1) * $page_set;

$blockSize = 5;
$blockStart = (int) (($page - 1) / $blockSize) * $blockSize + 1;
$blockEnd = min($total_page, $blockStart + $blockSize - 1);

// 블럭 단위 이전/다음 페이지
$prevPage = $blockStart > 1 ? $blockStart - 1 : 1;
$nextPage = $blockEnd < $total_page ? $blockEnd + 1 : $total_page;

// 6) 리스트 조회
$sqlList = " SELECT b.idx,
           b.notice,
           b.subject,
           DATE_FORMAT(b.wdate, '%Y.%m.%d') AS date_fmt,
           f.idx AS file_idx,
           b.privacy,
           b.name,
           EXISTS (
                SELECT 1
                FROM df_site_bbs r
                WHERE r.parno = b.idx
                LIMIT 1
            ) AS has_reply
      FROM df_site_bbs b
 LEFT JOIN df_site_bbs_files f ON b.idx = f.bbsidx
    {$addSql}
  GROUP BY b.idx
  ORDER BY b.notice = 'Y' DESC, b.wdate DESC
  LIMIT :offset, :page_set
";
// 바인딩
foreach ($binds as $key => $val) {
    $db->bind($key, $val);
}
$db->bind("offset", $offset);
$db->bind("page_set", $page_set);
$db->bind('code', $code);
$rows = $db->query($sqlList);