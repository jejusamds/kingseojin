<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

/**
 * 입력값 보안 필터
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
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * BBS 비밀번호 검증 및 세션 설정
 */
function verify_bbs_password($db, int $idx, string $passwd): void
{
    // 1) 빈 값 체크
    if ($passwd === '') {
        return_json(array(
            'result' => 'blank',
            'field' => 'passwd',
            'msg' => '비밀번호를 입력하세요.'
        ));
    }

    // 2) DB에서 해시 가져오기
    $sql = "SELECT passwd
              FROM df_site_bbs
             WHERE idx = :idx
             LIMIT 1";
    $row = $db->row($sql, array('idx' => $idx));

    if (!$row) {
        return_json(array(
            'result' => 'error',
            'msg' => '존재하지 않는 글입니다.'
        ));
    }

    // 3) 검증
    if (!password_verify($passwd, $row['passwd'])) {
        return_json(array(
            'result' => 'invalid',
            'field' => 'passwd',
            'msg' => '비밀번호가 일치하지 않습니다.'
        ));
    }

    // 4) 세션에 권한 표시
    $_SESSION['bbs_auth'][$idx] = true;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return_json(array('result' => 'error', 'msg' => '잘못된 요청입니다.'));
}

$mode = isset($_POST['mode']) ? $_POST['mode'] : '';

$filtered = array_map('auto_filter_input', $_POST);
$idx = isset($filtered['idx']) ? (int) $filtered['idx'] : 0;
$passwd = isset($filtered['passwd']) ? $filtered['passwd'] : '';

if ($idx <= 0) {
    return_json(array('result' => 'error', 'msg' => '잘못된 게시물 번호입니다.'));
}

$page_name = [
    'review' => 'center_sub02',
    'buy' => 'center_sub03',
    'inquiry' => 'center_sub04',
];

switch ($mode) {
    case 'delete':
        try {
            verify_bbs_password($db, $idx, $passwd);
            $sql = "DELETE FROM df_site_bbs WHERE idx = :idx or parno = :parno";
            $db->query($sql, ['idx' => $idx, 'parno' => $parno]);

            return_json([
                'result' => 'ok',
                'mode' => 'delete',
                'msg' => '게시물이 삭제되었습니다.',
                'redirect' => "/center/{$page_name[$code]}.html"
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return_json(['result' => 'error', 'msg' => '삭제 중 오류가 발생했습니다.']);
        }
        break;

    case 'update':
        try {
            verify_bbs_password($db, $idx, $passwd);
            $redirect = "/center/{$page_name[$code]}_write.html?idx=" . $idx;
            return_json(array(
                'result' => 'ok',
                'redirect' => $redirect
            ));
        } catch (Exception $e) {
            error_log($e->getMessage());
            return_json(array(
                'result' => 'error',
                'msg' => '일시적인 오류가 발생했습니다.'
            ));
        }
        break;

    case 'auth':
        try {
            verify_bbs_password($db, $idx, $passwd);
            $redirect = "/center/{$page_name[$code]}_view.html?idx=" . $idx;
            return_json(array(
                'result' => 'ok',
                'redirect' => $redirect
            ));
        } catch (Exception $e) {
            error_log($e->getMessage());
            return_json(array(
                'result' => 'error',
                'msg' => '일시적인 오류가 발생했습니다.'
            ));
        }
        break;

}