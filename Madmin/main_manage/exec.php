<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$param = "code=" . $code . "&big_mode=" . $big_mode . "&mode=" . $mode . "&s_show=" . $s_show . "&category=" . $category;

if ($mode == 'insert' || $mode == 'update') {
    $_SESSION['post_data'] = $_POST;
}

foreach ($_POST as $key => $val) {
    // 문자열 변수들 한번에 addslashes
    if (is_string($val)) {
        ${$key} = addslashes($val);
        if (preg_replace('/\s+/', '', $val) == '') {
            ${$key} = '';
        }
    }
}


//====================================================================================================
// 판매 채널 관리
//====================================================================================================
if ($big_mode == 'main_slide') {

    $this_table = "df_site_main_slide";

    //====================================================================================================
    //	Insert
    //====================================================================================================
    if ($mode == "insert") {

        // code 가 best 일때는 showset = 'Y' 인 데이터가 4개를 초과할 수 없음
        if ($code == 'best') {
            $sql = "SELECT COUNT(*) as cnt FROM {$this_table} WHERE code = 'best' AND showset = 'Y'";
            $row = $db->row($sql);
            if ($row['cnt'] >= 4) {
                error("베스트 제품은 최대 4개까지만 등록할 수 있습니다.");
            }
        }

        $up_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $big_mode;

        if (!is_dir($up_dir)) {
            echo exec("mkdir $up_dir");
            exec("chmod 707 $up_dir");
        }

        if (isset($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['tmp_name']) {
            $file_name = $_FILES['upfile']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
            }
            $upfile_tmp = "pc_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp);
            $upfile_sql = "";
            if (!move_uploaded_file($_FILES["upfile"]["tmp_name"], $save_dir)) {
                error("썸네일 업로드 중 오류가 발생하였습니다. pc", $big_mode . "_input.php?page=$page&$param");
            }
        }

        if (isset($_FILES['upfile_m']['tmp_name']) && $_FILES['upfile_m']['tmp_name']) {
            $file_name = $_FILES['upfile_m']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
            }
            $upfile_tmp_m = "m_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp_m);
            $upfile_sql = "";
            if (!move_uploaded_file($_FILES["upfile_m"]["tmp_name"], $save_dir)) {
                error("썸네일 업로드 중 오류가 발생하였습니다. m", $big_mode . "_input.php?page=$page&$param");
            }
        }

        $real_path = "/userfiles/" . $big_mode . "/" . $upfile_tmp;
        $real_path_m = "/userfiles/" . $big_mode . "/" . $upfile_tmp_m;

        $sql = "";
        $sql .= "   Insert into {$this_table} ";
        $sql .= "   Set       title         = '" . $title . "' ";
        $sql .= "	        , title_sub     = '" . $title_sub . "' ";
        $sql .= "	        , title_mobile     = '" . $title_mobile . "' ";
        $sql .= "	        , title_sub_mobile     = '" . $title_sub_mobile . "' ";
        $sql .= "	        , link          = '" . $link . "' ";
        $sql .= "	        , showset       = '" . $showset . "' ";
        $sql .= "	        , prior         = '" . date("ymdHis") . "' ";
        $sql .= "           , upfile        = '" . $real_path . "' ";
        $sql .= "           , upfile_m      = '" . $real_path_m . "' ";
        $sql .= "           , wdate         = NOW() ";
        $sql .= "	        , code          = '" . $code . "' ";
        $sql .= " ; ";

        $result = $db->query($sql);
        unset($_SESSION['post_data']);
        complete("저장 되었습니다.", "{$big_mode}_list.php?page=$page&$param");
    }

    //====================================================================================================
    //	Update
    //====================================================================================================
    if ($mode == 'update') {

        $up_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $big_mode;

        if (!is_dir($up_dir)) {
            echo exec("mkdir $up_dir");
            exec("chmod 707 $up_dir");
        }

        $upfile_sql = "";
        if (isset($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['tmp_name']) {
            $file_name = $_FILES['upfile']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
                exit();
            }
            $upfile_tmp = "pc_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp);
            if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $save_dir)) {
                $real_path = "/userfiles/" . $big_mode . "/" . $upfile_tmp;
                $upfile_sql .= ", upfile = '" . $real_path . "' ";
            } else {
                error("썸네일 업로드 중 오류가 발생하였습니다.");
            }
        }

        $upfile_sql_m = "";
        if (isset($_FILES['upfile_m']['tmp_name']) && $_FILES['upfile_m']['tmp_name']) {
            $file_name = $_FILES['upfile_m']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
                exit();
            }
            $upfile_tmp_m = "m_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp_m);
            if (move_uploaded_file($_FILES["upfile_m"]["tmp_name"], $save_dir)) {
                $real_path_m = "/userfiles/" . $big_mode . "/" . $upfile_tmp_m;
                $upfile_sql_m .= ", upfile_m = '" . $real_path_m . "' ";
            } else {
                error("썸네일 업로드 중 오류가 발생하였습니다.");
            }
        }

        $sql = "";
        $sql .= "	Update	{$this_table} ";
        $sql .= "	Set		title = '" . $title . "' ";
        $sql .= "		, title_sub = '" . $title_sub . "' ";
        $sql .= "	    , title_mobile     = '" . $title_mobile . "' ";
        $sql .= "	    , title_sub_mobile     = '" . $title_sub_mobile . "' ";
        $sql .= "		, link = '" . $link . "' ";
        $sql .= "		, showset = '" . $showset . "' ";
        $sql .= $upfile_sql;
        $sql .= $upfile_sql_m;
        $sql .= "	Where	idx = '" . $idx . "' ";
        $db->query($sql);
        unset($_SESSION['post_data']);
        complete("저장 되었습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }

    //====================================================================================================
    //	prior
    //====================================================================================================
    if ($mode == 'prior') {

        $addSql = "";
        $sql = "";
        $sql .= "	Select	wp.* ";
        $sql .= "	From	{$this_table} wp ";
        $sql .= "	Where	1 = 1 ";

        // 1단계 위로
        if ($posi == "up") {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 1 ";

            if ($row = $db->row($sql)) {
                $prior = $row['prior'];

                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 10단계 위로
        else if ($posi == "upup") {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);

            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }

            if ($total > 0) {
                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 1단계 아래로
        else if ($posi == "down") {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 1 ";

            if ($row = $db->row($sql)) {
                $prior = $row['prior'];

                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 10단계 아래로
        else if ($posi == "downdown") {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);

            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }

            if ($total > 0) {
                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        complete("순서를 변경하였습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }

    //====================================================================================================
    //	Delete
    //====================================================================================================
    if ($mode == 'delete') {

        $arr = explode("|", $selidx);
        for ($i = 0; $i < count($arr); $i++) {
            $idx = $arr[$i];

            if ($idx != "") {
                $sql = "";
                $sql .= "	Delete ";
                $sql .= "	From	{$this_table} ";
                $sql .= "	Where	idx = '" . $idx . "' ";
                $db->query($sql);
            }
        }

        complete("삭제 되었습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }

    //====================================================================================================
    //	delimg 썸네일 삭제
    //====================================================================================================
    if ($mode == 'delimg') {

        $sql = " Select upfile, upfile_m From {$this_table} Where idx='" . $idx . "' ";
        $row = $db->row($sql);

        $column = $pm == 'p' ? 'upfile' : 'upfile_m';
        @unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$big_mode}/" . $row[$column]);
        //@unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$big_mode}/" . $row['upfile']);

        $sql = "    Update {$this_table} set {$column} = '' where idx = '" . $idx . "' ";
        $db->query($sql);

        echo "Y";
    }
}

if ($big_mode == 'sub_slide') {

    $this_table = "df_site_sub_slide";

    //====================================================================================================
    //	Insert
    //====================================================================================================
    if ($mode == "insert") {

        $up_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $big_mode;

        if (!is_dir($up_dir)) {
            echo exec("mkdir $up_dir");
            exec("chmod 707 $up_dir");
        }

        if (isset($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['tmp_name']) {
            $file_name = $_FILES['upfile']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
            }
            $upfile_tmp = "pc_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp);
            $upfile_sql = "";
            if (!move_uploaded_file($_FILES["upfile"]["tmp_name"], $save_dir)) {
                error("썸네일 업로드 중 오류가 발생하였습니다. pc", $big_mode . "_input.php?page=$page&$param");
            }
        }

        if (isset($_FILES['upfile_m']['tmp_name']) && $_FILES['upfile_m']['tmp_name']) {
            $file_name = $_FILES['upfile_m']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
            }
            $upfile_tmp_m = "m_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp_m);
            $upfile_sql = "";
            if (!move_uploaded_file($_FILES["upfile_m"]["tmp_name"], $save_dir)) {
                error("썸네일 업로드 중 오류가 발생하였습니다. m", $big_mode . "_input.php?page=$page&$param");
            }
        }

        $real_path = "/userfiles/" . $big_mode . "/" . $upfile_tmp;
        $real_path_m = "/userfiles/" . $big_mode . "/" . $upfile_tmp_m;

        $sql = "";
        $sql .= "   Insert into {$this_table} ";
        $sql .= "   Set       category         = '" . $category . "' ";
        $sql .= "	        , title     = '" . $title . "' ";
        $sql .= "	        , title_sub     = '" . $title_sub . "' ";
        $sql .= "	        , title_mobile     = '" . $title_mobile . "' ";
        $sql .= "	        , title_sub_mobile     = '" . $title_sub_mobile . "' ";
        $sql .= "	        , link          = '" . $link . "' ";
        $sql .= "	        , showset       = '" . $showset . "' ";
        $sql .= "	        , prior         = '" . date("ymdHis") . "' ";
        $sql .= "           , upfile        = '" . $real_path . "' ";
        $sql .= "           , upfile_m      = '" . $real_path_m . "' ";
        $sql .= "           , wdate         = NOW() ";
        $sql .= " ; ";

        $result = $db->query($sql);
        unset($_SESSION['post_data']);
        complete("저장 되었습니다.", "{$big_mode}_list.php?page=$page&$param");
    }

    //====================================================================================================
    //	Update
    //====================================================================================================
    if ($mode == 'update') {

        $up_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $big_mode;

        if (!is_dir($up_dir)) {
            echo exec("mkdir $up_dir");
            exec("chmod 707 $up_dir");
        }

        $upfile_sql = "";
        if (isset($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['tmp_name']) {
            $file_name = $_FILES['upfile']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
                exit();
            }
            $upfile_tmp = "pc_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp);
            if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $save_dir)) {
                $real_path = "/userfiles/" . $big_mode . "/" . $upfile_tmp;
                $upfile_sql .= ", upfile = '" . $real_path . "' ";
            } else {
                error("썸네일 업로드 중 오류가 발생하였습니다.");
            }
        }

        $upfile_sql_m = "";
        if (isset($_FILES['upfile_m']['tmp_name']) && $_FILES['upfile_m']['tmp_name']) {
            $file_name = $_FILES['upfile_m']['name'];
            $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
            if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext) {
                error("이미지 파일만 업로드 가능합니다.");
                exit();
            }
            $upfile_tmp_m = "m_" . time() . "." . $ext;
            $save_dir = sprintf('%s/%s', $up_dir, $upfile_tmp_m);
            if (move_uploaded_file($_FILES["upfile_m"]["tmp_name"], $save_dir)) {
                $real_path_m = "/userfiles/" . $big_mode . "/" . $upfile_tmp_m;
                $upfile_sql_m .= ", upfile_m = '" . $real_path_m . "' ";
            } else {
                error("썸네일 업로드 중 오류가 발생하였습니다.");
            }
        }

        $sql = "";
        $sql .= "	Update	{$this_table} ";
        $sql .= "	Set		title = '" . $title . "' ";
        $sql .= "		, title_sub = '" . $title_sub . "' ";
        $sql .= "		, title_mobile = '" . $title_mobile . "' ";
        $sql .= "		, title_sub_mobile = '" . $title_sub_mobile . "' ";
        $sql .= "		, link = '" . $link . "' ";
        $sql .= "		, showset = '" . $showset . "' ";
        $sql .= $upfile_sql;
        $sql .= $upfile_sql_m;
        $sql .= "	Where	idx = '" . $idx . "' ";
        $db->query($sql);
        unset($_SESSION['post_data']);
        complete("저장 되었습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }

    //====================================================================================================
    //	Delete
    //====================================================================================================
    if ($mode == 'delete') {

        $arr = explode("|", $selidx);
        for ($i = 0; $i < count($arr); $i++) {
            $idx = $arr[$i];

            if ($idx != "") {
                $sql = "";
                $sql .= "	Delete ";
                $sql .= "	From	{$this_table} ";
                $sql .= "	Where	idx = '" . $idx . "' ";
                $db->query($sql);
            }
        }

        complete("삭제 되었습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }

    //====================================================================================================
    //	delimg 썸네일 삭제
    //====================================================================================================
    if ($mode == 'delimg') {

        $sql = " Select upfile, upfile_m From {$this_table} Where idx='" . $idx . "' ";
        $row = $db->row($sql);

        $column = $pm == 'p' ? 'upfile' : 'upfile_m';
        @unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$big_mode}/" . $row[$column]);
        //@unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$big_mode}/" . $row['upfile']);

        $sql = "    Update {$this_table} set {$column} = '' where idx = '" . $idx . "' ";
        $db->query($sql);

        echo "Y";
    }

    //====================================================================================================
    //	prior
    //====================================================================================================
    if ($mode == 'prior') {

        $addSql = " category = '" . $category . "' ";
        $sql = "";
        $sql .= "	Select	wp.* ";
        $sql .= "	From	{$this_table} wp ";
        $sql .= "	Where	1 = 1 ";
        $sql .= "   And		category = '" . $category . "' ";

        // 1단계 위로
        if ($posi == "up") {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 1 ";

            if ($row = $db->row($sql)) {
                $prior = $row['prior'];

                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 10단계 위로
        else if ($posi == "upup") {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);

            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }

            if ($total > 0) {
                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 1단계 아래로
        else if ($posi == "down") {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 1 ";

            if ($row = $db->row($sql)) {
                $prior = $row['prior'];

                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        // 10단계 아래로
        else if ($posi == "downdown") {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);

            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }

            if ($total > 0) {
                $sql = " Update {$this_table} Set prior='" . $prior . "' Where idx='" . $idx . "' ";
                $db->query($sql);

                $sql = " Update {$this_table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' " . $addSql;
                $db->query($sql);
            }
        }

        complete("순서를 변경하였습니다.", "{$big_mode}_list.php?page=" . $page . "&" . $param);
    }
}