<?php

exit;

try {
    $username = 'museumweek';
    $password = 'elvor0810!';
    $db_name = 'renewal_museumweek_kr';    

    $dsn = 'mysql:host=localhost;dbname='.$db_name.';charset=utf8mb4';
    
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection established successfully.";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}

// 테이블 선택해서 가상 데이터 입력
$table = isset($_GET['table']) ? trim($_GET['table']) : '';
$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;

if(empty($table)) {
    die("테이블 이름을 입력해주세요.");
}

// 오류 출력 설정 (디버깅용)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // 테이블 이름 검증 (알파벳, 숫자, 언더스코어만 허용)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        die("유효하지 않은 테이블 이름입니다.");
    }
    
    // 현재 데이터베이스의 모든 테이블 목록 확인
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 테이블 이름 추출
    $tableNames = [];
    foreach ($tables as $tbl) {
        // 첫 번째 컬럼 값을 가져옵니다 (테이블 이름)
        $tableNames[] = reset($tbl);
    }
    
    if (!in_array($table, $tableNames)) {
        die("테이블이 현재 데이터베이스에 존재하지 않습니다: " . htmlspecialchars($table));
    }
    
    // 테이블 구조 확인
    $sql = "DESCRIBE `" . $table . "`";
    $result = $db->query($sql);
    
    if (!$result || $result->rowCount() == 0) {
        die("테이블 구조를 조회할 수 없습니다: " . htmlspecialchars($table));
    }
    
    $fields = array();
    $types = array();
    $primaries = array();
    
    // 필드 정보 수집
    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        $fields[] = $row->Field;
        $types[$row->Field] = $row->Type;
        if ($row->Key == 'PRI') {
            $primaries[] = $row->Field;
        }
    }
    
    // 가상 데이터 생성 및 삽입
    $inserted = 0;
    for ($i = 0; $i < $rows; $i++) {
        $data = array();
        $placeholders = array();
        $insert_values = array();
        
        foreach ($fields as $field) {
            // AUTO_INCREMENT 필드는 건너뛰기
            if (in_array($field, $primaries) && strpos($types[$field], 'int') !== false) {
                continue;
            }
            
            $value = generateFakeData($field, $types[$field]);
            $data[$field] = $value;
            $placeholders[] = "?";
            $insert_values[] = $value;
        }
        
        if (count($data) > 0) {
            $sql = "INSERT INTO `" . $table . "` (`" . implode("`, `", array_keys($data)) . "`) 
                    VALUES (" . implode(", ", $placeholders) . ")";
            
            try {
                $stmt = $db->prepare($sql);
                $success = $stmt->execute($insert_values);
                if ($success) {
                    $inserted++;
                }
            } catch (PDOException $e) {
                echo "데이터 삽입 중 오류 발생: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "{$inserted}개의 가상 데이터가 {$table} 테이블에 삽입되었습니다.";
    
} catch (PDOException $e) {
    echo "데이터베이스 오류: " . $e->getMessage();
}

/**
 * 필드 이름과 타입에 따라 가상 데이터 생성
 * 
 * @param string $field 필드 이름
 * @param string $type 필드 타입
 * @return mixed 생성된 가상 데이터
 */
function generateFakeData($field, $type) {
    $field = strtolower($field);
    
    // d_type 필드 처리 (1~3 사이의 랜덤 값)
    if ($field == 'd_type') {
        return mt_rand(1, 3);
    }
    
    // d_name 필드 처리
    if ($field == 'd_name') {
        $prefixes = ['상품', '제품', '아이템', '물건', '상자'];
        $suffixes = ['A', 'B', 'C', 'Pro', 'Plus', 'Mini', 'Max'];
        return $prefixes[array_rand($prefixes)] . ' ' . $suffixes[array_rand($suffixes)] . ' ' . mt_rand(100, 999);
    }
    
    // d_thumb 필드 처리 (이미지 경로)
    if ($field == 'd_thumb') {
        return '/images/products/' . mt_rand(1, 50) . '.jpg';
    }
    
    // d_editor 필드 처리
    if (strpos($field, 'd_editor') !== false) {
        $paragraphs = mt_rand(1, 5);
        $text = '';
        for ($i = 0; $i < $paragraphs; $i++) {
            $sentences = mt_rand(3, 8);
            $paragraph = '';
            for ($j = 0; $j < $sentences; $j++) {
                $paragraph .= generateRandomSentence() . ' ';
            }
            $text .= "<p>" . trim($paragraph) . "</p>\n";
        }
        return $text;
    }
    
    // showset 필드 처리 (enum 값)
    if ($field == 'showset') {
        //return (mt_rand(0, 9) > 2) ? 'Y' : 'N';  // 70% 확률로 Y
        return 'Y';
    }
    
    // prior 필드 처리
    if ($field == 'prior') {
        return mt_rand(1, 100);
    }
    
    // wdate 필드 처리
    if ($field == 'wdate') {
        return date('Y-m-d H:i:s', mt_rand(strtotime('2023-01-01'), time()));
    }
    
    // 날짜/시간 관련 필드인 경우
    if (strpos($field, 'date') !== false || strpos($field, 'time') !== false ||
        strpos($field, 'created') !== false || strpos($field, 'updated') !== false) {
        return date('Y-m-d H:i:s', mt_rand(strtotime('2020-01-01'), time()));
    }
    
    // 이메일 필드인 경우
    if (strpos($field, 'email') !== false) {
        $domains = ['gmail.com', 'naver.com', 'daum.net', 'hotmail.com', 'yahoo.com'];
        return 'user' . mt_rand(1000, 9999) . '@' . $domains[array_rand($domains)];
    }
    
    // 이름 필드인 경우
    if (strpos($field, 'name') !== false) {
        $firstNames = ['김', '이', '박', '최', '정', '강', '조', '윤', '장', '임'];
        $lastNames = ['민수', '지현', '서연', '지훈', '민준', '예은', '도윤', '수빈', '준혁', '지은'];
        return $firstNames[array_rand($firstNames)] . $lastNames[array_rand($lastNames)];
    }
    
    // 전화번호 필드인 경우
    if (strpos($field, 'phone') !== false || strpos($field, 'tel') !== false || strpos($field, 'mobile') !== false) {
        return '010-' . mt_rand(1000, 9999) . '-' . mt_rand(1000, 9999);
    }
    
    // 주소 필드인 경우
    if (strpos($field, 'addr') !== false || strpos($field, 'address') !== false) {
        $cities = ['서울시', '부산시', '인천시', '대구시', '대전시', '광주시', '울산시', '세종시'];
        $districts = ['강남구', '서초구', '마포구', '종로구', '중구', '동대문구', '성북구', '영등포구'];
        return $cities[array_rand($cities)] . ' ' . $districts[array_rand($districts)] . ' 테스트길 ' . mt_rand(1, 100) . '번길 ' . mt_rand(1, 30);
    }
    
    // 데이터 타입에 따라 처리
    if (strpos($type, 'int') !== false) {
        return mt_rand(1, 1000);
    } elseif (strpos($type, 'float') !== false || strpos($type, 'double') !== false || strpos($type, 'decimal') !== false) {
        return round(mt_rand(100, 10000) / 100, 2);
    } elseif (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
        $length = 10;
        if (preg_match('/\((\d+)\)/', $type, $matches)) {
            $length = min((int)$matches[1], 100);
        }
        return generateRandomString($length);
    } elseif (strpos($type, 'enum') !== false || strpos($type, 'set') !== false) {
        preg_match_all("/'([^']*)'/", $type, $matches);
        $options = $matches[1];
        return $options[array_rand($options)];
    } elseif (strpos($type, 'date') !== false) {
        return date('Y-m-d', mt_rand(strtotime('2020-01-01'), time()));
    } elseif (strpos($type, 'time') !== false) {
        return date('H:i:s', mt_rand(0, 86400));
    } elseif (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
        return date('Y-m-d H:i:s', mt_rand(strtotime('2020-01-01'), time()));
    } elseif (strpos($type, 'blob') !== false || strpos($type, 'binary') !== false) {
        return null; // BLOB 타입은 일단 NULL로 처리
    } else {
        return generateRandomString(10);
    }
}

/**
 * 랜덤 문자열 생성
 * 
 * @param int $length 문자열 길이
 * @return string 생성된 랜덤 문자열
 */
function generateRandomString($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $result;
}

/**
 * 랜덤 문장 생성
 * 
 * @return string 생성된 랜덤 문장
 */
function generateRandomSentence() {
    $subjects = ['이 제품은', '해당 상품은', '본 아이템은', '이 물건은', '이 상품은'];
    $adjectives = ['훌륭한', '멋진', '실용적인', '유용한', '편리한', '효과적인', '최고의', '독특한'];
    $nouns = ['품질', '디자인', '기능', '성능', '내구성', '사용성', '가격대비성능', '만족도'];
    $verbs = ['자랑합니다', '제공합니다', '보장합니다', '약속합니다', '보여줍니다', '입증합니다'];
    
    return $subjects[array_rand($subjects)] . ' ' .
           $adjectives[array_rand($adjectives)] . ' ' .
           $nouns[array_rand($nouns)] . '을(를) ' .
           $verbs[array_rand($verbs)] . '.';
}
?>