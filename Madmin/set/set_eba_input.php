<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "searchbae=$searchbae&searchopt=$searchopt&searchkey=$searchkey&s_birthday=$s_birthday&s_memorial=$s_memorial&s_age=$s_age";
$param .= "&s_address=$s_address&s_job=$s_job&s_marriage=$s_marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day&page=$page";
//------------------------------------------------------------------------------------------------------------------------------------

// 회원정보
$sql = "select * from df_site_eba where id = '$id'";
$result = mysql_query($sql) or error(mysql_error());
$meminfo = mysql_fetch_object($result);
?>

			<script language="JavaScript">
			<!--
				function inputCheck(frm){
				   
				   if(frm.id.value == ""){
					  alert("아이디를 입력하세요");
					  frm.id.focus();
					  return false;
				   }
				   
				}
			//-->
			</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						EBA 정보
					</h3>
					<ul class="breadcrumb">
						<li>EBA 관리</li>
						<li class="active">EBA 정보</li>
					</ul>
				</div>

				<form name="frm" action="set_eba_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
				<input type="hidden" name="tmp">
				<input type="hidden" name="mode" value="<?=$mode?>">
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>기본 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>아이디</th>
								<td class="comALeft"><input name="id" type="text" value="<?=$meminfo->id?>" <? if($mode == "update") echo "readonly"; ?> class="form-control" style="width:88%;" ></td>
								<th>비밀번호</th>
								<td class="comALeft"><input name="passwd" type="password" maxlength="20" value="" placeholder="변경할 경우에만 입력해 주세요" class="form-control" style="width:88%;"></td>
							</tr>
							<tr>
								<th>이름</th>
								<td class="comALeft"><input name="name" type="text" value="<?=$meminfo->name?>" class="form-control" style="width:88%;"></td>
								<th>이메일</th>
								<td class="comALeft">
									<input name="email" type="text" value="<?=$meminfo->email?>" class="form-control" style="width:88%;">
									<!--
									<button class="btn btn-warning btn-sm" type="button" onClick="sendEmail('<?=$meminfo->name?>:<?=$meminfo->email?>');">발송</button>
									-->
								</td>
							</tr>
							<tr>
								<th>우편번호</th>
								<td class="comALeft">
									<input name="post" id="post" type="text" value="<?=$meminfo->post?>" class="form-control" style="width:20%;">
									<button class="btn btn-info btn-sm" type="button" onClick="execDaumPostcode('');">주소검색</button>
								</td>
								<th>휴대폰</th>
								<td class="comALeft">
									<? list($hphone, $hphone2, $hphone3) = explode("-",$meminfo->hphone); ?>
									<input type="text" name="hphone" value="<?=$hphone?>" class="form-control" style="width:15%;"> - 
									<input type="text" name="hphone2" value="<?=$hphone2?>" class="form-control" style="width:15%;"> - 
									<input type="text" name="hphone3" value="<?=$hphone3?>" class="form-control" style="width:15%;">
									<!--
									<button class="btn btn-warning btn-sm" type="button" onClick="sendSms('<?=$meminfo->name?>','<?=$meminfo->hphone?>');">발송</button>
									-->
								</td>
							</tr>
							<tr>
								<th>주소</th>
								<td class="comALeft" colspan="3">
									<input name="address" id="address" type="text" value="<?=$meminfo->address?>" class="form-control" style="width:60%;">
									<input name="address2" id="address2" type="text" value="<?=$meminfo->address2?>" class="form-control" style="width:30%;">
								</td>
							</tr>
							<tr>
								<th>가입일시</th>
								<td class="comALeft"><?=$meminfo->wdate?></td>
								<th>최종방문일</th>
								<td class="comALeft" colspan="3"><?=$meminfo->visit_time?></td>
							</tr>
							<tr>
								<th>이메일 수신</th>
								<td class="comALeft">
									<input type="radio" name="reemail" value="Y" <? if($meminfo->reemail == "Y") echo "checked"; ?> class="input01">예
									<input type="radio" name="reemail" value="N" <? if($meminfo->reemail == "N") echo "checked"; ?> class="input01">아니오
								</td>
								<th>SMS 수신</th>
								<td class="comALeft">
									<input type="radio" name="resms" value="Y" <? if($meminfo->resms == "Y") echo "checked"; ?> class="input01">예
									<input type="radio" name="resms" value="N" <? if($meminfo->resms == "N") echo "checked"; ?> class="input01">아니오
								</td>
							</tr>
							<!--
							<tr>
								<th>적립금</th>
								<td class="comALeft" colspan="3">
									<span style="color:#ff0000;"><?=number_format($total_reserve)?> 원</span>
									<button class="btn btn-success btn-xs" type="button" style="margin-left:20px;" onClick="reserveList('<?=$id?>','<?=$meminfo->name?>');">상세보기</button>
								</td>
							</tr>
							-->
							<tr>
								<th>관리자주석</th>
								<td class="comALeft" colspan="3" style="padding-top:7px;padding-bottom:7px;"><textarea name="comment" class="form-control" style="width:94%;height:100px;"><?=$meminfo->comment?></textarea></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>EBA 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>닉네임</th>
								<td class="comALeft"><input name="recom_id" type="text" value="<?=$meminfo->recom_id?>" class="form-control" style="width:88%;" ></td>
								<th>정산계좌 은행</th>
								<td class="comALeft">
									<select name="bank_code" class="form-control" style="width:88%;">
										<option value="">= 은행선택= </option>
										<? foreach($gBank as $bank_code => $bank_name){ ?>
										<option value="<?=$bank_code?>" <? if($meminfo->bank_code==$bank_code){ ?>selected<? } ?>><?=$bank_name?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>정산계좌 예금주</th>
								<td class="comALeft"><input name="bank_deposit" type="text" value="<?=$meminfo->bank_deposit?>" class="form-control" style="width:88%;" ></td>
								<th>정산계좌 통장번호</th>
								<td class="comALeft"><input name="bank_account" type="text" value="<?=$meminfo->bank_account?>" class="form-control" style="width:88%;"></td>
							</tr>
							<tr>
								<th>BA 구매적립</th>
								<td class="comALeft"><input name="per_ba" type="text" maxlength="2" value="<?=$meminfo->per_ba?>" class="form-control" style="width:15%;" > %</td>
								<th>BA 하부회원 구매적립</th>
								<td class="comALeft"><input name="per_m" type="text" maxlength="2" value="<?=$meminfo->per_m?>" class="form-control" style="width:15%;"> %</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>사업자 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>회사명</th>
								<td class="comALeft"><input name="biz_company" type="text" value="<?=$meminfo->biz_company?>" class="form-control" style="width:88%;" ></td>
								<th>사업자 번호</th>
								<td class="comALeft">
									<input name="biz_no1" type="text" maxlength="3" value="<?=substr($meminfo->biz_no,0,3)?>" class="form-control" style="width:15%;" >
									-
									<input name="biz_no2" type="text" maxlength="2" value="<?=substr($meminfo->biz_no,3,2)?>" class="form-control" style="width:10%;" >
									-
									<input name="biz_no3" type="text" maxlength="5" value="<?=substr($meminfo->biz_no,-5)?>" class="form-control" style="width:30%;" >
								</td>
							</tr>
							<tr>
								<th>우편번호</th>
								<td class="comALeft">
									<input name="biz_post" id="biz_post" type="text" value="<?=$meminfo->biz_post?>" class="form-control" style="width:20%;">
									<button class="btn btn-info btn-sm" type="button" onClick="execDaumPostcode('biz_');">주소검색</button>
								</td>
								<th>대표자</th>
								<td class="comALeft"><input name="biz_owner" type="text" value="<?=$meminfo->biz_owner?>" class="form-control" style="width:88%;" ></td>
							</tr>
							<tr>
								<th>주소</th>
								<td class="comALeft" colspan="3">
									<input name="biz_address" id="biz_address" type="text" value="<?=$meminfo->biz_address?>" class="form-control" style="width:60%;">
									<input name="biz_address2" id="biz_address2" type="text" value="<?=$meminfo->biz_address2?>" class="form-control" style="width:30%;">
								</td>
							</tr>
							<tr>
								<th>업태</th>
								<td class="comALeft"><input name="biz_uptae" type="text" value="<?=$meminfo->biz_uptae?>" class="form-control" style="width:88%;" ></td>
								<th>업종</th>
								<td class="comALeft"><input name="biz_jmok" type="text" value="<?=$meminfo->biz_jmok?>" class="form-control" style="width:88%;" ></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comACenter" style="width:10%;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='set_eba.php?page=<?=$page?>';">목록</button>
						</div>
						<div class="comFLeft comACenter" style="width:90%;">
							<button class="btn btn-info btn-sm" type="submit">확인</button>
							<button class="btn btn-default btn-sm" type="button" onClick="location.href='set_eba.php?page=<?=$page?>';">취소</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>

				</form>

			</div>
		</div>
	</div>
</div>

<!-- iOS에서는 position:fixed 버그가 있음, 적용하는 사이트에 맞게 position:absolute 등을 이용하여 top,left값 조정 필요 -->
<div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
<img src="//t1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
</div>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
	// 우편번호 찾기 화면을 넣을 element
	var element_layer = document.getElementById('layer');

	function closeDaumPostcode() {
		// iframe을 넣은 element를 안보이게 한다.
		element_layer.style.display = 'none';
	}

	function execDaumPostcode(obj) {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var fullAddr = ''; // 최종 주소 변수
				var extraAddr = ''; // 조합형 주소 변수

				// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;

				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
				if(data.userSelectedType === 'R'){
					//법정동명이 있을 경우 추가한다.
					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					// 건물명이 있을 경우 추가한다.
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				document.getElementById(obj+'post').value = data.zonecode; //5자리 새우편번호 사용
				document.getElementById(obj+'address').value = fullAddr;
				document.getElementById(obj+'address2').focus();

				// iframe을 넣은 element를 안보이게 한다.
				// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
				element_layer.style.display = 'none';
			},
			width : '100%',
			height : '100%'
		}).embed(element_layer);

		// iframe을 넣은 element를 보이게 한다.
		element_layer.style.display = 'block';

		// iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
		initLayerPosition();
	}

	// 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
	// resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
	// 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
	function initLayerPosition(){
		var width = 500; //우편번호서비스가 들어갈 element의 width
		var height = 460; //우편번호서비스가 들어갈 element의 height
		var borderWidth = 5; //샘플에서 사용하는 border의 두께

		// 위에서 선언한 값들을 실제 element에 넣는다.
		element_layer.style.width = width + 'px';
		element_layer.style.height = height + 'px';
		element_layer.style.border = borderWidth + 'px solid';
		// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
		element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
		element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
	}
</script>

</body>
</html>
