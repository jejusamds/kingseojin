<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&isuse=$isuse";
$param .= "&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//--------------------------------------------------------------------------------------------------

$type = "popup";
if($mode == "") $mode = "pinsert";
if($mode == "pupdate"){
	$sql = "select * from df_site_content$DB_ID where idx='$idx'";
	$popup_info = $db->row($sql);
	$popup_info['content'] = str_replace("\"", "'", $popup_info['content']);
}
if(!$popup_info['close_bg']) $popup_info['close_bg'] = "#cacaca";
if(!$popup_info['close_txt']) $popup_info['close_txt'] = "오늘 하루 열지 않음";
if(!$popup_info['close_txt_color']) $popup_info['close_txt_color'] = "#000000";
if(!$popup_info['close_align']) $popup_info['close_align'] = "right";
?>

<script language="JavaScript">
<!--
function inputCheck(frm){
   
   if(frm.title.value == ""){
      alert("제목을 입력하세요");
      frm.title.focus();
      return false;
   }
   
}
//-->
</script>

<script src="/js/spectrum.js"></script>
<link rel="stylesheet" href="/css/spectrum.css" />

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						팝업관리 - PC 등록/수정
					</h3>
					<ul class="breadcrumb">
						<li>팝업 및 배너 설정</li>
						<li class="active">팝업관리 - PC 등록/수정</li>
					</ul>
				</div>

				<form name="frm" action="page_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
				<input type="hidden" name="tmp">
				<input type="hidden" name="type" value="<?=$type?>">
				<input type="hidden" name="mode" value="<?=$mode?>">
				<input type="hidden" name="idx" value="<?=$idx?>">
				
				<input type="hidden" name="page" value="<?=$page?>">
				<input type="hidden" name="searchopt" value="<?=$searchopt?>">
				<input type="hidden" name="keyword" value="<?=$keyword?>">
				<input type="hidden" name="isuse" value="<?=$isuse?>">
				<input type="hidden" name="prev_year" value="<?=$prev_year?>">
				<input type="hidden" name="prev_month" value="<?=$prev_month?>">
				<input type="hidden" name="prev_day" value="<?=$prev_day?>">
				<input type="hidden" name="next_year" value="<?=$next_year?>">
				<input type="hidden" name="next_month" value="<?=$next_month?>">
				<input type="hidden" name="next_day" value="<?=$next_day?>">
				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>제목</th>
								<td class="comALeft" colspan="3"><input type="text" name="title" value="<?=$popup_info['title']?>" class="form-control" style="width:94%;" /></td>
							</tr>
							<?
							$sdate_list = explode("-",$popup_info['sdate']);
							$edate_list = explode("-",$popup_info['edate']);

							if(empty($sdate_list[0])) $sdate_year = date('Y');
							else $sdate_year = $sdate_list[0];
							if(empty($sdate_list[1])) $sdate_month = date('m');
							else $sdate_month = $sdate_list[1];
							if(empty($sdate_list[2])) $sdate_day = date('d');
							else $sdate_day = $sdate_list[2];

							if(empty($edate_list[0])) $edate_year = date('Y');
							else $edate_year = $edate_list[0];
							if(empty($edate_list[1])) $edate_month = date('m');
							else $edate_month = $edate_list[1];
							if(empty($edate_list[2])) $edate_day = date('d');
							else $edate_day = $edate_list[2];

							if($popup_info['posi_x'] == "") $posi_x = "100";
							else $posi_x = $popup_info['posi_x'];
							if($popup_info['posi_y'] == "") $posi_y = "100";
							else $posi_y = $popup_info['posi_y'];

							if($popup_info['size_x'] == "") $size_x = "340";
							else $size_x = $popup_info['size_x'];
							if($popup_info['size_y'] == "") $size_y = "400";
							else $size_y = $popup_info['size_y'];
							?>
							<tr>
								<th>게시기간</th>
								<td class="comALeft" colspan="3">
									<select name="sdate_year" class="form-control" style="width:auto;">
										<?                     
										for($ii=2020; $ii <= date("Y")+1; $ii++){
											if($ii == $sdate_year) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>년 
									<select name="sdate_month" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 12; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $sdate_month) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>월 
									<select name="sdate_day" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 31; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $sdate_day) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>일
									~
									<select name="edate_year" class="form-control" style="width:auto;">
										<?
										for($ii=2020; $ii <= date("Y")+1; $ii++){
											if($ii == $edate_year) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>년 
									<select name="edate_month" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 12; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $edate_month) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>월 
									<select name="edate_day" class="form-control" style="width:auto;">
										<?
										for($ii=1; $ii <= 31; $ii++){
											if($ii<10) $ii = "0".$ii;
											if($ii == $edate_day) echo "<option value=$ii selected>$ii</option>";
											else echo "<option value=$ii>$ii</option>";
										}
										?>
									</select>일
								</td>
							</tr>
							<tr>
								<th>팝업형태</th>
								<td class="comALeft" colspan="3">
									<input name="popup_type" type="radio" value="L" <? if($popup_info['poptype'] == "L" || $popup_info['poptype'] == "") echo "checked"; ?>> 레이어팝업 &nbsp; 
									<input name="popup_type" type="radio" value="W" <? if($popup_info['poptype'] == "W") echo "checked"; ?>> 일반팝업
								</td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td class="comALeft">
									<input name="isuse" type="radio" value="Y" <? if($popup_info['isuse'] == "Y" || $popup_info['isuse'] == "") echo "checked"; ?>> 사용함 &nbsp; 
									<input name="isuse" type="radio" value="N" <? if($popup_info['isuse'] == "N") echo "checked"; ?>> 사용안함
								</td>
								<th>스크롤여부</th>
								<td class="comALeft">
									<input name="scroll" type="radio" value="Y" <? if($popup_info['scroll'] == "Y") echo "checked"; ?>> 허용함&nbsp; 
									<input name="scroll" type="radio" value="N" <? if($popup_info['scroll'] == "N" || $popup_info['scroll'] == "") echo "checked"; ?>> 허용안함
								</td>
							</tr>
							<tr>
								<th>위치</th>
								<td class="comALeft">
									X : <input name="posi_x" type="text" value="<?=$posi_x?>" class="form-control" style="width:10%;"> &nbsp; 
									Y : <input name="posi_y" type="text" value="<?=$posi_y?>" class="form-control" style="width:10%;">
								</td>
								<th>크기</th>
								<td class="comALeft">
									가로 : <input name="size_x" type="text" value="<?=$size_x?>" class="form-control" style="width:10%;"> &nbsp; 
									세로 : <input name="size_y" type="text" value="<?=$size_y?>" class="form-control" style="width:10%;">
								</td>
							</tr>
							<tr>
								<th>내 용</th>
								<td class="comALeft" colspan="3" style="padding-top:7px; padding-bottom:7px;">
									<script src="/ckeditor/ckeditor.js"></script>
									<textarea name="content" id="content" cols="70" rows="23"><?=$popup_info['content']?></textarea>
									<script type="text/javascript">
										//<![CDATA[
										CKEDITOR.replace('content',{
											enterMode:'2',
											shiftEnterMode:'3',
											height:500,
											filebrowserImageUploadUrl:"/ckeditor/upload.php?type=Images"
										});
										//]]
									</script>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>창닫기 설정</span>
						</div>
						<table class="table orderInfo" cellpadding="0" cellspacing="0">
							<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
							<tr>
								<th>미리보기</th>
								<td class="comALeft" colspan="3">
									<div id="close_preview" style="width:400px; height:25px; line-height:25px; padding:0 10px; background-color:<?=$popup_info['close_bg']?>; text-align:<?=$popup_info['close_align']?>; color:<?=$popup_info['close_txt_color']?>;">
										<?=$popup_info['close_txt']?>
										<input type="checkbox"/>
									</div>
								</td>
							</tr>
							<tr>
								<th>배경색</th>
								<td class="comALeft">
									<input type="text" name="close_bg" value="<?=$popup_info['close_bg']?>" placeholder="색상선택..." class="colorpicker form-control" style="width:63px;" />
								</td>
								<th>문구 정렬</th>
								<td class="comALeft">
									<select name="close_align" class="form-control" style="width:auto;">
										<option value="right" <?if($popup_info['close_align']=="right"){?>selected<?}?>>오른쪽 정렬</option>
										<option value="center" <?if($popup_info['close_align']=="center"){?>selected<?}?>>가운데 정렬</option>
										<option value="left" <?if($popup_info['close_align']=="left"){?>selected<?}?>>왼쪽 정렬</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>문구</th>
								<td class="comALeft" colspan="3">
									<input type="text" name="close_txt" value="<?=$popup_info['close_txt']?>" class="form-control" style="width:30%;" />
									<input type="text" name="close_txt_color" value="<?=$popup_info['close_txt_color']?>" placeholder="색상선택..." class="colorpicker form-control" style="width:63px;" />
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comACenter" style="width:10%;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='page_popup.php?page=<?=$page?>&<?=$param?>';">목록</button>
						</div>
						<div class="comFRight comARight" style="width:85%; padding-right:20px;">
							<button class="btn btn-info btn-sm" type="submit">확인</button>
							<button class="btn btn-danger btn-sm" type="button" onClick="location.href='page_popup.php?page=<?=$page?>&<?=$param?>';">취소</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				
				</form>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$("input[name=close_bg]").bind("change", function(){
			$("#close_preview").css("background-color", $(this).val());
		});
		$("select[name=close_align]").bind("change", function(){
			$("#close_preview").css("text-align", $(this).val());
		});
		$("input[name=close_txt]").bind("keyup", function(){
			$("#close_preview").html( $(this).val() + "\n<input type=checkbox />" );
		});
		$("input[name=close_txt_color]").bind("change", function(){
			$("#close_preview").css("color", $(this).val());
		});
	});

	$(function() {
		$(".colorpicker").spectrum({
			allowEmpty:true,
			color: "",
			showInput: true,
			containerClassName: "full-spectrum",
			showInitial: true,
			showPalette: true,
			showSelectionPalette: true,
			showAlpha: true,
			maxPaletteSize: 10,
			preferredFormat: "hex",
			localStorageKey: "spectrum.demo",
			move: function (color) {

			},
			show: function () {

			},
			beforeShow: function () {

			},
			hide: function (color) {
				$(this).val(color.toHexString());
			},
			palette: [
				["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(153, 153, 153)","rgb(183, 183, 183)",
				"rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(239, 239, 239)", "rgb(243, 243, 243)", "rgb(255, 255, 255)"],
				["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
				"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
				["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
				"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
				"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
				"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
				"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
				"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
				"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
				"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
				"rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
				"rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",
				"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
				"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
			]
		});
	});
</script>

</body>
</html>
