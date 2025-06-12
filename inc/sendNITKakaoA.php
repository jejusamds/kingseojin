<?
require_once($_SERVER['DOCUMENT_ROOT']."/inc/sendNITAPI.php");

$sender = "010-5890-0524";
$param = array(
	'api'			=> "kakaoA",
	'action'		=> "RegistIssue",
	'templateCode'	=> $templateCode,
	'sender'		=> $sender,
	'content'		=> $content,
	'altContent'	=> $altContent,
	'altSendType'	=> $altSendType,
	'reserveDT'		=> $reserveDT,
	'receivers'		=> $receivers,
	'buttons'		=> $buttons,
	'api_send'		=> $api_send				// API 발송 형태 (curl / socket)
);

$result = sendNITAPI($param);

$result = json_decode($result);
$receiptNum = $result->receiptNum;
?>
