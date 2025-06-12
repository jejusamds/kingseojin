<?
include "../../inc/global.inc";

$sql = "select * from ez_domain where seq='$seq'";
$result = mysql_query($sql) or error(mysql_error());
$row = mysql_fetch_object($result);
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>도메인 정보</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="../inc/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
   if(frm.domain.value == ""){
      alert("도메인을 입력하세요");
      frm.domain.focus();
      return false;
   }
}
//-->
</script>
</head>

<body>
<table width="300" cellpadding=5 cellspacing=0><tr><td>
	
<table width="300" cellpadding=0 cellspacing=0>
  <tr>
    <td><font color="#000000"><b>+ 도메인 정보</b></font></td>
  </tr>
</table>
<table width="300" border="0" cellspacing="3" cellpadding="1" class="t_style">
<form name="frm" method="post" action="set_save.php" onSubmit="return inputCheck(this)">
<input type="hidden" name="mode" value="domain">
<input type="hidden" name="submode" value="<?=$submode?>">
<input type="hidden" name="seq" value="<?=$seq?>">
    <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
  <tr>
	<td width="100" height="25" align="center" bgcolor="#efefef" class="s01"><strong>도메인</strong></td>
	<td width="200" align="left" class="t_value"><input type="text" name="domain" size="25" class="input01" value="<?=$row->domain?>" /></td>
  </tr>
  <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
  <tr>
	<td height="25" align="center" bgcolor="#efefef" class="s01"><strong>구입사이트</strong></td>
	<td align="left" class="t_value"><input type="text" name="domain_buy" size="25" class="input01" value="<?=$row->domain_buy?>" /></td>
  </tr>
    <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
  <tr>
	<td height="25" align="center" bgcolor="#efefef" class="s01"><strong>아이디</strong></td>
	<td align="left" class="t_value"><input type="text" name="domain_id" size="25" class="input01" value="<?=$row->domain_id?>" /></td>
  </tr>
    <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
  <tr>
	<td height="25" align="center" bgcolor="#efefef" class="s01"><strong>비밀번호</strong></td>
	<td align="left" class="t_value"><input type="text" name="domain_pw" size="25" class="input01" value="<?=$row->domain_pw?>" /></td>
  </tr>
    <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
  <tr>
	<td height="25" align="center" bgcolor="#efefef" class="s01"><strong>만료일</strong></td>
	<td align="left" class="t_value"><input type="text" name="domain_end" size="25" class="input01" value="<?=$row->domain_end?>" /></td>
  </tr>
    <tr>
	<td colspan="2" bgcolor="eaeaea" height="1"></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td><input type="image" src="../img/bt_confirm.gif"></td>
  </tr>
</form>
</table>

</td></tr></table>
</body>
</html>