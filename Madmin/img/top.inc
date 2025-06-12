<? 
$gb = $_SERVER['REQUEST_URI'];
$gb = ereg_replace("/Madmin/","",$gb); 
$gb = ereg_replace("set/","",$gb); 
$gb = ereg_replace("page/","",$gb); 
$gb = ereg_replace("message/","",$gb); 
$gb = ereg_replace("send/","",$gb); 
$gb = ereg_replace("special/","",$gb); 
$gb = ereg_replace("reserve/","",$gb); 
$gb = ereg_replace("member/","",$gb); 
$gb = ereg_replace("bbs/","",$gb); 
$gb = substr("$gb",0,strpos($gb,".php"));
?>
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<body onLoad="MM_preloadImages('../img/m_09_over.gif','../img/m_10_over.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="../img/top_bg.gif"><table width="990" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><img src="../img/top_pc_1.gif" width="219" height="27" /></td>
        <td width="771" align="right"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="http://<?=$HTTP_HOST?>" target="_blank"><img src="../img/m_s_site_main.gif" border="0"></a></td>
            <td><a href="/Madmin"><img src="../img/m_s_admin_main.gif" border="0"></a></td>
            <td><a href="/Madmin/set/set_info.php"><img src="../img/m_s_edit.gif" border="0"></a></td>
            <td><a href="/Madmin/admin_logout.php"><img src="../img/m_s_logout.gif" border="0"></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="19"><img src="../img/top_pc_2.gif" width="19" height="61" /></td>
        <td width="200" align="center" background="../img/top_pc_3_bg.gif"><a href="/Madmin"><img src="../img/logo.gif" width="123" height="36" border="0" /></a></td>
        <td><table border="0" cellpadding="0" cellspacing="0" background="../img/top_m_bg.gif">
          <tr>
            <td>
		<!---------------- 메뉴 시작------------------------>
		<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="6"></td>
		<td><a href="/Madmin/page/page_popup.php" onMouseOver="m01.src='../img/m_01_over.gif'" onMouseOut="m01.src='../img/m_01<? if($gb=="page_popup" || $gb=="popup_input" || $gb=="page_join" || $gb=="page_privacy") { ?>_over<? } ?>.gif'"><img src="../img/m_01<? if($gb=="page_popup" || $gb=="popup_input" || $gb=="page_join" || $gb=="page_privacy") { ?>_over<? } ?>.gif" name="m01" border="0"></a></td>
                <td width="20"></td>
                <td><a href="../message/message.php" onMouseOver="m02.src='../img/m_02_over.gif'" onMouseOut="m02.src='../img/m_02<? if($gb=="message" || $gb=="message_input") { ?>_over<? } ?>.gif'"><img src="../img/m_02<? if($gb=="message" || $gb=="message_input") { ?>_over<? } ?>.gif" name="m02" border="0"></a></td>
				<td width="20"></td>
                <td><a href="../send/send_email.php" onMouseOver="m03.src='../img/m_03_over.gif'" onMouseOut="m03.src='../img/m_03<? if($gb=="send_email" || $gb=="send_sms") { ?>_over<? } ?>.gif'"><img src="../img/m_03<? if($gb=="send_email" || $gb=="send_sms") { ?>_over<? } ?>.gif" name="m03" border="0"></a></td>
				<td width="20"></td>
                <!--<td><a href="../contents/class_list.php">컨텐츠 관리</a></td>
				<td width="24"></td>-->
                <td><a href="../contents/class_list.php" onMouseOver="m05.src='../img/m_05_over.gif'" onMouseOut="m05.src='../img/m_05<? if($gb=="reserve_list" || $gb=="reserve_view") { ?>_over<? } ?>.gif'"><img src="../img/m_05<? if($gb=="reserve_list" || $gb=="reserve_view") { ?>_over<? } ?>.gif" name="m05" border="0"></a></td>
				<td width="20"></td>
				<td width="20"><a href="../member/product_list.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image16','','../img/m_09_over.gif',1)"><img src="../img/m_09.gif" name="Image16" width="68" height="56" border="0" id="Image16" /></a></td>
				<td width="20"></td>
				<td width="20"><a href="../member/purchasing_list.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image17','','../img/m_10_over.gif',1)"><img src="../img/m_10.gif" name="Image17" width="68" height="56" border="0" id="Image17" /></a></td>
				<td width="20"></td>
                <td><a href="../member/member_list.php" onMouseOver="m06.src='../img/m_06_over.gif'" onMouseOut="m06.src='../img/m_06<? if($gb=="member_list" || $gb=="member_input" || $gb=="member_level" || $gb=="level_input" || $gb=="member_out" || $gb=="analy_member") { ?>_over<? } ?>.gif'"><img src="../img/m_06<? if($gb=="member_list" || $gb=="member_input" || $gb=="member_level" || $gb=="level_input" || $gb=="member_out" || $gb=="analy_member") { ?>_over<? } ?>.gif" name="m06" border="0"></a></td>
				<td width="20"></td>
                <td><a href="../bbs/bbs_pro_list.php" onMouseOver="m07.src='../img/m_07_over.gif'" onMouseOut="m07.src='../img/m_07<? if($gb=="bbs_pro_list" || $gb=="bbs_pro_input" || $gb=="bbs_list" || $gb=="bbs_input" || $gb=="bbs_view") { ?>_over<? } ?>.gif'"><img src="../img/m_07<? if($gb=="bbs_pro_list" || $gb=="bbs_pro_input" || $gb=="bbs_list" || $gb=="bbs_input" || $gb=="bbs_view") { ?>_over<? } ?>.gif" name="m07" border="0"></a></td>
				<td width="20"></td>
                <td><a href="../marketing/view.php?id=index" onMouseOver="m08.src='../img/m_08_over.gif'" onMouseOut="m08.src='../img/m_08.gif'"><img src="../img/m_08.gif" name="m08" border="0"></a></td>
                <td><img src="../img/m_end.gif" /></td>
              </tr>
            </table>
			<!---------------- 메뉴 끝------------------------>
			</td>
          </tr>
          <tr>
            <td height="5"></td>
          </tr>
        </table></td>
      </tr>
      
    </table></td>
  </tr>
</table>
