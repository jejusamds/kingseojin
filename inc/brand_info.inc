<?
if(!empty($brand_code)){
	$sql  = "";
	$sql .= "	Select	sb.* ";
	$sql .= "	From	df_shop_brand sb ";
	$sql .= "	Where	sb.idx = '" .$brand_code. "' ";
	$result = mysql_query($sql) or error(mysql_error());
	$brand_info = mysql_fetch_object($result);
}
?>