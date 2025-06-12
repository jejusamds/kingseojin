<?
include $_SERVER['DOCUMENT_ROOT']."/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT']."/inc/util_lib.inc";
include $_SERVER["DOCUMENT_ROOT"]."/inc/bbs_info.inc"; 	 	// 게시판 정보


$param = "code=".$code."&s_opt=".$s_opt."&s_key=".$s_key;

// 회원권한
/*
$level_info = level_info();
if($_SESSION['userlevel']=="0"){
$level_info[$_SESSION['userlevel']]['level'] = "1";
}
$mem_level = $level_info[$_SESSION["userlevel"]][level];
$wpermi = $level_info[$bbs_info['wpermi']][level];
$apermi = $level_info[$bbs_info['apermi']][level];
*/


//=========================================================================================================
//	글 작성
//=========================================================================================================
if($mode == "write"){
	
	if($code == "CenterQna" || $code == "CenterNew"){
	}else{
		if($wpermi < $mem_level) error("글작성 권한이 없습니다.");
	}
	if($memid == "") $memid = $_SESSION['userid'];
	if($wdate == "") $wdate = date('Y-m-d H:i:s');
	if($privacy == "") $privacy = "N";
	if($event_win == "") $event_win = "N";
	
	if($code == "CenterQna" || $code == "CenterNew"){
	}else{
		$tel = $tel1 ."-". $tel2 ."-". $tel3;
	}
	$email = $email_id ."@". $email_add;
	
	$sql = " Select IFNULL(MAX(prino),0) From df_site_bbs Where code='".$code."' ";
	$prino = $db->single($sql);
	$prino++;
	
	$sql  = "";
	$sql .= "	Insert into df_site_bbs ";
	$sql .= "	Set		code			= '".$code."' ";
	$sql .= "	, 		parno			= 0 ";
	$sql .= "	, 		prino			= '".$prino."' ";
	$sql .= "	, 		depno			= 0 ";
	$sql .= "	, 		notice			= 'N' ";
	$sql .= "	, 		grp				= '".$grp."' ";
	$sql .= "	, 		memid			= '".$memid."' ";
	$sql .= "	, 		name			= '".$name."' ";
	$sql .= "	, 		tel				= '".$tel."' ";
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
	$db->query($sql);
	
	$bbsidx = $db->lastInsertId();
	
	include "./upfile.inc";
	
	echo "<script>document.location='bbs_list.php?page=$page&s_grp=$s_grp&$param';</script>";		

}

//=========================================================================================================
//	글 수정
//=========================================================================================================
else if($mode == "modify"){
	
	if($wpermi < $mem_level) error("글수정 권한이 없습니다.");
	
	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$row = $db->row($sql);
	
	//if($row['memid'] != $_SESSION['userid']) error("해당게시물을 수정할 수 없습니다.");

	if($privacy == "") $privacy = "N";
	if($event_win == "") $event_win = "N";
	if($code == "CenterQna" || $code == "CenterNew"){
	}else{
		$tel = $tel1 ."-". $tel2 ."-". $tel3;
	}
	$email = $email_id ."@". $email_add;

	$sql  = "";
	$sql .= "	Update	df_site_bbs ";
	$sql .= "	Set		grp			= '".$grp."' ";
	$sql .= "	, 		name		= '".$name."' ";
    $sql .= "	, 		passwd		= '".$passwd."' ";
	$sql .= "	, 		tel			= '".$tel."' ";
	$sql .= "	, 		email		= '".$email."' ";
	$sql .= "	, 		subject		= '".$subject."' ";
	$sql .= "	, 		content		= '".$content."' ";
	$sql .= "	, 		ctype		= '".$ctype."' ";
	$sql .= "	, 		privacy		= '".$privacy."' ";
	$sql .= "	Where	idx			= '".$idx."' ";
	$db->query($sql);

	$bbsidx = $idx;
	
	include "./upfile.inc";
	
	comalert("수정되었습니다.","bbs_view.php?idx=$idx&page=$page&s_grp=$s_grp&$param");
}

//=========================================================================================================
//	글 삭제
//=========================================================================================================
else if($mode == "delete"){
	
	if($wpermi < $mem_level) error("글삭제 권한이 없습니다.");

	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$row = $db->row($sql);

	//if($row['memid'] != $_SESSION['userid']) error("해당게시물을 삭제할 수 없습니다.");

	$sql = " Select * From df_site_bbs_files Where bbsidx='".$idx."' ";
	$row = $db->query($sql);
	for($ii=0; $ii<count($row); $ii++){
		@unlink("../userfiles/".$code."/".$row[$ii]['upfile']);
	}

	$sql = " Delete From df_site_bbs_files Where bbsidx='".$idx."' ";
	$db->query($sql);

	$sql = " Delete From df_site_bbs Where idx='".$idx."' ";
	$db->query($sql);

	comalert("삭제되었습니다.","bbs_list.php?page=$page&$param");

}

//=========================================================================================================
//	글 인증
//=========================================================================================================
else if($mode == "auth"){
	
	if($passwd == "") error("비밀번호를 입력하세요");
	
	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$row = $db->row($sql);
	$idx_passwd = $row['passwd'];
	
	$sql = " Select passwd From df_site_bbs Where idx='".$row['parno']."' ";
	$row = $db->row($sql);
	
	if($smode=="delete")
		$par_passwd = $idx_passwd;	//삭제일때 원글 비밀번호로 답글 삭제할 수 없도록
	else
		$par_passwd = $row['passwd'];
	
	if($idx_passwd == $passwd || $par_passwd == $passwd){
		// 인증세션 설정
		$_SESSION['bbsauth_idx'] = $idx;
		$_SESSION['bbsauth_pw'] = $passwd;

        $bbs_input_name = $code == 'contact' ? 'write' : 'input';

		if($smode == "view")
			$next_url = "bbs_view.php?idx=$idx&page=$page&s_grp=$s_grp&$param";
		else if($smode == "modify")
			$next_url = "bbs_".$bbs_input_name.".php?mode=$smode&idx=$idx&page=$page&s_grp=$s_grp&$param";
		else if($smode == "delete")
			$next_url = "bbs_save.php?mode=$smode&idx=$idx&page=$page&s_grp=$s_grp&$param";

		echo "<script>document.location='$next_url';</script>";
	}
	else{
		error("비밀번호가 일치하지 않습니다.");
	}

}


//========================================================
//	댓글 작성
//========================================================
else if($mode == "reply"){
	$param .= "&idx=$idx";
	$content = addslashes($content);



	$sql = " Select idx, prino, depno From df_site_comment Where code='".$code."' and bbsidx='".$idx."' Order by prino desc ";
	$row = $db->row($sql);
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	$sql = " Update df_site_comment set prino = prino+1 where code='".$code."' And depno=0  ";
	$db->query($sql);

	$sql  = "";
	$sql .= "	Insert Into df_site_comment ";
	$sql .= "	Set		code	=	'".$code."' ";
	$sql .= "	,		bbsidx	=	'".$idx."' ";
	$sql .= "	,		name	=	'".$name."' ";
	$sql .= "	,		memid	=	'".$memid."' ";
	$sql .= "	,		prino	=	0 ";
	$sql .= "	,		parno	=	'".$parno."' ";
	$sql .= "	,		depno	=	0 ";
	$sql .= "	,		notice	=	'N' ";
	$sql .= "	,		grp		=	'".$grp."' ";
	$sql .= "	,		privacy =	'".$comment_type."' ";
	$sql .= "	, 		email	=	'".$email."' ";
	$sql .= "	,		passwd	=	'".$passwd."' ";
	$sql .= "	,		subject =	'".$subject."' ";
	$sql .= "	,		content =	'".$content."' ";
	$sql .= "	, 		count	=	0 ";
	$sql .= "	, 		recom	=	0 ";
	$sql .= "	, 		ip		=	'".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "	,		wdate	=	NOW() ";
	$db->query($sql);

	comalert('댓글을 작성하였습니다.',"bbs_view.php?page=$page&s_grp=$s_grp&$param");
}

//========================================================
//	댓글의 댓글 작성
//========================================================
else if($mode == "re_reply"){
	$param .= "&idx=$r_idx";
	$content = addslashes($content);

	$sql = " Select idx, bbsidx, prino, depno From df_site_comment Where code='".$code."' and idx='".$idx."' ";
	
	$row = $db->row($sql);
	$parno = $row['idx'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	$sql = " Update df_site_comment set prino = prino+1 where code='".$code."' And parno='".$parno."' And prino >= '1' ";
	$db->query($sql);

	$sql  = "";
	$sql .= "	Insert Into df_site_comment ";
	$sql .= "	Set		code	=	'".$code."' ";
	$sql .= "	,		name	=	'".$name."' ";
	$sql .= "	,		memid	=	'".$memid."' ";
	$sql .= "	,		prino	=	'".$prino."' ";
	$sql .= "	,		parno	=	'".$parno."' ";
	$sql .= "	,		depno	=	'".$depno."' ";
	$sql .= "	,		notice	=	'N' ";
	$sql .= "	,		grp		=	'".$grp."' ";
	$sql .= "	,		privacy =	'".$comment_type."' ";
	$sql .= "	, 		email	=	'".$email."' ";
	$sql .= "	,		passwd	=	'".$passwd."' ";
	$sql .= "	,		subject =	'".$subject."' ";
	$sql .= "	,		content =	'".$content."' ";
	$sql .= "	, 		count	=	0 ";
	$sql .= "	, 		recom	=	0 ";
	$sql .= "	, 		ip		=	'".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "	,		wdate	=	NOW() ";
	
	$db->query($sql);

	comalert('댓글을 작성하였습니다.',"bbs_view.php?page=$page&s_grp=$s_grp&$param");
}


//========================================================
//	댓글 수정
//========================================================
else if($mode == "reply_update"){
	$param .= "&idx=$r_idx";
	$content = addslashes($content);
	
	$sql = " Select * From df_site_comment Where code='".$code."' And idx='".$idx."' ";
	$row = $db->row($sql);
	
	if($_SESSION['userid'] != $row['memid']){
		error("작성자 본인만 수정할 수 있습니다.");
		exit();
	}
	
	$sql  = "";
	$sql .= "	Update	df_site_comment ";
	$sql .= "	Set		content		= '".$content."' ";
	$sql .= "	Where	code		= '".$code."' ";
	$sql .= "	And		idx		= '".$idx."' ";
	$db->query($sql);

	comalert("댓글을 수정했습니다.","bbs_view.php?page=$page&s_grp=$s_grp&$param");
}

//========================================================
//	댓글의 댓글 수정
//========================================================
else if($mode == "comment_update"){
	$param .= "&idx=$r_idx";
	$content = addslashes($content);
	
	$sql = " Select * From df_site_comment Where code='".$code."' And idx='".$idx."' ";
	$row = $db->row($sql);
	
	if($_SESSION['userid'] != $row['memid']){
		error("작성자 본인만 수정할 수 있습니다.");
		exit();
	}
	

	$sql  = "";
	$sql .= "	Update	df_site_comment ";
	$sql .= "	Set		content		= '".$content."' ";
	$sql .= "	Where	code		= '".$code."' ";
	$sql .= "	And		idx			= '".$idx."' ";
	$db->query($sql);

	comalert("댓글을 수정했습니다.","bbs_view.php?page=$page&s_grp=$s_grp&$param");
}


//========================================================
//	댓글 삭제
//========================================================
else if($mode == "reply_del"){
	$param .= "&idx=$r_idx";
	
	//	comment 테이블에서 추출
	$sql = " Select idx, prino, depno, memid, bbsidx, code From df_site_comment Where  idx='".$idx."' ";
	$row = $db->row($sql);
	$bbsidx = $row['bbsidx'];
	$prino = $row['prino'];

	if($_SESSION['userid'] != $row['memid']){
		error("작성자 본인만 삭제할 수 있습니다.");
		exit();
	}

	$sql = " Update df_site_comment set prino=prino-1 Where code='".$code."' And bbsidx='".$bbsidx."' And prino >= '$prino' ";
	$db->query($sql);

	// comment 테이블에서 삭제
	$sql = " Delete From df_site_comment Where idx='".$idx."' ";
	$db->query($sql);

	//	comment테이블에서 댓글에 달린 답글 모두 삭제
	$sql = " Delete From df_site_comment Where parno='".$idx."' ";
	$db->query($sql);
	
	comalert('댓글을 삭제하였습니다.',"bbs_view.php?page=$page&s_grp=$s_grp&$param");
}


//========================================================
//	댓글의 댓글 삭제
//========================================================
else if($mode == "comment_del"){
	$param .= "&idx=$r_idx";
	
	//	comment테이블에서 추출
	$sql = " Select idx, parno, prino, depno, code, memid From df_site_comment Where idx='".$idx."' ";
	$row = $db->row($sql);
	$prino = $row['prino'];
	$parno = $row['parno'];
	
	
	
	if($_SESSION['userid'] != $row['memid']){
		error("작성자 본인만 삭제할 수 있습니다.");
		exit();
	}


	$sql = " Update df_site_comment set prino=prino-1 Where code='".$code."' And parno='".$parno."' And prino > '$prino' And prino != '0' ";
	$db->query($sql);

	$sql = " Delete From df_site_comment Where idx='".$idx."' ";
	$db->query($sql);

	comalert('답글을 삭제하였습니다.',"bbs_view.php?page=$page&s_grp=$s_grp&$param");
}

?>