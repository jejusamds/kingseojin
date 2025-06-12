<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>
<?
if ($admin_mode == "update") {
	$sql = "select * from df_site_admin where id = '$id'";
	$admin_info = $db->row($sql);
}
?>

<script language="javascript">
	<!--
	function inputCheck(frm) {
		if (frm.id.value == "") {
			alert("관리자 아이디를 입력하세요");
			frm.id.focus();
			return false;
		}
		if (frm.passwd.value == "") {
			alert("관리자 비밀번호를 입력하세요");
			frm.passwd.focus();
			return false;
		}
		if (frm.name.value == "") {
			alert("관리자 이름을 입력하세요");
			frm.name.focus();
			return false;
		}
		if (frm.email.value == "") {
			alert("관리자 이메일을 입력하세요");
			frm.email.focus();
			return false;
		}

		if (frm.reset_passwd.value != "" || frm.reset_passwd_chk.value != "") {
			if (frm.reset_passwd.value == "") {
				alert("변경할 비밀번호를 입력하세요");
				frm.reset_passwd.focus();
				return false;
			}
			if (frm.reset_passwd_chk.value == "") {
				alert("변경할 비밀번호 확인을 입력하세요");
				frm.reset_passwd_chk.focus();
				return false;
			}
			if (frm.reset_passwd.value != frm.reset_passwd_chk.value) {
				alert("변경할 비밀번호와 변경할 비밀번호 확인이 일치하지 않습니다.");
				frm.reset_passwd.focus();
				return false;
			}
		}
	}

	// 주소찾기
	function searchZip() {
		var url = "../member/search_zip.php";
		window.open(url, "searchZip", "height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

	function checkBasic(type) {
		var check00 = document.getElementById("01-00").checked;
		document.getElementById("01-01").checked = check00;
		document.getElementById("01-02").checked = check00;
		document.getElementById("01-03").checked = check00;
	}

	function checkBasic2(ck) {
		var check00 = document.getElementById("01-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("01-00").checked = true;
			document.getElementById("01-01").checked = true;
		}
	}

	function checkPage(type) {
		var check00 = document.getElementById("02-00").checked;
		document.getElementById("02-01").checked = check00;
		document.getElementById("02-02").checked = check00;
		document.getElementById("02-03").checked = check00;
		document.getElementById("02-04").checked = check00;
		document.getElementById("02-05").checked = check00;
		document.getElementById("02-06").checked = check00;
	}

	function checkPage2(ck) {
		var check00 = document.getElementById("02-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("02-00").checked = true;
			document.getElementById("02-01").checked = true;
		}
	}

	function checkAuto(type) {
		var check00 = document.getElementById("03-00").checked;
	}

	function checkMail(type) {
		var check00 = document.getElementById("04-00").checked;
		document.getElementById("04-01").checked = check00;
		document.getElementById("04-02").checked = check00;
	}

	function checkMail2(ck) {
		var check00 = document.getElementById("04-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("04-00").checked = true;
			document.getElementById("04-01").checked = true;
		}
	}

	function checkReserve(type) {
		var check00 = document.getElementById("05-00").checked;
		document.getElementById("05-01").checked = check00;
		document.getElementById("05-02").checked = check00;
		document.getElementById("05-03").checked = check00;
		document.getElementById("05-04").checked = check00;
		document.getElementById("05-05").checked = check00;
	}

	function checkReserve2(ck) {
		var check00 = document.getElementById("05-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("05-00").checked = true;
			document.getElementById("05-01").checked = true;
		}
	}

	function checkOrder(type) {
		var check00 = document.getElementById("06-00").checked;
		document.getElementById("06-01").checked = check00;
	}

	function checkOrder2(ck) {
		var check00 = document.getElementById("06-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("06-00").checked = true;
			document.getElementById("06-01").checked = true;
		}
	}

	function checkMember(type) {
		var check00 = document.getElementById("07-00").checked;
		document.getElementById("07-01").checked = check00;
		document.getElementById("07-02").checked = check00;
		document.getElementById("07-03").checked = check00;
		document.getElementById("07-04").checked = check00;
		document.getElementById("07-05").checked = check00;
	}

	function checkMember2(ck) {
		var check00 = document.getElementById("07-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("07-00").checked = true;
			document.getElementById("07-01").checked = true;
		}
	}

	function checkBbs(type) {
		var check00 = document.getElementById("08-00").checked;
		document.getElementById("08-01").checked = check00;
		document.getElementById("08-02").checked = check00;
	}

	function checkBbs2(ck) {
		var check00 = document.getElementById("08-00").checked
		if (ck.checked == true || check00) {
			document.getElementById("08-00").checked = true;
			document.getElementById("08-01").checked = true;
		}
	}

	function checkMarketing(type) {
		var check00 = document.getElementById("09-00").checked;
	}
	//
	-->
</script>

<div class="pageWrap">
	<div class="page-heading">
		<h3>
			관리자 설정
		</h3>
		<ul class="breadcrumb">
			<li>관리자설정</li>
			<li class="active">관리자 설정</li>
		</ul>
	</div>

	<form name="frm" action="set_save.php" method="post" onSubmit="return inputCheck(this);">
		<input type="hidden" name="tmp">
		<input type="hidden" name="mode" value="set_admin">
		<input type="hidden" name="admin_mode" value="<?= $admin_mode ?>">
		<input type="hidden" name="part" value="0">
		<div class="box" style="width:978px;">
			<div class="panel">
				<table class="table orderInfo" cellpadding="0" cellspacing="0">
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
					<tr>
						<th>아이디</th>
						<td class="comALeft"><input type="text" name="id" value="<?= $admin_info['id'] ?>" class="form-control" style="width:88%;" <? if ($admin_mode == "update") { ?>readOnly="readOnly" <? } ?> /></td>
						<th><?= $admin_mode == "update" ? "기존" : "" ?> 비밀번호</th>
						<td class="comALeft"><input type="password" name="passwd" class="form-control" style="width:88%;"></td>
					</tr>
					<?php if ($admin_mode == "update") { ?>
						<tr>
							<th>변경할 비밀번호</th>
							<td class="comALeft"><input type="password" name="reset_passwd" class="form-control" style="width:88%;" placeholder="비밀번호를 변경할경우에만 입력해주세요." /></td>
							<th>변경할 비밀번호 확인</th>
							<td class="comALeft"><input type="password" name="reset_passwd_chk" class="form-control" style="width:88%;"></td>
						</tr>
					<?php } ?>
					<tr>
						<th>이름</th>
						<td class="comALeft"><input type="text" name="name" value="<?= $admin_info['name'] ?>" class="form-control" style="width:88%;" /></td>
						<th>이메일</th>
						<td class="comALeft"><input type="text" name="email" value="<?= $admin_info['email'] ?>" class="form-control" style="width:88%;"></td>
					</tr>
				</table>
				</td>
				</tr>
				</table>
			</div>
		</div>

		<div class="box comMTop10 comMBottom10" style="width:978px;">
			<div class="comPTop10 comPBottom10 comACenter">
				<button class="btn btn-info btn-sm" type="submit"> 확 인 </button>
				<button class="btn btn-default btn-sm" type="button" onClick="location.href='set_admin.php';"> 취 소 </button>
			</div>
		</div>

	</form>

</div>
</div>
</div>
</div>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
	function execDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var fullAddr = ''; // 최종 주소 변수
				var fullAddr_alt = ''; // 최종 주소 변수
				var extraAddr = ''; // 조합형 주소 변수

				// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;
					fullAddr_alt = data.jibunAddress;
				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
					fullAddr_alt = data.roadAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
				if (data.userSelectedType === 'R') {
					//법정동명이 있을 경우 추가한다.
					if (data.bname !== '') {
						extraAddr += data.bname;
					}
					// 건물명이 있을 경우 추가한다.
					if (data.buildingName !== '') {
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
					fullAddr += (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				document.getElementById('com_post').value = data.zonecode; //5자리 새우편번호 사용
				document.getElementById('com_address').value = fullAddr;
			}
		}).open();
	}
</script>

</body>

</html>