<?
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/Eadmin_check.inc";


//===========================================================================================================
// 관리자설정
//===========================================================================================================
if ($mode == "set_admin") {
   if ($admin_mode == "insert") {

      $resno = $resno . "-" . $resno2;
      $post = $post . "-" . $post2;
      $tphone = $tphone . "-" . $tphone2 . "-" . $tphone3;
      $hphone = $hphone . "-" . $hphone2 . "-" . $hphone3;
      if (!$part) $part = 0;

      for ($ii = 0; $ii < count($permi); $ii++) {
         $tmp_permi .= $permi[$ii] . "/";
      }

      // $id 로 중복검사
      $sql = "select count(*) from df_site_admin where id = '$id'";
      $total = $db->single($sql);
      if ($total >= 1) {
         error("이미 등록된 아이디입니다.");
      }

      // 비번 hash
      $passwd = password_hash($passwd, PASSWORD_DEFAULT);

      $sql = "insert into df_site_admin(id, passwd, name, email, part, last, wdate, descript) 
                           values('$id', '$passwd', '$name', '$email', '$part', now(), now(), '$descript')";
      $db->query($sql);

      complete("관리자가 추가되었습니다.", "set_admin.php");
   } else if ($admin_mode == "update") {

      $resno = $resno . "-" . $resno2;
      $post = $post . "-" . $post2;
      $tphone = $tphone . "-" . $tphone2 . "-" . $tphone3;
      $hphone = $hphone . "-" . $hphone2 . "-" . $hphone3;
      if (!$part) $part = 0;

      for ($ii = 0; $ii < count($permi); $ii++) {
         $tmp_permi .= $permi[$ii] . "/";
      }

      $sql = "select passwd from df_site_admin where id = '$id'";
      $passwd_ori_db = $db->single($sql);
      // 비번이 맞아야 update 진행
      if (!password_verify($passwd, $passwd_ori_db)) {
         error("비밀번호를 확인해 주세요.");
      }

      // var_dump($_POST);
      // exit;

      // reset_passwd 이 있는경우 update 전 기존 비밀번호(passwd) 맞는지 확인후 맞으면 reset_passwd 로 변경
      $passwd_new = "";
      if (!empty($reset_passwd)) {
         // $reset_passwd 과 $reset_passwd_chk 이 같은지 확인
         if ($reset_passwd != $reset_passwd_chk) {
            error("변경할 비밀번호와 변경할 비밀번호 확인이 일치하지 않습니다.");
         }

         if (password_verify($passwd, $passwd_ori_db)) {
            $passwd_new = password_hash($reset_passwd, PASSWORD_DEFAULT);
         } else {
            error("기존 비밀번호가 맞지 않습니다.");
         }
      }

      $sql  = "update df_site_admin set ";
      if ($passwd_new != "") $sql .= "passwd = '$passwd_new', ";
      $sql .= "name = '$name', resno = '$resno', email = '$email', tphone = '$tphone', hphone = '$hphone', post = '$post', address = '$address', address2 = '$address2', part='$part', permi='$tmp_permi', descript = '$descript' where id = '$id'";
      $db->query($sql);

      complete("관리자 정보가 수정되었습니다.", "set_admin_input.php?admin_mode=update&id=$id");
   } else if ($admin_mode == "delete") {

      $sql = "select count(*) from df_site_admin";
      $total = $db->single($sql);

      if ($total <= 1) error("관리자계정이 하나밖에 없습니다. 삭제할 수 없습니다.");

      $sql = "delete from df_site_admin where id='$admin_id'";
      $db->query($sql);

      complete("관리자 삭제되었습니다.", "set_admin.php");
   }
}
