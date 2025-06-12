<? include "../inc/global.inc"; ?>
<?
$sql = "select title,linkurl,content, close_bg,close_align,close_txt,close_txt_color from ez_content where idx = '$idx'";
$result = mysql_query($sql);
$popup_info = mysql_fetch_object($result);

if(!$popup_info->close_bg) $popup_info->close_bg = "#cacaca";
if(!$popup_info->close_align) $popup_info->close_align = "left";
if(!$popup_info->close_txt) $popup_info->close_txt = "오늘 하루 열지 않음";
if(!$popup_info->close_txt_color) $popup_info->close_txt_color = "#000000";

if(!empty($popup_info->linkurl)) {
	$urlstr = "onclick=\"javascript:opener.location = '$popup_info->linkurl';window.close();\" style=\"cursor:hand;\"";
}
?>
<html>
<head>
<title><?=$popup_info->title?></title>
<link rel="stylesheet" type="text/css" href="/library/css/hodo.css">
<script language="javascript">
<!--
  function popupClose(){
    setCookie("popupDayClose<?=$idx?>", "true", 1);
    self.close();
  }

  function setCookie( name, value, expiredays ) 
  { 
    var todayDate = new Date(); 
    todayDate.setDate( todayDate.getDate() + expiredays ); 
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
  } 
  //추석 관련 팝업
  function chuseokClose(){
    opener.location.href = 'http://www.hodo1934.com/order01.html';
    self.close();
  }
  //블로그이벤트관련
  function bloggoClose(){
    opener.location.href = 'http://www.hodo1934.com/bbs/bbs_list.php?code=after';
    self.close();
  }
   //가맹점이벤트관련
  function storegoClose(){
    opener.location.href = 'http://hodo1934.com/store/storymap.php';
    self.close();
  } 
  
   //가맹점이벤트관련
  function goClose111006_1(){
    opener.location.href = 'http://hodo1934.com/contact.html';
    self.close();
  } 
  
  function goClose111006_2(){
    opener.location.href = 'http://hodo1934.com/store/storymap.php';
    self.close();
  } 
  function goClose_kek(){
    opener.location.href = 'http://hodo1934.com/fr_2.html';
    self.close();
  } 
//-->
</script>
</head>
<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0">
<tr><td><?=$popup_info->content?></td></tr>
</table>
<table width="100%" height="25" border="0" align="right" cellpadding="0" cellspacing="0" bgcolor="<?=$popup_info->close_bg?>">
  <tr>
    <td align="<?=$popup_info->close_align?>" style="padding-left:5px;">
		<span style="color:<?=$popup_info->close_txt_color?>; font-size:12px; vertical-align:middle;"><?=$popup_info->close_txt?></span>
		<input type="checkbox" onClick="popupClose<?=$pidx?>();" style="vertical-align:middle;">
	</td>
    <td align="right" style="width:30px; padding-right:5px;">
		<img src="images/x.gif" style="cursor:hand" onClick="javascript:laypop<?=$pidx?>.style.display='none';" WIDTH="12" HEIGHT="11">
	</td>
  </tr>
</table>
</body>
</html>