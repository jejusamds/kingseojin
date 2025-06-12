<?
include "../../inc/global.inc";

$sql  = "";
$sql .= "	Select	b.code, f.upfile, f.upfile_name ";
$sql .= "	From	df_site_bbs_files f, df_site_bbs b ";
$sql .= "	Where	f.idx		= '".$idx."' ";
$sql .= "	And		f.bbsidx	= b.idx ";
$row = $db->row($sql);

$file = "../../userfiles/".$row['code']."/".$row['upfile'];
$filename = $row['upfile_name'];

if(file_exists($file)) {
	if( strstr($HTTP_USER_AGENT,"MSIE 5.5")){ 
		header("Content-Type: doesn/matter"); 
		header("Content-Disposition: filename=$filename"); 
		header("Content-Transfer-Encoding: binary"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
	}
	else{ 
		Header("Content-type: file/unknown"); 
		Header("Content-Disposition: attachment; filename=$filename"); 
		Header("Content-Description: PHP3 Generated Data"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
	}

	if(is_file("$file")){ 
		$fp = fopen("$file","r"); 
		if(!fpassthru($fp)) {
			fclose($fp);
		}
	}
}
else{
	echo "<script>alert('첨부파일이 존재하지 않습니다.$file');history.go(-1);</script>";
}
?>