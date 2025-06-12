<script language="javascript">

    function readCookie(cookiename) {
        var Found = false;

        cookiedata = document.cookie;
        if (cookiedata.indexOf(cookiename) >= 0) {
            Found = true;
        }

        return Found;
    }

</script>

<?php
$today = date('Y-m-d');
$sql = " Select * From df_site_content Where type='popup' And isuse='Y' And sdate<='" . $today . "' And edate>='" . $today . "' Order by idx Desc ";
$popup_in = $db->query($sql);
for ($i = 0; $i < count($popup_in); $i++) {
    if ($popup_in[$i]['poptype'] == "L") {
        $pidx = $popup_in[$i]['idx'];
        include $_SERVER['DOCUMENT_ROOT'] . "/tool/layerpop.php";
    } else {
        $popup_in[$i]['size_y'] = $popup_in[$i]['size_y'] + 27;
        echo "<script>";
        echo "if(!readCookie('popupDayClose" . $popup_in[$i]['idx'] . "')){\n";
        echo "window.open('/tool/popup.php?idx=" . $popup_in[$i]['idx'] . "','','height=" . $popup_in[$i]['size_y'] . ", width=" . $popup_in[$i]['size_x'] . ", menubar=no, scrollbars=" . $popup_in[$i]['scroll'] . ", resizable=no, toolbar=no, status=no, top=" . $popup_in[$i]['posi_y'] . ", left=" . $popup_in[$i]['posi_x'] . "');\n";
        echo "}\n\n";
        echo "</script>";
    }
}

?>