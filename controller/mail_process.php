<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$debugLog = __DIR__ . '/debug_exec.log';
$mailLog = __DIR__ . '/mail_worker.log';

function writeDebug($msg)
{
    global $debugLog;
    //file_put_contents($debugLog, date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

function writeMailLog($msg)
{
    global $mailLog;
    //file_put_contents($mailLog, date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

writeDebug('process 진입');

// 실제 서버의 절대 경로
// require_once '/data/kci9874/public_html/phpmailer/Exception.php';
// require_once '/data/kci9874/public_html/phpmailer/PHPMailer.php';
// require_once '/data/kci9874/public_html/phpmailer/SMTP.php';

// ── 또는 __DIR__ 이용 예시 (권장) ──
require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

writeDebug('process 진입 3');

/**
 * Gmail SMTP로 메일 보내기
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMailViaGmail($toEmail, $toName, $subject, $bodyHtml, $attachmentPath = null, $attachmentName = null)
{
    writeDebug('sendMailViaGmail 진입');

    $gmailUser = "kiruke77@gmail.com";
    $gmailAppPassword = "xjxs cwlz geuq llpx";

    writeMailLog("sendMailViaGmail called 2 → gmailUser: {$gmailUser}, gmailAppPassword: {$gmailAppPassword}");

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $gmailUser;
        $mail->Password = $gmailAppPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom($gmailUser, '킹서진');
        $mail->addAddress($toEmail, $toName);

        if ($attachmentPath && file_exists($attachmentPath)) {
            $mail->addAttachment($attachmentPath, $attachmentName);
            writeMailLog("첨부파일 추가됨: {$attachmentPath}");
        } else {
            writeMailLog("첨부파일 없음 또는 경로 오류: {$attachmentPath}");
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->AltBody = strip_tags($bodyHtml);

        $mail->send();
        writeMailLog("메일 전송 성공");
        return true;
    } catch (Exception $e) {
        writeMailLog("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

writeDebug('process 진입 4');

// PHP_SAPI 검사 생략 → 바로 실행 
//writeDebug('PHP_SAPI = ' . PHP_SAPI . ' → 계속 실행');

if ($argc < 5) {
    //writeDebug('인자 개수 부족: argc=' . $argc);
    //exit("Usage: php mail_process.php toEmail toName subject bodyHtml [attachmentPath]\n");
}

$toEmail = $argv[1];
$toName = $argv[2];
$subject = $argv[3];
$bodyHtml = $argv[4];
$extra_mail = $argv[5];
$attachmentPath = isset($argv[6]) ? $argv[6] : null;

//writeDebug("CLI 파라미터 확인: to={$toEmail}, name={$toName}, subject 길이=" . strlen($subject) . ", body 길이=" . strlen($bodyHtml) . ", attachment={$attachmentPath}");

sendMailViaGmail($toEmail, $toName, $subject, $bodyHtml, $attachmentPath);
sendMailViaGmail($extra_mail, 'test', $subject, $bodyHtml, $attachmentPath);

writeDebug('스크립트 종료');
exit;
