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

include $_SERVER['DOCUMENT_ROOT'].'/include/header.html'; 
include $_SERVER["DOCUMENT_ROOT"]."/inc/bbs_info.inc"; 	 	// 게시판 정보

$param = "code=".$code."&s_opt=".$s_opt."&s_key=".$s_key."&idx=".$idx;
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
								
								<form action="bbs_save.php?param=<?=$param?>" method="post" name="frm">
                                    <input type="hidden" name="code" value="<?=$code?>">
                                    <input type="hidden" name="idx" value="<?=$idx?>">
                                    <input type="hidden" name="mode" value="<?=$mode?>">
                                    <input type="hidden" name="smode" value="<?=$smode?>">
									<div class="notice_write_con">
										<div class="contents_con">
											<div class="input_con">
												<ul>
													<li>
														<div class="list_div fl">
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
												</ul>
											</div>
										</div>

										<div class="btn_con">
											<a href="/bbs/bbs_list.php?code=contact" class="a_btn a_btn01">
												취소
											</a>
											<a href="javascript:pw_check();" class="a_btn a_btn02">
												확인
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
    <script language="JavaScript">

        function pw_check()
        {
            if ( document.forms.frm.passwd.value.replace(/ /g, "") == '' ) {
                alert("비밀번호를 입력해 주세요.");
                document.forms.frm.passwd.value = "";
                document.forms.frm.passwd.focus();
                return false;
            }
            document.forms.frm.submit();
        }

    </script>



<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>	