<?php

exit;

// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'df77_hsmodular';
$username = 'df77_hsmodular';
$password = 'elvor0810!';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class DummyDataGenerator {
    private $pdo;
    private $tableName;
    private $columns = [];
    
    public function __construct($pdo, $tableName) {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->analyzeTable();
    }
    
    private function analyzeTable() {
        $stmt = $this->pdo->query("SHOW CREATE TABLE {$this->tableName}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $createTable = $result['Create Table'];
        
        // 컬럼 정보 추출
        preg_match_all("/`(\w+)`\s+([^,\n]+)/", $createTable, $matches);
        
        for ($i = 0; $i < count($matches[1]); $i++) {
            $columnName = $matches[1][$i];
            $columnType = $matches[2][$i];
            
            // idx 컬럼 제외
            if ($columnName !== 'idx' && $columnName !== $this->tableName && $columnName !== 'IX_site_bbs_01' && $columnName !== 'IX_site_bbs_02') {
                $this->columns[$columnName] = [
                    'name' => $columnName,
                    'type' => $this->parseColumnType($columnType)
                ];
            }
        }
    }
    
    private function parseColumnType($columnType) {
        $type = strtolower(preg_replace('/\(.*?\)/', '', $columnType));
        return trim(explode(' ', $type)[0]);
    }
    
    private function generateDummyValue($columnName, $type) {
        switch ($type) {
            case 'int':
            case 'bigint':
            case 'tinyint':
                if ($columnName == 'count') return rand(0, 100);
                if ($columnName == 'recom') return rand(0, 10);
                if ($columnName == 'parno') return 0;
                if ($columnName == 'prino') return rand(1, 100);
                if ($columnName == 'depno') return 0;
                return rand(0, 1000);
                
            case 'varchar':
            case 'char':
                if ($columnName == 'code') return 'news';
                if ($columnName == 'memid') return 'admin';
                if ($columnName == 'name') return '최고관리자';
                if ($columnName == 'subject') return '더미 제목 ' . rand(1, 1000);
                if ($columnName == 'content') return '더미 내용 ' . rand(1, 1000);
                if ($columnName == 'ip') return '112.220.18.' . rand(1, 255);
                return 'dummy_' . rand(1000, 9999);
                
            case 'datetime':
                if ($columnName == 'wdate') {
                    return date('Y-m-d H:i:s', strtotime('-' . rand(0, 30) . ' days'));
                }
                return date('Y-m-d H:i:s');
                
            case 'date':
                if (strpos($columnName, 'sdate') !== false) {
                    return date('Y-m-d', strtotime('+' . rand(1, 10) . ' days'));
                }
                if (strpos($columnName, 'edate') !== false) {
                    return date('Y-m-d', strtotime('+' . rand(11, 30) . ' days'));
                }
                return date('Y-m-d');
                
            case 'enum':
                if ($columnName == 'notice') return 'N';
                if ($columnName == 'privacy') return 'N';
                if ($columnName == 'ctype') return 'H';
                return 'N';
                
            default:
                return null;
        }
    }
    
    public function generate($count = 1) {
        $data = [];
        
        for ($i = 0; $i < $count; $i++) {
            $row = [];
            foreach ($this->columns as $column => $info) {
                $row[$column] = $this->generateDummyValue($column, $info['type']);
            }
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function insert($data) {
        $columnNames = array_keys($this->columns);
        $placeholders = array_fill(0, count($columnNames), '?');
        
        $sql = "INSERT INTO {$this->tableName} (" . 
               implode(', ', $columnNames) . 
               ") VALUES (" . 
               implode(', ', $placeholders) . 
               ")";
               
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($data as $row) {
            try {
                $stmt->execute(array_values($row));
            } catch (PDOException $e) {
                echo "Error inserting row: " . $e->getMessage() . "\n";
                continue;
            }
        }
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $generator = new DummyDataGenerator($pdo, 'df_site_bbs');
    $dummyData = $generator->generate(50);
    $generator->insert($dummyData);
    
    echo "50개의 더미데이터가 성공적으로 생성되었습니다.";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>