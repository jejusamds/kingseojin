<?
include "../../inc/global.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title><?=$site_info->admin_title?></title>
<link href="../inc/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script language="JavaScript" type="text/javascript">
<!--
function delTradecom(idx){
   if(confirm('해당거래처를 삭제하시겠습니까?')){
      document.location = "set_save.php?mode=home_trade&sub_mode=delete&idx=" + idx;
   }
}
//-->
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <!-- 상단시작 -->
  <tr>
	<td><? include "../inc/top.inc";?></td>
  </tr>
  <!-- 상단끝 -->
  <tr>
	<td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <!-- 좌측시작 -->
		  <td width="220" valign="top"><? include "../inc/left_set.inc";?></td>
		  <!-- 좌측끝 -->
		  
		  <!-- 내용시작 -->
		  <td valign="top">
			<table width="770" border="0" cellspacing="0" cellpadding="13">
			  <tr>
				<td align="center">
				 <table width="730" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td align="left" background="../img/tit_bg.gif"><table border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> 거래처관리</td>
							</tr>
							<tr>
							  <td bgcolor="534741" height="1"></td>
							</tr>
						</table></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td height="20">&nbsp;</td>
				  </tr>
				  <tr>
					<td>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td width="12" height="25"></td>
							<td colspan="4" align="left"><font color="#000000"><strong>+ 거래처목록</strong></font></td>
							<td colspan="3" align="right"><a href="home_trade_input.php?sub_mode=insert"><img src="../img/bt_trade_reg.gif" border="0" /></a></td>
							<td width="12"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left.gif" width="12" height="25" /></td>
							<td width="90" align="center" background="../img/list_bar_bg.gif" class="s04">구분</td>
							<td width="135" align="center" background="../img/list_bar_bg.gif" class="s04">거래처명</td>
							<td width="110" align="center" background="../img/list_bar_bg.gif" class="s04">담당자</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif" class="s04">휴대폰</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif"class="s04">전화번호</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif" class="s04">팩스</td>
							<td width="50" align="center" background="../img/list_bar_bg.gif"class="s04">기능</td>
							<td><img src="../img/list_bar_right.gif" width="12" height="25" /></td>
						  </tr>
						  <?
						   function custom_type($type){
							   if($type == "BUY") return "매입처";
							   else if($type == "SAL") return "매출처";
							   else if($type == "DEL") return "배송업체";
							   else if($type == "OTH") return "기타";
							}

						  $sql = "select * from wiz_tradecom order by com_type asc, idx desc";
						  $result = mysql_query($sql) or error(mysql_error());
						  $total = mysql_num_rows($result);

						  $rows = 12;
						  $lists = 5;
							$page_count = ceil($total/$rows);
							if($page < 1 || $page > $page_count) $page = 1;
							$start = ($page-1)*$rows;
							$no = $total-$start;
							if($start>1) mysql_data_seek($result,$start);
							
						  while(($row = mysql_fetch_array($result)) && $rows){
						  ?>
						  <tr>
							<td height="24"></td>
							<td align="center"><?=custom_type($row[com_type])?></td>
							<td align="center"><?=$row[com_name]?></td>
							<td align="center"><?=$row[charge_name]?></td>
							<td align="center"><?=$row[charge_hand]?></td>
							<td align="center"><?=$row[charge_tel]?></td>
							<td align="center"><?=$row[com_fax]?></td>
							<td align="center"><a href="home_trade_input.php?sub_mode=update&idx=<?=$row[idx]?>"><img src="../img/bt_s_modify.gif" border="0" ></a>  <a href="javascript:delTradecom('<?=$row[idx]?>');"><img src="../img/bt_s_del.gif" border="0"></a></td>
							<td></td>
						  </tr>
						  <tr>
							<td></td>
							<td colspan="7" bgcolor="eaeaea" height="1"></td>
							<td></td>
						  </tr>
						  <?
								$no--;
								$rows--;
							}
							if($total <= 0){
						  ?>
							<tr><td height="30" colspan="7" align="center">등록된 거래처가 없습니다.</td></tr>
						  <?
							}
						  ?>
						  <!---------------------- 리스트 끝----------------------------->
						  <tr>
							<td colspan="9" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td colspan="7" bgcolor="464646"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						</table>
	
						
				   </td>
				  </tr>
				 </table>
				</td>
			  </tr>
			</table>
		  </td>
		  <!-- 내용끝 -->
		</tr>
	  </table>
	</td>
  </tr>
  <tr>
	<td><? include "../inc/footer.inc";?></td>
  </tr>
</table>

</body>
</html>
