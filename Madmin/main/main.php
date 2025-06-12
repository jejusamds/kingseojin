<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<?
$level_info = level_info();


// 오늘의 통계현황 - 총 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member ";
$memberTotal = $db->single($sql);
// 오늘의 통계현황 - 금일 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') ";
$memberToday = $db->single($sql);
// 오늘의 통계현황 - 10일간 가입회원
$memberDay = "";
for($i=9; $i>=0; $i--){
	$selectedDate = date("Y-m-d", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = '" .$selectedDate. "' ";
	$cnt = $db->single($sql);
	if($memberDay != "") $memberDay .= ",";
	$memberDay .= $cnt;
}


// 오늘의 통계현황 - 총 주문수
$sql = " Select IFNULL(COUNT(*),0) From df_shop_order Where is_del='N' And status_payment != '' ";
$orderTotal = $db->single($sql);
// 오늘의 통계현황 - 금일 주문수
$sql = " Select IFNULL(COUNT(*),0) From df_shop_order Where is_del='N' And status_payment != '' And DATE_FORMAT(order_date,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') ";
$orderToday = $db->single($sql);
// 오늘의 통계현황 - 10일간 주문수
$orderDay = "";
for($i=9; $i>=0; $i--){
	$selectedDate = date("Y-m-d", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_shop_order Where is_del='N' And status_payment != '' And DATE_FORMAT(order_date,'%Y-%m-%d') = '" .$selectedDate. "' ";
	$cnt = $db->single($sql);
	if($orderDay != "") $orderDay .= ",";
	$orderDay .= $cnt;
}


// 오늘의 통계현황 - 총 문의수
$sql = " Select IFNULL(COUNT(*),0) From df_site_bbs Where code = 'qna' And depno = 0 ";
$qnaTotal = $db->single($sql);
// 오늘의 통계현황 - 금일 문의수
$sql = " Select IFNULL(COUNT(*),0) From df_site_bbs Where code = 'qna' And depno = 0 And DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') ";
$qnaToday = $db->single($sql);
// 오늘의 통계현황 - 10일간 문의수
$qnaDay = "";
for($i=9; $i>=0; $i--){
	$selectedDate = date("Y-m-d", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_site_bbs Where code = 'qna' And depno = 0 And DATE_FORMAT(wdate,'%Y-%m-%d') = '" .$selectedDate. "' ";
	$cnt = $db->single($sql);
	if($qnaDay != "") $qnaDay .= ",";
	$qnaDay .= $cnt;
}


// 오늘의 통계현황 - 총 샘플신청수
$sql = " Select IFNULL(COUNT(*),0) From df_site_sample ";
$sampleTotal = $db->single($sql);
// 오늘의 통계현황 - 금일 샘플신청수
$sql = " Select IFNULL(COUNT(*),0) From df_site_sample Where DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') ";
$sampleToday = $db->single($sql);
// 오늘의 통계현황 - 10일간 샘플신청수
$sampleDay = "";
for($i=9; $i>=0; $i--){
	$selectedDate = date("Y-m-d", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_site_sample Where DATE_FORMAT(wdate,'%Y-%m-%d') = '" .$selectedDate. "' ";
	$cnt = $db->single($sql);
	if($sampleDay != "") $sampleDay .= ",";
	$sampleDay .= $cnt;
}


// 오늘의 방문현황 - PC 총 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' ";
$visitPcTotal = $db->single($sql);
// 오늘의 방문현황 - PC 금일 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' And ci_yy = DATE_FORMAT(now(), '%y') And ci_mm = DATE_FORMAT(now(), '%c') And ci_dd = DATE_FORMAT(now(), '%e') ";
$visitPcToday = $db->single($sql);
// 오늘의 방문현황 - PC 10일간 방문수
$visitPcDay = "";
for($i=9; $i>=0; $i--){
	$selectedYear = date("y", strtotime("-".$i." days"));
	$selectedMonth = date("n", strtotime("-".$i." days"));
	$selectedDay = date("j", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' And ci_yy = '" .$selectedYear. "' And ci_mm = '" .$selectedMonth. "' And ci_dd = '" .$selectedDay. "' ";
	$cnt = $db->single($sql);
	if($visitPcDay != "") $visitPcDay .= ",";
	$visitPcDay .= $cnt;
}


// 오늘의 방문현황 - MOBILE 총 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' ";
$visitMobileTotal = $db->single($sql);
// 오늘의 방문현황 - MOBILE 금일 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' And ci_yy = DATE_FORMAT(now(), '%y') And ci_mm = DATE_FORMAT(now(), '%c') And ci_dd = DATE_FORMAT(now(), '%e') ";
$visitMobileToday = $db->single($sql);
// 오늘의 방문현황 - MOBILE 10일간 방문수
$visitMobileDay = "";
for($i=9; $i>=0; $i--){
	$selectedYear = date("y", strtotime("-".$i." days"));
	$selectedMonth = date("n", strtotime("-".$i." days"));
	$selectedDay = date("j", strtotime("-".$i." days"));
	$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' And ci_yy = '" .$selectedYear. "' And ci_mm = '" .$selectedMonth. "' And ci_dd = '" .$selectedDay. "' ";
	$cnt = $db->single($sql);
	if($visitMobileDay != "") $visitMobileDay .= ",";
	$visitMobileDay .= $cnt;
}


// 오늘의 매출현황 - 금일 매출금액
$sql  = " Select"; 
$sql .= " ((Select Ifnull(Sum(prd_price),0)  " ;
$sql .= " From df_shop_order  ";
$sql .= " Where is_del='N' And status_payment In ('DC','DI','OY') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') )) as cnt ";
$pay_Today = $db->single($sql);


// 어제의 매출현황 - 금일 매출금액 
$pivotDate = date("Y-m-d",strtotime("-1 day")); 
$sql  = "   Select"; 
$sql .= "   ((Select Ifnull(Sum(prd_price),0)  ";
$sql .= "   From df_shop_order "; 
$sql .= "   Where is_del='N' And status_payment In ('2') ";
$sql .= "   And DATE_FORMAT(order_date,'%Y-%m-%d') = '$pivotDate' )) as cnt ";
$pay_Yester = $db->single($sql);


// 주간 매출현황 - 주간 매출금액
$offset 		= date("N") - 1;
$pivotDate 		= date("Y-m-d",strtotime("-".$offset." day")); 

$offset 		= 7 - date("N") ;
$pivotDate2 	= date("Y-m-d",strtotime("+".$offset." day")); 

$sql  = " Select"; 
$sql .= " ((Select Ifnull(Sum(prd_price),0)    " ;
$sql .= " From df_shop_order ";
$sql .= " Where is_del='N' And status_payment In ('2') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') >= DATE_FORMAT('$pivotDate','%Y-%m-%d') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') <= DATE_FORMAT('$pivotDate2','%Y-%m-%d') )) as cnt ";
$pay_Week = $db->single($sql);


// 지난 주간 매출현황 - 주간 매출금액
$offset 		= date("N") + 6 ;
$pivotDate 		= date("Y-m-d",strtotime("-".$offset." day")); 

$offset 		= date("N") ;
$pivotDate2 	= date("Y-m-d",strtotime("-".$offset." day")); 

$sql  = " Select"; 
$sql .= " ((Select Ifnull(Sum(prd_price),0) ";
$sql .= " From df_shop_order ";
$sql .= " Where is_del='N' And status_payment In ('2') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') >= DATE_FORMAT('$pivotDate','%Y-%m-%d') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') <= DATE_FORMAT('$pivotDate2','%Y-%m-%d'))) as cnt ";
$pay_preWeek = $db->single($sql);


// 월별 매출현황 - 월별 매출금액
$sql  = " Select"; 
$sql .= " ((Select Ifnull(Sum(prd_price),0) " ;
$sql .= " From df_shop_order ";
$sql .= " Where is_del='N' And status_payment In ('2') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m') = DATE_FORMAT(now(),'%Y-%m')) )as cnt";
$pay_Month = $db->single($sql);


// 저번달 매출현황 - 저번달 매출금액
$pivotDate = date("Y-m",strtotime(date("Y-m-01")."-1 day")); 

$sql  = " Select"; 
$sql .= " ((Select Ifnull(Sum(prd_price),0) " ;
$sql .= " From df_shop_order ";
$sql .= " Where is_del='N' And status_payment In ('2') ";
$sql .= " And DATE_FORMAT(order_date,'%Y-%m') = '".$pivotDate."' )) as cnt  ";
$pay_preMonth = $db->single($sql);
?>
			<script type="text/javascript">
				$(function() {
					var memberValues = [<?=$memberDay?>];
					$('#ChartMember').sparkline(memberValues, {
							type: 'bar',
							barWidth: 5,
							barSpacing: 3,
							barColor: '#65cea7',
							width: '70px',
							height: '40px'
						}
					);

					var orderValues = [<?=$orderDay?>];
					$('#ChartOrder').sparkline(orderValues, {
							type: 'bar',
							barWidth: 5,
							barSpacing: 3,
							barColor: '#fc8675',
							width: '70px',
							height: '40px'
						}
					);

					var qnaValues = [<?=$qnaDay?>];
					$('#ChartQNA').sparkline(qnaValues, {
							type: 'bar',
							barWidth: 5,
							barSpacing: 3,
							barColor: '#5ab5de',
							width: '70px',
							height: '40px'
						}
					);
					
					var sampleValues = [<?=$sampleDay?>];
					$('#ChartSAMPLE').sparkline(sampleValues, {
							type: 'bar',
							barWidth: 5,
							barSpacing: 3,
							barColor: '#f26e81',
							width: '70px',
							height: '40px'
						}
					);
					
					var visitPcValues = [<?=$visitPcDay?>];
					$('#ChartVisitPc').sparkline(visitPcValues, {
							type: 'line',
							width: '70px',
							height: '30px',
							lineColor: '#55accc',
							fillColor: '#edf7f9'
						}
					);

					var visitMobileValues = [<?=$visitMobileDay?>];
					$('#ChartVisitMobile').sparkline(visitMobileValues, {
							type: 'line',
							width: '70px',
							height: '30px',
							lineColor: '#55accc',
							fillColor: '#edf7f9'
						}
					);
					
					var visitTotalValues = [<?=$visitPcToday?>,<?=$visitMobileToday?>];
					$('#ChartVisitTotal').sparkline(visitTotalValues, {
							type: 'pie',
							width: '70px',
							height: '70px',
							sliceColors: ['#5ab5de', '#fc8675'],
							tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} {{value}} ({{percent.1}}%)',
							tooltipValueLookups: {
								names: {
									0: 'PC',
									1: 'MOBILE'
								}
							}
						}
					);
				});

				// 회원별 적립금내역
				function reserveList(id,name){
					var url = "/Madmin/member/member_reserve.php?id=" + id + "&name=" + name;
					window.open(url,"reserveList","height=800, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
				}
			</script>

			<div class="pageWrap">
				<div class="box comFLeft comMRight15" style="width:430px; height:145px;">
					<div class="panel">
						<div class="title">
							<div class="comFLeft">
								<i class="fa fa-bar-chart"></i>
								<span>오늘의 통계현황</span>
							</div>
							<div class="comFRight">
								<?=date("Y.m.d");?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="charts comMTop15">
							<div class="chart comMRight30">
								<div id="ChartMember"></div>
								<div style="margin-top:5px;">
									<span style="float:left; font-size:12px; color:#989898;">회원</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($memberToday)?></span>
								</div>
								<div class="clear"></div>
								<div>
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($memberTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>

							<div class="chart comMRight30">
								<div id="ChartOrder"></div>
								<div style="margin-top:5px;">
									<span style="float:left; font-size:12px; color:#989898;">주문</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($orderToday)?></span>
								</div>
								<div class="clear"></div>
								<div>
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($orderTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>

							<div class="chart  comMRight30">
								<div id="ChartQNA"></div>
								<div style="margin-top:5px;">
									<span style="float:left; font-size:12px; color:#989898;">문의</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($qnaToday)?></span>
								</div>
								<div class="clear"></div>
								<div>
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($qnaTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>
							
							<div class="chart">
								<div id="ChartSAMPLE"></div>
								<div style="margin-top:5px;">
									<span style="float:left; font-size:12px; color:#989898;">샘플</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($sampleToday)?></span>
								</div>
								<div class="clear"></div>
								<div>
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($sampleTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="box comFLeft comMRight15" style="width:315px; height:145px;">
					<div class="panel">
						<div class="title">
							<div class="comFLeft">
								<i class="fa fa-area-chart"></i>
								<span>오늘의 방문현황</span>
							</div>
							<div class="comFRight">
								<?=date("Y.m.d");?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="charts2 comMTop10">
							<div class="chart comMRight30">
								<div>
									<span style="font-size:12px; color:#989898;">PC</span><br/>
									<span style="font-size:16px; font-weight:bold;"><?=number_format($visitPcToday)?></span>
								</div>
								<div id="ChartVisitPc"></div>
								<div style="text-align:center;">
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($visitPcTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>
							<div class="chart comMRight30">
								<div>
									<span style="font-size:12px; color:#989898;">MOBILE</span><br/>
									<span style="font-size:16px; font-weight:bold;"><?=number_format($visitMobileToday)?></span>
								</div>
								<div id="ChartVisitMobile"></div>
								<div style="text-align:center;">
									<span style="float:left; font-size:12px; color:#989898;">총</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($visitMobileTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>
							<div class="chart">
								<div id="ChartVisitTotal"></div>
								<div style="text-align:center; margin-top:3px;">
									<span style="float:left; font-size:12px; color:#989898;">누적</span>
									<span style="float:right; font-size:12px; font-weight:bold;"><?=number_format($visitPcTotal+$visitMobileTotal)?></span>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="box comFLeft comMRight15" style="width:400px; height:145px;">
					<div class="panel">
						<div class="title">
							<div class="comFLeft">
								<i class="fa fa-line-chart"></i>
								<span>오늘의 매출 (입금완료)</span>
							</div>
							<div class="comFRight">
								<?=date("Y.m.d");?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="charts3 comMTop10">
							<div class="chart comMRight30">
								<div style="margin-bottom:10px;">
									<span style="font-size:12px; color:#989898;">오늘</span><br/>
									<span style="font-size:14px; font-weight:bold;">
									<?
										if ( $pay_Today > $pay_Yester ){
											echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Today < $pay_Yester ){
											echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Today = $pay_Yester ){
											echo "<strong>-</strong>";
										}
									?>
									</span>
									<span style="font-size:14px; font-weight:bold;"><?=number_format($pay_Today)?></span>
								</div>
								<div>
									<span style="font-size:12px; color:#989898;">어제</span><br/>
									<span style="font-size:12px; font-weight:bold; color:#616161;"><?=number_format($pay_Yester)?></span>
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="chart comMRight30">
								<div style="margin-bottom:10px;">
									<span style="font-size:12px; color:#989898;">이번주</span><br/>
									<span style="font-size:14px; font-weight:bold;">
									<?
										if ( $pay_Week > $pay_preWeek ){
											echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Week < $pay_preWeek ){
											echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Week = $pay_preWeek ){
											echo "<strong>-</strong>";
										}
									?>
									</span>
									<span style="font-size:14px; font-weight:bold;"><?=number_format($pay_Week)?></span>
								</div>
								<div>
									<span style="font-size:12px; color:#989898;">저번주</span><br/>
									<span style="font-size:12px; font-weight:bold; color:#616161;"><?=number_format($pay_preWeek)?></span>
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="chart">
								
								<div style="margin-bottom:10px;">
									<span style="font-size:12px; color:#989898;">이번달</span><br/>
									<span style="font-size:14px; font-weight:bold;">
									<?
										if ( $pay_Month > $pay_preMonth ){
											echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Month < $pay_preMonth ){
											echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
										}else if( $pay_Month = $pay_preMonth ){
											echo "<strong>-</strong>";
										}
									?>
									</span>
									<span style="font-size:14px; font-weight:bold;letter-spacing:-0.8px;"><?=number_format($pay_Month)?></span>
								</div>
								<div>
									<span style="font-size:12px; color:#989898;">저번달</span><br/>
									<span style="font-size:12px; font-weight:bold; color:#616161;"><?=number_format($pay_preMonth)?></span>
								</div>
								
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="clear"></div>
				<div class="box comMTop20" style="width:1180px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>최근 주문현황</span>
						</div>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width="80"/><col width="80"/><col width="150"/><col width=""/><col width="100"/><col width="80"/><col width="100"/><col width="80"/><col width="80"/>
							<thead>
								<tr>
									<td>주문일</td>
									<td>결제일</td>
									<td>주문번호</td>
									<td>주문상품</td>
									<td>주문자</td>
									<td>결제방법</td>
									<td>결제금액</td>
									<td>처리상태</td>
									<td>배송상태</td>
								</tr>
							</thead>
							<tbody>
<?
$sql  = "";
$sql .= "	Select	o.*, ";
$sql .= "			(Select CONCAT(prdname,'|',CAST(amount As CHAR)) From df_shop_order_sub Where orderid=o.orderid Order by idx Asc Limit 1) as order_goods, ";
$sql .= "			(Select COUNT(idx) From df_shop_order_sub Where orderid=o.orderid) as order_cnt ";
$sql .= "	From	df_shop_order o ";
$sql .= "	Where	o.is_del = 'N' ";
$sql .= "	And		IFNULL(o.status_payment,'') != '' ";
$sql .= "	Order by	o.order_date Desc ";
$sql .= "	Limit	5 ";
$row = $db->query($sql);

for($i=0; $i<count($row); $i++){
	if($row[$i]['status_payment'] == "1")
		$status_payment_color = "#ff0000";
	else if($row[$i]['status_payment'] == "2")
		$status_payment_color = "#000000";
	else if($row[$i]['status_payment'] == "3")
		$status_payment_color = "#0000ff";
	else if($row[$i]['status_payment'] == "4")
		$status_payment_color = "#66cccc";
	
	if($row[$i]['status_deliver'] == "1")
		$status_deliver_color = "#66cccc";
	else if($row[$i]['status_deliver'] == "2")
		$status_deliver_color = "#000000";
	else if($row[$i]['status_deliver'] == "3")
		$status_deliver_color = "#ff0000";
?>
								<tr>
									<td><?=date("m.d H:i", strtotime($row[$i]['order_date']))?></td>
									<td><?=$row[$i]['pay_date'] ? date("m.d H:i", strtotime($row[$i]['pay_date'])) : "-";?></td>
									<td>
										<a href="/Madmin/order/order_info.php?orderid=<?=$row[$i]['orderid']?>&page=<?=$page?>&<?=$param?>">
											<?=$row[$i]['orderid']?>
										</a>
									</td>
									<td>
										<a href="/Madmin/order/order_info.php?orderid=<?=$row[$i]['orderid']?>&page=<?=$page?>&<?=$param?>">
											<?
											$arrItem = explode("|", $row[$i]['order_goods']);
											echo $arrItem[0] ." (". $arrItem[1] ."개)";
											if($row[$i]['order_cnt'] > 1)
											echo " 외 " .($row[$i]['order_cnt']-1). "건";
											?>
										</a>
									</td>
									<td>
										<? if($row[$i]['send_id']){ ?>
										<a href="/Madmin/member/member_info.php?id=<?=$row[$i]['send_id']?>">
											<?=$row[$i]['send_name']?><br/>[<?=$row[$i]['send_id']?>]
										</a>
										<? }else{ ?>
										<?=$row[$i]['send_name']?><br/>[비회원]
										<? } ?>
									</td>
									<td><?=$gPayMethod[$row[$i]['pay_method']]?></td>
									<td><?=number_format($row[$i]['total_price'])?> 원</td>
									<td style="color:<?=$status_payment_color?>;"><?=$gStatusPayment[$row[$i]['status_payment']]?></td>
									<td style="color:<?=$status_deliver_color?>;"><?=$gStatusDeliver[$row[$i]['status_deliver']]?></td>
								</tr>
<?
}
?>
							</tdoby>
						</table>
					</div>
				</div>

				<div class="box comMTop20" style="width:1180px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-shopping-cart"></i>
							<span>최근 회원가입</span>
						</div>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width=""/><col width=""/><col width="100"/><col width="80"/><col width="120"/><col width="80"/><col width="120"/><col width="60"/><col width="120"/><col width="120"/>
							<thead>
								<tr>
									<td>회원등급</td>
									<td>아이디</td>
									<td>이름</td>
									<td>나이</td>
									<td>포인트</td>
									<td>결제횟수</td>
									<td>총구매금액</td>
									<td>접속수</td>
									<td>최근접속일</td>
									<td>회원가입일</td>
								</tr>
							</thead>
							<tbody>
<?
$sql  = "";
$sql .= "	Select	m.*, ";
$sql .= "			(Select IFNULL(SUM(reserve),0) From df_shop_reserve Where memid=m.id) As point, ";
$sql .= "			(Select COUNT(*) From df_shop_order Where send_id=m.id And IFNULL(status_payment,'')='2') As order_cnt, ";
$sql .= "			(Select IFNULL(SUM(total_price),0) From df_shop_order Where send_id=m.id And IFNULL(status_payment,'')='2') As order_amt ";
$sql .= "	From	df_site_member m ";
$sql .= "	Order by	m.wdate Desc ";
$sql .= "	Limit	5 ";
$row = $db->query($sql);

for($i=0; $i<count($row); $i++){
?>
								<tr>
									<td><?=$level_info[$row[$i]['level']]['name']?></td>
									<td>
										<a href="/Madmin/member/member_info.php?id=<?=$row[$i]['id']?>&page=<?=$page?>&<?=$param?>">
											<?=$row[$i]['id']?>
									</td>
									<td><?=$row[$i]['name']?></td>
									<td><?=getAge($row[$i]['birth'])?></td>
									<td>
										<a href="javascript:reserveList('<?=$row[$i]['id']?>','<?=$row[$i]['name']?>');">
											<?=number_format($row[$i]['point'])?> P
										</a>
									</td>
									<td><?=number_format($row[$i]['order_cnt'])?> 건</td>
									<td><?=number_format($row[$i]['order_amt'])?> 원</td>
									<td><?=number_format($row[$i]['visit'])?></td>
									<td><?=date("Y.m.d H:i", strtotime($row[$i]['visit_time']))?></td>
									<td><?=date("Y.m.d H:i", strtotime($row[$i]['wdate']))?></td>
								</tr>
<?
}
?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="box comFLeft comMTop20 comMRight15" style="width:582px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-file-text"></i>
							<span>최근 1:1문의</span>
						</div>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width=""/><col width="80"/><col width="60"/><col width="60"/>
							<thead>
								<tr>
									<td>제목</td>
									<td>작성자</td>
									<td>작성일</td>
									<td>답변</td>
								</tr>
							</thead>
							<tbody>
<?
$sql  = "";
$sql .= "	Select	b.*, ";
$sql .= "			(Select IFNULL(COUNT(*),0) From df_site_bbs Where code=b.code And depno>0 And parno=b.idx) As re_cnt ";
$sql .= "	From	df_site_bbs b ";
$sql .= "	Where	b.code	= 'qna' ";
$sql .= "	And		b.depno	= 0 ";
$sql .= "	Order by	b.prino Desc ";
$sql .= "	Limit	5 ";
$row = $db->query($sql);

for($i=0; $i<count($row); $i++){
	$row[$i]['subject'] = mb_strimwidth($row[$i]['subject'],0,60,"..","UTF-8");
?>
								<tr>
									<td class="comALeft">
										<a href="/Madmin/bbs/bbs_view.php?code=qna&idx=<?=$row[$i]['idx']?>">
											<?=$row[$i]['subject']?>
										</a>
									</td>
									<td><?=$row[$i]['name']?></td>
									<td><?=date("m.d",strtotime($row[$i]['wdate']))?></td>
									<td>
										<? if($row[$i]['re_cnt'] > 0){ ?>
										<span style="color:#0000ff;">완료</span>
										<? }else{ ?>
										<span style="color:#ff0000;">대기</span>
										<? } ?>
									</td>
								</tr>
<?
}
?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="box comFLeft comMTop20" style="width:582px;">
					<div class="panel">
						<div class="title">
							<div class="comFLeft">
								<i class="fa fa-comments-o"></i>
								<span>최근 샘플신청</span>
							</div>
							<div class="clear"></div>
						</div>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width="100"/><col width="100"/><col width=""/><col width="80"/><col width=""/>
							<thead>
								<tr>
									<td>아이디</td>
									<td>이름</td>
									<td>휴대폰</td>
									<td>발송여부</td>
									<td>신청일</td>
								</tr>
							</thead>
							<tbody>
<?
$sql  = "";
$sql .= "	Select	* ";
$sql .= "	From	df_site_sample ";
$sql .= "	Where	1 = 1 ";
$sql .= "	Order by	idx Desc ";
$sql .= "	Limit	5 ";
$row = $db->query($sql);

for($i=0; $i<count($row); $i++){
?>
								<tr>
									<td>
										<a href="/Madmin/bbs/sample_view.php?idx=<?=$row[$i]['idx']?>">
											<?=$row[$i]['user_id']?>
										</a>
									</td>
									<td><?=$row[$i]['name']?></td>
									<td><?=$row[$i]['hphone']?></td>
									<td><?=$row[$i]['send_yn']?></td>
									<td><?=$row[$i]['wdate']?></td>
								</tr>
<?
}
?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="clear comMBottom50"></div>

			</div>
		</div>

	</div>
</div>

</body>
</html>
