<?
// 관리자 로그인 체크
if($_SESSION['admin_id'] == ""){
?>
<script language="javascript">
<!--
if(parent != null)
   parent.document.location = "/Madmin/admin_login.html";
else
   document.location = "/Madmin/admin_login.html";
-->
</script>
<?
	exit();
}
?>