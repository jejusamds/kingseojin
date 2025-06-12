<?
$code = $_REQUEST['code'];
switch ( $code ) {
	case "notice":
		$Menu = "04";
		$sMenu = "04-1";
		$inc_title = "/customer/include/customer_title.html";
		$inc_nav = "/customer/include/customer_nav.html";
		break;
	case "faq":
		$Menu = "04";
		$sMenu = "04-2";
		$inc_title = "/customer/include/customer_title.html";
		$inc_nav = "/customer/include/customer_nav.html";
		break;
	case "customer":
		$Menu = "04";
		$sMenu = "04-3";
		$inc_title = "/customer/include/customer_title.html";
		$inc_nav = "/customer/include/customer_nav.html";
		break;
	case "special":
		$Menu = "04";
		$sMenu = "04-5";
		$inc_title = "/customer/include/customer_title.html";
		$inc_nav = "/customer/include/customer_nav.html";
		break;
	case "data":
		$Menu = "04";
		$sMenu = "04-6";
		$inc_title = "/customer/include/customer_title.html";
		$inc_nav = "/customer/include/customer_nav.html";
		break;
}

include $_SERVER["DOCUMENT_ROOT"]."/include/header.html";
include $_SERVER["DOCUMENT_ROOT"]."/inc/bbs_info.inc"; 	 	// 게시판 정보


$param = "code=".$code."&s_opt=".$s_opt."&s_key=".$s_key;
?>
		<div id="sub_con">
			<? include $_SERVER['DOCUMENT_ROOT'].$inc_title; ?>

			<? include $_SERVER['DOCUMENT_ROOT'].$inc_nav; ?>

			<? include $_SERVER['DOCUMENT_ROOT'].'/include/m_sub_route.html'; ?>

			<div class="contents_con">
				<div class="title_con">
					<div class="text_con">
						<span>
							<?=$route_text02?>
						</span>
					</div>

					<div class="bar"></div>
				</div>

				<div class="contents_con width_1200">
					
					<form action="bbs_save.php?<?=$param?>" method="post">
					<input type="hidden" name="mode" value="auth">
					<input type="hidden" name="smode" value="<?=$smode?>">
					<input type="hidden" name="idx" value="<?=$idx?>"> 
					<input type="hidden" name="page" value="<?=$page?>">
					<input type="hidden" name="s_grp" value="<?=$s_grp?>">
						<div class="notice_write_con">
							<div class="input_con">
								<table cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td align="left" class="title_td">
												<span>
													비밀번호
												</span>
											</td>
											<td align="left" class="info_td">
												<div class="password_con">
													<div class="input_con">
														<input type="password" name="passwd" class="input" />
													</div>

													<div class="text_con">
														<span>
															글 작성 시 입력하셨던 비밀번호를 입력해주세요.
														</span>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class="btn_con">
								<input type="submit" value="확인" class="input_btn" />

								<a href="bbs_list.php?page=<?=$page?>&s_grp=<?=$s_grp?>&<?=$param?>" class="a_btn">
									취소
								</a>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>

<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>	
