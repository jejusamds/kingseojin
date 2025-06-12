<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

/**
 * 보안 필터
 */
function auto_filter_input(string $data): string
{
    return SQL_Injection(RemoveXSS($data));
}

/**
 * JSON 응답 헬퍼
 */
function return_json(array $ret): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

/**
 * 파일 업로드 처리
 */
function upload_file(array $file, $code): array
{
    $origName = $file['name'];
    $tmpPath = $file['tmp_name'];
    $size = $file['size'];
    $error = $file['error'];

    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'hwp', 'txt', 'zip', 'rar', '7z'];

    if (!in_array($ext, $allowed, true)) {
        return_json(['result' => 'error', 'msg' => '허용되지 않는 파일 형식입니다.']);
    }
    if ($error !== UPLOAD_ERR_OK) {
        return_json(['result' => 'error', 'msg' => '파일 업로드 중 오류가 발생했습니다.']);
    }
    if ($size > 20 * 1024 * 1024) {
        return_json(['result' => 'error', 'msg' => '파일 크기는 20MB 이하만 가능합니다.']);
    }

    $newName = uniqid('', true) . '.' . $ext;
    $dest = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/' . $code . '/' . $newName;
    if (!move_uploaded_file($tmpPath, $dest)) {
        return_json(['result' => 'error', 'msg' => '파일 저장에 실패했습니다.']);
    }

    return ['saved' => $newName, 'original' => $origName];
}

$approved = ['submit', 'reset_csrf_token', 'update'];
if (empty($_POST['mode']) || !in_array($_POST['mode'], $approved, true)) {
    return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$filtered = array_map('auto_filter_input', $_POST);

if ($filtered['mode'] === 'reset_csrf_token') {
    unset($_SESSION['csrf_token']);
    return_json(['result' => 'ok', 'msg' => '토큰이 재발급되었습니다.']);
}
if (empty($filtered['csrf_token']) || $filtered['csrf_token'] !== $_SESSION['csrf_token']) {
    return_json(['result' => 'error', 'msg' => '잘못된 접근입니다 (CSRF).']);
}

$page_name = [
    'review' => 'center_sub02',
    'buy' => 'center_sub03',
    'inquiry' => 'center_sub04',
];

if ($filtered['mode'] === 'update') {

    $idx = isset($filtered['idx']) ? (int) $filtered['idx'] : 0;

    // 7-2) 필수 필드 검증
    $required = [
        'subject' => '제목을 입력하세요.',
        'content' => '내용을 입력하세요.',
        'name' => '작성자명을 입력하세요.',
        'passwd' => '비밀번호를 입력하세요.'
    ];
    foreach ($required as $field => $msg) {
        if (empty($filtered[$field])) {
            return_json([
                'result' => 'blank',
                'field' => $field,
                'msg' => $msg
            ]);
        }
    }

    // 7-3) 비공개 글일 경우 passwd 필수
    if (($filtered['privacy'] ?? '') === 'Y' && empty($filtered['passwd'])) {
        return_json(['result' => 'error', 'msg' => '비공개 글에는 비밀번호가 필수입니다.']);
    }

    // 7-4) 파일 업로드 (선택)
    $fileSaved = '';
    $fileOriginal = '';
    if (!empty($_FILES['upfile']['name'])) {
        $info = upload_file($_FILES['upfile'], $filtered['code']);
        $fileSaved = $info['saved'];
        $fileOriginal = $info['original'];
    }

    // 7-5) 메인 테이블 UPDATE
    $sql = "
        UPDATE df_site_bbs SET
            subject      = :subject,
            content      = :content,
            name         = :name,
            privacy      = :privacy,
            passwd       = :passwd
        WHERE idx = :idx
    ";
    $params = [
        'subject' => $filtered['subject'],
        'content' => $filtered['content'],
        'name' => $filtered['name'],
        'privacy' => ($filtered['privacy'] === 'Y' ? 'Y' : 'N'),
        'passwd' => password_hash($filtered['passwd'], PASSWORD_DEFAULT),
        'idx' => $idx
    ];
    if (!$db->query($sql, $params)) {
        return_json(['result' => 'error', 'msg' => '글 수정에 실패했습니다.']);
    }

    // 7-6) 업로드된 파일이 있으면 files 테이블에도 DELETE / INSERT
    if ($fileSaved) {

        $sql_del = "DELETE FROM df_site_bbs_files WHERE bbsidx = :bbsidx";
        $db->bind('bbsidx', $idx);
        $db->query($sql_del);

        $sql2 = "INSERT INTO df_site_bbs_files
                (bbsidx, upfile, upfile_name)
            VALUES
                (:bbsidx, :upfile, :upfile_name)
        ";
        $params2 = [
            'bbsidx' => $idx,
            'upfile' => $fileSaved,
            'upfile_name' => $fileOriginal,
        ];
        if (!$db->query($sql2, $params2)) {
            return_json(['result' => 'error', 'msg' => '파일 정보 저장에 실패했습니다.']);
        }
    }

    $_SESSION['bbs_auth'][$idx] = true;

    // 7-7) 최종 성공
    return_json([
        'result' => 'ok',
        'msg' => '수정이 완료되었습니다.',
        'redirect' => "/center/{$page_name[$code]}_view.html?idx={$idx}"
    ]);


}

if ($filtered['mode'] === 'submit') {
    $required = [
        'subject' => '제목을 입력하세요.',
        'content' => '내용을 입력하세요.',
        'name' => '작성자명을 입력하세요.',
        'passwd'  => '비밀번호를 입력하세요.',
    ];
    foreach ($required as $field => $msg) {
        if (empty($filtered[$field])) {
            return_json(['result' => 'blank', 'field' => $field, 'msg' => $msg]);
        }
    }

    if ($privacy == 'Y' && empty($passwd)) {
        return_json(['result' => 'error', 'msg' => '비공개 글에는 비밀번호가 필수입니다.']);
    }

    $fileSaved = '';
    $fileOriginal = '';
    if (!empty($_FILES['upfile']['name'])) {
        $info = upload_file($_FILES['upfile'], $code);
        $fileSaved = $info['saved'];
        $fileOriginal = $info['original'];
    }

    try {
        $sql = "INSERT INTO df_site_bbs
                (code, subject, content, upfile, upfile_name, name, passwd, privacy, wdate)
            VALUES
                (:code, :subject, :content, :upfile, :upfile_name, :name, :passwd, :privacy, NOW())
        ";
        $params = [
            'code' => $_POST['code'] ?? '',
            'subject' => $filtered['subject'],
            'content' => $filtered['content'],
            'upfile' => $fileSaved,
            'upfile_name' => $fileOriginal,
            'name' => $filtered['name'],
            'passwd' => password_hash($filtered['passwd'], PASSWORD_DEFAULT),
            'privacy' => ($filtered['privacy'] === 'Y' ? 'Y' : 'N'),
        ];

        if (!$db->query($sql, $params)) {
            return_json(['result' => 'error', 'msg' => 'DB 저장에 실패했습니다.']);
        }

        // 새로 삽입된 게시글의 PK(idx) 가져오기
        $bbsidx = $db->lastInsertId();

        // 파일이 업로드 되었다면, 파일 전용 테이블에도 기록
        if ($fileSaved) {
            $sql2 = "INSERT INTO df_site_bbs_files
                (bbsidx, upfile, upfile_name)
            VALUES
                (:bbsidx, :upfile, :upfile_name)
        ";
            $params2 = [
                'bbsidx' => $bbsidx,
                'upfile' => $fileSaved,
                'upfile_name' => $fileOriginal,
            ];

            if (!$db->query($sql2, $params2)) {
                return_json(['result' => 'error', 'msg' => '파일 정보 저장에 실패했습니다.']);
            }
        }

        // 모두 성공 시
        return_json([
            'result' => 'ok',
            'msg' => '등록이 완료되었습니다.',
            'redirect' => "/center/{$page_name[$code]}.html"
        ]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return_json(['result' => 'error', 'msg' => '일시적인 오류가 발생했습니다.']);
    }
}
