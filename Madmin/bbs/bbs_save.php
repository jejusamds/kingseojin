<?
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";


$param = "code=".$code."&searchgrp=".$searchgrp."&search_option=".$search_option."&keyword=".$keyword;

if(!$notice) $notice = "N";

// error_reporting(E_ALL & ~E_NOTICE);
// ini_set("display_errors", 1);

function file_upload($files, $code, $type) {
	$upfile = $files;
	$upfile_name = $upfile['name'];
	$upfile_tmp = $upfile['tmp_name'];
	$upfile_error = $upfile['error'];
	$upfile_ext = explode('.', $upfile_name);
	$upfile_ext = strtolower(end($upfile_ext));
	$upfile_thumb = "";
	
	if ($upfile_error === 0) {
		$upfile_name_new = uniqid('', true) . "." . $upfile_ext;
		$upfile_dest = $_SERVER["DOCUMENT_ROOT"] . "/userfiles/" . $code . "/" . $upfile_name_new;

        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/userfiles/" . $code . "/";
        if (!is_dir($uploadDir)) {
            if(!mkdir($uploadDir, 0777, true)) {
                echo "디렉토리 생성에 실패했습니다.";
                return "";
            }
        }

		move_uploaded_file($upfile_tmp, $upfile_dest);
		$upfile_thumb = $upfile_name_new;
	} else {
		echo "파일 업로드 중 오류가 발생했습니다.";
	}

	return $upfile_thumb;
}


//====================================================================================================
//	작성
//====================================================================================================
if($mode == "insert"){

	if($memid == "") $memid = $ez_admin[id];
	if($wdate == "") $wdate = date('Y-m-d H:i:s');
	if($privacy == "") $privacy = "N";
	if($event_win == "") $event_win = "N";
	
	$sql = " Select IFNULL(MAX(prino),0) From df_site_bbs Where code='".$code."' ";
	$prino = $db->single($sql);
	$prino++;


    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);

	$upfile_thumb = "";
	if($_FILES['upfile_thumb']['name']){
		$upfile_thumb = file_upload($_FILES['upfile_thumb'], $code, "thumb");
	}

	$sql  = "";
	$sql .= "	Insert into df_site_bbs ";
	$sql .= "	Set		code			= '".$code."' ";
    if ($code == 'data') {
        $sql .= "	, 		grp				= '".$grp."' ";
    }
	$sql .= "	, 		parno			= 0 ";
	$sql .= "	, 		prino			= '".$prino."' ";
	$sql .= "	, 		depno			= 0 ";
	$sql .= "	, 		notice			= '".$notice."' ";
	$sql .= "	, 		memid			= '".$memid."' ";
	$sql .= "	, 		name			= '".$name."' ";
	$sql .= "	, 		email			= '".$email."' ";
	$sql .= "	, 		subject			= '".$subject."' ";
	$sql .= "	, 		content			= '".$content."' ";
	$sql .= "	, 		ctype			= '".$ctype."' ";
	$sql .= "	, 		privacy			= '".$privacy."' ";
	$sql .= "	, 		passwd			= '".$passwd."' ";
	$sql .= "	, 		count			= '".$count."' ";
	$sql .= "	, 		ip				= '".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "	, 		wdate			= '".$wdate."' ";
	$sql .= "	, 		event_sdate		= '".$event_sdate."' ";
	$sql .= "	, 		event_edate		= '".$event_edate."' ";
	$sql .= "	,		rpermi			= '".implode(",",$rpermi)."' ";
	$sql .= "	,		media_url		= '".$media_url."' ";
	if ($upfile_thumb != "") {
		$sql .= "	,		upfile			= '".$upfile_thumb."' ";
	}
	
	$db->query($sql);
	
	$bbsidx = $db->lastInsertId();
	
	include "./bbs_upfile.inc";
	
	complete("게시물이 작성되었습니다.","bbs_list.php?page=".$page."&".$param);

}

//====================================================================================================
//	수정
//====================================================================================================
else if($mode == "update"){
	
	if($privacy == "") $privacy = "N";
	if($event_win == "") $event_win = "N";

	$upfile_thumb = "";
	if($_FILES['upfile_thumb']['name']){
		$upfile_thumb = file_upload($_FILES['upfile_thumb'], $code, "thumb");
	}

	if ($upfile_thumb != "") {
		$sql = " Select upfile From df_site_bbs Where idx='".$idx."' ";
		$row = $db->row($sql);
		@unlink("../../userfiles/".$code."/".$row['upfile']);
	}

	$sql  = "";
	$sql .= "	Update	df_site_bbs ";
	$sql .= "	Set		notice		= '".$notice."' ";
    if ($code == 'data') {
        $sql .= "	, 		grp				= '".$grp."' ";
    }
	$sql .= "	, 		memid		= '".$memid."' ";
	$sql .= "	, 		name		= '".$name."' ";
	$sql .= "	, 		email		= '".$email."' ";
	$sql .= "	, 		subject		= '".$subject."' ";
	$sql .= "	, 		content		= '".$content."' ";
	$sql .= "	, 		ctype		= '".$ctype."' ";
	$sql .= "	, 		privacy		= '".$privacy."' ";
	$sql .= "	, 		event_sdate	= '".$event_sdate."' ";
	$sql .= "	, 		event_edate	= '".$event_edate."' ";
	$sql .= "	,		rpermi			= '".implode(",",$rpermi)."' ";
	$sql .= "	,		media_url	= '".$media_url."' ";
	$sql .= "	,		count		= '".$count."' ";
	if ($upfile_thumb != "") {
		$sql .= "	,		upfile			= '".$upfile_thumb."' ";
	}
	$sql .= "	Where	idx			= '".$idx."' ";
	$db->query($sql);

	$bbsidx = $idx;
	
	include "./bbs_upfile.inc";
	
	complete("게시물이 수정되었습니다.","bbs_list.php?page=".$page."&".$param);
	
} 

//====================================================================================================
//	답변
//====================================================================================================
else if($mode == "reply"){
   
	$sql = " Select * From df_site_bbs Where code='".$code."' And idx='".$idx."' ";
	$row = $db->row($sql);
	
	$parno = $row['idx'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];
	
	$sql = " Update df_site_bbs Set prino=prino+1 Where code='".$code."' And prino >= '".$prino."' ";
	$db->query($sql);
	
	if($memid == "") $memid = $ez_admin[id];
	if($wdate == "") $wdate = date('Y-m-d H:i:s');
	if($privacy == "") $privacy = "N";
	if($event_win == "") $event_win = "N";
	
	$sql  = "";
	$sql .= "	Insert into df_site_bbs ";
	$sql .= "	Set		code			= '".$code."' ";
	$sql .= "	, 		parno			= '".$parno."' ";
	$sql .= "	, 		prino			= '".$prino."' ";
	$sql .= "	, 		depno			= '".$depno."' ";
	$sql .= "	, 		notice			= '".$notice."' ";
	$sql .= "	, 		grp				= '".$grp."' ";
	$sql .= "	, 		memid			= '".$memid."' ";
	$sql .= "	, 		name			= '".$name."' ";
	$sql .= "	, 		email			= '".$email."' ";
	$sql .= "	, 		subject			= '".$subject."' ";
	$sql .= "	, 		content			= '".$content."' ";
	$sql .= "	, 		ctype			= '".$ctype."' ";
	$sql .= "	, 		privacy			= '".$privacy."' ";
	$sql .= "	, 		passwd			= '".$passwd."' ";
	$sql .= "	, 		count			= 0 ";
	$sql .= "	, 		recom			= 0 ";
	$sql .= "	, 		ip				= '".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "	, 		wdate			= '".$wdate."' ";
	$sql .= "	, 		prdcode			= '".$prdcode."' ";
	$sql .= "	, 		sns_link		= '".$sns_link."' ";
	$sql .= "	, 		event_sdate		= '".$event_sdate."' ";
	$sql .= "	, 		event_edate		= '".$event_edate."' ";
	$sql .= "	, 		event_win		= '".$event_win."' ";
	$sql .= "	, 		event_winner	= '".$event_winner."' ";
	$result = $db->query($sql);
	
	if($result){
		$bbsidx = $db->lastInsertId();
		
		include "./bbs_upfile.inc";
		
		complete("답글이 작성되었습니다.","bbs_list.php?page=".$page."&".$param);
	}
	else{
		comalert("저장에 실패했습니다.","");
	}

} 

//====================================================================================================
//	선택 삭제
//====================================================================================================
else if($mode == "delbbs"){

	$array_seluser = explode("|",$seluser);
	$i = 0;
	while($array_seluser[$i]){
		$idx = $array_seluser[$i];
		
		$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
		$bbs_row = $db->row($sql);

		$sql = " Select * From df_site_bbs_files Where bbsidx='".$idx."' ";
		$row = $db->query($sql);
		
		$file_cnt = 0;
		for($ii=0; $ii<count($row); $ii++){
			@unlink("../../userfiles/".$code."/".$row[$ii]['upfile']);
			$file_cnt++;
		}

		$sql = " Delete From df_site_bbs_files Where bbsidx='".$idx."' ";
		$db->query($sql);

		$sql = " Delete From df_site_bbs Where idx='".$idx."' ";
		$db->query($sql);

		$i++;
	}

	complete("게시물이 삭제되었습니다.","bbs_list.php?page=$page&$param");
	
}

//====================================================================================================
//	삭제
//====================================================================================================
else if($mode == "delete"){
	
	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$bbs_row = $db->row($sql);
	
	$sql = " Select * From df_site_bbs_files Where bbsidx='".$idx."' ";
	$row = $db->query($sql);
	
	$file_cnt = 0;
	for($ii=0; $ii<count($row); $ii++){
		@unlink("../../userfiles/".$code."/".$row[$ii]['upfile']);
		$file_cnt++;
	}

	$sql = " Delete From df_site_bbs_files Where bbsidx='".$idx."' ";
	$db->query($sql);

	$sql = " Delete From df_site_bbs Where idx='".$idx."' ";
	$db->query($sql);
	
	if($code == "review"){
		// 포인트 차감  :  일반리뷰(500), 포토리뷰(2000), 포토리뷰+SNS링크(3000)
		$reservemsg	= "[관리자 리뷰삭제] 포인트 차감";
		if($file_cnt > 0 && $bbs_row['sns_link'])
			$reserve	= 3000;
		else if($file_cnt > 0)
			$reserve	= 2000;
		else
			$reserve	= 500;
		
		$sql  = "";
		$sql .= "	Insert into df_shop_reserve ";
		$sql .= "	Set		memid		= '".$bbs_row['memid']."' ";
		$sql .= "	,		reservemsg	= '".$reservemsg."' ";
		$sql .= "	,		reserve		= '".(-1)*$reserve."' ";
		$sql .= "	,		orderid		= '".$bbs_row['orderid']."' ";
		$sql .= "	,		prdcode		= '".$bbs_row['prdcode']."' ";
		$sql .= "	,		wdate		= NOW() ";
		$db->query($sql);
	}

	complete("게시물이 삭제되었습니다.","bbs_list.php?page=$page&$param");
	
}

//====================================================================================================
//	첨부파일 삭제
//====================================================================================================
else if($mode == "delimg"){
	
	$sql = " Select * From df_site_bbs_files Where idx='".$idx."' ";
	$row = $db->row($sql);
	
	@unlink("../../userfiles/".$code."/".$row['upfile']);
	
	$sql = " Delete From df_site_bbs_files Where idx='".$idx."' ";
	$db->query($sql);
	
	echo "Y";
}

//====================================================================================================
//	썸네일 삭제
//====================================================================================================
else if($mode == "delimg_thumb"){
	
	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$row = $db->row($sql);
	
	@unlink("../../userfiles/".$code."/".$row['upfile']);
	
	//$sql = " Delete From df_site_bbs Where idx='".$idx."' ";
	// update
	$sql = " Update df_site_bbs Set upfile='' Where idx='".$idx."' ";
	$db->query($sql);
	
	echo "Y";
}


//====================================================================================================
//	삭제
//====================================================================================================
else if($mode == "deletereply"){
	
	//	comment 테이블에서 추출
	$sql = " Select idx, prino, depno, memid, bbsidx, code From df_site_comment Where  idx='".$idx."' ";
	$row = $db->row($sql);
	$bbsidx = $row['bbsidx'];
	$prino = $row['prino'];


	$sql = " Update df_site_comment set prino=prino-1 Where code='".$code."' And bbsidx='".$bbsidx."' And prino >= '$prino' ";
	$db->query($sql);

	// comment 테이블에서 삭제
	$sql = " Delete From df_site_comment Where idx='".$idx."' ";
	$db->query($sql);

	//	comment테이블에서 댓글에 달린 답글 모두 삭제
	$sql = " Delete From df_site_comment Where parno='".$idx."' ";
	$db->query($sql);

	complete("댓글을 삭제했습니다.","bbs_list.php?page=$page&$param");
	
}
?>