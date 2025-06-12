<?
$code = $_REQUEST['code'];
switch ( $code ) {
	case "Event":
		$Menu = "03";
		$sMenu = "03-1";
		$inc_title = "/event/include/event_title.html";
		$inc_nav = "/event/include/event_nav.html";
		break;
	case "DataMart":
		$Menu = "04";
		$sMenu = "04-1";
		$inc_title = "/dataroom/include/dataroom_title.html";
		$inc_nav = "/dataroom/include/dataroom_nav.html";
		break;
	case "DataNews":
		$Menu = "04";
		$sMenu = "04-2";
		$inc_title = "/dataroom/include/dataroom_title.html";
		$inc_nav = "/dataroom/include/dataroom_nav.html";
		break;
	case "DataStudy":
		$Menu = "04";
		$sMenu = "04-3";
		$inc_title = "/dataroom/include/dataroom_title.html";
		$inc_nav = "/dataroom/include/dataroom_nav.html";
		break;
	case "CenterNotice":
		$Menu = "06";
		$sMenu = "06-1";
		$inc_title = "/center/include/center_title.html";
		$inc_nav = "/center/include/center_nav.html";
		break;
	case "CenterFaq":
		$Menu = "06";
		$sMenu = "06-2";
		$inc_title = "/center/include/center_title.html";
		$inc_nav = "/center/include/center_nav.html";
		break;
}

include $_SERVER["DOCUMENT_ROOT"]."/include/header.html";
include $_SERVER["DOCUMENT_ROOT"]."/inc/bbs_info.inc"; 	 	// 게시판 정보


$param = "code=".$code."&s_opt=".$s_opt."&s_key=".$s_key;

$level_info = level_info();
$mem_level = $level_info[$_SESSION["userlevel"]][level];
$wpermi = $level_info[$bbs_info['wpermi']][level];

$confirm_btn = "<input type=\"submit\" value=\"작성완료\" class=\"input_btn\" />";
$cancel_btn = "<a href=\"bbs_list.php?page=$page&s_grp=$s_grp&$param\" class=\"a_btn\">취소</a>";

if(!$mode)	$mode = "write";
// 작성
if($mode == "write"){
	if($wpermi < $mem_level) error("글작성 권한이 없습니다.");
	
	$bbs_row['name'] = $mem_info['user_name'];
	$bbs_row['tel'] = $mem_info['user_tel'];
}
// 수정
else if($mode == "modify"){
	$sql = " Select * From df_site_bbs Where idx='".$idx."' ";
	$bbs_row = $db->row($sql);
	
	if($wpermi == "13" && !$_SESSION["userid"]) { 
		if($_SESSION[bbsauth_idx] != $idx)
			error("해당게시물을 수정할 수 없습니다.");	//비밀번호인증방식
	}
	else{
		if($bbs_row['memid'] != $_SESSION["userid"])
			error("해당게시물을 수정할 수 없습니다.");	//로그인인증방식
	}
	
	$sqlF = " Select * From df_site_bbs_files Where bbsidx='".$idx."' ";
	$file_row = $db->row($sqlF);
}
?>
	<div id="container">
		<div id="sub_con">
			<? include $_SERVER['DOCUMENT_ROOT'].$inc_title; ?>

			<div class="contents_con">
				<? include $_SERVER['DOCUMENT_ROOT'].$inc_nav; ?>

				<div class="contents_con">
					
					<form name="frm" action="bbs_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
					<input type="hidden" name="mode" value="<?=$mode?>">
					<input type="hidden" name="idx" value="<?=$idx?>">
					<input type="hidden" name="ctype" value="T">
					<input type="hidden" name="privacy" value="<? if($bbs_row['privacy']=="Y" || $bbs_info['privacy']=="Y"){ ?>Y<? }else{ ?>N<? } ?>" />
					<input type="hidden" name="page" value="<?=$page?>">
					<input type="hidden" name="s_grp" value="<?=$s_grp?>">
						<div class="notice_write_con">
							<div class="input_con">
								<div class="info_div">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td align="left" class="title_td">
													<span>
														이름
													</span>
												</td>
												<td align="left" class="info_td">
													<input type="text" name="name" value="<?=$bbs_row['name']?>" placeholder="입력해주세요." class="input" style="width:243px;" />
												</td>
											</tr>
											<tr>
												<td align="left" class="title_td">
													<span>
														연락처
													</span>
												</td>
												<td align="left" class="info_td">
													<? list($tel1,$tel2,$tel3) = explode("-",$bbs_row['tel']); ?>
													<table cellpadding="0" cellspacing="0">
														<tbody>
															<tr>
																<td align="left">
																	<select name="tel1" class="select" style="width:180px;">
																		<option value="">선택</option>
																		<option value="010" <? if($tel1=="010"){ ?>selected<? } ?>>010</option>
																		<option value="011" <? if($tel1=="011"){ ?>selected<? } ?>>011</option>
																		<option value="016" <? if($tel1=="016"){ ?>selected<? } ?>>016</option>
																		<option value="017" <? if($tel1=="017"){ ?>selected<? } ?>>017</option>
																		<option value="018" <? if($tel1=="018"){ ?>selected<? } ?>>018</option>
																		<option value="019" <? if($tel1=="019"){ ?>selected<? } ?>>019</option>
																		<option value="02" <? if($tel1=="02"){ ?>selected<? } ?>>02</option>
																		<option value="031" <? if($tel1=="031"){ ?>selected<? } ?>>031</option>
																		<option value="032" <? if($tel1=="032"){ ?>selected<? } ?>>032</option>
																		<option value="033" <? if($tel1=="033"){ ?>selected<? } ?>>033</option>
																		<option value="041" <? if($tel1=="041"){ ?>selected<? } ?>>041</option>
																		<option value="042" <? if($tel1=="042"){ ?>selected<? } ?>>042</option>
																		<option value="043" <? if($tel1=="043"){ ?>selected<? } ?>>043</option>
																		<option value="061" <? if($tel1=="061"){ ?>selected<? } ?>>061</option>
																		<option value="062" <? if($tel1=="062"){ ?>selected<? } ?>>062</option>
																		<option value="063" <? if($tel1=="063"){ ?>selected<? } ?>>063</option>
																		<option value="064" <? if($tel1=="064"){ ?>selected<? } ?>>064</option>
																		<option value="065" <? if($tel1=="065"){ ?>selected<? } ?>>065</option>
																		<option value="061" <? if($tel1=="061"){ ?>selected<? } ?>>061</option>
																		<option value="062" <? if($tel1=="062"){ ?>selected<? } ?>>062</option>
																		<option value="063" <? if($tel1=="063"){ ?>selected<? } ?>>063</option>
																		<option value="064" <? if($tel1=="064"){ ?>selected<? } ?>>064</option>
																		<option value="070" <? if($tel1=="070"){ ?>selected<? } ?>>070</option>
																		<option value="080" <? if($tel1=="080"){ ?>selected<? } ?>>080</option>
																	</select>
																</td>
																<td align="center" width="30">
																	<span>-</span>
																</td>
																<td align="left">
																	<input type="tel" name="tel2" value="<?=$tel2?>" maxlength="4" class="input" style="width:138px;" onkeydown="onlyNumber(this)" />
																</td>
																<td align="center" width="30">
																	<span>-</span>
																</td>
																<td align="left">
																	<input type="tel" name="tel3" value="<?=$tel3?>" maxlength="4" class="input" style="width:138px;" onkeydown="onlyNumber(this)" />
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left" class="title_td">
													<span>
														E-mail
													</span>
												</td>
												<td align="left" class="info_td">
													<? list($email_id,$email_add) = explode("@",$bbs_row['email']); ?>
													<table cellpadding="0" cellspacing="0">
														<tbody>
															<tr>
																<td align="left">
																	<input type="text" name="email_id" value="<?=$email_id?>" placeholder="입력해주세요." class="input" style="width:243px;" />
																</td>
																<td align="center" width="30">
																	<span>@</span>
																</td>
																<td align="left" width="300">
																	<input type="text" name="email_add" value="<?=$email_add?>" id="email_address_input" class="input" style="width:243px;" />
																</td>
																<td align="left">
																	<select onchange="email_address_select(this.value)" class="select" style="width:285px;">
																		<option value="선택해주세요.">선택해주세요.</option>
																		<option value="naver.com">naver.com</option>
																		<option value="hanmail.net">hanmail.net</option>
																		<option value="gmail.com">gmail.com</option>
																		<option value="nate.com">nate.com</option>
																		<option value="daum.net">daum.net</option>
																		<option value="hotmail.com">hotmail.com</option>
																		<option value="yahoo.co.kr">yahoo.co.kr</option>
																		<option value="lycos.co.kr">lycos.co.kr</option>
																		<option value="">직접입력</option>
																	</select>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<div class="info_div">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td align="left" class="title_td">
													<span>
														제목
													</span>
												</td>
												<td align="left" class="info_td">
													<input type="text" name="subject" value="<?=$bbs_row['subject']?>" placeholder="제목을 입력해주세요." class="input" style="width:898px;" />
												</td>
											</tr>
											<tr>
												<td align="left" class="title_td">
													<span>
														내용
													</span>
												</td>
												<td align="left" class="info_td">
													<textarea name="content" placeholder="내용을 입력해주세요." class="textarea"><?=$bbs_row['content']?></textarea>
												</td>
											</tr>
											<tr>
												<td align="left" class="title_td">
													<span>
														첨부파일
													</span>
												</td>
												<td align="left" class="info_td">
													<div class="file_con">
														<table cellpadding="0" cellspacing="0">
															<tbody>
																<tr>
																	<td align="left" class="btn_td">
																		<label for="file-input">
																			<input id="file-input" name="upfile[]" type="file" class="file_input" onchange="image_file_input(this.value)" />
																			<span class="file_btn">파일찾기</span>
																		</label>
																	</td>
																	<td align="left">
																		<input type="text" id="image_file_view_input" class="input" readonly="readonly" />
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="btn_con">
								<?=$confirm_btn?>
								<?=$cancel_btn?>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" language="javascript">
		// 숫자만 입력
		function onlyNumber(obj) {
			$(obj).keyup(function(){
				 $(this).val($(this).val().replace(/[^0-9]/g,""));
			}); 
		}

		// 이메일
		function email_address_select(val){
			var state = $('.email_select option:selected').val();
			if ( state == '선택해주세요.' ) {
				$("#email_address_input").val("");
			}else{
				$("#email_address_input").val(val);
				$("#email_address_input").focus();
			}
		}

		// 첨부파일
		function image_file_input(val){
			$("#image_file_view_input").val(val);
			$("#image_file_view_input").focus();
		}

		// 작성완료
		function inputCheck(frm){
			if(frm.name.value.trim() == ""){ alert("이름을 입력해 주세요."); frm.name.focus(); return false; }
		
			if(frm.tel1.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel1.focus(); return false; }
			if(frm.tel2.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel2.focus(); return false; }
			if(frm.tel3.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel3.focus(); return false; }
			if(frm.email_id.value.trim() == ''){ alert('이메일을 입력해 주세요.'); frm.email_id.focus(); return false; }
			if(frm.email_add.value.trim() == ''){ alert('이메일을 입력해 주세요.'); frm.email_add.focus(); return false; }

			if(frm.subject.value.trim() == ""){ alert("제목을 입력해 주세요."); frm.subject.focus(); return false; }
			if(frm.content.value.trim() == ""){ alert("내용을 입력해 주세요."); frm.content.focus(); return false; }
		}
	</script>

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>	
