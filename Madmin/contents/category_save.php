<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
header('Content-Type: application/json; charset=utf-8');

$mode = $_REQUEST['mode'] ?? '';


if ($mode === 'upload') {
    $id = (int) ($_POST['id'] ?? 0);
    if (!$id)
        throw new Exception('잘못된 요청입니다.');

    if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('파일 업로드에 실패했습니다.');
    }

    // 1) 저장 경로 & 파일명 생성
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/contents/category/';
    if (!is_dir($uploadDir))
        mkdir($uploadDir, 0755, true);

    $origName = basename($_FILES['thumbnail']['name']);
    $ext = pathinfo($origName, PATHINFO_EXTENSION);
    $newName = uniqid('cat_') . '.' . $ext;
    $destPath = $uploadDir . $newName;

    // 2) 실제 파일 이동
    if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destPath)) {
        throw new Exception('파일 저장에 실패했습니다.');
    }

    // 3) DB 갱신
    $db->query("UPDATE df_site_category
                        SET f_thumbnail = :thumb
                    WHERE f_idx       = :id
                    ",
        [
            'thumb' => $newName,
            'id' => $id
        ]
    );

    // 4) 성공 응답 (클라이언트에서 바로 src 로 사용)
    echo json_encode([
        'success' => true,
        'url' => '/userfiles/contents/category/' . $newName
    ]);
    exit;
}

// ─────────────────────────────────────────────────────────────
// GET all categories
// ─────────────────────────────────────────────────────────────
if ($mode == 'get') {
    $sql = " SELECT f_idx   AS id,
                COALESCE(f_parent_idx, '#') AS parent,
                f_name  AS text
            FROM df_site_category
            ORDER BY f_order
        ";
    $data = $db->query($sql, [], PDO::FETCH_ASSOC);
    echo json_encode($data);
    exit;
}

switch ($mode) {

    case 'get_image':
        $id = (int) ($_REQUEST['id'] ?? 0);
        if (!$id)
            throw new Exception('잘못된 요청입니다.');
        // depth, name, thumbnail 만 가져옴
        $row = $db->row("SELECT f_depth, f_name, f_thumbnail
                            FROM df_site_category
                            WHERE f_idx = :id
                            ",
            ['id' => $id],
            PDO::FETCH_ASSOC
        );
        // 1·2차만 허용
        if ($row['f_depth'] > 2) {
            echo json_encode(['success' => false, 'error' => '1·2차 카테고리만 이미지가 있습니다.']);
            exit;
        }
        echo json_encode([
            'success' => true,
            'depth' => $row['f_depth'],
            'name' => $row['f_name'],
            'thumbnail' => $row['f_thumbnail']
        ]);
        exit;

    case 'delete_image':
        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            throw new Exception('잘못된 요청입니다.');
        }

        // 1) 현재 썸네일 파일명 조회
        $row = $db->row(
            "SELECT f_thumbnail
               FROM df_site_category
              WHERE f_idx = :id",
            ['id' => $id],
            PDO::FETCH_ASSOC
        );
        $filename = $row['f_thumbnail'];

        // 2) 파일 시스템에서 삭제
        if ($filename) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/contents/category/' . $filename;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        // 3) DB에서 컬럼 초기화
        $db->query(
            "UPDATE df_site_category
                SET f_thumbnail = NULL
              WHERE f_idx = :id",
            ['id' => $id]
        );

        // 4) JSON 응답
        echo json_encode(['success' => true]);
        exit;


    // ───────────────────────────────────────────
    // 1) Create new category (max depth = 3)
    // ───────────────────────────────────────────
    case 'create':
        $parent = isset($_POST['parent']) && $_POST['parent'] !== ''
            ? (int) $_POST['parent']
            : null;
        $name = trim($_POST['text'] ?? '');
        if ($name === '') {
            throw new Exception('이름을 입력해주세요.');
        }

        // compute new depth
        if ($parent === null) {
            $depth = 1;
        } else {
            $parentDepth = $db->single(
                "SELECT f_depth FROM df_site_category WHERE f_idx = :p",
                ['p' => $parent]
            );
            $depth = $parentDepth + 1;
        }

        if ($depth > 3) {
            throw new Exception('최대 3단계까지만 생성 가능합니다.');
        }

        // compute order among siblings
        if ($parent === null) {
            $order = $db->single(
                "SELECT COALESCE(MAX(f_order),0)+1 FROM df_site_category WHERE f_parent_idx IS NULL",
                []
            );
        } else {
            $order = $db->single(
                "SELECT COALESCE(MAX(f_order),0)+1 FROM df_site_category WHERE f_parent_idx = :p",
                ['p' => $parent]
            );
        }

        // insert
        $sql = "INSERT INTO df_site_category
                        (f_parent_idx, f_depth, f_name, f_order, f_regdate)
                    VALUES
                        (:parent, :depth, :name, :order, NOW())
                    ";
        $db->query($sql, [
            'parent' => $parent,
            'depth' => $depth,
            'name' => $name,
            'order' => $order
        ]);
        $newId = $db->lastInsertId();

        echo json_encode(['success' => true, 'id' => $newId]);
        break;

    // ───────────────────────────────────────────
    // 2) Rename category
    // ───────────────────────────────────────────
    case 'rename':
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['text'] ?? '');
        if (!$id || $name === '') {
            throw new Exception('잘못된 요청입니다.');
        }
        $db->query("UPDATE df_site_category
                        SET f_name = :name
                        WHERE f_idx = :id
                        ", [
            'name' => $name,
            'id' => $id
        ]);
        echo json_encode(['success' => true]);
        break;

    // ───────────────────────────────────────────
    // 3) Delete category + descendants
    // ───────────────────────────────────────────
    case 'delete':
        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'error' => '잘못된 요청입니다.']);
            exit;
        }

        // 1) 삭제할 ID 모으기
        $toDelete = [$id];
        $lvl2 = $db->query(
            "SELECT f_idx FROM df_site_category WHERE f_parent_idx = :id",
            ['id' => $id],
            PDO::FETCH_COLUMN
        );
        foreach ($lvl2 as $c2) {
            $toDelete[] = $c2;
            $lvl3 = $db->query(
                "SELECT f_idx FROM df_site_category WHERE f_parent_idx = :c2",
                ['c2' => $c2],
                PDO::FETCH_COLUMN
            );
            foreach ($lvl3 as $c3) {
                $toDelete[] = $c3;
            }
        }

        // 2) 이름 기반 플레이스홀더 생성
        $placeholders = [];
        $bind = [];
        foreach ($toDelete as $i => $val) {
            // 플레이스홀더 이름: del0, del1, ...
            $key = "del{$i}";
            $placeholders[] = ":{$key}";
            $bind[$key] = (int) $val;
        }

        // 3) DELETE 쿼리 실행
        $sql = "DELETE FROM df_site_category WHERE f_idx IN (" . implode(',', $placeholders) . ")";
        $db->query($sql, $bind);

        // 4) 성공 응답
        echo json_encode(['success' => true]);
        exit;


    // ───────────────────────────────────────────
    // 4) Move category (max depth = 3)
    // ───────────────────────────────────────────
    case 'move':
        $id = (int) ($_POST['id'] ?? 0);
        $position = (int) ($_POST['position'] ?? 0);
        if (!$id) {
            throw new Exception('잘못된 요청입니다.');
        }

        // 1) 현재 부모와 depth 가져오기 (parent 변경 불허)
        $current = $db->row(
            "SELECT f_parent_idx AS parent, f_depth AS depth
         FROM df_site_category
        WHERE f_idx = :i",
            ['i' => $id]
        );
        $parent = $current['parent'];  // 기존 parent 그대로
        $origDepth = $current['depth'];  // 기존 depth 그대로

        // 2) 같은 부모의 형제 ID 목록
        if ($parent === null) {
            $siblings = $db->query(
                "SELECT f_idx FROM df_site_category
             WHERE f_parent_idx IS NULL
          ORDER BY f_order",
                [],
                PDO::FETCH_COLUMN
            );
        } else {
            $siblings = $db->query(
                "SELECT f_idx FROM df_site_category
             WHERE f_parent_idx = :p
          ORDER BY f_order",
                ['p' => $parent],
                PDO::FETCH_COLUMN
            );
        }

        // 3) 순서 변경: 현재 ID 빼고, 원하는 위치에 삽입
        $siblings = array_values(array_filter($siblings, fn($v) => $v !== $id));
        array_splice($siblings, $position, 0, [$id]);

        // 4) 각 노드에 새로운 order만 업데이트
        foreach ($siblings as $idx => $sid) {
            $db->query("UPDATE df_site_category
                                SET f_order = :o
                            WHERE f_idx   = :i
                        ", [
                'o' => $idx + 1,
                'i' => $sid
            ]);
        }

        echo json_encode(['success' => true]);
        break;


    default:
        throw new Exception('Invalid mode');
}
