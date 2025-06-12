<?

$ordmail = "
<table border=0 cellpadding=0 cellspacing=0 width=900 align=center class=text_style05>
	<tr><td>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr><td style='padding:0 0 5 0' valign=bottom><img src=http://$HTTP_HOST/images/sett_t01.gif></td>
				<td></td></tr>
			</table>
	</td></tr>
	<tr><td>
			<table border=0 cellpadding=0 cellspacing=0 width=100% class=text_style05>
			<tr align=center>
				<td background=http://$HTTP_HOST/images/form_bar_bg1.gif height=32 width=80>이미지</td>
				<td background=http://$HTTP_HOST/images/form_bar_bg1.gif align=center width=560>상품정보</td>
				<td background=http://$HTTP_HOST/images/form_bar_bg1.gif width=100>상품가격</td>
				<td background=http://$HTTP_HOST/images/form_bar_bg1.gif width=80>적립금</td>
				<td background=http://$HTTP_HOST/images/form_bar_bg1.gif width=80>구매수량</td>
			</tr>
";

	$sql = "select * from df_shop_basket where orderid = '$order_info->orderid'";
	$result = mysql_query($sql) or error(mysql_error());
	$prd_num = mysql_num_rows($result);
	
	$no = 0;
	while($row = mysql_fetch_object($result)){
	
		$prd_price += ($row->prdprice * $row->amount);
		if($row->prdimg == "") $row->prdimg = "http://$HTTP_HOST/images/noimage.gif";
		else $row->prdimg = "http://$HTTP_HOST/prdimg/".$row->prdimg;
		
		$optcode = "";
		if($row->opttitle != '') $optcode = "$row->opttitle : $row->optcode / ";
		if($row->opttitle2 != '') $optcode .= "$row->opttitle2 : $row->optcode2 / ";
		if($row->opttitle3 != '') $optcode .= "$row->opttitle3 : $row->optcode3 / ";
							
$ordmail .= "
		<tr>
		<td style=padding:10 align=center><a href='http://$HTTP_HOST/product/product_view.html?prdcode=$row->prdcode' target='prdview'><img src='$row->prdimg' border='0' width='50' height='50'></a></td>
		<td><a href='http://$HTTP_HOST/product/product_view.html?prdcode=$row->prdcode' target='prdview'>$row->prdname</a><br>$optcode</td>
		<td class=price align=center>".number_format($row->prdprice)."원</td>
		<td align=center><b>".number_format($row->prdreserve*$row->amount)."원</b></td>
		<td align=center><b>".$row->amount."</b></td>
		</tr>
		<tr><td colspan=7 height=1 bgcolor=#E5E5E5></td></tr>";
		
		$no++;
	}

// 회원할인
if($order_info->discount_price > 0){
	$discount_msg = " - 회원할인(<b>".number_format($order_info->discount_price)."원</b>)";
}

// 적립금 사용
if($order_info->reserve_use > 0){
	$reserve_msg = " - 적립금 사용(<b>".number_format($order_info->reserve_use)."원</b>)";
}
	    
// 쿠폰사용
if($order_info->coupon_use > 0){
	$coupon_msg = " - 쿠폰 사용(<b>".number_format($order_info->coupon_use)."원</b>)";
}
if($order_info->escrow_yn == "Y"){
   $escrow_pr = " <span style=\"color:#006A34\">( 에스크로 결제 )</span>";
}   
$ordmail .= "
		<tr><td colspan=5 height=2 bgcolor=#E5E5E5></td></tr>
		<tr>
		<td height=38 background=http://$HTTP_HOST/images/form_bar_bg2.gif align=center colspan=5>
		<b>총결제금액 </b>:  상품(<b>".number_format($order_info->prd_price)."원)</b> ".$discount_msg." + 배송비(<b>".number_format($order_info->deliver_price)."원</b>)".$coupon_msg.$reserve_msg." = <span class=price style='color:#ff0000;'>".number_format($order_info->total_price)."원</span>
		</td>
		</tr>
		</table>
		</td></tr>
		<tr><td><br></td></tr>
		<tr><td>
		<table border=0 cellpadding=0 cellspacing=0 style='width:100%;border:1px solid #e5e5e5;'>
		<tr height=40>
		<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>주문번호</td>
		<td style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>$order_info->orderid</td>
		<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>주문일</td>
		<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->order_date</td>
		</tr>
		<tr height=40>
		<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;'>결제방법</td>
		<td colspan=3 style='padding-left:20px;'>".pay_method($order_info->pay_method). $escrow_pr."</td></tr>";


if($order_info->deliver_num != ""){
$ordmail .= "
					  운송장번호 : $order_info->deliver_num";
}

if($order_info->account != "" && $order_info->account_name != ""){


$ordmail .= "
		<tr height=40>
		<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>입금계좌</td>
		<td style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>$order_info->account</td>
		<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>입금자명</td>
		<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->account_name</td>
		</tr>";

}

$ordmail .= "
  </table>
</td></tr>
<tr><td>

<br><br>
	<table border=0 cellpadding=5 cellspacing=0 width=100%>
	<tr><td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% class=text_style05>
				<tr><td style='padding:10px 0 5px 0' valign=bottom><img src=http://$HTTP_HOST/images/order_title02.gif></td></tr>
				<tr><td>
				<table border=0 cellpadding=0 cellspacing=0 style='width:100%;border:1px solid #e5e5e5;'>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>주문하시는 분</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->send_name</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>전화번호</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->send_tphone</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>휴대전화번호</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->send_hphone</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>이메일</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->send_email</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;'>주소</td>
				<td style='padding-left:20px;'>[$order_info->send_post] $order_info->send_address</td>
				</tr>
				</table>
				</td></tr>
				</table>
		</td>
   </tr>
   </table>
	   
</td></tr>
<tr><td>
<br><br>
   <table border=0 cellpadding=5 cellspacing=0 width=100%>
		<td valign=top>
				<table border=0 cellpadding=0 cellspacing=0 width=100% class=text_style05>
				<tr><td style='padding:10px 0 5px 0' valign=bottom><img src=http://$HTTP_HOST/images/order_title03.gif></td></tr>
				<tr><td>
				<table border=0 cellpadding=0 cellspacing=0 style='width:100%;border:1px solid #e5e5e5;'>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>받으시는 분</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->rece_name</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>전화번호</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->rece_tphone</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;'>휴대전화번호</td>
				<td style='padding-left:20px;border-bottom:1px solid #e5e5e5;'>$order_info->rece_hphone</td>
				</tr>
				<tr height=40>
				<td width=180 bgcolor=#f8f8f8 style='padding-left:20px;border-right:1px solid #e5e5e5;'>주소</td>
				<td style='padding-left:20px;'>[$order_info->rece_post] $order_info->rece_address</td>
				</tr>
				</table>
				</td></tr></table>
		</td></tr>
		</table>
			
		 </td>
	</tr>
</table>
";

?>