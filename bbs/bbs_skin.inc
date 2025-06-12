<?php
// 게시글 설정
$page_set = $bbs_info['rows'] ? $bbs_info['rows'] : 10;
$block_set = $bbs_info['lists'] ? $bbs_info['lists'] : 10;
if (!$page) $page = 1;

// 조건 설정
$addSql  = "";
$addSql .= " And b.code = :code ";
$addSql .= " And b.depno = 0 ";

// 게시글
$sql  = "";
$sql .= "	Select	COUNT(*) ";
$sql .= "	From	df_site_bbs b ";
$sql .= "	Where	1 = 1 ";
$sql .= $addSql;
$db->bind("code", $code);
$total = $db->single($sql);
$pageCnt = (($total - 1) / $page_set) + 1; //전체 페이지의 수
if ($page > $pageCnt) $page = 1;

$total_page = ceil($total / $page_set); // 총페이지수(올림함수)
$offset = ($page - 1) * $page_set; // 페이지 시작 레코드 번호
?>

<div class="gallery_notice_con">
	<ul>
		<?php
		if ($total > 0) {
			$sql  = "";
			$sql .= "	Select	b.*, ";
			$sql .= "			(Select COUNT(*) From df_site_bbs_files Where bbsidx = b.idx) As file_cnt ";
			$sql .= "	From	df_site_bbs b ";
			$sql .= "	Where	1 = 1 ";
			$sql .= $addSql;
			$sql .= "	Order by	b.notice Desc, b.prino Desc ";
			$sql .= "	Limit	:offset, :page_set";
			$db->bind("code", $code);
			$db->bind("offset", $offset);
			$db->bind("page_set", $page_set);	
			$bbs_row = $db->query($sql);

			for ($i = 0; $i < count($bbs_row); $i++) {
				$bbs_row[$i]['subject'] = mb_strimwidth($bbs_row[$i]['subject'], 0, 80, "..", "UTF-8");
				$end_class = "";
				$today = date("Y-m-d");
				if ($today <= $bbs_row[$i]['event_sdate'] || $today >= $bbs_row[$i]['event_edate']) {
					$end_class = "end";
				}
				$thumb = "/img/sub/gallery_notice_list_img_con_none_img.png";
				if ($bbs_row[$i]['upfile'] != "") {
					if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$code}/" . $bbs_row[$i]['upfile'])) {
						$thumb = "/userfiles/{$code}/" . $bbs_row[$i]['upfile'];
					}
				}
		?>
				<li class="<?= $end_class ?>">
					<a href="/bbs/bbs_view.php?idx=<?= $bbs_row[$i]['idx'] ?>&page=<?= $page ?>&<?= $param ?>">
						<div class="list_div">
							<div class="img_con" style="background-image:url('<?= $thumb ?>');">
								<img src="/img/sub/gallery_notice_list_img_con_blank_img.png" alt="컨텐츠 이미지" class="fx" />
							</div>

							<div class="text_con">
								<div class="text01_con">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td align="left" class="text_td">
													<span>
														<?= $bbs_row[$i]['subject'] ?>
													</span>
												</td>
												<td align="left" class="arrow_td">
													<img src="/img/sub/gallery_notice_list_arrow.svg" alt="화살표" class="fx" />
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<div class="text02_con">
									<div class="state_icon">
										<span class="icon icon01">
											진행중
										</span>

										<span class="icon icon02">
											마감
										</span>
									</div>

									<span>
										<?= str_replace("-", ".", $bbs_row[$i]['event_sdate']) ?>~<?= str_replace("-", ".", $bbs_row[$i]['event_edate']) ?>
									</span>
								</div>
							</div>
						</div>
					</a>
				</li>
			<?php
			}
		} else {
			?>
			<li class="none_li">
				<span>
					등록된 게시글이 없습니다.
				</span>
			</li>
		<?php
		}
		?>
	</ul>
</div>

<?php
print_pagelist_hs($total, $page_set, $block_set, $page, "&s_grp=" . $s_grp . "&" . $param, $code);
?>