<?
if(!empty($catcode)){
	$sql  = "";
	$sql .= "	Select	* ";
	$sql .= "	From	df_shop_category ";
	$sql .= "	Where	depthno	= '1' ";
	$sql .= "	And		SUBSTRING(catcode,1,2) = '".substr($catcode,0,2)."' ";
	$cat_info = $db->row($sql);
}
?>