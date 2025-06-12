<?
require_once($_SERVER['DOCUMENT_ROOT']."/inc/sendNITAPI.php");

$snd = "070-5172-6119";
$param = array(
	'api'			=> "message",
	'action'		=> "RegistIssue",
	'reserveDT'		=> null,					// 예약전송일시(yyyyMMddHHmmss) ex)20161108200000, null인경우 즉시전송
	'adsYN'			=> false,					// 광고문자 전송여부 (true / false)
	'snd'			=> $snd,					// 발신번호
	'sndnm'			=> $sndnm,					// 발신자명
	'rcv'			=> $rcv,					// 수신번호
	'rcvnm'			=> $rcvnm,					// 수신자 성명
	'msg'			=> $msg,					// 메시지 내용
	'sjt'			=> $sjt,					// 메시지 제목
	'files'			=> "",						// 첨부파일(최대 300KByte, JPEG 파일포맷 전송가능)
	'api_send'		=> $api_send				// API 발송 형태 (curl / socket)
);

$result = sendNITAPI($param);

$result = json_decode($result);
$receiptNum = $result->receiptNum;
?>
