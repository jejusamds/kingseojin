<?
include "../../inc/global.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";

if(!$sub_mode) $sub_mode = "insert";

if($sub_mode == "update"){
	$sql = "select * from ez_coupon where cocode = '$cocode'";
	$result = mysql_query($sql) or error(mysql_error());
	$coinfo = mysql_fetch_array($result);

	$sdate_list = explode("-",$coinfo[sdate]);
	$edate_list = explode("-",$coinfo[edate]);

   if(empty($sdate_list[0])) $sdate_year = date('Y');
   else $sdate_year = $sdate_list[0];
   
   if(empty($sdate_list[1])) $sdate_month = date('m');
   else $sdate_month = $sdate_list[1];
   
   if(empty($sdate_list[2])) $sdate_day = date('d');
   else $sdate_day = $sdate_list[2];

		if(empty($edate_list[0])) $edate_year = date('Y');
   else $edate_year = $edate_list[0];
   
   if(empty($edate_list[1])) $edate_month = date('m');
   else $edate_month = $edate_list[1];
   
   if(empty($edate_list[2])) $edate_day = date('d');
   else $edate_day = $edate_list[2];
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
   
   if(frm.cocode.value==""){
		alert("쿠폰번호을 입력하시기 바랍니다.");
		frm.cocode.focus();
		return false;
	}
	if(frm.cocode.value.length > 30){
		alert("쿠폰번호는 최대 30자까지 입력할 수 있습니다.");
		frm.cocode.focus();
		return false;
	}
	if(frm.coname.value==""){
		alert("쿠폰명을 입력하시기 바랍니다.");
		frm.coname.focus();
		return false;
	}
	if(frm.coprice.value==""){
		alert("쿠폰가격을 입력하시기 바랍니다.");
		frm.coprice.focus();
		return false;
	}
}
-->
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
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> 쿠폰입력</td>
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
						<!------------- 최근 게시물 시작------------------>
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
								  <input type="hidden" name="mode" value="coupon">
								  <input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
								  <input type="hidden" name="cocode" value="<?=$cocode?>">

								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>쿠폰번호</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input name="cocode" value="<?=$coinfo[cocode]?>" type="text" size="30" class="input" <?
									if($cocode)	 {
										echo "readonly";
									}
									?>></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>쿠폰명</strong></td>
								  <td align="left" class="t_value"><input name="coname" value="<?=$coinfo[coname]?>" size="50" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>쿠폰사용기간</strong></td>
								  <td align="left" class="t_value"><select name="sdate_year" class="select2">
									<?                     
									   for($ii=2008; $ii <= 2020; $ii++){
										 if($ii == $sdate_year) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  년 
									  <select name="sdate_month" class="select2">
										<?
									   for($ii=1; $ii <= 12; $ii++){
										 if($ii<10) $ii = "0".$ii;
										 if($ii == $sdate_month) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  월 
									  <select name="sdate_day" class="select2">
										<?
									   for($ii=1; $ii <= 31; $ii++){
										 if($ii<10) $ii = "0".$ii;
										 if($ii == $sdate_day) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  일 ~ 
									  <select name="edate_year" class="select2">
										<?
									   for($ii=2008; $ii <= 2020; $ii++){
										 if($ii == $edate_year) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  년 
									  <select name="edate_month" class="select2">
										<?
									   for($ii=1; $ii <= 12; $ii++){
										 if($ii<10) $ii = "0".$ii;
										 if($ii == $edate_month) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  월 
									  <select name="edate_day" class="select2">
										<?
									   for($ii=1; $ii <= 31; $ii++){
										 if($ii<10) $ii = "0".$ii;
										 if($ii == $edate_day) echo "<option value=$ii selected>$ii";
										 else echo "<option value=$ii>$ii";
									   }
									?>
									  </select>
									  일</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>쿠폰가격</strong></td>
								  <td align="left" class="t_value"><input name="coprice" value="<?=$coinfo[coprice]?>" type="text" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<? 	if($sub_mode == "update")	{	?>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>사용여부</strong></td>
								  <td align="left" class="t_value"><select name="use_gb">
										<option value="Y" <? if($coinfo[use_gb] == "Y")	echo "selected";?>>Y</option>
										<option value="N" <? if($coinfo[use_gb] == "N")	echo "selected";?>>N</option>
									</select></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<?	 }	?>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>기타사항</strong></td>
								  <td align="left" class="t_value"><textarea name="etc" cols="70" rows="5" class="textarea" style="width:100%"><?=$cominfo[etc]?></textarea></td>
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
							<td height="40" colspan="3" align="center"><input type="image" src="../img/bt_confirm.gif" width="42" height="20" border="0" />&nbsp;&nbsp;<a href="coupon_list.php"><img src="../img/bt_cancel.gif" width="42" height="20" border="0" /></a></td>
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
<?
/*
CREATE TABLE ez_coupon (
  cocode varchar(30) NOT NULL,
  coname varchar(200) default NULL, 
  sdate datetime default NULL,
  edate datetime default NULL,
  coprice varchar(10) default NULL,
  memid varchar(16) default NULL,
  use_gb char(1) default NULL,
  etc text,
  reg_date datetime default NULL,
  PRIMARY KEY  (cocode)
);
*/
?>