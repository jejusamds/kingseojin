<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<style>
	.pagination {margin:0 auto;}
</style>

<script language="JavaScript" type="text/javascript">
<!--
	function regDelivery(){
		$.colorbox({iframe:true, width:700, height:450, href:"deliver_input.php"});
	}
	
	function delDelivery(){
		var selidx = "";
		$("input[name=select_checkbox]").each(function(){
			if($(this).prop("checked")){
				if(selidx) selidx += "|" + $(this).val();
				else selidx = $(this).val();
			}
		});
		
		if(!selidx || selidx==""){
			alert("삭제하실 내역을 선택해 주세요");
			return;
		}
		
		if(confirm('선택하신 내역을 삭제하시겠습니까?')){
			location.href = "set_save.php?mode=deliver_delete&selidx="+selidx;
		}
	}
	
	$(document).on("click", "input[name=select_tmp]", function(){
		$this = $(this);
		$("input[name=select_checkbox]").prop("checked", $this.prop("checked"));
	});
//-->
</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						지역별 배송비 설정
					</h3>
					<ul class="breadcrumb">
						<li>관리자설정</li>
						<li class="active">지역별 배송비 설정</li>
					</ul>
				</div>

				<div class="box" style="width:978px;">
					<div class="panel">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="30"/><col width="250"/><col width=""/><col width="150"/>
							<thead>
							<tr>
								<td><input type="checkbox" name="select_tmp"></td>
								<td>특수지역명(도서, 산간 등)</td>
								<td>우편번호 범위</td>
								<td>추가 배송비</td>
							</tr>
							</thead>
							<tbody>
<?
$sql = " Select * From df_shop_add_shipping Order by idx Desc ";
$result = mysql_query($sql) or error(mysql_error());
$total = mysql_num_rows($result);

$rows = 20;
$lists = 10;
$page_count = ceil($total/$rows);
if($page < 1 || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
if($start>1) mysql_data_seek($result,$start);

while(($row = mysql_fetch_array($result)) && $rows){
?>
							<tr>
								<td><input type="checkbox" name="select_checkbox" value="<?=$row['idx']?>"></td>
								<td><?=$row['title']?></td>
								<td>[<?=$row['post_from']?>] 부터 [<?=$row['post_to']?>] 까지</td>
								<td>
									<?if($row['deliver_yn'] == "N"){?>
									<span style="color:#ff0000;">배송불가지역</span>
									<?}else{?>
									<?=number_format($row['price'])?>원
									<?}?>
								</td>
							</tr>
<?
	$no--;
	$rows--;
}
if($total <= 0){
?>
							<tr><td height="30" colspan="4" align="center">등록된 내역이 없습니다.</td></tr>
<?
}
?>
							<tbody>
						</table>
						<div class="comALeft comMTop10">
							<button class="btn btn-danger btn-xs" type="button" onClick="delDelivery();">삭제</button>
						</div>
					</div>
				</div>

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comACenter" style="width:85%; display:inline-block;">
							<? print_pagelist_admin($page, $lists, $page_count, ""); ?>
						</div>
						<div class="comFRight comARight" style="width:10%; padding-right:10px;">
							<button class="btn btn-primary btn-sm" type="button" onClick="regDelivery();">등록</button>
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
