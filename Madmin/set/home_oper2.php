<?
include "../../inc/global.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";
include "../../inc/oper_info.inc";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title><?=$site_info->admin_title?></title>
<script language="JavaScript" src="/js/util_lib.js"></script>
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
// ������ ���� �ٽ�����
function setReserve(frm){
   
   var reserve_per = frm.reserve_per.value;
   
   if(Check_Num(reserve_per)){
   	if(confirm("��� ��ǰ�� �������� ��ǰ������ "+reserve_per+"% �� �ϰ����� �˴ϴ�.\n\n�����Ͻðڽ��ϱ�?")){
      	document.location = "set_save.php?mode=setreserve&reserve_per=" + reserve_per;
      }
   }else{
      alert("���ڸ� �Է��ϼ���");
      frm.reserve_per.value = "";
      frm.reserve_per.focus();
   }
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
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> ���������</td>
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
						<div align="left"><font color="#000000"><strong>+ ��������</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <form name="frm" action="set_save.php" method="post" enctype="multipart/form-data">
						  <input type="hidden" name="tmp">
						  <input type="hidden" name="mode" value="oper_info">
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
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>�������</strong></td>
								  <td width="85%" align="left" class="t_value"><?
										$pay_list = explode("/",$oper_info->pay_method);
										for($ii=0; $ii<count($pay_list); $ii++){
										$pay_method[$pay_list[$ii]] = true;
										}
										?>
									<input type="checkbox" name="pay_method[]" value="PB" <? if($pay_method["PB"]==true) echo "checked";?>>�������Ա�&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PC" <? if($pay_method["PC"]==true) echo "checked";?>>ī�����&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PN" <? if($pay_method["PN"]==true) echo "checked";?>>������ü&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PV" <? if($pay_method["PV"]==true) echo "checked";?>>�������&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PH" <? if($pay_method["PH"]==true) echo "checked";?>>�޴�������</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>�����ý���</strong></td>
								  <td align="left" class="t_value">
									<input name="pay_agent" value="DACOM" type="radio" <? if($oper_info->pay_agent == "DACOM") echo "checked"; ?> > 
									<b>DACOM</b> (LG������ ���ڰ�������) <a href="http://ecredit.dacom.net" target="_blank">http://ecredit.dacom.net</a><br>
									&nbsp; &nbsp; &nbsp; <!-- ��û�� ���԰�ο� <b>"���(WIZSHOP)"</b> �� �� �Է��ϼ��� ���� <font color=red>�����ᰡ 3.5</font>���� ���������ϴ�.<br>
									&nbsp; &nbsp; &nbsp; �Ǵ� --> �����ϱ⸦ Ŭ�� �� ��û�Ͻñ� �ٶ��ϴ�. <a href="http://pgweb.dacom.net/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop" target="_blank"><font color=red>>>�����ϱ�</font></a>
								</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>DACOM ���̵�</strong></td>
								  <td align="left" class="t_value"><input name="pay_id" value="<?=$oper_info->pay_id?>" type="text" class="input"><br><br>
									<font color=red>
									  <b>������ ������ > ������� > ������������ > "���ΰ�����ۿ���" �� ����(������)</b>���� �� �����ϼ���.<br>
										�ݵ�� �����ؾ� ī����� ������ ���������� �̷�����ϴ�.
									</font>
								</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>DACOM ����Ű</strong></td>
								  <td align="left" class="t_value"><input name="pay_key" value="<?=$oper_info->pay_key?>" type="text" size="50" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>������¹�ȣ</strong></td>
								  <td align="left" class="t_value"><textarea cols="50" rows="5" name="pay_account" class="textarea"><?=$oper_info->pay_account?></textarea></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ������ ID: DACOM (ecredit)���� �ο����� ������ ���� ID�� �Է��ϼ���</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ������¹�ȣ: �ֹ��� ����� ������¸� ���ٿ� �ϳ��� �Է��մϴ�. ex) �������� 484201-01-127831, ������: ȫ�浿</font></td>
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
						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ ����ũ��</strong></font></div>
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
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��뿩��</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input name="pay_escrow" value="Y" type="radio" <? if($oper_info->pay_escrow == "Y") echo "checked"; ?> > �����
									<input name="pay_escrow" value="N" type="radio" <? if($oper_info->pay_escrow == "N") echo "checked"; ?> > ������ 
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>����ũ�� ����url</strong></td>
								  <td align="left" class="t_value"> http://<?=$HTTP_HOST?>/shop/dacom/escrow_save.php</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- �������Ա�: ����ũ�� ���� 10�����̻��� �ֹ������� �������Ա� ����� ������ϴ�.</font></td>
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

						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ ����������</strong></font></div>
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
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��뿩��</strong></td>
								  <td align="left" class="t_value" colspan="3">
									<input type="radio" name="reserve_use" value="Y" <? if($oper_info->reserve_use == "Y") echo "checked"; ?>>�����
									<input type="radio" name="reserve_use" value="N" <? if($oper_info->reserve_use == "N") echo "checked"; ?>>������</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>ȸ������ ������</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_join" type="text" value="<?=$oper_info->reserve_join?>" class="input"></td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>��õ�� ������</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_recom" type="text" value="<?=$oper_info->reserve_recom?>" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>�ּһ�� ������</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_min" type="text" value="<?=$oper_info->reserve_min?>" class="input"></td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>1ȸ �ִ��� ������</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_max" type="text" value="<?=$oper_info->reserve_max?>" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��ǰ���Ž� ������</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_buy" type="text" value="<?=$oper_info->reserve_buy?>" class="input"> %</td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>������ �ϰ�����</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_per" type="text" value="<?=$oper_info->reserve_per?>" class="input" size="10"> % &nbsp;
									<input type="button" class="gbtn" value=" �� �� " onClick="setReserve(this.form);"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ��뿩��: ��ǰ���Խ� ������ ����/��� , ȸ�����Խ�, ��õ���ΰ�� �� ������ ����� �����մϴ�.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ��ǰ���Ž� ������: ��ǰ��Ͻ� �Ǹűݾ׿� �ۼ��� �ۼ�Ʈ�� �����Ͽ� �������� �ڵ����˴ϴ�.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ������ �ϰ�����: ���� ��ϵǾ��ִ� ��ǰ�� �������� �ۼ��� �ۼ�Ʈ�� �ٽ� �����մϴ�.</font></td>
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
						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ ��ǰ����</strong></font></div>
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
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��뿩��</strong></td>
								  <td width="35%" align="left" class="t_value">
									<input type="radio" name="review_use" value="Y" <? if($oper_info->review_use == "Y") echo "checked"; ?>>�����
									<input type="radio" name="review_use" value="N" <? if($oper_info->review_use == "N") echo "checked"; ?>>������</td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>�ۼ�����</strong></td>
								  <td width="35%" align="left" class="t_value">
									<input type="radio" name="review_level" value="E" <? if($oper_info->review_level == "E") echo "checked"; ?>>ȸ��+��ȸ��
									<input type="radio" name="review_level" value="M" <? if($oper_info->review_level == "M") echo "checked"; ?>>ȸ����</td>
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
                        <br /><br /><br />
                        
                        <!-- ���⼭ ���� �߰� ���� -->
                        						<div align="left"><font color="#000000"><strong>+ �������</strong></font></div>
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
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>�ù��</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input name="del_com" value="<?=$oper_info->del_com?>" type="text" class="input">
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>�����������</strong></td>
								  <td align="left" class="t_value"> <input name="del_trace" value="<?=$oper_info->del_trace?>" type="text" size="80" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>������</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DA" <? if($oper_info->del_method == "DA") echo "checked"; ?>> ��ۺ� ���׹���
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>�����ںδ�</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DB" <? if($oper_info->del_method == "DB") echo "checked"; ?>> �����ںδ� (����)
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>������</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DC" <? if($oper_info->del_method == "DC") echo "checked"; ?>>
									<input name="del_fixprice" type="text" value="<?=$oper_info->del_fixprice?>" class="input">��
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>���Ű��ݺ�</strong></td>
								  <td width="85%" align="left" class="t_value">
									<table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td>
										  <input type="radio" name="del_method" value="DD" <? if($oper_info->del_method == "DD") echo "checked"; ?>>
										  <input type="text" name="del_staprice" value="<?=$oper_info->del_staprice?>" class="input">
										</td>
										<td>&nbsp;�̻󱸸Ž� <input type="text" name="del_staprice2" value="<?=$oper_info->del_staprice2?>" class="input"> ��</td>
									  </tr>                  
									  <tr>
										<td></td>
										<td>&nbsp;���ϱ��Ž� <input type="text" name="del_staprice3" value="<?=$oper_info->del_staprice3?>" class="input"> ��</td>
									  </tr>   
									</table>   
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>��������</strong></td>
								  <td width="85%" align="left" class="t_value">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
										  <td width="10"></td>
										  <td>�����ȣ</td>
										  <td>������</td>

										</tr>
                                        
                                       <? for($iu = 1; $iu <= 50; $iu++){ ?> 
										<tr>
										  <td width="10"></td>
										  <td> 
										  <input name="del_extrapost<?=$iu?>" type="text" value="<?=$oper_info->{'del_extrapost'.$iu}?>" class="input" size="9"> ����  
										  <input name="del_extrapostq<?=$iu?>2" type="text" value="<?=$oper_info->{'del_extrapostq'.$iu.'2'}?>" class="input" size="9"> ���� 
										  </td>
										  <td>
										  <input name="del_extraprice<?=$iu?>" type="text" value="<?=$oper_info->{'del_extraprice'.$iu}?>" class="input" size="20">�� <input type="checkbox" name="q_view<?=$iu?>" value="1" <? if($oper_info->{'q_view'.$iu} == "1"){?> checked="checked" <? } ?> />��ۺҰ�
										  </td>
										</tr>
									   <? } ?>
									  </table>
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ��۷� ����: ��۷Ḧ 4���� ���·� �����ϸ� �� ��Ȳ�� ��۷� ������ �մϴ�.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- ��������: ���������� ���� ��۷Ḧ ���� �մϴ�. �����ֱ� �Ѱ���� ��� 695840 ���� 695844 ���� 2000��</font></td>
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
                        <!-- ���⼭���� �߰� �� -->


						  <table>
						  <tr>
							<td height="40" colspan="3" align="center"><input type="image" src="../img/bt_confirm.gif" width="42" height="20" border="0" />&nbsp;&nbsp;<a href="../"><img src="../img/bt_cancel.gif" width="42" height="20" border="0" /></a></td>
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
