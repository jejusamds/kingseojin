<?php
// /controller/file_process.php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';  // DB 연결 등
// 1) 파라미터 검증
$bbsidx  = isset($_GET['bbs'])  ? (int)$_GET['bbs']       : 0;
$upfile  = isset($_GET['file']) ? basename($_GET['file']) : '';

if (!$bbsidx || !$upfile) {
    header("HTTP/1.1 400 Bad Request");
    exit('잘못된 요청입니다.');
}

// 2) DB에서 원본 파일명 조회
$sql = "
    SELECT upfile_name
    FROM df_site_bbs_files
    WHERE bbsidx = :bbsidx
      AND upfile  = :upfile
    LIMIT 1
";
$db->bind('bbsidx', $bbsidx);
$db->bind('upfile', $upfile);
$row = $db->row($sql);

if (!$row) {
    header("HTTP/1.1 404 Not Found");
    exit('파일 정보가 존재하지 않습니다.');
}

// 3) 실제 파일 경로
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/' . $getDir . '/';
$filePath  = $uploadDir . $upfile;
//echo $filePath;exit; 
if (!is_file($filePath)) {
    header("HTTP/1.1 404 Not Found");
    exit('파일이 존재하지 않습니다.');
}

// 4) 다운로드 헤더 전송
$originalName = $row['upfile_name'];
// 한글 파일명 대응
$encodedName  = rawurlencode($originalName);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename*=UTF-8''{$encodedName}");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// 5) 파일 출력
readfile($filePath);
exit;
