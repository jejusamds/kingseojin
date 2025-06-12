<?php
include "../inc/global.inc";


exit; 

// 비밀번호 hash화
$passwd = '1234';
$passwd = password_hash($passwd, PASSWORD_DEFAULT);

$sql = "update df_site_admin set passwd = '$passwd' where id = 'admin'";
$db->query($sql);



