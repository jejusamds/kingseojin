<?
include "../../inc/global.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";

if($sub_mode == "update"){
	$sql = "select * from wiz_tradecom where idx = '$idx'";
	$result = mysql_query($sql) or error(mysql_error());
	$cominfo = mysql_fetch_array($result);
}
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
<script language="javascript">
<!--
function inputCheck(frm){
   
   if(frm.com_name.value == ""){
      alert("��ȣ�� �Է��ϼ���");
      frm.com_name.focus();
      return false;
   }
   if(frm.com_type.value == ""){
      alert("��ü������ �����ϼ���");
      frm.com_type.focus();
      return false;
   }
}

// �����ȣ ã��
function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
-->
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
		  <td>
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
					<td><table width="730" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>
						<!------------- �ֱ� �Խù� ����------------------>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								  <form name="frm" action="set_save.php" method="post" onSubmit="return inputCheck(this);">
								  <input type="hidden" name="tmp">
								  <input type="hidden" name="mode" value="home_trade">
								  <input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
								  <input type="hidden" name="idx" value="<?=$idx?>">

								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>����ڵ�Ϲ�ȣ</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="com_num" value="<?=$cominfo[com_num]?>" type="text" class="input"></td>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��ü����</strong></td>
								  <td width="35%" align="left" class="t_value">
									<select name="com_type">
									<option value="">:: ���� ::
									<option value="BUY" <? if($cominfo[com_type] == "BUY") echo "selected"; ?>>����ó
									<option value="SAL" <? if($cominfo[com_type] == "SAL") echo "selected"; ?>>����ó
									<option value="DEL" <? if($cominfo[com_type] == "DEL") echo "selected"; ?>>��۾�ü
									<option value="OTH" <? if($cominfo[com_type] == "OTH") echo "selected"; ?>>��Ÿ
									</select></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>��ȣ</strong></td>
								  <td align="left" class="t_value"><input name="com_name" value="<?=$cominfo[com_name]?>" type="text"  class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>��ǥ��</strong></td>
								  <td align="left" class="t_value"><input name="com_owner" value="<?=$cominfo[com_owner]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>������ּ�</strong></td>
								  <td width="85%" align="left" class="t_value" colspan="3"><? list($com_post, $com_post2) = explode("-", $cominfo[com_post]); ?>
									<input type="text" name="com_post" value="<?=$com_post?>" size="6" class="input"> - 
									<input type="text" name="com_post2" value="<?=$com_post2?>" size="6" class="input">
									<input name="Button" type="button" class="gbtn" value=" ã �� " onClick="searchZip();"><br>
									<input name="com_address" value="<?=$cominfo[com_address]?>" type="text" size="50" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>����</strong></td>
								  <td align="left" class="t_value"><input name="com_kind" value="<?=$cominfo[com_kind]?>" type="text" class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>����</strong></td>
								  <td align="left" class="t_value"><input name="com_class" value="<?=$cominfo[com_class]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>��ȭ��ȣ</strong></td>
								  <td align="left" class="t_value"><input name="com_tel" value="<?=$cominfo[com_tel]?>" type="text" class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>�ѽ���ȣ</strong></td>
								  <td align="left" class="t_value"><input name="com_fax" value="<?=$cominfo[com_fax]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>�ŷ�����</strong></td>
								  <td align="left" class="t_value"><input name="com_bank" value="<?=$cominfo[com_bank]?>" type="text" class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>���¹�ȣ</strong></td>
								  <td align="left" class="t_value"><input name="com_account" value="<?=$cominfo[com_account]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>Ȩ������</strong></td>
								  <td align="left" class="t_value" colspan="3"><input name="com_homepage" value="<?=$cominfo[com_homepage]?>" size="30" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>�����</strong></td>
								  <td align="left" class="t_value"><input name="charge_name" value="<?=$cominfo[charge_name]?>" type="text" class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>����� �̸���</strong></td>
								  <td align="left" class="t_value"><input name="charge_email" value="<?=$cominfo[charge_email]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>����� �޴���</strong></td>
								  <td align="left" class="t_value"><input name="charge_hand" value="<?=$cominfo[charge_hand]?>" type="text" class="input"></td>
								  <td align="center" bgcolor="#efefef" class="s01"><strong>����� ��ȭ</strong></td>
								  <td align="left" class="t_value"><input name="charge_tel" value="<?=$cominfo[charge_tel]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>��Ÿ����</strong></td>
								  <td align="left" class="t_value" colspan="3"><textarea name="descript" cols="70" rows="5" class="textarea" style="width:100%"><?=$cominfo[descript]?></textarea></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>

						  <table>
						  <tr>
							<td height="40" colspan="3" align="center"><input type="image" src="../img/bt_confirm.gif" width="42" height="20" border="0" />&nbsp;&nbsp;<a href="home_trade.php"><img src="../img/bt_cancel.gif" width="42" height="20" border="0" /></a></td>
						  </tr>
						  </table>
						</td>
					  </tr>
					</table>
				   </td>
				  </tr></form>

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
