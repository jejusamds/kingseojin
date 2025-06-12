<? if($_SESSION[userid] == ""){ ?>
	<script>
		alert("로그인 후 이용하세요");
		location.href = "/mobile/member/login.html";
	</script>
<? } ?>