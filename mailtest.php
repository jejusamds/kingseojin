<?php
// 최소한의 테스트 - 파일명: minimal.php
// PHPMailer 없이 순수 PHP로만

echo "시작: " . date('H:i:s') . "<br>";

// 1. 가장 간단한 메일 전송
echo "PHP mail() 함수 테스트 중...<br>";

$result = mail(
    'metal98@hanmail.net',
    '간단 테스트 ' . date('H:i:s'),
    '이것은 PHP mail() 함수 테스트입니다. 시간: ' . date('Y-m-d H:i:s'),
    'From: kiruke77@gmail.com'
);

if ($result) {
    echo "✅ 성공! PHP mail() 함수가 작동합니다.<br>";
} else {
    echo "❌ 실패! PHP mail() 함수가 작동하지 않습니다.<br>";
}

echo "완료: " . date('H:i:s') . "<br>";

// 2. PHPMailer 없이 첨부파일 보내기 (성공 시에만)
if ($result) {
    echo "<br><h3>첨부파일 테스트용 함수</h3>";
    echo "<pre>";
    echo htmlspecialchars('
function sendMailWithAttachment($to, $subject, $message, $file_path = null) {
    $from = "kiruke77@gmail.com";
    $from_name = "킹서진";
    
    if ($file_path && file_exists($file_path)) {
        // 첨부파일이 있는 경우
        $boundary = md5(time());
        
        $headers = "From: $from_name <$from>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $body .= $message . "\r\n\r\n";
        
        // 첨부파일
        $file_content = chunk_split(base64_encode(file_get_contents($file_path)));
        $file_name = basename($file_path);
        
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $body .= $file_content . "\r\n";
        $body .= "--$boundary--";
        
        return mail($to, $subject, $body, $headers);
    } else {
        // 일반 HTML 메일
        $headers = "From: $from_name <$from>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
}

// 사용 예시:
$result = sendMailWithAttachment(
    "받는사람@이메일.com",
    "제목",
    "<h1>안녕하세요</h1><p>메시지 내용</p>",
    "/path/to/file.pdf"  // 첨부파일 경로 (선택사항)
);
    ');
    echo "</pre>";
    
    echo "<p><strong style='color: green;'>✅ 이 방법을 사용하세요!</strong></p>";
    echo "<p>PHPMailer가 문제를 일으키므로 순수 PHP mail() 함수를 사용하는 것이 좋습니다.</p>";
}

echo "<br><h3>호스팅 환경 정보</h3>";
echo "PHP 버전: " . PHP_VERSION . "<br>";
echo "mail() 함수 사용 가능: " . (function_exists('mail') ? '예' : '아니오') . "<br>";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? '예' : '아니오') . "<br>";
echo "openssl 확장: " . (extension_loaded('openssl') ? '예' : '아니오') . "<br>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
</style>