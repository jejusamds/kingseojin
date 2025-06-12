<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<style>
	.pagination {margin:0 auto;}
</style>

<script language="JavaScript" type="text/javascript">
<!--
	function delAdmin(mem_id){
		if(confirm('BAE를 삭제하시면 BA에 등록된 EBA 정보도 삭제됩니다.\n\n해당 EBA를 삭제하시겠습니까?')){
			document.location = "set_eba_save.php?mode=delete&mem_id=" + mem_id;
		}
	}
//-->
</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						EBA 설정
					</h3>
					<ul class="breadcrumb">
						<li>EBA 설정</li>
						<li class="active">EBA 설정</li>
					</ul>
				</div>

				<div class="box" style="width:978px;">
					<div class="panel">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="50"/><col width="150"/><col width="150"/><col width="150"/><col width="150"/><col width="150"/><col width=""/>
							<thead>
							<tr>
								<td>번호</td>
								<td>아이디</td>
								<td>비밀번호</td>
								<td>이름</td>
								<td>마지막 접속</td>
								<td>등록일</td>
								<td>기능</td>
							</tr>
							</thead>
							<tbody>
<?
$sql = " Select * From df_site_eba Order by wdate Desc ";
$result = mysql_query($sql) or error(mysql_error());
$total = mysql_num_rows($result);

$rows = 15;
$lists = 10;
$page_count = ceil($total/$rows);
if($page < 1 || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
if($start>1) mysql_data_seek($result,$start);

while(($row = mysql_fetch_array($result)) && $rows){
?>
							<tr>
								<td><?=$no?></td>
								<td><?=$row[id]?></td>
								<td><?=$row[passwd]?></td>
								<td><?=$row[name]?></td>
								<td><?=$row[last]?></td>
								<td><?=$row[wdate]?></td>
								<td>
									<button class="btn btn-info btn-xs" type="button" onClick="location.href='set_eba_input.php?mode=update&id=<?=$row[id]?>';">수정</button>
									<button class="btn btn-danger btn-xs" type="button" onClick="delAdmin('<?=$row[id]?>');">삭제</button>
								</td>
							</tr>
<?
	$no--;
	$rows--;
}
if($total <= 0){
?>
							<tr><td colspan="7">등록된 EBA가 없습니다.</td></tr>
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
							<? print_pagelist_admin($page, $lists, $page_count, ""); ?>
						</div>
						<div class="comFRight comARight" style="width:10%; padding-right:10px;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='set_eba_input.php?mode=insert';">EBA 등록</button>
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
