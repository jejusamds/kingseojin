<?php

include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

require_once $_SERVER['DOCUMENT_ROOT'].'/inc/Mobile_Detect.php';
$detect = new Mobile_Detect;

include $_SERVER['DOCUMENT_ROOT']."/inc/_df_counter.php";

$csrf_token = generate_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;

function generate_csrf_token()
{
    return bin2hex(random_bytes(32));
}

// 헤더에서 사용하는 메뉴 배열
include 'get_menu.php';
include 'privacy.php';

