<?
include $_SERVER["DOCUMENT_ROOT"]."/inc/global.inc"; 			// DB컨넥션, 접속자 파악
include $_SERVER["DOCUMENT_ROOT"]."/inc/util_lib.inc";		// 유틸라이브러리

$sessid = $_SESSION['userid'];
$sesskey = session_id();

$que = " SELECT sesskey, expiry, sessid, remoteip FROM Session WHERE sessid='".$sessid."' AND sesskey='".$sesskey."' ";
$result = mysql_query($que) or error(mysql_errno());
$row = mysql_fetch_object($result);
$row_cnt = mysql_num_rows($result);

if ( $row_cnt ) {
    $code = "success";
} else {
    $code= "failed";
}

$ret = array();
$ret["code"] = $code;

if( $row_cnt ) {
	$DBsesskey = Trim($row->sesskey);
	$sesskey = Trim($sesskey);

	if ( $DBsesskey == $sesskey ) { $sess_same = "success"; }
	else { $sess_same = "failed"; }
	
	$ret["sC"] = $sess_same;
	$ret["sK"] = $DBsesskey;
	$ret["sP"] = $row->remoteip;
}
else{
	$ret["sC"] = $code;
	$ret["sP"] = $row->remoteip;
}

echo json_encode($ret);
?>
