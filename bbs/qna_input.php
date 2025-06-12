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
	case "CenterNew":
		$Menu = "06";
		$sMenu = "06-3";
		$inc_title = "/center/include/center_title.html";
		$inc_nav = "/center/include/center_nav.html";
		break;
	case "CenterQna":
		$Menu = "06";
		$sMenu = "06-4";
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
	if(!$_SESSION["userid"] || $_SESSION["userid"]=='') error("로그인 후 이용해주세요.");
	
	$bbs_row['name'] = $mem_info['store_name'];
	$bbs_row['tel'] = $mem_info['user_tel'];
	$bbs_row['wdate'] = date("Y.m.d");
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
														작성자
													</span>
												</td>
												<td align="left" class="info_td" width="340">
													<input type="hidden" name="name" value="<?=$bbs_row['name']?>" placeholder="입력해주세요." class="input" style="width:243px;" />
													<span>
														<?=$bbs_row['name']?>
													</span>
												</td>
												<td align="left" class="title_td">
													<span>
														작성일
													</span>
												</td>
												<td align="left" class="info_td">
													<span>
														<?=$bbs_row['wdate']?>
													</span>
												</td>
											</tr>
											<tr>
												<td align="left" class="title_td">
													<span>
														공개여부
													</span>
												</td>
												<td align="left" class="info_td">
													<div class="check_con">
														<ul>
															<li>
																<label class="radio_label">
																	<input type="radio" name="show_type" disabled />
																	<div class="check_icon"></div>
																	<span>
																		공개
																	</span>
																</label>
															</li>
															<li>
																<label class="radio_label">
																	<input type="radio" name="show_type" checked="checked" />
																	<div class="check_icon"></div>
																	<span>
																		비공개
																	</span>
																</label>
															</li>
														</ul>
													</div>
												</td>
												<td align="left" class="title_td">
													<span>
														연락처
													</span>
												</td>
												<td align="left" class="info_td" width="340">
													<input type="hidden" name="tel" value="<?=$bbs_row['tel']?>" placeholder="입력해주세요." class="input" style="width:243px;" />
													<span>
														<?=$bbs_row['tel']?>
													</span>
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
																		<input type="text" id="image_file_view_input" class="input" readonly="readonly" value="<?=$file_row['upfile_name']?>"/>
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
			//if(frm.name.value.trim() == ""){ alert("이름을 입력해 주세요."); frm.name.focus(); return false; }
		
			//if(frm.tel1.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel1.focus(); return false; }
			//if(frm.tel2.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel2.focus(); return false; }
			//if(frm.tel3.value.trim() == ''){ alert('연락처를 입력해 주세요.'); frm.tel3.focus(); return false; }
			//if(frm.email_id.value.trim() == ''){ alert('이메일을 입력해 주세요.'); frm.email_id.focus(); return false; }
			//if(frm.email_add.value.trim() == ''){ alert('이메일을 입력해 주세요.'); frm.email_add.focus(); return false; }

			if(frm.subject.value.trim() == ""){ alert("제목을 입력해 주세요."); frm.subject.focus(); return false; }
			if(frm.content.value.trim() == ""){ alert("내용을 입력해 주세요."); frm.content.focus(); return false; }
		}
	</script>

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>	
