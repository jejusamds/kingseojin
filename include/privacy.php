<?php

$sql = "SELECT * FROM df_site_page";
$privacy_list = $db->query($sql);
$privacy_arr = [];

foreach ($privacy_list as $privacy_row) {
    $privacy_arr[$privacy_row['type']] = $privacy_row['content'];
}
