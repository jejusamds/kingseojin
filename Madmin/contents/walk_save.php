<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";

$this_table = 'df_site_board_walk';

$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;

foreach ($_POST as $key => $val) {
    if (is_string($val)) {
        ${$key} = addslashes(${$key});
    }
}

if (!$notice) $notice = "N";

function file_upload($files, $code, $type)
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

//====================================================================================================
//	작성
//====================================================================================================
if ($mode == "insert") {

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $image = file_upload($_FILES['image'], $code, "walk");
    }

    $wip   = $_SERVER['REMOTE_ADDR'];
    $wdate = date('Y-m-d H:i:s');

    $sqlMain  = "INSERT INTO {$this_table} SET
                    area_1         = :area_1,
                    area_2         = :area_2,
                    course_name    = :course_name,
                    `explain`      = :explain,
                    explain_detail = :explain_detail,
                    schedule       = :schedule,
                    tour_course    = :tour_course,
                    commentary     = :commentary";
    $sqlMain .= "	, background_color= '" . $background_color . "' ";                    
    if ($image !== "") {
        $sqlMain .= ", image = :image";
    }
    $sqlMain .= ", wdate = :wdate, wip = :wip";

    $db->bind('area_1',        $area_1);
    $db->bind('area_2',        $area_2);
    $db->bind('course_name',   $course_name);
    $db->bind('explain',       $explain);
    $db->bind('explain_detail', $explain_detail);
    $db->bind('schedule',      $schedule);
    $db->bind('tour_course',   $tour_course);
    $db->bind('commentary',    $commentary);
    if ($image !== "") {
        $db->bind('image', $image);
    }
    $db->bind('wdate', $wdate);
    $db->bind('wip',   $wip);

    $db->query($sqlMain);

    $bbsidx = $db->lastInsertId();

    // 개별 일정 INSERT
    if (!empty($_POST['occur_date']) && is_array($_POST['occur_date'])) {
        $sqlSched = "
            INSERT INTO df_site_board_walk_schedule (
                walk_id, occur_date, start_time, end_time
            ) VALUES (
                :walk_id, :occur_date, :start_time, :end_time
            )";
        foreach ($_POST['occur_date'] as $i => $date) {
            $start = $_POST['start_time'][$i] ?? '';
            $end   = $_POST['end_time'][$i]   ?? '';
            if (!$date || !$start || !$end) continue;

            $db->bind('walk_id',     $bbsidx);
            $db->bind('occur_date',  $date);
            $db->bind('start_time',  $start);
            $db->bind('end_time',    $end);
            $db->query($sqlSched);
        }
    }



    include "./contents_upfile.php";
    complete("게시물이 작성되었습니다.", "walk_list.php?page={$page}&{$param}");
}

//====================================================================================================
//	수정
//====================================================================================================
else if ($mode == "update") {

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $image = file_upload($_FILES['image'], $code, "walk");
    }

    if ($image !== "") {
        $sql = "SELECT image FROM {$this_table} WHERE idx = '" . $idx . "'";
        $row = $db->row($sql);
        @unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/contents/{$code}/" . $row['image']);
    }

    $sql  = "UPDATE {$this_table} SET
                area_1         = :area_1,
                area_2         = :area_2,
                course_name    = :course_name,
                `explain`      = :explain,
                explain_detail = :explain_detail,
                schedule       = :schedule,
                tour_course    = :tour_course,
                commentary     = :commentary";

    $sql .= "	, background_color= '" . $background_color . "' ";                    
    if ($image !== "") {
        $sql .= ", image = :image";
    }
    $sql .= " WHERE idx = :idx";

    $db->bind('area_1',         $area_1);
    $db->bind('area_2',         $area_2);
    $db->bind('course_name',    $course_name);
    $db->bind('explain',        $explain);
    $db->bind('explain_detail', $explain_detail);
    $db->bind('schedule',       $schedule);
    $db->bind('tour_course',    $tour_course);
    $db->bind('commentary',     $commentary);
    if ($image !== "") {
        $db->bind('image', $image);
    }
    $db->bind('idx', $idx);

    $db->query($sql);

    // 일정 테이블 업데이트
    // 기존 행 삭제
    $db->query("DELETE FROM df_site_board_walk_schedule WHERE walk_id = '{$idx}'");

    // 새로 넘어온 배열로 재삽입
    if (! empty($_POST['occur_date']) && is_array($_POST['occur_date'])) {
        $sqlSched = "
            INSERT INTO df_site_board_walk_schedule (
                walk_id, occur_date, start_time, end_time
            ) VALUES (
                :walk_id, :occur_date, :start_time, :end_time
            )";
        foreach ($_POST['occur_date'] as $i => $date) {
            $start = $_POST['start_time'][$i] ?? '';
            $end   = $_POST['end_time'][$i]   ?? '';
            if (! $date || ! $start || ! $end) continue;

            $db->bind('walk_id',     $idx);
            $db->bind('occur_date',  $date);
            $db->bind('start_time',  $start);
            $db->bind('end_time',    $end);
            $db->query($sqlSched);
        }
    }

    $bbsidx = $idx;
    include "./contents_upfile.php";
    complete("게시물이 수정되었습니다.", "walk_list.php?page={$page}&{$param}");
}


//====================================================================================================
//	선택 삭제
//====================================================================================================
else if ($mode == "delbbs") {

    $array_seluser = explode("|", $seluser);
    $i = 0;
    while ($array_seluser[$i]) {
        $idx = $array_seluser[$i];

        $sql = " Select * From {$this_table} Where idx='" . $idx . "' ";
        $bbs_row = $db->row($sql);

        $sql = " Select * From {$this_table}_files Where bbsidx='" . $idx . "' ";
        $row = $db->query($sql);

        $file_cnt = 0;
        for ($ii = 0; $ii < count($row); $ii++) {
            @unlink("../../userfiles/contents/" . $code . "/" . $row[$ii]['upfile']);
            $file_cnt++;
        }

        $sql = " Delete From {$this_table}_files Where bbsidx='" . $idx . "' ";
        $db->query($sql);

        $sql = " Delete From {$this_table}_schedule Where walk_id='" . $idx . "' ";
        $db->query($sql);

        $sql = " Delete From {$this_table} Where idx='" . $idx . "' ";
        $db->query($sql);


        $i++;
    }

    complete("게시물이 삭제되었습니다.", "walk_list.php?page=$page&$param");
}

//====================================================================================================
//	삭제
//====================================================================================================
else if ($mode == "delete") {

    $sql = " Select * From {$this_table} Where idx='" . $idx . "' ";
    $bbs_row = $db->row($sql);

    $sql = " Select * From {$this_table}_files Where bbsidx='" . $idx . "' ";
    $row = $db->query($sql);

    $file_cnt = 0;
    for ($ii = 0; $ii < count($row); $ii++) {
        @unlink("../../userfiles/contents/" . $code . "/" . $row[$ii]['upfile']);
        $file_cnt++;
    }

    $sql = " Delete From {$this_table}_files Where bbsidx='" . $idx . "' ";
    $db->query($sql);

    $sql = " Delete From {$this_table}_schedule Where walk_id='" . $idx . "' ";
    $db->query($sql);

    $sql = " Delete From {$this_table} Where idx='" . $idx . "' ";
    $db->query($sql);

    complete("게시물이 삭제되었습니다.", "walk_list.php?page=$page&$param");
}

//====================================================================================================
//	첨부파일 삭제
//====================================================================================================
else if ($mode == "delimg") {

    $sql = " Select * From {$this_table}_files Where idx='" . $idx . "' ";
    $row = $db->row($sql);

    @unlink("../../userfiles/contents/" . $code . "/" . $row['upfile']);

    $sql = " Delete From {$this_table}_files Where idx='" . $idx . "' ";
    $db->query($sql);

    echo "Y";
}

//====================================================================================================
//	썸네일 삭제
//====================================================================================================
else if ($mode == "delimg_thumb") {

    $sql = " Select * From {$this_table} Where idx='" . $idx . "' ";
    $row = $db->row($sql);

    @unlink("../../userfiles/contents/" . $code . "/" . $row['upfile']);

    //$sql = " Delete From {$this_table} Where idx='".$idx."' ";
    // update
    $sql = " Update {$this_table} Set upfile='' Where idx='" . $idx . "' ";
    $db->query($sql);

    echo "Y";
}
