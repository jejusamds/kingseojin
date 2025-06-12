<?php
$code = $_REQUEST['code'];
switch ($code) {
	case "news":
		$Menu = "05";
		$sMenu = "05-1";
		$sMenu_slide = "0";
		break;
	case "tv":
		$Menu = "05";
		$sMenu = "05-2";
		$sMenu_slide = "1";
		break;
	case "review":
		$Menu = "05";
		$sMenu = "05-3";
		$sMenu_slide = "2";
		break;
}
$s_opt = "All";
include $_SERVER["DOCUMENT_ROOT"] . "/include/header.html";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/bbs_info.inc";

$param = "code=" . $code . "&s_opt=" . $s_opt . "&s_key=" . $s_key;
?>
<div id="container">
	<div id="sub_con">
		<?php
		include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_banner.html';
		?>

		<?php
		include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_nav.html';
		?>

		<div class="contents_con">
			<div class="contents_con">
				<div class="notice_list_con">
					<?
					if ($bbs_info['bbstype'] == "PHOTO")
						include "photo_skin.inc";
					else if ($bbs_info['bbstype'] == "FAQ")
						include "faq_skin.inc";
					else if ($bbs_info['bbstype'] == "BBS")
						include "bbs_skin.inc";
					else if ($bbs_info['bbstype'] == "MEDIA")
						include "media_skin.inc";
					?>
				</div>
			</div>
			<?php
			include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_information.html';
			?>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		// 게시글 마감 시 예외처리
		$(".gallery_notice_con > ul > li.end").each(function() {
			$(this).find("a").attr("href", "javascript:alert('마감되었습니다.');");
		});
	});
</script>
<? include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html'; ?>