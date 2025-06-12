<?
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";


// 이미지 파일 업로드
$up_dir = "../../userfiles/banner";		// 업로드파일 위치

if(isset($_FILES['upfile_pc01']['tmp_name']) && $_FILES['upfile_pc01']['tmp_name']){
    $file_name = $_FILES['upfile_pc01']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));

	$upfile_pc01_tmp = time() ."_PC01.". $ext;
    $save_dir = sprintf('%s/%s', $up_dir, $upfile_pc01_tmp);
 
    if (move_uploaded_file($_FILES["upfile_pc01"]["tmp_name"],$save_dir))
        $upfile_pc01_sql = ", upfile_pc01='".$upfile_pc01_tmp."' ";
}
if(isset($_FILES['upfile_pc02']['tmp_name']) && $_FILES['upfile_pc02']['tmp_name']){
    $file_name = $_FILES['upfile_pc02']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));

	$upfile_pc02_tmp = time() ."_PC02.". $ext;
    $save_dir = sprintf('%s/%s', $up_dir, $upfile_pc02_tmp);
 
    if (move_uploaded_file($_FILES["upfile_pc02"]["tmp_name"],$save_dir))
        $upfile_pc02_sql = ", upfile_pc02='".$upfile_pc02_tmp."' ";
}
if(isset($_FILES['upfile_mo01']['tmp_name']) && $_FILES['upfile_mo01']['tmp_name']){
    $file_name = $_FILES['upfile_mo01']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));

	$upfile_mo01_tmp = time() ."_MO01.". $ext;
    $save_dir = sprintf('%s/%s', $up_dir, $upfile_mo01_tmp);
 
    if (move_uploaded_file($_FILES["upfile_mo01"]["tmp_name"],$save_dir))
        $upfile_mo01_sql = ", upfile_mo01='".$upfile_mo01_tmp."' ";
}
if(isset($_FILES['upfile_mo02']['tmp_name']) && $_FILES['upfile_mo02']['tmp_name']){
    $file_name = $_FILES['upfile_mo02']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));

	$upfile_mo02_tmp = time() ."_MO02.". $ext;
    $save_dir = sprintf('%s/%s', $up_dir, $upfile_mo02_tmp);
 
    if (move_uploaded_file($_FILES["upfile_mo02"]["tmp_name"],$save_dir))
        $upfile_mo02_sql = ", upfile_mo02='".$upfile_mo02_tmp."' ";
}


// 입력 ================================================================================
if($mode == "insert"){

	$sql  = "";
	$sql .= "	Insert into df_banner_main ";
	$sql .= "	Set		subject = '".$subject."' ";
	$sql .= "	,		url = '".$url."' ";
	$sql .= "	,		url_link = '".$url_link."' ";
	$sql .= "	,		prior = '".date("ymdHis")."' ";
	$sql .= "	,		showset = '".$showset."' ";
	$sql .= "	,		upfile_pc01 = '".$upfile_pc01_tmp."' ";
	$sql .= "	,		upfile_pc02 = '".$upfile_pc02_tmp."' ";
	$sql .= "	,		upfile_mo01 = '".$upfile_mo01_tmp."' ";
	$sql .= "	,		upfile_mo02 = '".$upfile_mo02_tmp."' ";
	$db->query($sql);
	
	complete("입력 되었습니다.","banner_main.php");   

}

// 수정 ================================================================================
else if($mode == "update"){

	$sql  = "";
	$sql .= "	Update	df_banner_main ";
	$sql .= "	Set		subject = '".$subject."' ";
	$sql .= "	,		url = '".$url."' ";
	$sql .= "	,		url_link = '".$url_link."' ";
	$sql .= "	,		showset = '".$showset."' ";
	$sql .= $upfile_pc01_sql;
	$sql .= $upfile_pc02_sql;
	$sql .= $upfile_mo01_sql;
	$sql .= $upfile_mo02_sql;
	$sql .= "	Where	idx = '".$idx."' ";
	$db->query($sql);
   
	complete("수정 되었습니다.","banner_main_input.php?mode=update&idx=$idx");   

}

// 삭제 ================================================================================
else if($mode == "delete"){

	$array_seluser = explode("|",$selected);
	$i=0;

	while($array_seluser[$i]){
		$idx = $array_seluser[$i];

		// 이미지 삭제
		$sql = "select upfile_pc01, upfile_pc02, upfile_mo01, upfile_mo02 from df_banner_main where idx=$idx";
		$prd_info = $db->row($sql);
		
		if(is_file($up_dir."/".$prd_info['upfile_pc01'])){
			@unlink($up_dir."/".$prd_info['upfile_pc01']);
		}
		if(is_file($up_dir."/".$prd_info['upfile_pc02'])){
			@unlink($up_dir."/".$prd_info['upfile_pc02']);
		}
		if(is_file($up_dir."/".$prd_info['upfile_mo01'])){
			@unlink($up_dir."/".$prd_info['upfile_mo01']);
		}
		if(is_file($up_dir."/".$prd_info['upfile_mo02'])){
			@unlink($up_dir."/".$prd_info['upfile_mo02']);
		}
		
		$sql = "delete from df_banner_main where idx=$idx";
		$db->query($sql);

		$i++;
	}
	
	complete("삭제 하였습니다.","banner_main.php");   

}

// 진열순서 ================================================================================
else if($mode == "prior"){

	$sql  = "";
	$sql .= "	Select	wp.* ";
	$sql .= "	From	df_banner_main wp ";
	$sql .= "	Where	1 = 1 ";

	// 1단계 위로
	if($posi == "up"){
		$sql .= " And wp.prior >= '".$prior."' And wp.idx != '".$idx."' Order by wp.prior Asc Limit 1 ";

		if($row = $db->row($sql)){
			$prior = $row['prior'];

			$sql = " Update df_banner_main Set prior='".$prior."' Where idx='".$idx."' ";
			$db->query($sql);
			
			$sql = " Update df_banner_main Set prior=prior-1 Where prior<='".$prior."' And idx!='".$idx."' ";
			$db->query($sql);
		}
	}
	
	// 10단계 위로
	else if($posi == "upup"){
		$sql .= " And wp.prior >= '".$prior."' And wp.idx != '".$idx."' Order by wp.prior Asc Limit 10 ";
		$row = $db->query($sql);
		$total = count($row);
		
		for($i=0; $i<count($row); $i++){
			$prior = $row[$i]['prior'];
		}
		
		if($total > 0){
			$sql = " Update df_banner_main Set prior='".$prior."' Where idx='".$idx."' ";
			$db->query($sql);
			
			$sql = " Update df_banner_main Set prior=prior-1 Where prior<='".$prior."' And idx!='".$idx."' ";
			$db->query($sql);
		}
	}

	// 1단계 아래로
	else if($posi == "down"){
		$sql .= " And wp.prior <= '".$prior."' And wp.idx != '".$idx."' Order by wp.prior Desc Limit 1 ";
		
		if($row = $db->row($sql)){
			$prior = $row['prior'];
			
			$sql = " Update df_banner_main Set prior='".$prior."' Where idx='".$idx."' ";
			$db->query($sql);
			
			$sql = " Update df_banner_main Set prior=prior+1 Where prior>='".$prior."' And idx!='".$idx."' ";
			$db->query($sql);
		}
	}

	// 10단계 아래로
	else if($posi == "downdown"){
		$sql .= " And wp.prior <= '".$prior."' And wp.idx != '".$idx."' Order by wp.prior Desc Limit 10 ";
		$row = $db->query($sql);
		$total = count($row);
		
		for($i=0; $i<count($row); $i++){
			$prior = $row[$i]['prior'];
		}	
		
		if($total > 0){
			$sql = " Update df_banner_main Set prior='".$prior."' Where idx='".$idx."' ";
			$db->query($sql);
			
			$sql = " Update df_banner_main Set prior=prior+1 Where prior>='".$prior."' And idx!='".$idx."' ";
			$db->query($sql);
		}
	}
   
	complete("진열순서를 변경하였습니다.","banner_main.php");

}
?>
