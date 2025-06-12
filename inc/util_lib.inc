<?
//======================================================================================
//	SQL Injection 관련
//======================================================================================
function SQL_Injection($get_Str)
{
	//return @preg_replace("/( select| union| insert| update| delete| drop|\"|\'|#|\/\*|\*\/|\\\|\;)/i","", $get_Str); 
	return @preg_replace("/( select| union| insert| update| delete| drop|#|\/\*|\*\/|\\\|\;)/i", "", $get_Str);
}

//======================================================================================
//	Cross Site Scripting 관련 함수
//======================================================================================
function RemoveXSS($val)
{
	// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	// this prevents some character re-spacing such as <java\0script>
	// note that you have to handle splits with \n, \r, and \t later since they *are*
	// allowed in some inputs
	$val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

	// straight replacements, the user should never need these since they're normal characters
	// this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&
	// #X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
	$search  = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';

	for ($i = 0; $i < strlen($search); $i++) {
		// ;? matches the ;, which is optional
		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		// &#x0040 @ search for the hex values
		$val = preg_replace('/(&#[x|X]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val);
		// with a ;
		// &#00064 @ 0{0,7} matches '0' zero to seven times
		$val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
	}

	// now the only remaining whitespace attacks are \t, \n, and \r
	$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	$ra = array_merge($ra1, $ra2);

	$found = true; // keep replacing as long as the previous round replaced something 
	while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
					$pattern .= '|(&#0{0,8}([9][10][13]);?)?';
					$pattern .= ')?';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
			$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = false;
			}
		}
	}
	return $val;
}

function AESEncrypt($sValue, $sSecretKey)
{
	return rtrim(
		base64_encode(
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_256,
				$sSecretKey,
				$sValue,
				MCRYPT_MODE_ECB,
				mcrypt_create_iv(
					mcrypt_get_iv_size(
						MCRYPT_RIJNDAEL_256,
						MCRYPT_MODE_ECB
					),
					MCRYPT_RAND
				)
			)
		),
		"\0"
	);
}

function AESDecrypt($sValue, $sSecretKey)
{
	return rtrim(
		mcrypt_decrypt(
			MCRYPT_RIJNDAEL_256,
			$sSecretKey,
			base64_decode($sValue),
			MCRYPT_MODE_ECB,
			mcrypt_create_iv(
				mcrypt_get_iv_size(
					MCRYPT_RIJNDAEL_256,
					MCRYPT_MODE_ECB
				),
				MCRYPT_RAND
			)
		),
		"\0"
	);
}

// 랜덤 문자열 생성
function randomStr($length)
{
	$chars = "0123456789";
	return substr(str_shuffle($chars), 0, $length);
}

function sendMessage($token, $link, $url, $msg)
{

	$server_key = 'AAAA7TmZv7U:APA91bEVPahcl9ZgIwaJlS0tLn15fUEK48cU112rUoBlTMJM6DSHh4i9tKq5acJ0mVInfy9n95G6xmQbVDzh9MwxsXAjAk4vhZh3eJk0CGTPIowUYI4UjsiHXqflD7fYv4ogrUozLuRx';

	$cmd  = "";
	$cmd .= "curl -X POST --header 'Authorization: key=" . $server_key . "' --header 'Content-Type: application/json' https://fcm.googleapis.com/fcm/send -d ";
	$cmd .= "'{\"registration_ids\":[";
	if (is_array($token)) {
		$str = "";
		for ($i = 0; $i < count($token); $i++) {
			if ($str) $str .= ",\"" . $token[$i] . "\"";
			else $str = "\"" . $token[$i] . "\"";
		}
		$cmd .= $str;
	} else {
		$cmd .= "\"" . $token . "\"";
	}
	$cmd .= "],";
	if ($link == "Y") {
		$cmd .= "\"notification\":{\"body\":\"" . $msg . "\"},";
		$cmd .= "\"data\":{\"link\":\"Y\",\"url\":\"" . $url . "\"},";
		$cmd .= "\"priority\":\"high\"}'";
	} else {
		$cmd .= "\"notification\":{\"body\":\"" . $msg . "\"},";
		$cmd .= "\"data\":{\"link\":\"N\",\"url\":\"\"},";
		$cmd .= "\"priority\":\"high\"}'";
	}
	exec($cmd);
}

function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
{
	/*
		$interval can be:
		yyyy - Number of full years
		q - Number of full quarters
		m - Number of full months
		y - Difference between day numbers
			(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		d - Number of full days
		w - Number of full weekdays
		ww - Number of full weeks
		h - Number of full hours
		n - Number of full minutes
		s - Number of full seconds (default)
		*/

	if (!$using_timestamps) {
		$datefrom = strtotime($datefrom, 0);
		$dateto = strtotime($dateto, 0);
	}
	$difference = $dateto - $datefrom; // Difference in seconds

	switch ($interval) {

		case 'yyyy': // Number of full years

			$years_difference = floor($difference / 31536000);
			if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {
				$years_difference--;
			}
			if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {
				$years_difference++;
			}
			$datediff = $years_difference;
			break;

		case "q": // Number of full quarters

			$quarters_difference = floor($difference / 8035200);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$quarters_difference--;
			$datediff = $quarters_difference;
			break;

		case "m": // Number of full months

			$months_difference = floor($difference / 2678400);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$months_difference--;
			$datediff = $months_difference;
			break;

		case 'y': // Difference between day numbers

			$datediff = date("z", $dateto) - date("z", $datefrom);
			break;

		case "d": // Number of full days

			$datediff = floor($difference / 86400);
			break;

		case "w": // Number of full weekdays

			$days_difference = floor($difference / 86400);
			$weeks_difference = floor($days_difference / 7); // Complete weeks
			$first_day = date("w", $datefrom);
			$days_remainder = floor($days_difference % 7);
			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
			if ($odd_days > 7) { // Sunday
				$days_remainder--;
			}
			if ($odd_days > 6) { // Saturday
				$days_remainder--;
			}
			$datediff = ($weeks_difference * 5) + $days_remainder;
			break;

		case "ww": // Number of full weeks

			$datediff = floor($difference / 604800);
			break;

		case "h": // Number of full hours

			$datediff = floor($difference / 3600);
			break;

		case "n": // Number of full minutes

			$datediff = floor($difference / 60);
			break;

		default: // Number of full seconds (default)

			$datediff = $difference;
			break;
	}

	return $datediff;
}


//======================================================================================
// 적립금 계산 (회원등급별 추가적립금)
//======================================================================================
function getReserveSellprice($ori_sellprice, $ori_reserve)
{
	if ($_SESSION[userid]) {
		$sql = " Select discount From df_site_level Where idx='" . $_SESSION['userlevel'] . "' ";
		$result = mysql_query($sql) or error(mysql_error());
		$row = mysql_fetch_object($result);

		$new_reserve = $ori_sellprice * ($row->discount / 100);
		return $ori_reserve + $new_reserve;
	} else {
		return $ori_reserve;
	}
}

//======================================================================================
// 2017.12.07	적립금 계산 - 쿠폰,적립금 사용액 제외한 금액으로 계산
//======================================================================================
function getReserveTotalprice($total_price, $reserve_buy)
{
	if ($_SESSION['userid']) {
		$sql = " Select discount From df_site_level Where idx='" . $_SESSION['userlevel'] . "' ";
		$result = mysql_query($sql) or error(mysql_error());
		$row = mysql_fetch_object($result);

		$reserve = $total_price * (($reserve_buy + $row->discount) / 100);
		return $reserve;
	} else {
		return 0;
	}
}

function generateRandomPassword($length = 8, $strength = 0)
{
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

// 네이버 번역 API
//	(ko:한국어, en:영어, ja:일본어, zh-CN:중국어(간체))
function xTranslate($source, $text)
{
	$client_id = "UnnU8aUrOMWGdxhdamQ5";
	$client_secret = "QsnuYQSULG";

	$url = "https://openapi.naver.com/v1/language/translate";
	$target = "ko";
	$data = "source=" . $source . "&target=" . $target . "&text=" . $text;
	$is_post = true;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$headers = array();
	$headers[] = "X-Naver-Client-Id: " . $client_id;
	$headers[] = "X-Naver-Client-Secret: " . $client_secret;

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response = curl_exec($ch);
	curl_close($ch);

	$jsonParser = json_decode($response);
	$translatedResult = $jsonParser->message->result->translatedText . PHP_EOL;

	return $translatedResult;
}


// YAHOO 환율 api
//	$ctype  :  CNYKRW, JPYKRW, USDKRW
function xChange($ctype)
{
	$exchange_url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22" . $ctype . "%22)&format=json&env=store://datatables.org/alltableswithkeys&callback=";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $exchange_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
	$rt = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($rt);

	$ret = $data->query->results->rate->Ask;
	return $ret;
}

function _mb_cut_str($str, $limit, $after_str, $char_set)
{
	$_val = $str;
	$_val = mb_strcut($_val, 0, $limit, $char_set);
	if (strlen($str) > $limit) {
		$_val .= $after_str;
	}
	return $_val;
}

// 문자열 끊기 (이상의 길이일때는 ... 로 표시)
function cut_str($str, $len)
{

	if (strlen($str) < $len) return $str;
	$str = substr($str, 0, $len);
	$j = 0;

	for ($i = strlen($str) - 1; $i >= 0; $i--) {
		if (ord($str[$i]) <= 127) break;
		$j++;
	}

	$str = ($j % 2) ? substr($str, 0, strlen($str) - 1) : $str;
	$str .= "...";  //문자열 자르고나서 뒤에 붙이는거예요.
	return $str;
}

// 배송정책 이름
function deliver_name($deliver_method)
{
	if ($deliver_method == "DA") return "전액무료";
	else if ($deliver_method == "DB") return "수신자부담(착불)";
	else if ($deliver_method == "DC") return "고정값";
	else if ($deliver_method == "DD") return "구매가격별";
}

// 배송비
function deliver_price($prd_price, $oper_info)
{
	global $deliver_msg;
	$deliver_price = 0;

	if ($oper_info->del_method == "DA") { // 배송비 전액무료
		$deliver_price = 0;
		$deliver_msg = "배송비 전액무료";
	} else if ($oper_info->del_method == "DB") { // 수신자부담 (착불)
		$deliver_price = 0;
		$deliver_msg = "수신자부담 (착불)";
	} else if ($oper_info->del_method == "DC") { // 고정값
		$deliver_price = $oper_info->del_fixprice;
		$deliver_msg = "고정 " . number_format($oper_info->del_fixprice) . "원";
	} else if ($oper_info->del_method == "DD") { // 구매가격별
		if ($oper_info->del_staprice <= $prd_price) $deliver_price = $oper_info->del_staprice2;
		else $deliver_price = $oper_info->del_staprice3;

		$deliver_msg = number_format($oper_info->del_staprice) . "원 이상구매시 ";

		if ($oper_info->del_staprice2 == 0) $deliver_msg .= "무료배송";
		else $oper_info->del_staprice2 = $deliver_msg .= number_format($oper_info->del_staprice2) . "원";
	}

	return $deliver_price;
}

// 추가 배송비 
function deliver_gogo($tmp_post, $oper_info)
{
	if ($_SESSION[userlevel] == 10 || $_SESSION[userlevel] == 9 || $_SESSION[userlevel] == 8) {
		$deliver_price2 = 0;
	} else {
		$sql = " Select price From df_shop_add_shipping Where post_from <= '" . $tmp_post . "' And post_to >= '" . $tmp_post . "' Limit 1 ";
		$result = mysql_query($sql) or error(mysql_error());
		$row = mysql_fetch_object($result);
		if ($row) $deliver_price2 = $row->price;
		else $deliver_price2 = 0;
	}

	return $deliver_price2;
}

// 회원등급 리스트
function level_list()
{
	global $db;

	$sql = "select idx,level,name from df_site_level order by level asc, idx asc";
	$row = $db->query($sql);
	for ($i = 0; $i < count($row); $i++) {
		echo "<option value=\"" . $row[$i]['idx'] . "\">" . $row[$i]['name'] . "</option>";
	}
}
// 등급정보
function level_info()
{
	global $db;

	$level_info[""]['level'] = 13;
	$level_info[""]['name'] = "전체";
	$level_info[""]['distype'] = "N";
	$level_info["0"]['level'] = 0;
	$level_info["0"]['name'] = "관리자";
	$level_info["0"]['distype'] = "Y";

	$sql = " Select * From df_site_level ";
	$row = $db->query($sql);
	for ($i = 0; $i < count($row); $i++) {
		$level_info[$row[$i]['idx']]['level'] = $row[$i]['level'];
		$level_info[$row[$i]['idx']]['name'] = $row[$i]['name'];
		$level_info[$row[$i]['idx']]['distype'] = $row[$i]['distype'];
	}

	return $level_info;
}

// 전문가 회원등급 리스트
function spe_level_list()
{
	$sql = "select idx,level,name from df_site_spe_level order by level asc, idx asc";
	$result = mysql_query($sql) or error(mysql_error());
	while ($row = mysql_fetch_object($result)) {
		echo "<option value='$row->idx'>$row->name";
	}
}
// 등급정보
function spe_level_info()
{
	$spe_level_info[""][level] = 11;
	$spe_level_info[""][name] = "전체";
	$spe_level_info[""][icon] = "";
	$spe_level_info[""][icon2] = "";
	$spe_level_info["0"][level] = 0;
	$spe_level_info["0"][name] = "관리자";
	$spe_level_info["0"][icon] = "";
	$spe_level_info["0"][icon2] = "";

	$sql = "select * from df_site_spe_level";
	$result = mysql_query($sql) or error(mysql_error());

	while ($row = mysql_fetch_object($result)) {
		$spe_level_info[$row->idx][level] = $row->level;
		$spe_level_info[$row->idx][name] = $row->name;
		$spe_level_info[$row->idx][icon] = $row->icon;
		$spe_level_info[$row->idx][icon2] = $row->icon2;
	}

	return $spe_level_info;
}

// 등급할인액
function level_discount($idx, $prd_price)
{
	global $discount_msg;
	$discount_price = 0;

	if ($idx != "" && $prd_price > 0) {
		$sql = "select * from df_site_level where idx = '$idx'";
		$result = mysql_query($sql) or error(mysql_error());
		$row = mysql_fetch_object($result);

		if ($row->discount > 0) {
			if ($row->distype == "W") {
				$discount_price = $row->discount;
			} else {
				$discount_price = floor(($prd_price * ($row->discount / 100)) / 100) * 100;
			}
		}
	}

	if ($discount_price > 0) $discount_msg = " - 회원할인(<b>" . number_format($discount_price) . "원</b>)";

	return $discount_price;
}



// 레벨체크해서 화면에 보여줌
function func_level($lev)
{

	$sql = "";
	if (!$lev) {
		$lev = 0;
	}
	$sql = "select * from df_site_level where idx = $lev";
	$result = mysql_query($sql) or error(mysql_error());
	$row = mysql_fetch_object($result);
	$name00[icon] = $row->icon;
	$name00[name] = $row->name;
	return $name00;
}

// 결제방법
function pay_method($pay_method)
{

	if ($pay_method == "PB") $pay_method = "무통장입금";
	else if ($pay_method == "PC") $pay_method = "신용카드";
	else if ($pay_method == "PN") $pay_method = "실계좌이체";
	else if ($pay_method == "PV") $pay_method = "가상계좌(에스크로)";
	else if ($pay_method == "PH") $pay_method = "휴대폰소액결제";
	else if ($pay_method == "CP") $pay_method = "쿠폰결재";

	return $pay_method;
}

// 배송상태
function order_status($status)
{

	if ($status == "OR") $status = "주문접수";
	else if ($status == "OY") $status = "결제완료";
	else if ($status == "DI") $status = "배송처리";
	else if ($status == "DC") $status = "배송완료";
	else if ($status == "CR") $status = "취소요청";
	else if ($status == "CC") $status = "취소완료";

	return $status;
}
// 비방글, 욕설체크
function check_abuse($str)
{

	global $bbs_info;

	$abuse_list = explode(",", $bbs_info->abtxt);
	for ($ii = 0; $ii < count($abuse_list); $ii++) {
		$abuse_list[$ii] = trim($abuse_list[$ii]);
		if (!empty($abuse_list[$ii])) {
			if (strpos($str, $abuse_list[$ii]) !== false) {
				error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
			}
		}
	}
}

// 파일이 이미지인지
function isImgtype($srcimg)
{

	if (is_file($srcimg)) {

		$image_info = getimagesize($srcimg);
		switch ($image_info['mime']) {
			case 'image/gif':
				return true;
				break;
			case 'image/jpeg':
				return true;
				break;
			case 'image/png':
				return true;
				break;
			case 'image/bmp':
				return true;
				break;
			default:
				return false;
				break;
		}
	} else {
		return false;
	}
}

// 포토게시판 이미지 리사이즈
function bbsimg_resize($srcimg, $dstimg, $imgpath, $rewidth, $reheight)
{

	$src_info = getimagesize("$imgpath/$srcimg");

	if ($rewidth < $src_info[0] || $reheight < $src_info[1]) {
		if (($src_info[0] - $rewidth) > ($src_info[1] - $reheight)) {
			$reheight = round(($src_info[1] * $rewidth) / $src_info[0]);
		} else {
			$rewidth = round(($src_info[0] * $reheight) / $src_info[1]);
		}
	} else {
		exec("cp $imgpath/$srcimg $imgpath/$dstimg");
		return;
	}

	$dst = imageCreatetrueColor($rewidth, $reheight);

	if ($src_info[2] == 1) {

		$src = ImageCreateFromGIF("$imgpath/$srcimg");
		imagecopyResampled($dst, $src, 0, 0, 0, 0, $rewidth, $reheight, ImageSX($src), ImageSY($src));
		Imagejpeg($dst, "$imgpath/$dstimg", 100);
	} else if ($src_info[2] == 2) {

		$src = ImageCreateFromJPEG("$imgpath/$srcimg");
		imagecopyResampled($dst, $src, 0, 0, 0, 0, $rewidth, $reheight, ImageSX($src), ImageSY($src));
		Imagejpeg($dst, "$imgpath/$dstimg", 100);
	} else if ($src_info[2] == 3) {

		$src = ImageCreateFromPNG("$imgpath/$srcimg");
		imagecopyResampled($dst, $src, 0, 0, 0, 0, $rewidth, $reheight, ImageSX($src), ImageSY($src));
		Imagepng($dst, "$imgpath/$dstimg");
	}

	imageDestroy($src);
	imageDestroy($dst);
}

// 관리자페이지 리스트 출력
function print_pagelist_admin($total, $page_set, $block_set, $page, $param)
{

	$total_page = ceil($total / $page_set); // 총페이지수(올림함수)
	$total_block = ceil($total_page / $block_set); // 총블럭수(올림함수)

	$block = ceil($page / $block_set); // 현재블럭(올림함수)

	// 페이지번호 & 블럭 설정
	$first_page = (($block - 1) * $block_set) + 1; // 첫번째 페이지번호
	$last_page = min($total_page, $block * $block_set); // 마지막 페이지번호

	$prev_page = $page - 1; // 이전페이지
	$next_page = $page + 1; // 다음페이지

	$prev_block = $block - 1; // 이전블럭
	$next_block = $block + 1; // 다음블럭

	// 이전블럭을 블럭의 마지막으로 하려면...
	$prev_block_page = $prev_block * $block_set; // 이전블럭 페이지번호
	// 이전블럭을 블럭의 첫페이지로 하려면...
	//$prev_block_page = $prev_block * $block_set - ($block_set - 1);
	$next_block_page = $next_block * $block_set - ($block_set - 1); // 다음블럭 페이지번호
?>
	<div class="pagination">
		<ul>
			<li class="prev"><a href="<?= $_SERVER['PHP_SELF'] ?>?page=1<?= $param ?>">← 처음</a></li>
			<li class="prev"><a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $prev_block_page ?><?= $param ?>">← 이전</a></li>
			<? for ($i = $first_page; $i <= $last_page; $i++) { ?>
				<li class="<? if ($page == $i) { ?>active<? } ?>"><a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?><?= $param ?>"><?= $i ?></a></li>
			<? } ?>
			<li class="next"><a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $next_block_page ?><?= $param ?>">다음 → </a></li>
			<li class="next"><a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $total_page ?><?= $param ?>">마지막 → </a></li>
		</ul>
		<div class="clear"></div>
	</div>
<?
}

// 페이지 리스트 출력
function print_pagelist($total, $page_set, $block_set, $page, $param, $write_btn)
{

	$total_page = ceil($total / $page_set); // 총페이지수(올림함수)
	$total_block = ceil($total_page / $block_set); // 총블럭수(올림함수)

	$block = ceil($page / $block_set); // 현재블럭(올림함수)

	// 페이지번호 & 블럭 설정
	$first_page = (($block - 1) * $block_set) + 1; // 첫번째 페이지번호
	$last_page = min($total_page, $block * $block_set); // 마지막 페이지번호

	$prev_page = $page - 1; // 이전페이지
	$next_page = $page + 1; // 다음페이지

	$prev_block = $block - 1; // 이전블럭
	$next_block = $block + 1; // 다음블럭

	// 이전블럭을 블럭의 마지막으로 하려면...
	$prev_block_page = $prev_block * $block_set; // 이전블럭 페이지번호
	// 이전블럭을 블럭의 첫페이지로 하려면...
	//$prev_block_page = $prev_block * $block_set - ($block_set - 1);
	$next_block_page = $next_block * $block_set - ($block_set - 1); // 다음블럭 페이지번호
?>
	<div class="number_list_con">
		<div class="btn_con">
			<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $prev_block_page ?><?= $param ?>">
				<img src="/img/sub/number_list_prev_btn.png" alt="번호목록 이전 버튼" />
			</a>
		</div>

		<div class="list_con">
			<? for ($i = $first_page; $i <= $last_page; $i++) { ?>
				<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?><?= $param ?>" class="list_a <? if ($page == $i) { ?>on<? } ?>"><?= $i ?></a>
			<? } ?>
		</div>

		<div class="btn_con">
			<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $next_block_page ?><?= $param ?>">
				<img src="/img/sub/number_list_next_btn.png" alt="번호목록 다음 버튼" />
			</a>
		</div>
	</div>
<?
}

function print_pagelist_hs($total, $page_set, $block_set, $page, $param, $code)
{

	$total_page = ceil($total / $page_set); // 총페이지수(올림함수)
	$total_block = ceil($total_page / $block_set); // 총블럭수(올림함수)

	$block = ceil($page / $block_set); // 현재블럭(올림함수)

	// 페이지번호 & 블럭 설정
	$first_page = (($block - 1) * $block_set) + 1; // 첫번째 페이지번호
	$last_page = min($total_page, $block * $block_set); // 마지막 페이지번호

	$prev_page = $page - 1; // 이전페이지
	$next_page = $page + 1; // 다음페이지

	$prev_block = $block - 1; // 이전블럭
	$next_block = $block + 1; // 다음블럭

	// 이전블럭을 블럭의 마지막으로 하려면...
	$prev_block_page = $prev_block * $block_set; // 이전블럭 페이지번호
	// 이전블럭을 블럭의 첫페이지로 하려면...
	//$prev_block_page = $prev_block * $block_set - ($block_set - 1);
	$next_block_page = $next_block * $block_set - ($block_set - 1); // 다음블럭 페이지번호
?>
	<div class="number_list_con">
		<div class="contents_con">
			<div class="btn_con">
				<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $prev_block_page ?><?= $param ?>">
					<img src="/img/sub/number_list_prev_btn.svg" alt="번호목록 이전 버튼" />
				</a>
			</div>

			<div class="list_con">
				<? for ($i = $first_page; $i <= $last_page; $i++) { ?>
					<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?><?= $param ?>" class="list_a <? if ($page == $i) { ?>on<? } ?>"><?= $i ?></a>
				<? } ?>
			</div>

			<div class="btn_con">
				<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $next_block_page ?><?= $param ?>">
					<img src="/img/sub/number_list_next_btn.svg" alt="번호목록 다음 버튼" />
				</a>
			</div>
		</div>
	</div>
<?
}

// MOBILE 페이지 리스트 출력
function print_pagelist_mobile($total, $page_set, $block_set, $page, $param, $write_btn)
{

	$total_page = ceil($total / $page_set); // 총페이지수(올림함수)
	$total_block = ceil($total_page / $block_set); // 총블럭수(올림함수)

	$block = ceil($page / $block_set); // 현재블럭(올림함수)

	// 페이지번호 & 블럭 설정
	$first_page = (($block - 1) * $block_set) + 1; // 첫번째 페이지번호
	$last_page = min($total_page, $block * $block_set); // 마지막 페이지번호

	$prev_page = $page - 1; // 이전페이지
	$next_page = $page + 1; // 다음페이지

	$prev_block = $block - 1; // 이전블럭
	$next_block = $block + 1; // 다음블럭

	// 이전블럭을 블럭의 마지막으로 하려면...
	$prev_block_page = $prev_block * $block_set; // 이전블럭 페이지번호
	// 이전블럭을 블럭의 첫페이지로 하려면...
	//$prev_block_page = $prev_block * $block_set - ($block_set - 1);
	$next_block_page = $next_block * $block_set - ($block_set - 1); // 다음블럭 페이지번호
?>
	<div class="number_list_con">
		<div class="btn_con prev">
			<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $prev_block_page ?><?= $param ?>">
				<img src="/mobile/img/sub/number_list_prev_btn.png" alt="번호목록 이전 버튼" class="fx" />
			</a>
		</div>

		<div class="list_con">
			<? for ($i = $first_page; $i <= $last_page; $i++) { ?>
				<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?><?= $param ?>" class="list_a <? if ($page == $i) { ?>on<? } ?>"><?= $i ?></a>
			<? } ?>
		</div>

		<div class="btn_con next">
			<a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $next_block_page ?><?= $param ?>">
				<img src="/mobile/img/sub/number_list_next_btn.png" alt="번호목록 다음 버튼" class="fx" />
			</a>
		</div>
	</div>
<?
}

// 제품페이지 리스트 출력
function print_pagelist_web($page, $list_amount, $page_count, $param)
{

	global $code, $catcode, $orderby;

	if (($page % $list_amount) == 0) $tmp = $page - 1;
	else $tmp = $page;

	$spage = floor($tmp / $list_amount) * $list_amount + 1;
	if ($spage <= 1) $ppage = 1;
	else $ppage = $spage - $list_amount;

	$epage = $spage + $list_amount - 1;
	if ($epage >= $page_count) {
		$epage = $page_count;
		$npage = $page_count;
	} else {
		$npage = $epage + 1;
	}

	echo "<table border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td><span class='prev_next'><A HREF='$PHP_SELF?page=$ppage$param'><img src='../img/btn_prev.gif' border='0' alt='' /></a></span>";
	for ($spage; $spage <= $epage; $spage++) {
		if ($page == $spage) echo "<strong class='font_org'>&nbsp;$spage</strong>&nbsp;|";
		else echo "<A HREF='$PHP_SELF?page=$spage$param'>&nbsp;$spage&nbsp;|</A>";
	}
	echo "<span class='prev_next'><A HREF='$PHP_SELF?page=$npage$param'><img src='../img/btn_next.gif' border='0' alt='' /></A></span>
		<A HREF='$PHP_SELF?page=$npage&allgb=all$param'><img src='../img/btn_tview.gif' alt='전체보기' align='absmiddle' border='0' /></a>";
	echo "</td>";
	echo "</tr></table>";
}

// 이메일 발송
function send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content, $cc = "", $bcc = "")
{

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: =?utf-8?b?" . base64_encode($se_name) . "?= <" . $se_email . ">\r\n";
	$headers .= "Content-Type: text/html;charset=utf-8\r\n";
	$headers .= "Reply-To: $reply\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion();

	if ($cc) $headers .= "cc: $cc\n";
	if ($bcc) $headers .= "bcc: $bcc";

	$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	$result = mail($re_email, $subject, $content, $headers);
	return $result;
}


// SMS 발송
function send_sms($se_num, $re_num, $message)
{
	// 문자 발송 START ================================================================
	$msg = $message;
	$snd = $se_num;
	$rcv = $re_num;
	$rcvnm = "TIMEINNATURE";
	$sjt = "TIMEINNATURE";
	include $_SERVER['DOCUMENT_ROOT'] . "/Popbill/sms.php";
	// 문자 발송 END ================================================================

	//		global $DOCUMENT_ROOT;
	//		$post_data = array(
	//		    'se_num' => $se_num,
	//		    're_num' => $re_num,
	//		    'message' => $message
	//		);
	//				
	//		$ask_url = "http://".$_SERVER['HTTP_HOST']."/encoder/gsms_send.php";
	//		// Send a request to example.com 
	//		$result = post_request($ask_url, $post_data);
	//		 
	//		if ($result['status'] == 'ok'){
	//		 
	//		    // Print headers 
	//		    //echo $result['header']; 
	//		 
	//		    //echo '<hr />';
	//		 
	//		    // print the result of the whole request:
	//		    //echo $result['content'];
	//		 
	//		}
	//		else {
	//		    echo 'A error occured: ' . $result['error']; 
	//		}
}
// sms발송 이전 테스트
function send_smstest($se_num, $re_num, $message)
{
	global $DOCUMENT_ROOT;
	include "$DOCUMENT_ROOT/encoder/sms_send.php";
}

function info_replace($site_info, $re_info, $order_info, $msg)
{

	global $HTTP_HOST;

	$date = date('Y') . "년 " . date('m') . "월 " . date('d') . "일";
	$msg = str_replace("{[DATE]}", $date, $msg);
	$msg = str_replace("{[MEM_ID]}", $re_info[id], $msg);
	$msg = str_replace("{[MEM_PW]}", $re_info[passwd], $msg);
	$msg = str_replace("{[MEM_NAME]}", $re_info[name], $msg);
	$msg = str_replace("{[MEM_DELI]}", $re_info[deliver], $msg);
	$msg = str_replace("{[MEM_DELI_COM]}", $re_info[deliver_com], $msg);
	$msg = str_replace("{[REDATE]}", $re_info[redate], $msg);		//예약시
	$msg = str_replace("{[RETIME]}", $re_info[retime], $msg);		//예약시
	$msg = str_replace("{[SUBJECT]}", $re_info[subject], $msg);	//답변시
	$msg = str_replace("{[CONTENT]}", $re_info[content], $msg);	//답변시
	$msg = str_replace("{[SITE_NAME]}", $site_info->site_name, $msg);
	$msg = str_replace("{[SITE_EMAIL]}", $site_info->site_email, $msg);
	$msg = str_replace("{[SITE_TEL]}", $site_info->com_tel, $msg);
	$msg = str_replace("{[SITE_URL]}", "http://" . $HTTP_HOST, $msg);
	$msg = str_replace("{ORDER_INFO}", $order_info, $msg);

	return $msg;
}

// 메일SMS 내용 생성
function send_mailsms($se_info, $re_info, $order_info = "")
{

	global $DOCUMENT_ROOT;

	// 관리자 정보 가져오기
	include "$DOCUMENT_ROOT/inc/site_info.inc";

	$se_name = $site_info->site_name;
	$se_email = $site_info->site_email;
	$se_hand = $site_info->site_hand;
	$se_tel = $site_info->com_tel;

	if (!$site_info->com_tel) {
		$site_info->com_tel = $site_info->site_hand;
	}

	$se_info[sms_msg] = info_replace($site_info, $re_info, $order_info, $se_info[sms_msg]);
	$se_info[sms_opermsg] = info_replace($site_info, $re_info, $order_info, $se_info[sms_opermsg]);
	$se_info[email_subj] = info_replace($site_info, $re_info, $order_info, $se_info[email_subj]);
	$se_info[email_msg] = info_replace($site_info, $re_info, $order_info, $se_info[email_msg]);

	if ($re_info[pay_method] == "PB") {
		$se_info[sms_msg] = "";
		$se_info[sms_msg] .= $re_info[name] . "님의 주문이 접수되었습니다.\n";
		$se_info[sms_msg] .= "감사합니다.\n\n";
		$se_info[sms_msg] .= "[무통장 입금안내]\n";
		$se_info[sms_msg] .= "금액: " . number_format($re_info[total_price]) . "원\n";
		$se_info[sms_msg] .= "계좌: " . $re_info[account] . "\n";
		$se_info[sms_msg] .= "입금자: " . $re_info[account_name] . "\n\n";
		$se_info[sms_msg] .= "※ 무통장입금 주문시 2일내 입금확인이 안되면 자동취소됩니다.\n";
		$se_info[sms_msg] .= "※ 입금자명은 꼭 동일하게 입금해 주셔야 합니다.\n";
	}

	if ($se_info[sms_cust] == "Y") {	//sms고객수신
		send_sms($site_info->com_tel, $re_info[hphone], $se_info[sms_msg]);
	}
	if ($se_info[sms_oper] == "Y") {	//sms관리자수신
		send_sms($site_info->com_tel, $site_info->site_hand, $se_info[sms_opermsg]);
	}
	if ($se_info[email_cust] == "Y") {	//메일고객수신
		send_mail($site_info->site_name, $site_info->site_email, $re_info[name], $re_info[email], $se_info[email_subj], $se_info[email_msg]);
	}
	if ($se_info[email_oper] == "Y") {	//메일관리자수신
		send_mail($site_info->site_name, $site_info->site_email, $site_info->site_name, $site_info->site_email, $se_info[email_subj], $se_info[email_msg]);
	}
}

function deliver_send1($tmp_post, $oper_info)
{

	for ($iu = 1; $iu <= 30; $iu++) {
		if ($oper_info->{'del_extrapost' . $iu} <= $tmp_post && $tmp_post <= $oper_info->{'del_extrapostq' . $iu . '2'}) $deliver_true = $oper_info->{'q_view' . $iu};
	}

	return $deliver_true;
}


/* ######post식 소켓전송함수 ######## */
function post_request($url, $data, $referer = '')
{
	// Convert the data array into URL Parameters like a=b&foo=bar etc.
	$data = makeQueryString($data);

	// parse the given URL
	$url = parse_url($url);

	if ($url['scheme'] != 'http') {
		die('Error: Only HTTP request are supported !');
	}

	// extract host and path:
	$host = $url['host'];
	$path = $url['path'];

	// open a socket connection on port 80 - timeout: 30 sec
	$fp = fsockopen($host, 80, $errno, $errstr, 30);

	if ($fp) {

		// send the request headers:
		fputs($fp, "POST /encoder/gsms_send.php HTTP/1.1\r\n");
		fputs($fp, "Host: $host\r\n");

		if ($referer != '')
			fputs($fp, "Referer: $referer\r\n");

		fputs($fp, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n"); // utf-8
		fputs($fp, "Content-length: " . strlen($data) . "\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data);

		$result = '';
		while (!feof($fp)) {
			// receive the results of the request
			$result .= fgets($fp, 128);
		}
	} else {
		return array(
			'status' => 'err',
			'error' => "$errstr ($errno)"
		);
	}

	// close the socket connection:
	fclose($fp);

	// split the result header from the content
	$result = explode("\r\n\r\n", $result, 2);

	$header = isset($result[0]) ? $result[0] : '';
	$content = isset($result[1]) ? $result[1] : '';

	// return as structured array:
	return array(
		'status' => 'ok',
		'header' => $header,
		'content' => $content
	);
}

function makeQueryString($params, $prefix = '')
{
	$queryString = '';
	if (is_array($params)) {
		foreach ($params as $key => $value) {
			$correctKey = $prefix;
			if ('' === $prefix) {
				$correctKey = $prefix . $key;
			} else {
				$correctKey = $prefix . "[" . $key . "]";
			}
			if (!is_array($value)) {
				$queryString .= urlencode($correctKey) . "="
					. urlencode($value) . "&";
			} else {
				$queryString .= makeQueryString($value, $correctKey);
			}
		}
	}
	return $queryString;
}
/* ######post식 소켓전송함수 끝 ######## */

// 글 내용에서 이미지 src 추출
function getMPrvImg($content)
{
	preg_match("/]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $RESULT);
	preg_match_all("/]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $RESULT);
	return $RESULT;
}

// VIMEO / YOUTUBE ID
function getClipID($content)
{
	preg_match_all('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $content, $res);

	// VIMEO
	if ($res[5][0]) {
		$clip_id = $res[5][0];
	}

	// YOUTUBE
	else {
		preg_match_all('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|[^#]*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $content, $res);
		if ($res[1][0]) {
			$clip_id = $res[1][0];
		} else { // 네이버tv
			$tmp = explode('/', $content);
			$clip_id = $tmp[4];
		}
	}

	return $clip_id;
}

// VIMEO / YOUTUBE 썸네일 URL
function getClipThumbnail($content)
{
	preg_match_all('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $content, $res);

	// VIMEO
	if ($res[5][0]) {
		$clip_id = $res[5][0];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/" . $clip_id . ".json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$output2 = json_decode($output);
		if (!$output2  || $output == '') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/oembed.json?url=https://vimeo.com/" . $clip_id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);

			$output2 = json_decode($output);
			return $output2->thumbnail_url . ".jpg";
		} else {
			return $output2[0]->thumbnail_large;
		}
	}

	// YOUTUBE
	else {
		preg_match_all('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|[^#]*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $content, $res);

		if ($res[1][0]) {

			$clip_id = $res[1][0];

			//$thumbURL = 'http://img.youtube.com/vi/'.$clip_id.'/0.jpg';
			$thumbURL = "https://img.youtube.com/vi/" . $clip_id . "/hqdefault.jpg";
			return $thumbURL;
		} else {  // 네이버tv
			$tmp = explode('/', $content);
			$clip_id = $tmp[4];

			$url = "https://tv.naver.com/oembed?url=https://tv.naver.com/v/" . $clip_id;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$response = curl_exec($ch);
			curl_close($ch);
			$datas = json_decode($response);

			return $datas->thumbnail_url;
		}
	}
}

// VIMEO / YOUTUBE IFRAME
function getClipIframe($content, $autoplay = "", $main = "")
{
	preg_match_all('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $content, $res);

	if ($autoplay == "Y") $auto = "autoplay=1";
	else $auto = "";

	$wrap_class = "videoWrapper" . $main;

	// VIMEO
	if ($res[5][0]) {
		$clip_id = $res[5][0];

		$html  = "";
		$html .= "<div class=\"" . $wrap_class . "\">";
		$html .= "<iframe src=\"https://player.vimeo.com/video/" . $clip_id . "?title=0&byline=0&portrait=0&" . $auto . "\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		$html .= "</div>";

		return $html;
	}

	// YOUTUBE
	else {
		preg_match_all('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|[^#]*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $content, $res);

		if ($res[1][0]) {
			$clip_id = $res[1][0];

			$html  = "";
			$html .= "<div class=\"" . $wrap_class . "\">";
			$html .= "<iframe src=\"https://www.youtube.com/embed/" . $clip_id . "?rel=0&amp;showinfo=0&amp;" . $auto . "\" frameborder=\"0\" allowfullscreen></iframe>";
			$html .= "</div>";

			return $html;
		} else { // 네이버tv
			$tmp = explode('/', $content);
			$clip_id = $tmp[4];

			if ($autoplay == "Y") $auto = "autoplay=true";
			else $auto = "";

			$html  = "";
			$html .= "<div class=\"" . $wrap_class . "\">";
			$html .= "<iframe src=\"https://tv.naver.com/embed/" . $clip_id . "?" . $auto . "&oNm=outKeyPlayer.nhn\" frameborder=\"0\" allowfullscreen></iframe>";
			$html .= "</div>";

			return $html;
		}
	}
}

function getClipUrl($content, $autoplay = "", $main = "")
{
	preg_match_all('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $content, $res);

	if ($autoplay == "Y") $auto = "autoplay=1";
	else $auto = "";

	$wrap_class = "videoWrapper" . $main;

	// VIMEO
	if ($res[5][0]) {
		$clip_id = $res[5][0];

		$html  = "";
		$html .= "https://player.vimeo.com/video/" . $clip_id . "?title=0&byline=0&portrait=0&" . $auto;


		return $html;
	}

	// YOUTUBE
	else {
		preg_match_all('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|[^#]*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $content, $res);

		if ($res[1][0]) {
			$clip_id = $res[1][0];

			$html  = "";
			$html .= "https://www.youtube.com/embed/" . $clip_id . "?rel=0&amp;showinfo=0&amp;" . $auto;


			return $html;
		} else {
			$tmp = explode('/', $content);
			$clip_id = $tmp[4];

			if ($autoplay == "Y") $auto = "autoplay=true";
			else $auto = "";

			$html  = "";
			$html .= "https://tv.naver.com/embed/" . $clip_id . "?" . $auto . "&oNm=outKeyPlayer.nhn";

			return $html;
		}
	}
}

function addHypen($phoneNumber)
{
	$phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

	if (strlen($phoneNumber) == 11) {
		$formatted = substr($phoneNumber, 0, 3) . '-' . substr($phoneNumber, 3, 4) . '-' . substr($phoneNumber, 7);
		return $formatted;
	} else {
		return $phoneNumber;
	}
}
?>