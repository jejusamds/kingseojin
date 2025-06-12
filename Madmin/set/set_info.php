<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var fullAddr_alt = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;
					fullAddr_alt = data.jibunAddress;
                }
				else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
					fullAddr_alt = data.roadAddress;
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
                document.getElementById('com_post').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('com_address').value = fullAddr;
            }
        }).open();
    }
</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						사이트 관리 설정
					</h3>
					<ul class="breadcrumb">
						<li>관리자설정</li>
						<li class="active">사이트 관리 설정</li>
					</ul>
				</div>

				<form name="frm" action="set_save.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="tmp">
				<input type="hidden" name="mode" value="set_info">
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>사이트 정보</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>사이트 제목</th>
								<td class="comALeft" colspan="3"><input type="text" name="site_title" value="<?=$site_info->site_title?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<tr>
								<th>사이트 소개</th>
								<td class="comALeft" colspan="3"><input type="text" name="site_intro" value="<?=$site_info->site_intro?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<tr>
								<th>관리자 제목</th>
								<td class="comALeft" colspan="3"><input type="text" name="admin_title" value="<?=$site_info->admin_title?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<tr>
								<th>SNS 이미지</th>
								<td class="comALeft" colspan="3">
									<input type="file" name="site_image" class="form-control" style="width:40%;" /> (198 * 180 px)
									<?if(is_file("../../userfiles/site/".$site_info->site_image)){?>
									<a href="/userfiles/site/<?=$site_info->site_image?>" target="_blank" style="margin-left:20px;"><?=$site_info->site_image?></a>
									<?}?>
								</td>
							</tr>
						</table>
						<div style="color:#ff0000; margin-top:7px;">
							※ 네이버 검색결과 노출은 네이버 웹마스터도구에서 사이트를 등록하시고 소유확인을 하셔야 합니다.
							<button class="btn btn-danger btn-xs" type="button" onClick="window.open('http://webmastertool.naver.com/');">네이버 웹마스터도구 바로가기</button>
						</div>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>관리자 정보</span>
							<font color="red"> (관리자정보는 회원가입,탈퇴,폼메일 등 사이트에서 일어나는 상황을 통보받습니다.)</font>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>업체명</th>
								<td class="comALeft" colspan="3"><input type="text" name="site_name" value="<?=$site_info->site_name?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<tr>
								<th>사이트 URL</th>
								<td class="comALeft"><input type="text" name="site_url" value="<?=$site_info->site_url?>" class="form-control" style="width:88%;" /></td>
								<th>관리자 E-mail</th>
								<td class="comALeft"><input type="text" name="site_email" value="<?=$site_info->site_email?>" class="form-control" style="width:88%;" /></td>
							</tr>
							<tr>
								<th>관리자 전화번호</th>
								<td class="comALeft"><input type="text" name="site_tel" value="<?=$site_info->site_tel?>" class="form-control" style="width:88%;" /></td>
								<th>관리자 휴대폰</th>
								<td class="comALeft"><input type="text" name="site_hand" value="<?=$site_info->site_hand?>" class="form-control" style="width:88%;" /></td>
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
								<th>사업자등록번호</th>
								<td class="comALeft" colspan="3"><input type="text" name="com_num" value="<?=$site_info->com_num?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<tr>
								<th>상호</th>
								<td class="comALeft"><input type="text" name="com_name" value="<?=$site_info->com_name?>" class="form-control" style="width:88%;" /></td>
								<th>대표자명</th>
								<td class="comALeft"><input type="text" name="com_owner" value="<?=$site_info->com_owner?>" class="form-control" style="width:88%;" /></td>
							</tr>
							<tr>
								<th>우편번호</th>
								<td class="comALeft" colspan="3">
									<input name="com_post" id="com_post" type="text" value="<?=$site_info->com_post?>" class="form-control" style="width:10%;">
									<button class="btn btn-success btn-sm" type="button" onClick="execDaumPostcode();">찾기</button>
								</td>
							</tr>
							<tr>
								<th>주소</th>
								<td class="comALeft" colspan="3"><input name="com_address" id="com_address" type="text" value="<?=$site_info->com_address?>" class="form-control" style="width:94%;"></td>
							</tr>
							<tr>
								<th>업태</th>
								<td class="comALeft"><input type="text" name="com_kind" value="<?=$site_info->com_kind?>" class="form-control" style="width:88%;" /></td>
								<th>종목</th>
								<td class="comALeft"><input type="text" name="com_class" value="<?=$site_info->com_class?>" class="form-control" style="width:88%;" /></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td class="comALeft"><input type="text" name="com_tel" value="<?=$site_info->com_tel?>" class="form-control" style="width:88%;" /></td>
								<th>팩스번호</th>
								<td class="comALeft"><input type="text" name="com_fax" value="<?=$site_info->com_fax?>" class="form-control" style="width:88%;" /></td>
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
