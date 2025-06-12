<?
header("Content-Type: text/html; charset=UTF-8");
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/config.inc";


// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "searchbae=$searchbae&searchopt=$searchopt&searchkey=$searchkey&s_birthday=$s_birthday&s_memorial=$s_memorial&s_age=$s_age";
$param .= "&s_address=$s_address&s_job=$s_job&s_marriage=$s_marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day&page=$page";
//------------------------------------------------------------------------------------------------------------------------------------


// 회원등록
if($mode == "insert"){
	
	// recom_id 중복 여부 확인
	$sql = " Select * From df_site_eba Where recom_id='".$recom_id."' ";
	$result = mysql_query($sql) or error(mysql_error());
	$total = mysql_num_rows($result);
	
	if($total > 0){
		comalert("이미 사용중인 EBA 닉네임 입니다.","");
		exit();
	}
	else{
		$hphone = $hphone."-".$hphone2."-".$hphone3;
		
		if(!$per_ba || $per_ba=="") $per_ba = "0";
		if(!$per_m || $per_m=="") $per_m = "0";

		$sql  = "";
		$sql .= "	Insert Into df_site_eba ";
		$sql .= "	Set	id = '$id' ";
		$sql .= "	,		passwd = '$passwd' ";
		$sql .= "	,		name = '$name' ";
		$sql .= "	,		email = '$email' ";
		$sql .= "	,		hphone = '$hphone' ";
		$sql .= "	,		post = '$post' ";
		$sql .= "	,		address = '$address' ";
		$sql .= "	,		address2 = '$address2' ";
		$sql .= "	,		reemail = '$reemail' ";
		$sql .= "	,		resms = '$resms' ";
		$sql .= "	,		recom_id = '$recom_id' ";
		$sql .= "	,		bank_code = '$bank_code' ";
		$sql .= "	,		bank_deposit = '$bank_deposit' ";
		$sql .= "	,		bank_account = '$bank_account' ";
		$sql .= "	,		upfile = '$upfile_tmp' ";
		$sql .= "	,		visit = '1' ";
		$sql .= "	,		visit_time = Now() ";
		$sql .= "	,		comment = '$comment' ";
		$sql .= "	,		biz_no = '" .$biz_no1.$biz_no2.$biz_no3. "' ";
		$sql .= "	,		biz_company = '" .$biz_company. "' ";
		$sql .= "	,		biz_owner = '" .$biz_owner. "' ";
		$sql .= "	,		biz_post = '" .$biz_post. "' ";
		$sql .= "	,		biz_address = '" .$biz_address. "' ";
		$sql .= "	,		biz_address2 = '" .$biz_address2. "' ";
		$sql .= "	,		biz_uptae = '" .$biz_uptae. "' ";
		$sql .= "	,		biz_jmok = '" .$biz_jmok. "' ";
		$sql .= "	,		per_ba = '" .$per_ba. "' ";
		$sql .= "	,		per_m = '" .$per_m. "' ";
		$sql .= "	,		wdate = Now() ";
		
		$result = mysql_query($sql) or error(mysql_error());

		complete("EBA를 등록하였습니다.","set_eba.php?$param");
	}
   
}

// 회원정보 수정
else if($mode == "update"){
 
	if(!$per_ba || $per_ba=="") $per_ba = "0";
	if(!$per_m || $per_m=="") $per_m = "0";

	// 회원정보
	$sql = "select * from df_site_eba where id = '$id'";
	$result = mysql_query($sql) or error(mysql_error());
	$meminfo = mysql_fetch_object($result);
	
	// EBA 닉네임을 변경했을 경우
	if($meminfo->recom_id != $recom_id){
		// recom_id 중복 여부 확인
		$sql = " Select * From df_site_eba Where recom_id='".$recom_id."' ";
		$result = mysql_query($sql) or error(mysql_error());
		$total = mysql_num_rows($result);
		
		if($total > 0){
			comalert("이미 사용중인 EBA 닉네임 입니다.","");
			exit();
		}
	}

	$hphone = $hphone."-".$hphone2."-".$hphone3;

	$sql  = "";
	$sql .= "	Update	df_site_eba ";
	$sql .= "	Set		name = '$name' ";
	$sql .= "	,			email = '$email' ";
	$sql .= "	,			hphone = '$hphone' ";
	$sql .= "	,			post = '$post' ";
	$sql .= "	,			address = '$address' ";
	$sql .= "	,			address2 = '$address2' ";
	$sql .= "	,			reemail = '$reemail' ";
	$sql .= "	,			resms = '$resms' ";
	$sql .= "	,			comment = '$comment' ";
	if($passwd) {
		$sql .= "	,		passwd = '$passwd' ";
	}
	$sql .= "	,			recom_id = '$recom_id' ";
	$sql .= "	,			bank_code = '$bank_code' ";
	$sql .= "	,			bank_deposit = '$bank_deposit' ";
	$sql .= "	,			bank_account = '$bank_account' ";
	$sql .= "	,			biz_no = '" .$biz_no1.$biz_no2.$biz_no3. "' ";
	$sql .= "	,			biz_company = '" .$biz_company. "' ";
	$sql .= "	,			biz_owner = '" .$biz_owner. "' ";
	$sql .= "	,			biz_post = '" .$biz_post. "' ";
	$sql .= "	,			biz_address = '" .$biz_address. "' ";
	$sql .= "	,			biz_address2 = '" .$biz_address2. "' ";
	$sql .= "	,			biz_uptae = '" .$biz_uptae. "' ";
	$sql .= "	,			biz_jmok = '" .$biz_jmok. "' ";
	$sql .= "	,			per_ba = '" .$per_ba. "' ";
	$sql .= "	,			per_m = '" .$per_m. "' ";
	$sql .= "	Where	id = '$id' ";
         
	$result = mysql_query($sql) or error(mysql_error());
	
	// EBA 닉네임 변경했을 경우 BA 회원들의 EBA 닉네임을 변경
	if($result && $meminfo->recom_id != $recom_id){
		$sql = " Update df_site_spe_member Set eba_id='".$recom_id."' Where eba_id='".$meminfo->recom_id."' ";
		$result = mysql_query($sql) or error(mysql_error());
	}

	complete("EBA정보를 수정하였습니다.","set_eba_input.php?mode=$mode&id=$id&$param");
   
}

// 회원 삭제
else if($mode == "delete"){

	// BA 테이블에서 지움
	$sql = "update df_site_spe_member set bae_id='' where bae_id = '$mem_id'";
	$result = mysql_query($sql) or error(mysql_error());

	// 회원테이블에서 삭제
	$sql = "delete from df_site_eba where id = '$mem_id'";
	$result = mysql_query($sql) or error(mysql_error());

	complete("EBA를 삭제하였습니다.","set_eba.php?$param");

}

?>