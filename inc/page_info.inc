<?
$sql = "select * from df_site_page where type = '$page_type'";
$result = mysql_query($sql) or error(mysql_error());
$page_info = mysql_fetch_object($result);

if(is_file("$_SERVER['DOCUMENT_ROOT']/shopimg/subimg/$page_info->subimg"))
	$subimg = "<img src='/shopimg/subimg/$page_info->subimg'>";
else
	$subimg = "";
?>