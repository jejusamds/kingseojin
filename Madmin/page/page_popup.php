<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&isuse=$isuse";
$param .= "&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//--------------------------------------------------------------------------------------------------
?>

<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx){
   if(confirm('해당팝업을 삭제하시겠습니까?')){
      document.location = "page_save.php?mode=pdelete&idx=" + idx;
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
						팝업관리 - PC
					</h3>
					<ul class="breadcrumb">
						<li>팝업 및 배너 설정</li>
						<li class="active">팝업관리 - PC</li>
					</ul>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
						<input type="hidden" name="tmp">
						<input type="hidden" name="page" value="<?=$page?>">
						<input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<tbody>
							<tr>
								<td width="90" height="26" align="right" style="padding-left:5px">공지기간</td>
								<td colspan="7" class="comALeft" style="padding-left:5px">
									<select name="prev_year" class="form-control" style="width:auto;">
										<?                     
										if(empty($next_year)) $next_year = date("Y");
										if(empty($next_month)) $next_month = date("m");
										if(empty($next_day)) $next_day = date("d");

										for($ii=2020; $ii <= date("Y")+1; $ii++){
											if($ii == $prev_year) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>년
									<select name="prev_month" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 12; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $prev_month) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>월
									<select name="prev_day" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 31; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $prev_day) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>일
									~
									<select name="next_year" class="form-control" style="width:auto;">
										<?
										for($ii=2020; $ii <= date("Y")+1; $ii++){
											if($ii == $next_year) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>년
									<select name="next_month" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 12; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $next_month) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>월
									<select name="next_day" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 31; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $next_day) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>일
								</td>
							</tr>
							<tr>
								<td height="26" align="right" style="padding-left:5px">사용여부</td>
								<td style="padding-left:5px" class="comALeft">
									<select name="isuse" class="form-control" style="width:auto;">
										<option value="">전체</option>
										<option value="Y" <? if($isuse == "Y") echo "selected"; ?>>사용</option>
										<option value="N" <? if($isuse == "N") echo "selected"; ?>>중지</option>
									</select>
								</td>
								<td width="1"><img src="../img/list_vline.gif" /></td>
								<td height="26" align="right" style="padding-left:5px">조건검색</td>
								<td class="comALeft" style="padding-left:5px">
									<select name="searchopt" class="form-control" style="width:auto;">
										<option value="title" <? if($searchopt == "title") echo "selected"; ?>>제목</option>
										<option value="content" <? if($searchopt == "content") echo "selected"; ?>>내용</option>
									</select>
									<input type="text" name="keyword" value="<?=$keyword?>" class="form-control"  style="width:auto;"/>
									<button class="btn btn-info btn-sm" type="submit">검색</button>
								</td>
							</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="60"/><col width=""/><col width="180"/><col width="100"/><col width="60"/><col width="110"/>
							<thead>
							<tr>
								<td>번호</td>
								<td>제목</td>
								<td>공지기간</td>
								<td>등록일</td>
								<td>사용여부</td>
								<td>기능</td>
							</tr>
							</thead>
							<tbody>
<?
$page_set = 15;
$block_set = 10;
if(!$page) $page = 1;


$addSql  = "";
$addSql .= " And type = 'popup' ";
if($prev_year != ""){
	$addSql .= " And DATE_FORMAT(sdate,'%Y-%m-%d') >= '".$prev_year."-".$prev_month."-".$prev_day."' ";
	$addSql .= " And DATE_FORMAT(edate,'%Y-%m-%d') <= '".$next_year."-".$next_month."-".$next_day."' ";
}
if($isuse != "")
	$addSql .= " And isuse = '".$isuse."' ";
if($keyword != "")
	$addSql .= " And ".$searchopt." Like '%".$keyword."%' ";

$sql  = "";
$sql .= "	Select	COUNT(*) ";
$sql .= "	From	df_site_content ";
$sql .= "	Where	1 = 1 ";
$sql .= $addSql;
$total = $db->single($sql);
$pageCnt = (($total-1)/$page_set) +1;						//전체 페이지의 수
if($page > $pageCnt) $page = 1;

if($total > 0){
	$sql  = "";
	$sql .= "	Select	* ";
	$sql .= "	From	df_site_content ";
	$sql .= "	Where	1 = 1 ";
	$sql .= $addSql;
	$sql .= "	Order by	idx Desc ";
	$sql .= "	Limit	" .(($page-1)*$page_set). "," .$page_set;
	$row = $db->query($sql);

	for($i=0; $i<count($row); $i++){
		if($row[$i]['edate'] < date("Y-m-d")){
			$row[$i]['isuse'] = "<font color='#0000FF'>만료</font>";
		}
		else{
			if($row[$i]['isuse'] == "Y") $row[$i]['isuse'] = "<font color='#FF0000'>사용</font>";
			else $row[$i]['isuse'] = "<font color='#0000FF'>중지</font>";
		}
?>
						  <tr>
							<td><?=$total-($page-1)*$page_set-$i?></td>
							<td class="comALeft"><?=$row[$i]['title']?></td>
							<td><?=$row[$i]['sdate']?> ~ <?=$row[$i]['edate']?></td>
							<td><?=$row[$i]['wdate']?></td>
							<td><?=$row[$i]['isuse']?></td>
							<td>
								<button class="btn btn-success btn-xs" type="button" onClick="location.href='popup_input.php?mode=pupdate&idx=<?=$row[$i]['idx']?>&page=<?=$page?>&<?=$param?>';">수정</button>
								<button class="btn btn-danger btn-xs" type="button" onClick="delContent('<?=$row[$i]['idx']?>');">삭제</button>
							</td>
						  </tr>
<?
	}
}
else{
?>
							<tr><td colspan="7">작성된 팝업이 없습니다.</td></tr>
<?
}
?>
							<tbody>
						</table>
					</div>
				</div>

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comACenter" style="width:85%; display:inline-block;">
							<? print_pagelist_admin($total, $page_set, $block_set, $page, $param); ?>
						</div>
						<div class="comFRight comARight" style="width:10%; padding-right:10px;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='popup_input.php?page=<?=$page?>&<?=$param?>';">팝업등록</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
					
<!--
<button class="btn btn-default btn-xs" type="button">Extra small button</button>
<button class="btn btn-primary btn-xs" type="button">Extra small button</button>
<button class="btn btn-info btn-xs" type="button">Extra small button</button>
<button class="btn btn-danger btn-xs" type="button">Extra small button</button>
<button class="btn btn-success btn-xs" type="button">Extra small button</button>
<button class="btn btn-warning btn-xs" type="button">Extra small button</button>
-->

</body>
</html>
