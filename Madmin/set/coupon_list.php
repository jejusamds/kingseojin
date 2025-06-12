<?
include "../../inc/global.inc";
include "../../inc/util_lib.inc";
include "../../inc/Eadmin_check.inc";
include "../../inc/site_info.inc";


// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&use_gb=$use_gb";
//--------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title><?=$site_info->admin_title?></title>
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
<script language="JavaScript" type="text/javascript">
<!--
//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
function selectAll(){
	
	var i; 	
	
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].cocode != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].cocode != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택회원 삭제
function userDelete(){

	var i;
	var seluser = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].cocode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					seluser = seluser + document.forms[i].cocode.value + "|";
				}
			}
	}
	
	if(seluser == ""){
		alert("삭제할 쿠폰를 선택하세요.");
		return;
	}else{
		if(confirm("선택한 쿠폰를 정말 삭제하시겠습니까?")){
			document.location = "set_save.php?mode=coupon&sub_mode=delete&seluser=" + seluser;
		}
	}
}
//-->
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
		  <td valign="top">
			<table width="770" border="0" cellspacing="0" cellpadding="13">
			  <tr>
				<td align="center">
				 <table width="730" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td align="left" background="../img/tit_bg.gif"><table border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td height="23" align="left" class="l02"><img src="../img/main_ic_circle.gif" width="11" height="11" /> 쿠폰관리</td>
							</tr>
							<tr>
							  <td bgcolor="534741" height="1"></td>
							</tr>
						</table></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td height="10">&nbsp;</td>
				  </tr>
				  <tr>
					<td>
					  <table width="100%" border="0" cellpadding="5" cellspacing="4" bgcolor="#ebebeb">
						  <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
						  <input type="hidden" name="tmp">
						  <input type="hidden" name="page" value="<?=$page?>">
						  <input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
						  <tr>
							<td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td height="26" align="right" style="padding-left:5px">사용여부 : </td>
								<td align="left" style="padding-left:5px" class="s01"><select name="use_gb">
								  <option value="">전체</option>
								  <option value="Y" <? if($use_gb == "Y") echo "selected"; ?>>사용</option>
								  <option value="N" <? if($use_gb == "N") echo "selected"; ?>>미사용</option>
								</select></td>
								<td width="1"><img src="../img/list_vline.gif" /></td>
								<td height="26" align="right" style="padding-left:5px">조건검색</td>
								<td align="left" class="s01" style="padding-left:5px"><table border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td><span class="s01" style="padding-left:5px">
										<select name="searchopt" class="select">
										  <option value="cocode" <? if($searchopt == "cocode") echo "selected"; ?>>쿠폰번호</option>
										  <option value="coname" <? if($searchopt == "coname") echo "selected"; ?>>쿠폰명</option>
										  <option value="coprice" <? if($searchopt == "coprice") echo "selected"; ?>>쿠폰가격</option>
										  <option value="memid" <? if($searchopt == "memid") echo "selected"; ?>>회원아이디</option>
										</select>
									  </span></td>
									  <td><span class="s01" style="padding-left:5px">
										<input type="text" name="keyword" value="<?=$keyword?>" class="input01" />
									  </span></td>
									  <td><span class="s01" style="padding-left:5px">
										<input name="image" type="image" onclick="submit" src="../img/bt_seach.gif" align="absmiddle" />
									  </span></td>
									</tr>
								</table></td>
							  </tr>
							</table></td>
						  </tr></form>
						</table>
					</td>
				  </tr>
				   <tr>
					<td height="10">&nbsp;</td>
				  </tr>
				  <tr>
					<td>
						<table width="730" border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td width="12" height="25"></td>
							<td colspan="5" align="left"><font color="#000000"><strong>+ 쿠폰목록</strong></font></td>
							<td colspan="4" align="right"><a href="coupon_input.php"><img src="../img/bt_coupon_reg.gif" border="0" ></a></td>
							<td width="12"></td>
						  </tr>
						  <form>
						  <tr>
							<td><img src="../img/list_bar_left.gif" width="12" height="25" /></td>
							<td width="14" align="center" background="../img/list_bar_bg.gif" class="s04"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></td>
							<td width="120" align="center" background="../img/list_bar_bg.gif" class="s04">쿠폰번호</td>
							<td width="180" align="center" background="../img/list_bar_bg.gif" class="s04">쿠폰명</td>
							<td width="160" align="center" background="../img/list_bar_bg.gif"class="s04">쿠폰사용기간</td>
							<td width="60" align="center" background="../img/list_bar_bg.gif" class="s04">쿠폰가격</td>
							<td width="70" align="center" background="../img/list_bar_bg.gif" class="s04">회원아이디</td>
							<td width="80" align="center" background="../img/list_bar_bg.gif" class="s04">쿠폰등록일</td>
							<td width="40" align="center" background="../img/list_bar_bg.gif" class="s04">사용</td>
							<td width="40" align="center" background="../img/list_bar_bg.gif"class="s04">기능</td>
							<td><img src="../img/list_bar_right.gif" width="12" height="25" /></td>
						  </tr>
						  </form>
						  <?
						  $join_sdate = $prev_year."-".$prev_month."-".$prev_day;
						  $join_edate = $next_year."-".$next_month."-".$next_day;

							$query = "select cocode, coname, sdate, edate, coprice, memid,  use_gb, reg_date from ez_coupon where cocode!='' ";
							if($prev_year != "") $query .= " and sdate > '$join_sdate' and edate <= '$join_edate 23:59:59'";
							if($searchopt)	$query .= " and $searchopt like '%$keyword%'";
							if($use_gb)	$query .= " and use_gb = '$use_gb'";
							$query .=" ORDER BY reg_date desc";

						  $result = mysql_query($query) or error(mysql_error());
						  $total = mysql_num_rows($result);

						  $rows = 20;
						  $lists = 5;
							$page_count = ceil($total/$rows);
							if($page < 1 || $page > $page_count) $page = 1;
							$start = ($page-1)*$rows;
							$no = $total-$start;
							if($start>1) mysql_data_seek($result,$start);
							
						  while(($row = mysql_fetch_object($result)) && $rows){
							  if($row->isuse == "Y")	 {	$row->isuse="<font color='#FF0000'>사용</font>";	
							  }	 else	{
								  $row->isuse="<font color='#0000FF'>중지</font>";	
							  }
						  ?>
						  <form name="frm<?=$no?>">
						  <input type="hidden" name="cocode" value="<?=$row->cocode?>">
						  <tr>
							<td height="24"></td>
							<td align="center"><input type="checkbox" name="select_checkbox"></td>
							<td align="center"><?=$row->cocode?></td>
							<td align="center"><?=$row->coname?></td>
							<td align="center"><?=$row->sdate?>~<?=$row->edate?></td>
							<td align="center"><?=$row->coprice?></td>
							<td align="center"><?=$row->memid?></td>
							<td align="center"><?=substr($row->reg_date,0,10)?></td>
							<td align="center"><?=$row->use_gb?></td>
							<td align="center"><a href="coupon_input.php?sub_mode=update&cocode=<?=$row->cocode?>"><img src="../img/bt_s_modify.gif" border="0"></a></td>
							<td></td>
						  </tr>
						  <tr>
							<td></td>
							<td colspan="9" bgcolor="eaeaea" height="1"></td>
							<td></td>
						  </tr>
						  </form>
						  <?
								$no--;
								$rows--;
							}
							if($total <= 0){
						  ?>
							<tr><td height="30" colspan="11" align="center">등록된 쿠폰이 없습니다.</td></tr>
						  <?
							}
						  ?>
						  <!---------------------- 리스트 끝----------------------------->
						  <tr>
							<td colspan="11" height="4"></td>
						  </tr>
						  <tr>
							<td><img src="../img/list_bar_left2.gif" width="12" height="4" /></td>
							<td colspan="9" bgcolor="464646"></td>
							<td><img src="../img/list_bar_right2.gif" width="12" height="4" /></td>
						  </tr>
						</table>
						<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
							<tr> 
							  <td width="20"></td>
							  <td align="left" width="20" valign="top"><img src="../img/arrow_90.gif" border="0"></td>
							  <td align="left" width="85" style="padding-top:8px">
								<a href="javascript:userDelete();"><img src="../img/bt_del.gif" border="0"></a>
							  </td>
							  <td style="padding-top:8px"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
							</tr>
						</table>
						
				   </td>
				  </tr>
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
