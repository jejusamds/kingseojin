<?
$time = time();

// 카운트 작동
$yy = date("y");
$mm = date("m");
$dd = date("d");
$hh = date("H");
$ww = date("w");

$pm_val = "P";
if ( $detect->isMobile() || $detect->isTablet() ) $pm_val = "M";

$code = $_REQUEST['code'] ? $_REQUEST['code'] : $_SESSION['code'];


if(!isset($_COOKIE['df_count_flag'])) {
	// ip에 대한 시간별 카운트
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$domain = $ip_address;  // gethostbyaddr 제거
	$referer = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 250) : '';

	$query = "
		INSERT INTO df_counter_ip (
			ci_code, ci_pm, ci_ip, ci_domain, ci_yy, ci_mm, ci_dd, ci_ww, ci_hh, ci_hit, ci_uptime
		) VALUES (
			'$code', '$pm_val', '$ip_address', '$domain', '$yy', '$mm', '$dd', '$ww', '$hh', 1, UNIX_TIMESTAMP()
		)
		ON DUPLICATE KEY UPDATE 
			ci_hit = ci_hit + 1, 
			ci_uptime = UNIX_TIMESTAMP()
	";
	$db->query($query);

	// 이전url이 어디인지 체크
	$referer = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 250) : '';

	$query = "
		INSERT INTO df_counter_url (
			cu_code, cu_pm, cu_url, cu_hit, cu_uptime
		) VALUES (
			'$code', '$pm_val', '$referer', 1, UNIX_TIMESTAMP()
		)
		ON DUPLICATE KEY UPDATE 
			cu_hit = cu_hit + 1,
			cu_uptime = UNIX_TIMESTAMP()
	";
	$db->query($query);

	setcookie("df_count_flag", 1, 0, "/");
	$DESPLAY_COUNT_FLAG = true;

	// 브라우져에 대한 카운트 (현재 사용 X)
	// $query = "select * from df_counter_browser where cb_code='".$code."' and cb_pm='$pm_val' and cb_browse='".$_SERVER['HTTP_USER_AGENT']."'";
	// $affected_rows = $db->query($query);
	// if(count($affected_rows) > 0) {
		// $query = "update df_counter_browser set cb_hit=cb_hit+1, cb_uptime=unix_timestamp() where cb_code='".$code."' and cb_pm='$pm_val' and cb_browse='".$_SERVER['HTTP_USER_AGENT']."'";
	// }
	// else {
		// $query = "insert into df_counter_browser set cb_code='".$code."', cb_pm='$pm_val', cb_browse='".$_SERVER['HTTP_USER_AGENT']."', cb_hit=1, cb_uptime=unix_timestamp()";
	// }
	//$db->query($query);

}


// @session_start();
// $query = "delete from df_counter_now where pm='$pm_val' and uptime<$time-120";
// $db->query($query);

// $query = "replace into df_counter_now set session_id='" . session_id() . "', pm='$pm_val', ip='$_SERVER[REMOTE_ADDR]', uptime=unix_timestamp()";
// $db->query($query);

///////////////////////////////////////////////////여기까지는 출력정보가 없음.//////////////////////////////////////////////////////





/*
///////////////////////////////////////////////////여기부터는 헤드 이후에 기록할것/////////////////////////////////////////////////
if($DESPLAY_COUNT_FLAG == true) {

	setcookie("df_count_flag_display", 1, 0, "/");
	$len = strlen($_SERVER[DOCUMENT_ROOT]);
	$INIT_DISPLAY = substr(dirname(__FILE__), $len) . "/init_display.php";
	echo "<script>document.write(\"<img border='0' width='0' height='0' src='{$INIT_DISPLAY}?w=\"+screen.width+\"&h=\"+screen.height+\"'>\");</script>";
}
*/
?>