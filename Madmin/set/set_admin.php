<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>

<style>
	.pagination {
		margin: 0 auto;
	}
</style>

<script language="JavaScript" type="text/javascript">
	<!--
	function delAdmin(admin_id) {
		if (confirm('해당관리자를 삭제하시겠습니까?')) {
			document.location = "set_save.php?mode=set_admin&admin_mode=delete&admin_id=" + admin_id;
		}
	}
	//
	-->
</script>

<div class="pageWrap">
	<div class="page-heading">
		<h3>
			관리자 설정
		</h3>
		<ul class="breadcrumb">
			<li>관리자설정</li>
			<li class="active">관리자 설정</li>
		</ul>
	</div>

	<div class="box" style="width:978px;">
		<div class="panel">
			<table class="table noMargin" cellpadding="0" cellspacing="0">
				<col width="50" />
				<col width="150" />
				<col width="150" />
				<!--col width="150" /-->
				<col width="150" />
				<col width="" />
				<col width="120" />
				<thead>
					<tr>
						<td>번호</td>
						<td>구분</td>
						<td>아이디</td>
						<!--td>비밀번호</td-->
						<td>이름</td>
						<td>마지막 접속</td>
						<td>기능</td>
					</tr>
				</thead>
				<tbody>
					<?
					$page_set = 15;
					$block_set = 10;
					if (!$page) $page = 1;

					$sql  = "";
					$sql .= "	Select	COUNT(*) ";
					$sql .= "	From	df_site_admin ";
					$sql .= "	Where	1 = 1 ";
					if ($ez_admin[part] == "9") {
						$sql .= " And id = '" . $ez_admin[id] . "' ";
					}
					$total = $db->single($sql);
					$pageCnt = (($total - 1) / $page_set) + 1;						//전체 페이지의 수
					if ($page > $pageCnt) $page = 1;

					if ($total > 0) {
						$sql  = "";
						$sql .= "	Select	* ";
						$sql .= "	From	df_site_admin ";
						$sql .= "	Where	1 = 1 ";
						if ($ez_admin[part] == "9") {
							$sql .= " And id = '" . $ez_admin[id] . "' ";
						}
						$sql .= "	Order by	wdate Desc ";
						$sql .= "	Limit	" . (($page - 1) * $page_set) . "," . $page_set;
						$row = $db->query($sql);

						for ($i = 0; $i < count($row); $i++) {
					?>
							<tr>
								<td><?= $total - ($page - 1) * $page_set - $i ?></td>
								<td><?= $row[$i]['part'] == "0" ? "관리자" : "도매장"; ?></td>
								<td><?= $row[$i]['id'] ?></td>
								<!--td><?= $row[$i]['passwd'] ?></td-->
								<td><?= $row[$i]['name'] ?></td>
								<td><?= $row[$i]['last'] ?></td>
								<td>
									<button class="btn btn-info btn-xs" type="button" onClick="location.href='set_admin_input.php?admin_mode=update&id=<?= $row[$i]['id'] ?>';">수정</button>
									<? if ($ez_admin[part] != "9") { ?>
										<button class="btn btn-danger btn-xs" type="button" onClick="delAdmin('<?= $row[$i]['id'] ?>');">삭제</button>
									<? } ?>
								</td>
							</tr>
						<?
						}
					}
					if ($total <= 0) {
						?>
						<tr>
							<td colspan="7">등록된 관리자가 없습니다.</td>
						</tr>
					<?
					}
					?>
				<tbody>
			</table>
		</div>
	</div>

	<div class="box comMTop10 comMBottom10" style="width:978px;">
		<div class="comPTop10 comPBottom10">
			<div class="comFLeft comACenter" style="width:85%; display:inline-block;">
				<? print_pagelist_admin($total, $page_set, $block_set, $page, "&" . $param); ?>
			</div>
			<div class="comFRight comARight" style="width:10%; padding-right:10px;">
				<? if ($ez_admin[part] != "9") { ?>
					<button class="btn btn-primary btn-sm" type="button" onClick="location.href='set_admin_input.php?admin_mode=insert';">관리자 등록</button>
				<? } ?>
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