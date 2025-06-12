<?php
$sql2 = "select title,linkurl,content,posi_x,posi_y,size_x,size_y, close_bg,close_align,close_txt,close_txt_color from df_site_content where idx = '$pidx'";
$popup_info = $db->row($sql2, null, PDO::FETCH_OBJ);

if (!$popup_info->close_bg)
    $popup_info->close_bg = "#cacaca";
if (!$popup_info->close_align)
    $popup_info->close_align = "left";
if (!$popup_info->close_txt)
    $popup_info->close_txt = "오늘 하루 열지 않음";
if (!$popup_info->close_txt_color)
    $popup_info->close_txt_color = "#000000";
?>
<script language="javascript">

    function close_layer<?= $pidx ?>() {
        laypop<?= $pidx ?>.style.display = 'none';
    }

    function popupClose<?= $pidx ?>() {
        setCookie("popupDayClose<?= $pidx ?>", "true", 1);
        laypop<?= $pidx ?>.style.display = 'none';
    }

    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

</script>

<div id="laypop<?= $pidx ?>"
    style="position:absolute; left:<?= $popup_info->posi_x ?>px; top:<?= $popup_info->posi_y ?>px; width:<?= $popup_info->size_x ?>px; height:<?= $popup_info->size_y ?>px; z-index:999; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000">
    <table border="0" cellpadding="0" style="cursor:hand" cellspacing="0">
        <tr>
            <td onclick="location.href = '<?= $popup_info->linkurl ?>';"><?= $popup_info->content ?></td>
        </tr>
    </table>
    <table width="100%" height="25" border="0" align="right" cellpadding="0" cellspacing="0"
        bgcolor="<?= $popup_info->close_bg ?>">
        <tr>
            <td align="<?= $popup_info->close_align ?>" style="padding-left:5px;">
                <span
                    style="color:<?= $popup_info->close_txt_color ?>; vertical-align:middle;"><?= $popup_info->close_txt ?></span>
                <input type="checkbox" onClick="popupClose<?= $pidx ?>();" style="vertical-align:middle;">
            </td>
            <td align="right" style="width:30px; padding-right:5px;">
                <img src="tool/images/x.gif" style="cursor:hand"
                    onClick="javascript:laypop<?= $pidx ?>.style.display='none';" WIDTH="12" HEIGHT="11">
            </td>
        </tr>
    </table>
</div>

<script>
    if (!readCookie('popupDayClose<?= $pidx ?>')) {
        laypop<?= $pidx ?>.style.display = '';
    }
    else {
        laypop<?= $pidx ?>.style.display = 'none';
    }
</script>