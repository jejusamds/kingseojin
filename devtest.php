<?php

phpinfo();exit;

error_reporting(E_ALL);
ini_set("display_errors", 1);

$accessToken = 'IGAAKZAGXyJNUZABZAE9IV3RqNTA5aGZAjeHV4N2FyR3kyRDFpc2FEX2ZADUjZAqU3NWckh0azkxbVFoTC1kOGFieWRoRnlFU1ZAwdUNLQnZAFV0tlb2VsUl9EYlNtejUzRm1pSkJRZADBmajZABbVlmTkRoOEFLNjFUMHhQdmdrMzRDWnRuSQZDZD';
$userId = '17841422073124179';

$url = "https://graph.instagram.com/{$userId}/media?" . http_build_query(array(
    'fields' => 'id,media_type,media_url,permalink,caption,timestamp,username',
    'access_token' => $accessToken
));

$response = file_get_contents($url);
$data = json_decode($response, true);

// 에러 응답 체크
if (isset($data['error'])) {
    echo "에러 발생: " . $data['error']['message'];
    exit;
}

var_dump($data);

foreach ($data['data'] as $post) {
    if ($post['media_type'] === 'IMAGE') {
        echo '<div style="margin:10px; display:inline-block;">';
        echo '<a href="' . $post['permalink'] . '" target="_blank">';
        echo '<img src="' . $post['media_url'] . '" width="200" style="display:block;">';
        echo '</a>';
        if (isset($post['caption'])) {
            echo '<p>' . htmlspecialchars($post['caption']) . '</p>';
        }
        echo '</div>';
    }
}
?>
