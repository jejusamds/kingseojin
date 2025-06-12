						<div class="gallery_notice_con">
							<ul>
<?
// 게시글 설정
$page_set = $bbs_info['rows'] ? $bbs_info['rows'] : 9;
$block_set = $bbs_info['lists'] ? $bbs_info['lists'] : 10;
if(!$page) $page = 1;


// 조건 설정
$addSql  = "";
$addSql .= " And b.code = '".$code."' ";
$addSql .= " And b.depno = 0 ";
if($s_grp != "")
	$addSql .= " And b.grp = '".$s_grp."' ";
if($s_key != ""){
	if($s_opt == "All")
		$addSql .= " And (b.subject Like '%".$s_key."%' Or b.content Like '%".$s_key."%') ";
	else
		$addSql .= " And b.".$s_opt." Like '%".$s_key."%' ";
}

// 게시글
$sql  = "";
$sql .= "	Select	COUNT(*) ";
$sql .= "	From	df_site_bbs b ";
$sql .= "	Where	1 = 1 ";
$sql .= $addSql;
$total = $db->single($sql);
$pageCnt = (($total-1)/$page_set) +1;						//전체 페이지의 수
if($page > $pageCnt) $page = 1;

if($total > 0){
	$sql  = "";
	$sql .= "	Select	b.*, ";
	$sql .= "			(Select upfile From df_site_bbs_files Where bbsidx=b.idx Order by idx Asc Limit 1) As upfile ";
	$sql .= "	From	df_site_bbs b ";
	$sql .= "	Where	1 = 1 ";
	$sql .= $addSql;
	$sql .= "	Order by	b.notice Desc, b.prino Desc ";
	$sql .= "	Limit	" .(($page-1)*$page_set). "," .$page_set;
	$bbs_row = $db->query($sql);
	
	$open_tag = false;
	for($i=0; $i<count($bbs_row); $i++){
		if(is_file("../userfiles/".$code."/".$bbs_row[$i]['upfile']))
			$thumbnail = "/userfiles/".$code."/".$bbs_row[$i]['upfile'];
		else{
			$tmp = getMPrvImg($bbs_row[$i]['content']);
			$thumbnail = $tmp[1][0];
		}
			
		$bbs_row[$i]['subject'] = mb_strimwidth($bbs_row[$i]['subject'],0,28,"..","UTF-8");
		
		if($code == "Event"){
			if($bbs_row[$i]['event_edate'] < date("Y-m-d"))
				$div_class = "end";
			else
				$div_class = "ing";
		}
		else
			$div_class = "";
?>
								<li>
									<a href="/bbs/bbs_view.php?idx=<?=$bbs_row[$i]['idx']?>&page=<?=$page?>&s_grp=<?=$s_grp?>&<?=$param?>">
										<div class="list_div <?=$div_class?>">
											<div class="img_con">
												<!--<div class="ing_con">
													<img src="/img/sub/gallery_notice_event_ing.png" alt="진행중" />
												</div>-->

												<div class="end_con">
													<div class="text_con">
													<? if($s_grp == "예약상품" || $bbs_row[$i]['grp']=="예약상품"){ ?>
														<span>
															예약상품이<br />
															마감 되었습니다.
														</span>
													<? } else { ?>
														<span>
															행사가<br />
															마감 되었습니다.
														</span>
													<? } ?>
													</div>
												</div>

												<img src="<?=$thumbnail?>" alt="썸네일 이미지" width="383" height="383" />
											</div>

											<div class="text_con">
												<? if($bbs_info['grp'] != ""){ ?>
												<div class="sale_con">
													<span>
														<?=$bbs_row[$i]['grp']?>
													</span>
												</div>
												<? } ?>

												<div class="title_con">
													<span>
														<?=$bbs_row[$i]['subject']?>
													</span>
												</div>

												<div class="date_con">
													<span>
														<?=date("Y.m.d",strtotime($bbs_row[$i]['event_sdate']))?> ~ <?=date("Y.m.d",strtotime($bbs_row[$i]['event_edate']))?>
													</span>
												</div>
											</div>
										</div>
									</a>
								</li>
<?
	}
}
else{
?>
								<li class="none_li">
									<span>
										등록된 게시글이 없습니다.
									</span>
								</li>
<?
}
?>
							</ul>
						</div>
