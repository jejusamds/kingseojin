<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&isuse=$isuse";
$param .= "&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//--------------------------------------------------------------------------------------------------

$type = "popup";
if($mode == "") $mode = "mpinsert";
if($mode == "mpupdate"){
	$sql = "select * from df_site_content_mobile where idx='$idx'";
	$popup_info = $db->row($sql);
	$popup_info['content'] = str_replace("\"", "'", $popup_info['content']);
}
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

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						팝업관리 - MOBILE 등록/수정
					</h3>
					<ul class="breadcrumb">
						<li>팝업 설정</li>
						<li class="active">팝업관리 - MOBILE 등록/수정</li>
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
										for($ii=2023; $ii <= date("Y")+1; $ii++){
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
										for($ii=2023; $ii <= date("Y")+1; $ii++){
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
								<th>사용여부</th>
								<td class="comALeft" colspan="3">
									<input name="isuse" type="radio" value="Y" <? if($popup_info['isuse'] == "Y" || $popup_info['isuse'] == "") echo "checked"; ?>> 사용함 &nbsp; 
									<input name="isuse" type="radio" value="N" <? if($popup_info['isuse'] == "N") echo "checked"; ?>> 사용안함
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

				<div class="box comMTop20 comMBottom20" style="width:978px;">
					<div class="comPTop20 comPBottom20">
						<div class="comFLeft comACenter" style="width:10%;">
							<button class="btn btn-primary btn-sm" type="button" onClick="location.href='mobile_popup.php?page=<?=$page?>&<?=$param?>';">목록</button>
						</div>
						<div class="comFRight comARight" style="width:85%; padding-right:20px;">
							<button class="btn btn-info btn-sm" type="submit">확인</button>
							<button class="btn btn-danger btn-sm" type="button" onClick="location.href='mobile_popup.php?page=<?=$page?>&<?=$param?>';">취소</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				
				</form>

			</div>
		</div>
	</div>
</div>

</body>
</html>
