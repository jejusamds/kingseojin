<?
include "../inc/global.inc";
include "../inc/util_lib.inc";


if ($admin_id == "") error("아이디를 입력하세요");
if ($admin_pw == "") error("비밀번호를 입력하세요");


$sql = " Select id, passwd, name, email, tphone, part From df_site_admin Where id = '" . $admin_id . "' ";
$admin_info = $db->row($sql);

if (password_verify($admin_pw, $admin_info['passwd'])) {
	//방문회수 증가
	$sql = "update df_site_admin set last = now() where id='$admin_id'";
	$db->query($sql);

	// 아이디 저장
	if ($_POST['saveid'] == "Y") setcookie("admin_id", $admin_id, time() + 3600 * 24 * 365, "/");

	$_SESSION['admin_id'] = $admin_info['id'];
	$_SESSION['admin_name'] = $admin_info['name'];
	$_SESSION['admin_email'] = $admin_info['email'];
	$_SESSION['admin_tphone'] = $admin_info['tphone'];
	$_SESSION['admin_part'] = $admin_info['part'];
	$_SESSION['admin_code'] = "";

	Header("Location: ./");
} else {
	error("회원정보가 일치하지 않습니다.", "");
}
