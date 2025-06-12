<? 
include "../../inc/global.inc"; 
include "../../inc/util_lib.inc"; 
include "../../inc/Eadmin_check.inc";


// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&isuse=$isuse";
$param .= "&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//--------------------------------------------------------------------------------------------------


function addUpdate($type,$subimg,$subimg_size,$subimg_name){
	
	global $db, $content, $addinfo, $addinfo2, $info_use, $info_ess;
	/*
	if($subimg_size > 0){
	    exec("cp $subimg ../../images/subimg/$subimg_name");	    
		$subimg_sql = " subimg='$subimg_name', ";
	}
	*/
	$sql = " select idx from df_site_page where type='$type' ";
	$exist = $db->query($sql);
	
	if($exist){
		$sql = "update df_site_page set $subimg_sql content='$content', addinfo='$addinfo', addinfo2='$addinfo2' where type='$type'";
	}else{
		$sql = "insert into df_site_page values('','$type','$subimg_name','$content','$addinfo','$addinfo2')";
	}

	$db->query($sql);
	
}

if($mode == "update"){
	
	addUpdate($type,$subimg,$subimg_size,$subimg_name);
	complete("수정되었습니다.","$page");
}


//팝업입력
if($mode == "pinsert"){
	
	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$edate_day;
	
	$sql = "	Insert Into df_site_content (type, isuse, scroll, posi_x, posi_y, size_x, size_y, sdate, edate, linkurl, title, content, wdate, poptype, close_bg, close_align, close_txt, close_txt_color) 
					Values ('$type', '$isuse', '$scroll', '$posi_x', '$posi_y', '$size_x', '$size_y', '$sdate', '$edate', '$linkurl', '$title', '$content',now(), '$popup_type', '$close_bg', '$close_align', '$close_txt', '$close_txt_color')";

	$db->query($sql);
	
	complete("추가되었습니다.","page_popup.php?page=$page&$param");


// 팝업 수정
}else if($mode == "pupdate"){
	
	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$edate_day;
	
	if(!empty($type)) $where_sql = " where type = '$type' and idx = '$idx'";
	else $where_sql = " where idx = '$idx'";
	
	$sql  = "";
	$sql .= "	Update	df_site_content ";
	$sql .= "	Set			isuse = '$isuse' ";
	$sql .= "	,			scroll = '$scroll' ";
	$sql .= "	,			posi_x = '$posi_x' ";
	$sql .= "	,			posi_y = '$posi_y' ";
	$sql .= "	,			size_x = '$size_x' ";
	$sql .= "	,			size_y = '$size_y' ";
	$sql .= "	,			sdate = '$sdate' ";
	$sql .= "	,			edate = '$edate' ";
	$sql .= "	,			linkurl = '$linkurl' ";
	$sql .= "	,			poptype = '$popup_type' ";
	$sql .= "	,			title = '$title' ";
	$sql .= "	,			content = '$content' ";
	$sql .= "	,			close_bg = '$close_bg' ";
	$sql .= "	,			close_align = '$close_align' ";
	$sql .= "	,			close_txt = '$close_txt' ";
	$sql .= "	,			close_txt_color = '$close_txt_color' ";
	$sql .= $where_sql;
	$db->query($sql);
	
	complete("수정되었습니다.","page_popup.php?page=$page&$param");


// 팝업 삭제	
}else if($mode == "pdelete"){
	
	$sql = "delete from df_site_content$DB_ID where idx = '$idx'";
	
	$db->query($sql);
	
	complete("삭제되었습니다.","page_popup.php?page=$page&$param");
	
	
}


// 모바일 팝업입력
else if($mode == "mpinsert"){
	
	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$edate_day;
	
	$sql = "	Insert Into df_site_content_mobile (type,  isuse, sdate, edate, linkurl, title, content, wdate, close_bg, close_align, close_txt, close_txt_color) 
					Values ('$type', '$isuse', '$sdate', '$edate', '$linkurl', '$title', '$content',now(), '$close_bg', '$close_align', '$close_txt', '$close_txt_color')";

	$db->query($sql);
	
	complete("추가되었습니다.","mobile_popup.php?page=$page&$param");


// 모바일 팝업 수정
}else if($mode == "mpupdate"){
	
	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$edate_day;
	
	if(!empty($type)) $where_sql = " where type = '$type' and idx = '$idx'";
	else $where_sql = " where idx = '$idx'";
	
	$sql  = "";
	$sql .= "	Update	df_site_content_mobile ";
	$sql .= "	Set			isuse = '$isuse' ";
	$sql .= "	,			sdate = '$sdate' ";
	$sql .= "	,			edate = '$edate' ";
	$sql .= "	,			linkurl = '$linkurl' ";
	$sql .= "	,			title = '$title' ";
	$sql .= "	,			content = '$content' ";
	$sql .= "	,			close_bg = '$close_bg' ";
	$sql .= "	,			close_align = '$close_align' ";
	$sql .= "	,			close_txt = '$close_txt' ";
	$sql .= "	,			close_txt_color = '$close_txt_color' ";
	$sql .= $where_sql;
	
	$db->query($sql);
	
	complete("수정되었습니다.","mobile_popup.php?page=$page&$param");


// 모바일 팝업 삭제	
}else if($mode == "mpdelete"){
	
	$sql = "delete from df_site_content_mobile where idx = '$idx'";
	
	$db->query($sql);
	
	complete("삭제되었습니다.","mobile_popup.php?page=$page&$param");

	
}

?>