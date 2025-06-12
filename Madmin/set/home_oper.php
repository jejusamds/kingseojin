<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<script language="javascript">
<!--
	// 적립금 비율 다시적용
	function setReserve(frm){
		var reserve_per = frm.reserve_per.value;

		if(Check_Num(reserve_per)){
			if(confirm("모든 상품의 적립금이 상품가격의 "+reserve_per+"% 로 일괄적용 됩니다.\n\n진행하시겠습니까?")){
				document.location = "set_save.php?mode=setreserve&reserve_per=" + reserve_per;
			}
		}else{
			alert("숫자를 입력하세요");
			frm.reserve_per.value = "";
			frm.reserve_per.focus();
		}
	}
-->
</script>

<?
$pay_list = explode("/",$oper_info->pay_method);
for($ii=0; $ii<count($pay_list); $ii++){
	$pay_method[$pay_list[$ii]] = true;
}
?>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						운영정보 설정
					</h3>
					<ul class="breadcrumb">
						<li>관리자설정</li>
						<li class="active">운영정보 설정</li>
					</ul>
				</div>

				<form name="frm" action="set_save.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="tmp">
				<input type="hidden" name="mode" value="oper_info">
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>결제 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>결제방법</th>
								<td class="comALeft" colspan="3">
									<input type="checkbox" name="pay_method[]" value="PB" <? if($pay_method["PB"]==true) echo "checked";?>>무통장입금 &nbsp; &nbsp;
									<input type="checkbox" name="pay_method[]" value="PC" <? if($pay_method["PC"]==true) echo "checked";?>>카드결제 &nbsp; &nbsp;
									<input type="checkbox" name="pay_method[]" value="PN" <? if($pay_method["PN"]==true) echo "checked";?>>계좌이체 &nbsp; &nbsp;
									<input type="checkbox" name="pay_method[]" value="PV" <? if($pay_method["PV"]==true) echo "checked";?>>가상계좌 &nbsp; &nbsp;
									<input type="checkbox" name="pay_method[]" value="PH" <? if($pay_method["PH"]==true) echo "checked";?>>휴대폰결제
								</td>
							</tr>
							<tr>
								<th>은행계좌번호</th>
								<td class="comALeft" colspan="3"><input type="text" name="pay_account" value="<?=$oper_info->pay_account?>" class="form-control" style="width:94%;"></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>적립금 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>사용여부</th>
								<td class="comALeft" colspan="3">
									<input type="radio" name="reserve_use" value="Y" <? if($oper_info->reserve_use == "Y") echo "checked"; ?>>사용함 &nbsp; &nbsp;
									<input type="radio" name="reserve_use" value="N" <? if($oper_info->reserve_use == "N") echo "checked"; ?>>사용안함
								</td>
							</tr>
							<tr>
								<th>회원가입 적립금</th>
								<td class="comALeft"><input type="text" name="reserve_join" value="<?=$oper_info->reserve_join?>" class="form-control" style="width:88%;"></td>
								<th>추천인 적립금</th>
								<td class="comALeft"><input type="text" name="reserve_recom" value="<?=$oper_info->reserve_recom?>" class="form-control" style="width:88%;"></td>
							</tr>
							<tr>
								<th>최소사용 적립금</th>
								<td class="comALeft"><input type="text" name="reserve_min" value="<?=$oper_info->reserve_min?>" class="form-control" style="width:88%;"></td>
								<th>1회 최대사용 적립금</th>
								<td class="comALeft"><input type="text" name="reserve_max" value="<?=$oper_info->reserve_max?>" class="form-control" style="width:88%;"></td>
							</tr>
							<tr>
								<th>상품구매시 적립금</th>
								<td class="comALeft"><input type="text" name="reserve_buy" value="<?=$oper_info->reserve_buy?>" class="form-control" style="width:88%;"></td>
								<th>적립금 일괄적용</th>
								<td class="comALeft">
									<input type="text" name="reserve_per" value="<?=$oper_info->reserve_per?>" class="form-control" style="width:50%;">
									<button class="btn btn-success btn-sm" type="button" onClick="setReserve(this.form);">적용</button>
								</td>
							</tr>
						</table>
						<div style="line-height:20px; color:#ff0000;">- 사용여부: 상품구입시 적립금 누적/사용 , 회원가입시, 추천인인경우 등 적립금 사용이 가능합니다.</div>
						<div style="line-height:20px; color:#ff0000;">- 상품구매시 적립금: 상품등록시 판매금액에 작성한 퍼센트를 적용하여 적립금이 자동계산됩니다.</div>
						<div style="line-height:20px; color:#ff0000;">- 적립금 일괄적용: 현재 등록되어있는 상품에 적립금을 작성한 퍼센트로 다시 적용합니다.</div>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>상품평 설정</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>사용여부</th>
								<td class="comALeft">
									<input type="radio" name="review_use" value="Y" <? if($oper_info->review_use == "Y") echo "checked"; ?>>사용함 &nbsp; &nbsp;
									<input type="radio" name="review_use" value="N" <? if($oper_info->review_use == "N") echo "checked"; ?>>사용안함
								</td>
								<th>작성권한</th>
								<td class="comALeft">
									<input type="radio" name="review_level" value="E" <? if($oper_info->review_level == "E") echo "checked"; ?>>회원+비회원 &nbsp; &nbsp;
									<input type="radio" name="review_level" value="M" <? if($oper_info->review_level == "M") echo "checked"; ?>>회원만
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>배송 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width=""/>
							<tr>
								<th>택배사</th>
								<td class="comALeft"><input name="del_com" value="<?=$oper_info->del_com?>" type="text" class="form-control" style="width:94%;"></td>
							</tr>
							<tr>
								<th>배송추적설정</th>
								<td class="comALeft"><input name="del_trace" value="<?=$oper_info->del_trace?>" type="text" class="form-control" style="width:94%;"></td>
							</tr>
							<tr>
								<th>무료배송</th>
								<td class="comALeft"><input type="radio" name="del_method" value="DA" <? if($oper_info->del_method == "DA") echo "checked"; ?>> 배송비 전액무료</td>
							</tr>
							<tr>
								<th>수신자부담</th>
								<td class="comALeft"><input type="radio" name="del_method" value="DB" <? if($oper_info->del_method == "DB") echo "checked"; ?>> 수신자부담 (착불)</td>
							</tr>
							<tr>
								<th>고정값</th>
								<td class="comALeft">
									<input type="radio" name="del_method" value="DC" <? if($oper_info->del_method == "DC") echo "checked"; ?>>
									<input name="del_fixprice" type="text" value="<?=$oper_info->del_fixprice?>" class="form-control comARight" style="width:10%;">원
								</td>
							</tr>
							<tr>
								<th>구매가격별</th>
								<td class="comALeft">
									<input type="radio" name="del_method" value="DD" <? if($oper_info->del_method == "DD") echo "checked"; ?>>
									<input type="text" name="del_staprice" value="<?=$oper_info->del_staprice?>" class="form-control comARight" style="width:10%;">원
									&nbsp;이상 구매시 <input type="text" name="del_staprice2" value="<?=$oper_info->del_staprice2?>" class="form-control comARight" style="width:10%;">원,
									&nbsp;이하 구매시 <input type="text" name="del_staprice3" value="<?=$oper_info->del_staprice3?>" class="form-control comARight" style="width:10%;">원
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20 comACenter">
						<button class="btn btn-info btn-sm" type="submit">확인</button>
					</div>
				</div>

				</form>

			</div>
		</div>
	</div>
</div>

</body>
</html>
