<?
// 게시판 정보
$sql = "select * from df_site_bbsinfo where code = '$code'";
$bbs_info = $db->row($sql);
if(!$bbs_info || $bbs_info['usetype'] != "Y") error("해당게시판이 존재하지 않거나 파일용량이 너무 큽니다.");
?>