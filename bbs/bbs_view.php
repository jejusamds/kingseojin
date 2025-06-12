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
include $_SERVER["DOCUMENT_ROOT"] . "/include/header.html";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/bbs_info.inc"; 	 	// 게시판 정보

$param = "code=" . $code;

// 게시물 정보
$sql = " Select * From df_site_bbs Where idx = :idx ";
$db->bind("idx", $idx);
$bbs_row = $db->row($sql);

// 첨부파일 정보
$sqlF = " Select * From df_site_bbs_files Where bbsidx = :idx Order by idx Asc ";
$db->bind("idx", $idx);
$file_row = $db->query($sqlF);

// 조회수 증가
$sql = " Update df_site_bbs Set count=count+1 Where idx = :idx ";
$db->bind("idx", $idx);
$db->query($sql);

// 이전글
$sql = " Select idx, subject From df_site_bbs Where code = :code And prino < :prino And depno=0 Order by prino Desc Limit 1 ";
$db->bind("code", $code);
$db->bind("prino", $bbs_row['prino']);
$rowP = $db->row($sql);
// 다음글
$sql = " Select idx, subject From df_site_bbs Where code = :code And prino > :prino And depno=0 Order by prino Asc Limit 1 ";
$db->bind("code", $code);
$db->bind("prino", $bbs_row['prino']);
$rowN = $db->row($sql);
?>
<div id="container">
	<div id="sub_con">
		<div class="contents_con">
			<div class="contents_con">

				<div class="notice_view_con">
					<div class="contents_con">
						<div class="title_con">
							<div class="text01_con">
								<span>
									<?= $bbs_row['subject'] ?>
								</span>
							</div>

							<div class="text02_con">
								<?php if ($code != 'news') { ?>
									<span>
										<?= str_replace('-', '.', date('Y-m-d', strtotime($bbs_row['wdate']))) ?>
									</span>
								<?php } else { ?>
									<span class="state_icon">
										진행중
									</span>
									<span>
										<?= str_replace("-", ".", $bbs_row['event_sdate']) ?>~<?= str_replace("-", ".", $bbs_row['event_edate']) ?>
									</span>
								<?php } ?>

							</div>
						</div>

						<div class="contents_con">
							<div class="posts_con">
								<?php if ($code == 'tv') { ?>
									<div class="video_con">
										<iframe src="https://www.youtube.com/embed/<?= $bbs_row['media_url'] ?>" title="YouTube video player"
											frameborder="0"
											allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
											referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
									</div>
								<?php } ?>
								<div class="contents_con">
									<?= $bbs_row['content'] ?>
								</div>
							</div>
						</div>
					</div>

					<div class="btn_con">
						<a href="/bbs/bbs_list.php?<?= $param ?>" class="a_btn">
							목록
						</a>
					</div>
				</div>

			</div>

			<?php
			include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_information.html';
			?>
		</div>
	</div>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html';
?>