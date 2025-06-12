<?php
$code = $_REQUEST['code'];
switch ( $code ) {
	case "notice":
		$Menu = "07";
        $sMenu = "07-1";
        $ssMenu = "07-1-1";
		break;
    case "news":
        $Menu = "07";
        $sMenu = "07-2";
        $ssMenu = "07-2-1";
        break;
    case "contact":
        $Menu = "07";
        $sMenu = "07-3";
        $ssMenu = "07-3-1";
        break;
}
$s_opt = "All";
include $_SERVER["DOCUMENT_ROOT"]."/include/header.html";
include $_SERVER["DOCUMENT_ROOT"]."/inc/bbs_info.inc"; 	 	// 게시판 정보

$param = "code=".$code."&s_opt=".$s_opt."&s_key=".$s_key;

if($mode == "write" || $mode == ""){
	$mode = "write";
}else if($mode == "modify"){
	$sql = "select * from df_site_bbs where code = '$code' and idx='$idx'";
	$bbs_row = $db->row($sql);

	if($bbs_row['parno']){
		$reply_mode = true;
		$parno_ = $bbs_row['parno'];
		$sql_r = "Select * From df_site_bbs Where code = '$code' And idx = '$parno_'  ";
		$reply_row = $db->row($sql_r);
	}
}
?>
	<div id="container">
		<div id="sub_con">
			<div class="contents_con">
				<? include $_SERVER['DOCUMENT_ROOT'].'/include/sub_left.html'; ?>

				<div class="right_con">
					<div class="contents_con">
						<? include $_SERVER['DOCUMENT_ROOT'].'/include/sub_route.html'; ?>

						<div class="contents_con">
							<? include $_SERVER['DOCUMENT_ROOT'].'/include/sub_nav.html'; ?>
							
							<div class="contents_con">
								
								<form action="bbs_save.php?<?=$param?>" name="frm" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <input type="hidden" name="mode" value="<?=$mode?>">
					                <input type="hidden" name="idx" value="<?=$idx?>">
                                    <input type="hidden" name="ctype" value="T">
                                    <input type="hidden" name="memid" value="customer">
									<div class="notice_write_con">
										<div class="contents_con">
											<div class="input_con">
												<ul>
													<li>
														<div class="list_div">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="title_td">
																			<span>
																				제목 <font class="dot">*</font>
																			</span>
																		</td>
																		<td valign="top" align="left" class="info_td">
																			<input type="text" name="subject" value="<?=$bbs_row['subject']?>" placeholder="제목을 입력해주세요." class="input" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</li>
													<li>
														<div class="list_div fl">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="title_td">
																			<span>
																				작성자 <font class="dot">*</font>
																			</span>
																		</td>
																		<td valign="top" align="left" class="info_td">
																			<input type="text" name="name" value="<?=$bbs_row['name']?>" placeholder="작성자를 입력해주세요." class="input" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>

														<div class="list_div fr">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="title_td">
																			<span>
																				비밀번호 <font class="dot">*</font>
																			</span>
																		</td>
																		<td valign="top" align="left" class="info_td">
																			<input type="password" name="passwd" placeholder="비밀번호를 입력해주세요." class="input" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</li>
													<li>
														<div class="list_div">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="info_td">
																			<textarea name="content" placeholder="내용을 입력해주세요." class="textarea"><?=$bbs_row['content']?></textarea>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</li>
													<li>
														<div class="list_div">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="title_td">
																			<span>
																				첨부파일
																			</span>
																		</td>
																		<td valign="top" align="left" class="info_td">
																			<div class="file_con">
                                                                                <div class="input_con">
																					<table cellpadding="0" cellspacing="0">
																						<tbody>
																							<tr>
																								<td align="left" class="input_td">
																									<input type="text" placeholder="선택된 파일 없음"  id="" class="input image_file_view_input" readonly="readonly">
																								</td>
																								<td align="left" class="btn_td">
																									<label>
																										<input name="upfile[]" type="file" onchange="image_file_input(this.value, this)">
																										<span class="file_btn">파일 선택</span>
																									</label>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>

                                                                                <?php
                                                                                $sqlF = " Select * From df_site_bbs_files Where bbsidx='".$idx."' Order by idx Asc ";
                                                                                $rsF = $db->query($sqlF);
                                                                                if ( $rsF ) {
                                                                                ?>
                                                                                    <div class="list_con">
																					<ul>
                                                                                        <?php for ( $ii = 0 ; $ii < count($rsF) ; $ii++ ) { ?>
																						<li>
																							<table cellpadding="0" cellspacing="0">
																								<tbody>
																									<tr>
																										<td valign="top" align="left" class="btn_td">
                                                                                                            <a href="javascript:;" onclick="del_img(this, <?=$rsF[$ii]['idx']?>);">
																												<img src="/img/sub/notice_write_file_delete_btn.png" alt="게시판 글쓰기 파일 삭제 버튼" class="fx w_img" />
																												<img src="/img/sub/m_notice_write_file_delete_btn.png" alt="모바일 게시판 글쓰기 파일 삭제 버튼" class="fx m_img" />
																											</a>
																										</td>
																										<td valign="top" align="left" class="text_td">
                                                                                                            <a href="down.php?code=<?=$code?>&idx=<?=$rsF[$ii]['idx']?>">
                                                                                                                <span><?=$rsF[$ii]['upfile_name']?></span>
                                                                                                            </a>
																										</td>
																									</tr>
																								</tbody>
																							</table>
																						</li>
                                                                                        <?php } ?>
																					</ul>
																				</div> <!--list_con-->
                                                                                <?php } ?>

																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</li>
													<li>
														<div class="list_div">
															<table cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td valign="top" align="left" class="info_td">
																			<div class="agree_con">
																				<div class="title_con">
																					<span>개인정보 수집 및 이용동의</span>
																				</div>

																				<div class="text_con">내용이들어갑니다.</div>

																				<div class="check_con">
																					<label class="checkbox_label">
																						<input type="checkbox" name="is_agree" />
																						<div class="check_icon"></div>
																						<span>
																							위의 개인정보 수집 및 이용동의의 내용을 읽었으며, 이에 동의합니다.
																						</span>
																					</label>
																				</div>
																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</li>
												</ul>
											</div>
										</div>

										<div class="btn_con">
											<a href="/bbs/bbs_list.php?code=contact" class="a_btn a_btn01">
												취소
											</a>

											<a href="javascript:inputCheck();" class="a_btn a_btn02">
                                                <?=$mode == 'write' ? '문의' : '수정'?>
											</a>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" language="javascipt">
		//첨부파일
		function image_file_input(val, me){
            $(me).parent().parent().parent().find(".image_file_view_input").val(val);
            $(me).parent().parent().parent().find(".image_file_view_input").focus();
			//$(".image_file_view_input").val(val);
			//$(".image_file_view_input").focus();
		}

        function del_img(me, idx) {
            var $this = $(me);
            
            if ( confirm("해당파일을 삭제하시겠습니까?") ) {
                var params = 'mode=delimg&idx='+idx+"&code=<?=$code?>";
                $.ajax({
                    type: 'post',
                    url: '../Madmin/bbs/bbs_save.php',
                    data: params,
                    dataType: 'html',
                    success:function(res){
                        if(res.trim() != 'Y'){
                            alert('파일 삭제에 실패했습니다.');
                            return;
                        }
                        else{
                            $this.closest('li').remove();
                        }
                    },
                    error:function(e){ 
                        alert(e.responseText);  
                    }  
                });        
            }
        }

        function inputCheck(){
            var frm = document.forms.frm;
            
            if(frm.subject.value == ""){
                alert("제목을 입력해 주세요.");
                frm.subject.focus();
                return false;
            }
            if(frm.name.value == ""){
                alert("작성자명을 입력해 주세요.");
                frm.name.focus();
                return false;
            }
            if(frm.passwd.value == ""){
                alert("비밀번호를 입력해 주세요.");
                frm.passwd.focus();
                return false;
            }
            if(frm.content.value == ""){
                alert("내용을 입력해 주세요.");
                frm.content.focus();
                return false;
            }
            if(!frm.is_agree.checked)
            {
                alert("개인정보 수집 및 이용동의에 체크해 주세요.");
                frm.is_agree.focus();
                return false;
            }
			
			frm.submit();
		}
	</script>

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>	