<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/bbs_info.inc"; ?>

<?
if($code == "event" && !$searchgrp)
	$searchgrp = "Y";
$param = "code=".$code."&searchgrp=".$searchgrp."&search_option=".$search_option."&keyword=".$keyword;
?>
			<script language="JavaScript" type="text/javascript">
			<!--
			//체크박스선택 반전
			function onSelect(form){
				if(form.select_tmp.checked){
					selectAll();
				}else{
					selectEmpty();
				}
			}

			//체크박스 전체선택
			function selectAll(){
				
				var i; 	
				
				for(i=0;i<document.forms.length;i++){
					if(document.forms[i].idx != null){
						if(document.forms[i].select_checkbox){
							document.forms[i].select_checkbox.checked = true;
						}
					}
				}
				return;
			}

			//체크박스 선택해제
			function selectEmpty(){

				var i;

				for(i=0;i<document.forms.length;i++){
					if(document.forms[i].select_checkbox){
						if(document.forms[i].idx != null){
							document.forms[i].select_checkbox.checked = false;
						}
					}
				}
				return;
			}

			//선택게시물 삭제
			function bbsDelete(){

				var i;
				var seluser = "";
				for(i=0;i<document.forms.length;i++){
					if(document.forms[i].idx != null){
						if(document.forms[i].select_checkbox){
							if(document.forms[i].select_checkbox.checked)
								seluser = seluser + document.forms[i].idx.value + "|";
							}
						}
				}
				
				if(seluser == ""){
					alert("삭제할 게시물을 선택하세요.");
					return;
				}else{
					if(confirm("선택한 게시물을 정말 삭제하시겠습니까?")){
						document.location = "bbs_save.php?mode=delbbs&seluser="+seluser+"&page=<?=$page?>&<?=$param?>";
					}
				}
			}

			//-->
			</script>

			<style>
				.pagination {margin:0 auto;}
			</style>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						<?=$bbs_info['title']?>
					</h3>
					<ul class="breadcrumb">
						<li>게시판 관리</li>
						<li class="active"><?=$bbs_info['title']?></li>
					</ul>
				</div>

				<form action="bbs_list.php" method="get">
				<input type="hidden" name="code" value="<?=$code?>" />
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="80"/><col width=""/>
							<tbody>
								<tr>
									<td>조건검색</td>
									<td class="comALeft" style="padding-left:5px">
										<?
										if($code == "event"){
										?>
										<select name="searchgrp" onChange="document.location='<?=$_SERVER['PHP_SELF']?>?code=<?=$code?>&searchgrp='+this.value" class="form-control" style="width:auto;">
											<option value="A" <? if($searchgrp=="A"){ ?>selected<? } ?>>진행여부</option>
											<option value="Y" <? if($searchgrp=="Y"){ ?>selected<? } ?>>진행</option>
											<option value="N" <? if($searchgrp=="N"){ ?>selected<? } ?>>종료</option>
										</select>
										<?										
										}
										else{
											if($bbs_info['grp'] != ""){
												$catlist = explode(",",$bbs_info['grp']);
										?>
										<select name="searchgrp" onChange="document.location='<?=$_SERVER['PHP_SELF']?>?code=<?=$code?>&searchgrp='+this.value" class="form-control" style="width:auto;">
											<option value="">분류</option>
											<?
											for($ii=0;$ii<count($catlist);$ii++){
												if($searchgrp == $catlist[$ii]) $selected = "selected";
												else $selected = "";
											?>
											<option value="<?=$catlist[$ii]?>" <?=$selected?>><?=$catlist[$ii]?></option>
											<?
											}
											?>
										</select>
										<?
											}
										}
										?>
										<select name="search_option" class="form-control" style="width:auto;">
											<option value="subject" <? if($search_option=="subject"){ ?>selected<? } ?>>제목</option>
											<option value="content" <? if($search_option=="content"){ ?>selected<? } ?>>내용</option>
											<option value="name" <? if($search_option=="name"){ ?>selected<? } ?>>작성자</option>
										</select>
										<input type="text" name="keyword" value="<?=$keyword?>" class="form-control" style="width:auto;"/>
										<button class="btn btn-info btn-sm" type="submit">검색</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				</form>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<table class="table" cellpadding="0" cellspacing="0">
							<col width="20"/>
                            <col width="20"/>
                            <col width="200"/>
                            <col width="100"/>
                            <col width="120"/>
                            <col width="60"/>
							<thead>
								<form>
								<tr>
									<td><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></td>
									<td>번호</td>
									<td>제목</td>
									<td>작성자</td>
									<?if($code=="CenterQna" || $code=="CenterNew"){?>
									<td>답변여부</td>
									<?}?>
									<td><?=$code=="event" ? "기간" : "작성일";?></td>
									<td>조회</td>
								</tr>
								</form>
							</thead>
							<tbody>
<?
// 공지글 가져오기
$sql = "select * from df_site_bbs where code = '$code' and notice = 'Y' order by prino desc";
$row = $db->query($sql);
for($i=0; $i<count($row); $i++){
?>
								<form name="frm<?=$no?>">
								<input type="hidden" name="idx" value="<?=$row[$i]['idx']?>">
								<tr>
									<td><input type="checkbox" name="select_checkbox"></td>
									<td><font color='red'><b>[공지]</b></font></td>
									<td class="comALeft"><a href="bbs_view.php?idx=<?=$row[$i]['idx']?>&page=<?=$page?>&<?=$param?>"><font color='B90020'><?=$row[$i]['subject']?></font></a></td>
									<td><?=$row[$i]['name']?></td>
									<td><?=$row[$i]['wdate']?></td>
									<td><?=$row[$i]['count']?></td>
								</tr>
								</form>
<?
}


$page_set = 15;
$block_set = 10;
if(!$page) $page = 1;


$addSql  = "";
$addSql .= " And b.code = '".$code."' ";
$addSql .= " And b.notice != 'Y' ";
$addSql .= " And b.depno = 0 ";
if($searchgrp){
	if($code == "event"){
		if($searchgrp == "Y")
			$addSql .= " And b.event_edate >= DATE_FORMAT(NOW(),'%Y-%m-%d') ";
		else if($searchgrp == "N")
			$addSql .= " And b.event_edate < DATE_FORMAT(NOW(),'%Y-%m-%d') ";
	}
	else{
		$addSql .= " And b.grp = '".$searchgrp."' ";
	}
}
if($keyword)
	$addSql .= " And b.".$search_option." Like '%".$keyword."%' ";

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
	$sql .= "	Order by	b.idx Desc ";
	$sql .= "	Limit	" .(($page-1)*$page_set). "," .$page_set;
    
	$row = $db->query($sql);

	for($i=0; $i<count($row); $i++){
		$row[$i]['subject'] = "<a href=\"bbs_view.php?idx=".$row[$i]['idx']."&page=".$page."&".$param."\">".$row[$i]['subject']."</a>";	//	subject
		if($code == "event"){
			if($row[$i]['event_edate'] >= date("Y-m-d")) $row[$i]['subject'] = "[진행] ".$row[$i]['subject'];						// grp
			else if($row[$i]['event_edate'] < date("Y-m-d")) $row[$i]['subject'] = "[종료] ".$row[$i]['subject'];						// grp
		}
		else{
			if($row[$i]['grp'] != "") $row[$i]['subject'] = "[".$row[$i]['grp']."] ".$row[$i]['subject'];						// grp
		}
		if($row[$i]['privacy'] == "Y") $row[$i]['subject'] = "<img src=../img/bbs/icon_lock.gif border=0 align=absmiddle> ".$row[$i]['subject'];	// privacy
		$re_space = ""; for($ii=0; $ii < $row[$i]['depno']; $ii++) $re_space .= "&nbsp;&nbsp;";					// respace
		$row[$i]['subject'] = $re_space.$row[$i]['subject'];
		if($row[$i]['depno'] != 0) $row[$i]['subject'] = "<img src=../img/bbs/icon_re.gif align=absmiddle>".$row[$i]['subject'];				// re

		if((date("Ymd")-date("Ymd",strtotime($row[$i]['wdate']))) < $bbs_info['new']) $row[$i]['subject'] .= " <img src=../img/bbs/icon_new.gif> ";	// new
		if($row[$i]['count'] > $bbs_info['hot']) $row[$i]['subject'] .= " <img src=../img/bbs/icon_hot.gif> ";	// hot

		$row2total = 0;
		if($code == "CenterNew" || $code == "CenterQna"){
			$sql2 = " Select * From df_site_bbs Where  parno='".$row[$i]['idx']."' And depno>0 ";
			$row2 = $db->query($sql2);
			$row2total = count($row2);
		}
?>
								<form name="frm<?=$no?>">
								<input type="hidden" name="idx" value="<?=$row[$i]['idx']?>">
								<tr>
									<td><input type="checkbox" name="select_checkbox"></td>
									<td><?=$total-($page-1)*$page_set-$i?></td>
									<td class="comALeft"><?=$re_space?><?=$row[$i]['subject']?></td>
									<td>
										<?=$row[$i]['name']?>
										<? if($row[$i]['memid']){ ?>
										<br/>(<a href="/Madmin/member/member_input.php?id=<?=$row[$i]['memid']?>" target="_blank"><?=$row[$i]['memid']?></a>)
										<? } ?>
									</td>
									<?if($code=="CenterQna" || $code=="CenterNew"){?>
									<td><?=$row2total>0?"답변":"미답변"?></td>
									<?}?>
									<td>
										<? if($code == "event"){ ?>
										<?=$row[$i]['event_sdate']?> ~ <?=$row[$i]['event_edate']?>
										<? }else{ ?>
										<?=$row[$i]['wdate']?>
										<? } ?>
									</td>
									<td><?=$row[$i]['count']?></td>
								</tr>
								</form>
<?
	}
}
else{
?>
								<tr>
									<td height="30" colspan="7" align="center">등록된 글이 없습니다.</td>
								</tr>
<?
}
?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
							<button class="btn btn-danger btn-sm" type="button" onClick="bbsDelete();">삭제</button>
						</div>
						<div class="comFCenter comACenter" style="width:70%; display:inline-block;">
							<? print_pagelist_admin($total, $page_set, $block_set, $page, "&".$param); ?>
						</div>
						<div class="comFRight comARight" style="width:15%; padding-right:10px;">
							<button class="btn btn-default btn-sm" type="button" onClick="location.href='bbs_input.php?page=<?=$page?>&<?=$param?>';">글등록</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>
