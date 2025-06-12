					<div class="customer_notice_con">
						<ul>
<?
// 게시글 설정
$page_set = $bbs_info['rows'] ? $bbs_info['rows'] : 10;
$block_set = $bbs_info['lists'] ? $bbs_info['lists'] : 10;
if(!$page) $page = 1;


// 조건 설정
$addSql  = "";
$addSql .= " And b.code = '".$code."' ";
$addSql .= " And b.depno = 0 ";
$addSql .= " And IFNULL(b.notice,'') != 'Y' ";
if($search_key){
	if($search_opt == "All")
		$addSql .= " And (b.subject Like '%".$search_key."%' Or b.content Like '%".$search_key."%') ";
	else
		$addSql .= " And b.".$search_opt." Like '%".$search_key."%' ";
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
	$sql .= "	Select	b.* ";
	$sql .= "	From	df_site_bbs b ";
	$sql .= "	Where	1 = 1 ";
	$sql .= $addSql;
	$sql .= "	Order by	b.prino Desc ";
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
			
		$bbs_row[$i]['subject'] = mb_strimwidth($bbs_row[$i]['subject'],0,46,"..","UTF-8");
?>
							<li>
								<a href="/bbs/bbs_view.php?idx=<?=$bbs_row[$i]['idx']?>&page=<?=$page?>&<?=$param?>">
									<div class="list_div">
										<table cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td align="left" class="img_td">
														<div class="img_con" style="background-image:url('<?=$thumbnail?>');"></div>
													</td>
													<td valign="top" align="left" class="text_td">
														<div class="title_con">
															<span>
																<?=$bbs_row[$i]['subject']?>
															</span>
														</div>

														<div class="bar"></div>

														<div class="info_con">
															<span>
																<? if($code=="CommunityHundred"){ ?>
																<?=$bbs_row[$i]['name']?>
																<? }else{ ?>
																<?=$bbs_row[$i]['name']?> <font class="bar">│</font> <?=date("Y.m.d",strtotime($bbs_row[$i]['wdate']))?> <font class="bar">│</font> 조회수 : <?=number_format($bbs_row[$i]['count'])?>
																<? } ?>
															</span>
															
														</div>
													</td>
												</tr>
											</tbody>
										</table>
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