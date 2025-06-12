<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";

$code = "product";
$this_table = 'df_site_product';

$param = "searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword . "&category_1=" . $category_1 . "&category_2=" . $category_2 . "&category_3=" . $category_3;

foreach ($_POST as $key => $val) {
    if (is_string($val)) {
        ${$key} = addslashes(${$key});
    }
}

if (!$notice)
    $notice = "N";

function file_upload($files, $code)
{
    $upfile = $files;
    $upfile_name = $upfile['name'];
    $upfile_tmp = $upfile['tmp_name'];
    $upfile_error = $upfile['error'];
    $upfile_ext = explode('.', $upfile_name);
    $upfile_ext = strtolower(end($upfile_ext));
    $upfile_thumb = "";

    if ($upfile_error === 0) {
        $upfile_name_new = uniqid('', true) . "." . $upfile_ext;
        $upfile_dest = $_SERVER["DOCUMENT_ROOT"] . "/userfiles/contents/" . $code . "/" . $upfile_name_new;

        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/userfiles/contents/" . $code . "/";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo "디렉토리 생성에 실패했습니다.";
                return "";
            }
        }

        move_uploaded_file($upfile_tmp, $upfile_dest);
        $upfile_thumb = $upfile_name_new;
    } else {
        echo "파일 업로드 중 오류가 발생했습니다.";
    }

    return $upfile_thumb;
}


if ($mode === 'get_category') {
    $depth = (int) $_GET['depth'];
    $sql = "
      SELECT f_idx AS idx, f_name AS name
      FROM df_site_category
      WHERE f_depth = :depth
    ";
    $params = ['depth' => $depth];

    // 1차는 parent 조건 생략, 2·3차는 parent_idx 조건 추가
    if ($depth > 1 && isset($_GET['parent_idx'])) {
        $sql .= " AND f_parent_idx = :parent";
        $params['parent'] = (int) $_GET['parent_idx'];
    }
    $sql .= " ORDER BY f_order";

    $list = $db->query($sql, $params, PDO::FETCH_ASSOC);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($list);
    exit;
}

//====================================================================================================
//	작성
//====================================================================================================
else if ($mode == "insert") {

    $cat3 = (int) ($_POST['category_3'] ?? 0);
    $name = $_POST['f_name'] ?? '';
    $origin = $_POST['f_origin'] ?? '';
    $size = $_POST['f_size'] ?? '';
    $material = $_POST['f_material'] ?? '';
    $price = $_POST['f_price'] ?? '';
    $details = $_POST['f_details'] ?? '';
    $details_2 = $_POST['f_details_2'] ?? '';

    // 썸네일 업로드 처리
    $thumb = '';
    if (!empty($_FILES['f_thumbnail']['name'])) {
        $thumb = file_upload($_FILES['f_thumbnail'], 'product');
    }

    $sql = "INSERT INTO df_site_product
      SET
        f_idx       = NULL,
        f_cat_idx   = :cat3,
        f_thumbnail = :thumb,
        f_name      = :name,
        f_origin    = :origin,
        f_size      = :size,
        f_material  = :material,
        f_price     = :price,
        f_details   = :details,
        f_details_2   = :details_2,
        f_regdate   = NOW()
    ";

    $sql .= "	,		prior = '" . date("ymdHis") . "' ";

    $bind = [
        'cat3' => $cat3,
        'thumb' => $thumb,
        'name' => $name,
        'origin' => $origin,
        'size' => $size,
        'material' => $material,
        'price' => $price,
        'details' => $details,
        'details_2' => $details_2,
    ];

    $db->query($sql, $bind);
    $newIdx = $db->lastInsertId();
    complete("제품이 등록되었습니다.", "contents_list.php?page={$page}&{$param}");
}

//====================================================================================================
//	수정
//====================================================================================================
else if ($mode == "update") {

    $cat3 = (int) ($_POST['category_3'] ?? 0);
    $name = $_POST['f_name'] ?? '';
    $origin = $_POST['f_origin'] ?? '';
    $size = $_POST['f_size'] ?? '';
    $material = $_POST['f_material'] ?? '';
    $price = $_POST['f_price'] ?? '';
    $details = $_POST['f_details'] ?? '';
    $details_2 = $_POST['f_details_2'] ?? '';

    // 썸네일 업로드 처리
    $thumb = '';
    if (!empty($_FILES['f_thumbnail']['name'])) {
        $thumb = file_upload($_FILES['f_thumbnail'], 'product');
    }

    $thumbSql = $thumb
        ? " , f_thumbnail = :thumb "
        : "";

    $sql = "UPDATE df_site_product
      SET
        f_cat_idx   = :cat3,
        f_name      = :name,
        f_origin    = :origin,
        f_size      = :size,
        f_material  = :material,
        f_price     = :price,
        f_details   = :details,
        f_details_2   = :details_2
        {$thumbSql},
        f_regdate   = NOW()
      WHERE f_idx = :idx
    ";
    $bind = [
        'cat3' => $cat3,
        'name' => $name,
        'origin' => $origin,
        'size' => $size,
        'material' => $material,
        'price' => $price,
        'details' => $details,
        'details_2' => $details_2,
        'idx' => $idx,
    ];
    if ($thumb) {
        $bind['thumb'] = $thumb;
    }

    $db->query($sql, $bind);
    complete("제품 정보가 수정되었습니다.", "contents_list.php?page={$page}&{$param}");
}

//====================================================================================================
//	선택 삭제
//====================================================================================================
else if ($mode == "delbbs") {

    $array_seluser = explode("|", $seluser);
    $i = 0;
    while ($array_seluser[$i]) {
        $idx = $array_seluser[$i];

        $sql = " Select * From {$this_table} Where f_idx='" . $idx . "' ";
        $bbs_row = $db->row($sql);

        // $sql = " Select * From {$this_table}_files Where bbsidx='" . $idx . "' ";
        // $row = $db->query($sql);

        $file_cnt = 0;
        for ($ii = 0; $ii < count($row); $ii++) {
            @unlink("../../userfiles/contents/" . $code . "/" . $row[$ii]['f_thumbnail']);
            $file_cnt++;
        }

        // $sql = " Delete From {$this_table}_files Where bbsidx='" . $idx . "' ";
        // $db->query($sql);

        $sql = " Delete From {$this_table} Where f_idx='" . $idx . "' ";
        $db->query($sql);


        $i++;
    }

    complete("게시물이 삭제되었습니다.", "contents_list.php?page=$page&$param");
}

//====================================================================================================
//	삭제
//====================================================================================================
else if ($mode == "delete") {

    $sql = " Select * From {$this_table} Where idx='" . $idx . "' ";
    $bbs_row = $db->row($sql);

    // $sql = " Select * From {$this_table}_files Where bbsidx='" . $idx . "' ";
    // $row = $db->query($sql);

    $file_cnt = 0;
    for ($ii = 0; $ii < count($row); $ii++) {
        @unlink("../../userfiles/contents/" . $code . "/" . $row[$ii]['f_thumbnail']);
        $file_cnt++;
    }

    // $sql = " Delete From {$this_table}_files Where bbsidx='" . $idx . "' ";
    // $db->query($sql);

    $sql = " Delete From {$this_table} Where f_idx='" . $idx . "' ";
    $db->query($sql);

    complete("게시물이 삭제되었습니다.", "contents_list.php?page=$page&$param");
}

//====================================================================================================
//	첨부파일 삭제
//====================================================================================================
else if ($mode == "delimg") {

    $sql = " Select * From {$this_table} Where f_idx='" . $idx . "' ";
    $row = $db->row($sql);

    @unlink("../../userfiles/contents/" . $code . "/" . $row['f_thumbnail']);

    $sql = " Delete From {$this_table} Where f_idx='" . $idx . "' ";
    $db->query($sql);

    echo "Y";
}

//====================================================================================================
//	썸네일 삭제
//====================================================================================================
else if ($mode == "delimg_thumb") {

    $sql = " Select * From {$this_table} Where f_idx='" . $idx . "' ";
    $row = $db->row($sql);

    @unlink("../../userfiles/contents/" . $code . "/" . $row['f_thumbnail']);

    //$sql = " Delete From {$this_table} Where idx='".$idx."' ";
    // update
    $sql = " Update {$this_table} Set f_thumbnail='' Where f_idx='" . $idx . "' ";
    $db->query($sql);

    echo "Y";
} 

//====================================================================================================
//	순서 변경
//====================================================================================================
else if ($mode === "prior") {

    $cat1 = isset($_GET['category_1']) ? (int) $_GET['category_1'] : 0;
    $cat2 = isset($_GET['category_2']) ? (int) $_GET['category_2'] : 0;
    $cat3 = isset($_GET['category_3']) ? (int) $_GET['category_3'] : 0;

    $from = "
        FROM df_site_product wp
        LEFT JOIN df_site_category AS c3 ON wp.f_cat_idx     = c3.f_idx
        LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx = c2.f_idx
        LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx = c1.f_idx
    ";

    $where = "WHERE 1=1 ";

    if ($keyword !== "" && $search_option === "f_name") {
        $where .= " AND wp.f_name LIKE '%" . $s_key . "%' ";
    }
    if ($cat1) {
        $where .= " AND c1.f_idx      = '{$cat1}' ";
    }
    if ($cat2) {
        $where .= " AND c2.f_idx      = '{$cat2}' ";
    }
    if ($cat3) {
        $where .= " AND wp.f_cat_idx  = '{$cat3}' ";
    }

    switch ($posi) {
        case "up":
            // — 1단계 위로
            $sql = "SELECT wp.* {$from} {$where}
                     AND wp.prior >= '{$prior}'
                     AND wp.f_idx != '{$idx}'
                     ORDER BY wp.prior ASC
                     LIMIT 1";
            if ($target = $db->row($sql)) {
                $newPrior = $target['prior'];
                $db->query("UPDATE df_site_product 
                            SET prior='{$newPrior}' 
                            WHERE f_idx='{$idx}'");
                $db->query("UPDATE df_site_product 
                            SET prior=prior-1 
                            WHERE prior<='{$newPrior}' 
                              AND f_idx!='{$idx}'");
            }
            break;

        case "upup":
            // — 10단계 위로
            $sql = "SELECT wp.* {$from} {$where}
                     AND wp.prior >= '{$prior}'
                     AND wp.f_idx != '{$idx}'
                     ORDER BY wp.prior ASC
                     LIMIT 10";
            $rows = $db->query($sql);
            if (!empty($rows)) {
                // 가장 마지막(10번째)의 prior
                $last = end($rows);
                $newPrior = $last['prior'];
                $db->query("UPDATE df_site_product 
                            SET prior='{$newPrior}' 
                            WHERE f_idx='{$idx}'");
                $db->query("UPDATE df_site_product 
                            SET prior=prior-1 
                            WHERE prior<='{$newPrior}' 
                              AND f_idx!='{$idx}'");
            }
            break;

        case "down":
            // — 1단계 아래로
            $sql = "SELECT wp.* {$from} {$where}
                     AND wp.prior <= '{$prior}'
                     AND wp.f_idx != '{$idx}'
                     ORDER BY wp.prior DESC
                     LIMIT 1";
            if ($target = $db->row($sql)) {
                $newPrior = $target['prior'];
                $db->query("UPDATE df_site_product 
                            SET prior='{$newPrior}' 
                            WHERE f_idx='{$idx}'");
                $db->query("UPDATE df_site_product 
                            SET prior=prior+1 
                            WHERE prior>='{$newPrior}' 
                              AND f_idx!='{$idx}'");
            }
            break;

        case "downdown":
            // — 10단계 아래로
            $sql = "SELECT wp.* {$from} {$where}
                     AND wp.prior <= '{$prior}'
                     AND wp.f_idx != '{$idx}'
                     ORDER BY wp.prior DESC
                     LIMIT 10";
            $rows = $db->query($sql);
            if (!empty($rows)) {
                $last = end($rows);
                $newPrior = $last['prior'];
                $db->query("UPDATE df_site_product 
                            SET prior='{$newPrior}' 
                            WHERE f_idx='{$idx}'");
                $db->query("UPDATE df_site_product 
                            SET prior=prior+1 
                            WHERE prior>='{$newPrior}' 
                              AND f_idx!='{$idx}'");
            }
            break;
    }

    complete(
        "순서를 변경하였습니다.",
        "../contents/contents_list.php?page={$page}&{$param}"
    );
}
