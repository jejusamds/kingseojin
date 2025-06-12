<?
if($_SESSION['userid'] != ""){
	$sql = " Select m.* From df_site_member m Where m.user_id = '" .$_SESSION['userid']. "' ";
	$mem_info = $db->row($sql);
	
	$_SESSION['userlevel'] = $mem_info['level'];
}
?>