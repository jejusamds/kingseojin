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
   if(confirm('�ش�ŷ�ó�� �����Ͻðڽ��ϱ�?')){
      document.location = "set_save.php?mode=home_trade&sub_mode=delete&idx=" + idx;
   }
}
//-->
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <!-- ��ܽ��� -->
  <tr>
	<td><? include "../inc/top.inc";?></td>
  </tr>
  <!-- ��ܳ� -->
  <tr>
	<td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <!-- �������� -->
		  <td width="220" valign="top"><? include "../inc/left_set.inc";?></td>
		  <!-- ������ -->
		  
		  <!-- ������� -->
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
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> �ŷ�ó����</td>
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
							<td colspan="4" align="left"><font color="#000000"><strong>+ �ŷ�ó���</strong></font></td>
							<td colspan="3" align="right"><a href="home_trade_input.php?sub_mode=insert"><img src="../img/bt_trade_reg.gif" border="0" /></a></td>
							<td width="12"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left.gif" width="12" height="25" /></td>
							<td width="90" align="center" background="../img/list_bar_bg.gif" class="s04">����</td>
							<td width="135" align="center" background="../img/list_bar_bg.gif" class="s04">�ŷ�ó��</td>
							<td width="110" align="center" background="../img/list_bar_bg.gif" class="s04">�����</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif" class="s04">�޴���</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif"class="s04">��ȭ��ȣ</td>
							<td width="100" align="center" background="../img/list_bar_bg.gif" class="s04">�ѽ�</td>
							<td width="50" align="center" background="../img/list_bar_bg.gif"class="s04">���</td>
							<td><img src="../img/list_bar_right.gif" width="12" height="25" /></td>
						  </tr>
						  <?
						   function custom_type($type){
							   if($type == "BUY") return "����ó";
							   else if($type == "SAL") return "����ó";
							   else if($type == "DEL") return "��۾�ü";
							   else if($type == "OTH") return "��Ÿ";
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
							<tr><td height="30" colspan="7" align="center">��ϵ� �ŷ�ó�� �����ϴ�.</td></tr>
						  <?
							}
						  ?>
						  <!---------------------- ����Ʈ ��----------------------------->
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
		  <!-- ���볡 -->
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
