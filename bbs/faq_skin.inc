<?
// 게시글 설정
$page_set = $bbs_info['rows'] ? $bbs_info['rows'] : 10;
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

$total_page = ceil ($total / $page_set); // 총페이지수(올림함수)
?>
						<div class="search_con">
							<form method="get" name="frmBBSSearch" action="bbs_list.php">
							<input type="hidden" name="code" value="<?=$code?>" />
								<div class="select_con">
									<select name="s_opt" class="select">
										<option value="All" <? if($s_opt=="All"){ ?>selected<? } ?>>전체</option>
										<option value="subject" <? if($s_opt=="subject"){ ?>selected<? } ?>>제목</option>
										<option value="content" <? if($s_opt=="content"){ ?>selected<? } ?>>내용</option>
									</select>
								</div>

								<div class="input_con">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td align="left">
													<input type="text" name="s_key" value="<?=$s_key?>" placeholder="검색어를 입력하세요." class="input" />
												</td>
												<td align="center" class="btn_td">
													<input type="image" src="/img/sub/notice_search_btn.png" alt="검색 버튼" />
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</form>
						</div>

						<div class="faq_notice_con">
							<ul>
<?
if($total > 0){
	$sql  = "";
	$sql .= "	Select	b.* ";
	$sql .= "	From	df_site_bbs b ";
	$sql .= "	Where	1 = 1 ";
	$sql .= $addSql;
	$sql .= "	Order by	b.notice Desc, b.prino Desc ";
	$sql .= "	Limit	" .(($page-1)*$page_set). "," .$page_set;
	$bbs_row = $db->query($sql);
	
	for($i=0; $i<count($bbs_row); $i++){
		$bbs_row[$i]['subject'] = mb_strimwidth($bbs_row[$i]['subject'],0,80,"..","UTF-8");
?>
								<li>
									<div class="list_div">
										<div class="q_con">
											<table cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td valign="top" align="left" class="icon_td">
															<span>
																Q
															</span>
														</td>
														<td valign="top" align="left">
															<span>
																<?=$bbs_row[$i]['subject']?>
															</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="a_con">
											<table cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td valign="top" align="left" class="icon_td">
															<span>
																A
															</span>
														</td>
														<td valign="top" align="left">
															<span>
																<?=$bbs_row[$i]['content']?>
															</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
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

						<script type="text/javascript" language="javascript">
							// 자주하는 질문
							$(".faq_notice_con > ul > li .list_div > .q_con").bind("click",function(){
								$(".faq_notice_con > ul > li .list_div > .a_con").slideUp();

								if( $(this).next(".a_con").css("display")=="none" ){
									$(this).next().slideDown();
								}else{
									$(this).next().slideUp();
								}
							});
						</script>
