<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

			<script language="JavaScript" type="text/javascript">
			<!--
				function delContent(idx){
					if(confirm('해당 배너를 삭제하시겠습니까?')){
						document.location = "banner_main_save.php?mode=delete&selected=" + idx;
					}
				}
			//-->
			</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						메인 슬라이드
					</h3>
					<ul class="breadcrumb">
						<li>팝업 설정</li>
						<li class="active">메인 슬라이드</li>
					</ul>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="60"/><col width="200"/><col width=""/><col width="60"/><col width="60"/><col width="110"/>
							<thead>
							<tr>
								<td>번호</td>
								<td>이미지</td>
								<td>제목</td>
								<td>노출</td>
								<td>진열순서</td>
								<td>기능</td>
							</tr>
							</thead>
							<tbody>
<?
$page_set = 15;
$block_set = 10;
if(!$page) $page = 1;


$addSql  = "";

$sql  = "";
$sql .= "	Select	COUNT(*) ";
$sql .= "	From	df_banner_main ";
$sql .= "	Where	1 = 1 ";
$sql .= $addSql;
$total = $db->single($sql);
$pageCnt = (($total-1)/$page_set) +1;						//전체 페이지의 수
if($page > $pageCnt) $page = 1;

if($total > 0){
	$sql  = "";
	$sql .= "	Select	* ";
	$sql .= "	From	df_banner_main ";
	$sql .= "	Where	1 = 1 ";
	$sql .= $addSql;
	$sql .= "	Order by	prior Desc, idx Desc ";
	$sql .= "	Limit	" .(($page-1)*$page_set). "," .$page_set;
	$row = $db->query($sql);

	for($i=0; $i<count($row); $i++){
?>
							<tr>
								<td><?=$total-($page-1)*$page_set-$i?></td>
								<td>
									<?if(is_file("../../userfiles/banner/".$row[$i]['upfile_pc01'])){?>
									<img src="/userfiles/banner/<?=$row[$i]['upfile_pc01']?>" width="200" />
									<?}?>
								</td>
								<td><?=$row[$i]['subject']?></td>
								<td><?=$row[$i]['showset']?></td>
								<td style="padding:0;">
									<ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
										<li style="float:left;width:20px;height:12px;text-align:center;"><a href="banner_main_save.php?mode=prior&posi=upup&idx=<?=$row[$i]['idx']?>&prior=<?=$row[$i]['prior']?>&page=<?=$page?>"><img src="../img/upup_icon.gif" border="0" alt="10단계 위로"></a></li>
										<li style="float:left;width:20px;height:12px;text-align:center;"></li>
										<li style="float:left;width:20px;height:12px;text-align:center;"><a href="banner_main_save.php?mode=prior&posi=up&idx=<?=$row[$i]['idx']?>&prior=<?=$row[$i]['prior']?>&page=<?=$page?>"><img src="../img/up_icon.gif" border="0" alt="1단계 위로"></a></li>
										<li style="float:left;width:20px;height:12px;text-align:center;"><a href="banner_main_save.php?mode=prior&posi=down&idx=<?=$row[$i]['idx']?>&prior=<?=$row[$i]['prior']?>&page=<?=$page?>"><img src="../img/down_icon.gif" border="0" alt="1단계 아래로"></a></li>
										<li style="float:left;width:20px;height:12px;text-align:center;"></li>
										<li style="float:left;width:20px;height:12px;text-align:center;"><a href="banner_main_save.php?mode=prior&posi=downdown&idx=<?=$row[$i]['idx']?>&prior=<?=$row[$i]['prior']?>&page=<?=$page?>"><img src="../img/downdown_icon.gif" border="0" alt="10단계 아래로"></a></li>
									</ul>
									<div class="clear"></div>
								</td>
								<td>
									<button class="btn btn-success btn-xs" type="button" onClick="location.href='banner_main_input.php?mode=update&idx=<?=$row[$i]['idx']?>&page=<?=$page?>';">수정</button>
									<button class="btn btn-danger btn-xs" type="button" onClick="delContent('<?=$row[$i]['idx']?>');">삭제</button>
								</td>
							</tr>
<?
	}
}
else{
?>
							<tr>
								<td colspan="5">등록된 배너가 없습니다.</td>
							</tr>
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
							<? print_pagelist_admin($total, $page_set, $block_set, $page, "&".$param); ?>
						</div>
						<div class="comFRight comARight" style="width:10%; padding-right:10px;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='banner_main_input.php?page=<?=$page?>';">배너 등록</button>
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
