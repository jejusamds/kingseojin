<?
include "../../inc/global.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";
include "../../inc/oper_info.inc";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title><?=$site_info->admin_title?></title>
<script language="JavaScript" src="/js/util_lib.js"></script>
<link href="../inc/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
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
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <!-- 상단시작 -->
  <tr>
	<td><? include "../inc/top.inc";?></td>
  </tr>
  <!-- 상단끝 -->
  <tr>
	<td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <!-- 좌측시작 -->
		  <td width="220" valign="top"><? include "../inc/left_set.inc";?></td>
		  <!-- 좌측끝 -->
		  
		  <!-- 내용시작 -->
		  <td>
			<table width="770" border="0" cellspacing="0" cellpadding="13">
			  <tr>
				<td align="center">
				 <table width="730" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td align="left" background="../img/tit_bg.gif"><table border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> 운영정보설정</td>
							</tr>
							<tr>
							  <td bgcolor="534741" height="1"></td>
							</tr>
						</table></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td height="20">&nbsp;</td>
				  </tr>
				  <tr>
					<td><table width="730" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>
						<!------------- 최근 게시물 시작------------------>
						<div align="left"><font color="#000000"><strong>+ 결제정보</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <form name="frm" action="set_save.php" method="post" enctype="multipart/form-data">
						  <input type="hidden" name="tmp">
						  <input type="hidden" name="mode" value="oper_info">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>결제방법</strong></td>
								  <td width="85%" align="left" class="t_value"><?
										$pay_list = explode("/",$oper_info->pay_method);
										for($ii=0; $ii<count($pay_list); $ii++){
										$pay_method[$pay_list[$ii]] = true;
										}
										?>
									<input type="checkbox" name="pay_method[]" value="PB" <? if($pay_method["PB"]==true) echo "checked";?>>무통장입금&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PC" <? if($pay_method["PC"]==true) echo "checked";?>>카드결제&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PN" <? if($pay_method["PN"]==true) echo "checked";?>>계좌이체&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PV" <? if($pay_method["PV"]==true) echo "checked";?>>가상계좌&nbsp; 
									<input type="checkbox" name="pay_method[]" value="PH" <? if($pay_method["PH"]==true) echo "checked";?>>휴대폰결제</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>결제시스템</strong></td>
								  <td align="left" class="t_value">
									<input name="pay_agent" value="DACOM" type="radio" <? if($oper_info->pay_agent == "DACOM") echo "checked"; ?> > 
									<b>DACOM</b> (LG데이콤 전자결제서비스) <a href="http://ecredit.dacom.net" target="_blank">http://ecredit.dacom.net</a><br>
									&nbsp; &nbsp; &nbsp; <!-- 신청시 가입경로에 <b>"위즈샵(WIZSHOP)"</b> 를 꼭 입력하세요 결제 <font color=red>수수료가 3.5</font>으로 저렴해집니다.<br>
									&nbsp; &nbsp; &nbsp; 또는 --> 가입하기를 클릭 후 신청하시기 바랍니다. <a href="http://pgweb.dacom.net/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop" target="_blank"><font color=red>>>가입하기</font></a>
								</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>DACOM 아이디</strong></td>
								  <td align="left" class="t_value"><input name="pay_id" value="<?=$oper_info->pay_id?>" type="text" class="input"><br><br>
									<font color=red>
									  <b>데이콤 관리자 > 계약정보 > 상점정보관리 > "승인결과전송여부" 를 전송(웹전송)</b>으로 꼭 변경하세요.<br>
										반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.
									</font>
								</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>DACOM 보안키</strong></td>
								  <td align="left" class="t_value"><input name="pay_key" value="<?=$oper_info->pay_key?>" type="text" size="50" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>은행계좌번호</strong></td>
								  <td align="left" class="t_value"><textarea cols="50" rows="5" name="pay_account" class="textarea"><?=$oper_info->pay_account?></textarea></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 가맹점 ID: DACOM (ecredit)에서 부여받은 가맹점 고유 ID를 입력하세요</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 은행계좌번호: 주문시 사용할 은행계좌를 한줄에 하나씩 입력합니다. ex) 국민은행 484201-01-127831, 예금주: 홍길동</font></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>
						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ 에스크로</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>사용여부</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input name="pay_escrow" value="Y" type="radio" <? if($oper_info->pay_escrow == "Y") echo "checked"; ?> > 사용함
									<input name="pay_escrow" value="N" type="radio" <? if($oper_info->pay_escrow == "N") echo "checked"; ?> > 사용안함 
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>에스크로 수신url</strong></td>
								  <td align="left" class="t_value"> http://<?=$HTTP_HOST?>/shop/dacom/escrow_save.php</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 무통장입금: 에스크로 사용시 10만원이상의 주문에서는 무통장입금 방법이 사라집니다.</font></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>

						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ 적립금정보</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>사용여부</strong></td>
								  <td align="left" class="t_value" colspan="3">
									<input type="radio" name="reserve_use" value="Y" <? if($oper_info->reserve_use == "Y") echo "checked"; ?>>사용함
									<input type="radio" name="reserve_use" value="N" <? if($oper_info->reserve_use == "N") echo "checked"; ?>>사용안함</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>회원가입 적립금</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_join" type="text" value="<?=$oper_info->reserve_join?>" class="input"></td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>추천인 적립금</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_recom" type="text" value="<?=$oper_info->reserve_recom?>" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>최소사용 적립금</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_min" type="text" value="<?=$oper_info->reserve_min?>" class="input"></td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>1회 최대사용 적립금</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_max" type="text" value="<?=$oper_info->reserve_max?>" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>상품구매시 적립금</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_buy" type="text" value="<?=$oper_info->reserve_buy?>" class="input"> %</td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>적립금 일괄적용</strong></td>
								  <td width="35%" align="left" class="t_value"><input name="reserve_per" type="text" value="<?=$oper_info->reserve_per?>" class="input" size="10"> % &nbsp;
									<input type="button" class="gbtn" value=" 적 용 " onClick="setReserve(this.form);"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 사용여부: 상품구입시 적립금 누적/사용 , 회원가입시, 추천인인경우 등 적립금 사용이 가능합니다.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 상품구매시 적립금: 상품등록시 판매금액에 작성한 퍼센트를 적용하여 적립금이 자동계산됩니다.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 적립금 일괄적용: 현재 등록되어있는 상품에 적립금을 작성한 퍼센트로 다시 적용합니다.</font></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>
						<br><br><br>
						<div align="left"><font color="#000000"><strong>+ 상품평설정</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>사용여부</strong></td>
								  <td width="35%" align="left" class="t_value">
									<input type="radio" name="review_use" value="Y" <? if($oper_info->review_use == "Y") echo "checked"; ?>>사용함
									<input type="radio" name="review_use" value="N" <? if($oper_info->review_use == "N") echo "checked"; ?>>사용안함</td>
								  <td width="15%" align="center" bgcolor="#efefef" class="s01"><strong>작성권한</strong></td>
								  <td width="35%" align="left" class="t_value">
									<input type="radio" name="review_level" value="E" <? if($oper_info->review_level == "E") echo "checked"; ?>>회원+비회원
									<input type="radio" name="review_level" value="M" <? if($oper_info->review_level == "M") echo "checked"; ?>>회원만</td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
							    </tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>
                        <br /><br /><br />
                        
                        <!-- 여기서 부터 추가 시작 -->
                        						<div align="left"><font color="#000000"><strong>+ 배송정보</strong></font></div>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td colspan="3"><table width="100%" border="0" cellspacing="3" cellpadding="1" class="t_style">
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>택배사</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input name="del_com" value="<?=$oper_info->del_com?>" type="text" class="input">
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td height="25" align="center" bgcolor="#efefef" class="s01"><strong>배송추적설정</strong></td>
								  <td align="left" class="t_value"> <input name="del_trace" value="<?=$oper_info->del_trace?>" type="text" size="80" class="input"></td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>무료배송</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DA" <? if($oper_info->del_method == "DA") echo "checked"; ?>> 배송비 전액무료
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>수신자부담</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DB" <? if($oper_info->del_method == "DB") echo "checked"; ?>> 수신자부담 (착불)
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>고정값</strong></td>
								  <td width="85%" align="left" class="t_value">
									<input type="radio" name="del_method" value="DC" <? if($oper_info->del_method == "DC") echo "checked"; ?>>
									<input name="del_fixprice" type="text" value="<?=$oper_info->del_fixprice?>" class="input">원
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>구매가격별</strong></td>
								  <td width="85%" align="left" class="t_value">
									<table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td>
										  <input type="radio" name="del_method" value="DD" <? if($oper_info->del_method == "DD") echo "checked"; ?>>
										  <input type="text" name="del_staprice" value="<?=$oper_info->del_staprice?>" class="input">
										</td>
										<td>&nbsp;이상구매시 <input type="text" name="del_staprice2" value="<?=$oper_info->del_staprice2?>" class="input"> 원</td>
									  </tr>                  
									  <tr>
										<td></td>
										<td>&nbsp;이하구매시 <input type="text" name="del_staprice3" value="<?=$oper_info->del_staprice3?>" class="input"> 원</td>
									  </tr>   
									</table>   
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td width="15%" height="25" align="center" bgcolor="#efefef" class="s01"><strong>지역할증</strong></td>
								  <td width="85%" align="left" class="t_value">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
										  <td width="10"></td>
										  <td>우편번호</td>
										  <td>할증료</td>

										</tr>
                                        
                                       <? for($iu = 1; $iu <= 50; $iu++){ ?> 
										<tr>
										  <td width="10"></td>
										  <td> 
										  <input name="del_extrapost<?=$iu?>" type="text" value="<?=$oper_info->{'del_extrapost'.$iu}?>" class="input" size="9"> 부터  
										  <input name="del_extrapostq<?=$iu?>2" type="text" value="<?=$oper_info->{'del_extrapostq'.$iu.'2'}?>" class="input" size="9"> 까지 
										  </td>
										  <td>
										  <input name="del_extraprice<?=$iu?>" type="text" value="<?=$oper_info->{'del_extraprice'.$iu}?>" class="input" size="20">원 <input type="checkbox" name="q_view<?=$iu?>" value="1" <? if($oper_info->{'q_view'.$iu} == "1"){?> checked="checked" <? } ?> />배송불가
										  </td>
										</tr>
									   <? } ?>
									  </table>
								  </td>
								</tr>
								<tr>
								  <td colspan="4" bgcolor="eaeaea" height="1"></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 배송료 선택: 배송료를 4가지 형태로 구분하며 각 상황별 배송료 설정을 합니다.</font></td>
								</tr>
								<tr>
								  <td colspan="4" align="left"><font color="red">- 지역할증: 각지역별로 할증 배송료를 설정 합니다. 북제주군 한경면인 경우 695840 부터 695844 까지 2000원</font></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr>
							<td colspan="3" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td bgcolor="464646" width="706"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>						  
						</table>
                        <!-- 여기서까지 추가 끝 -->


						  <table>
						  <tr>
							<td height="40" colspan="3" align="center"><input type="image" src="../img/bt_confirm.gif" width="42" height="20" border="0" />&nbsp;&nbsp;<a href="../"><img src="../img/bt_cancel.gif" width="42" height="20" border="0" /></a></td>
						  </tr>
						  </table>
						</td>
					  </tr>
					</table>
				   </td>
				  </tr></form>


				 </table>
				</td>
			  </tr>
			</table>
		  </td>
		  <!-- 내용끝 -->
		</tr>
	  </table>
	</td>
  </tr>
  <tr>
	<td><? include "../inc/footer.inc";?></td>
  </tr>
</table>

</body>
</html>
