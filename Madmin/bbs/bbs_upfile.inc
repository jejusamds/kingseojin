<?
$upfile_path = "../../userfiles/".$code;		// 업로드파일 위치
$upfile_idx = date('Ymdhis').rand(1,9);		// 업로드파일명
if(!is_dir($upfile_path)){							// 업로드 디렉토리 생성
	echo exec("mkdir $upfile_path");
	exec("chmod 777 $upfile_path");
}


for($i=0; $i<count($_FILES['upfile']['tmp_name']); $i++){
	if(isset($_FILES['upfile']['tmp_name'][$i]) && $_FILES['upfile']['tmp_name'][$i]){
		$upfile_name_tmp = $_FILES['upfile']['name'][$i];
		$ext = strtolower(substr($upfile_name_tmp, (strrpos($upfile_name_tmp, '.') + 1)));
		$upfile_tmp = $upfile_idx."_".$i.".".$ext;
		$save_dir = sprintf('%s/%s', $upfile_path, $upfile_tmp);
		if(move_uploaded_file($_FILES["upfile"]["tmp_name"][$i],$save_dir)){
			if($old_idx[$i]){
				$sql = " Select upfile From df_site_bbs_files Where idx='".$old_idx[$i]."' ";
				$upfile_old = $db->single($sql);
				if($upfile_old)
					@unlink($upfile_path."/".$upfile_old);
				
				$sql  = "";
				$sql .= "	Update	df_site_bbs_files ";
				$sql .= "	Set		bbsidx		= '".$bbsidx."' ";
				$sql .= "	,		upfile		= '".$upfile_tmp."' ";
				$sql .= "	,		upfile_name	= '".$upfile_name_tmp."' ";
				$sql .= "	Where	idx			= '".$old_idx[$i]."' ";
				$db->query($sql);
			}
			else{
				$sql  = "";
				$sql .= "	Insert into df_site_bbs_files ";
				$sql .= "	Set		bbsidx		= '".$bbsidx."' ";
				$sql .= "	,		upfile		= '".$upfile_tmp."' ";
				$sql .= "	,		upfile_name	= '".$upfile_name_tmp."' ";
				$db->query($sql);
			}
		}
	}
}

?>