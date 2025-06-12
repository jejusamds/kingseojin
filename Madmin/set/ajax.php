<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/global.inc"; 			// DB컨넥션, 접속자 파악
include $_SERVER['DOCUMENT_ROOT']."/inc/util_lib.inc";


// 시도 선택
if($mode == "sido"){
	
	$sql  = "";
	$sql .= "	Select	gugun, Min(zipcode) As min_zip, Max(zipcode) As max_zip ";
	$sql .= "	From	df_site_zipcode ";
	$sql .= "	Where	sido = '".rawurldecode($val)."' ";
	$sql .= "	Group by	gugun ";
	$sql .= "	Order by	gugun Asc ";
	$result = mysql_query($sql) or error(mysql_error());
	
	$no = 0;
	while($row = mysql_fetch_object($result)){
		$data[$no] = array(
			"gugun" => $row->gugun,
			"min" => $row->min_zip,
			"max" => $row->max_zip
		);
		$no++;
	}
	
	header('Content-Type: application/json');
	echo json_encode($data);

}

// 구군 선택
else if($mode == "gugun"){
	
	$sql  = "";
	$sql .= "	Select	if(ifnull(dong_law,'')='',myeon,dong_law) As dong, Min(zipcode) As min_zip, Max(zipcode) As max_zip ";
	$sql .= "	From	df_site_zipcode ";
	$sql .= "	Where	sido = '".rawurldecode($sido)."' ";
	$sql .= "	And		gugun = '".rawurldecode($val)."' ";
	$sql .= "	Group by	if(ifnull(dong_law,'')='',myeon,dong_law) ";
	$sql .= "	Order by	if(ifnull(dong_law,'')='',myeon,dong_law) Asc ";
	$result = mysql_query($sql) or error(mysql_error());
	
	$no = 0;
	while($row = mysql_fetch_object($result)){
		$data[$no] = array(
			"dong" => $row->dong,
			"min" => $row->min_zip,
			"max" => $row->max_zip
		);
		$no++;
	}
	
	header('Content-Type: application/json');
	echo json_encode($data);

}
?>