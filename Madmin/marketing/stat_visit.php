<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<?
if($_SESSION['admin_part'] != "0"){
	error("접근 권한이 없습니다.");
}

if(!$date_from) $date_from = date("Y-m-01");
if(!$date_to) $date_to = date("Y-m-d");

$diff = strtotime($date_to) - strtotime($date_from);
if($diff <= 86400) $gtype = "time";
else $gtype = "day";


// 어제, 오늘 검색일 경우 24간의 현황을 보여줌
if($gtype == "time"){
	$from = date("y-m-d", strtotime($date_from));
	$to = date("y-m-d", strtotime($date_to));

	$visitCountP = "";
	$visitCountM = "";
	$datas = array();

	for($i=0; $i<24; $i++){
		$sql  = "";
		$sql .= "	Select	IFNULL(SUM(CASE ci_pm WHEN 'P' THEN 1 ELSE 0 END),0) As p_cnt, IFNULL(SUM(CASE ci_pm WHEN 'M' THEN 1 ELSE 0 END),0) As m_cnt ";
		$sql .= "	From	df_counter_ip ";
		$sql .= "	Where	CONCAT(ci_yy, '-', ci_mm, '-', ci_dd) >= '" .$from. "' ";
		$sql .= "	And		CONCAT(ci_yy, '-', ci_mm, '-', ci_dd) <= '" .$to. "' ";
		$sql .= "	And		ci_hh = '" .$i. "' ";
		if($s_code != "")
			$sql .= " And ci_code = '".$s_code."' ";
		$row = $db->row($sql);
		
		if($visitCountP != "") $visitCountP .= ",";
		$visitCountP .= $row['p_cnt'];
		
		if($visitCountM != "") $visitCountM .= ",";
		$visitCountM .= $row['m_cnt'];

		$datas[$i] = array(
			"p_cnt" => $row['p_cnt'],
			"m_cnt" => $row['m_cnt']
		);
	}
	
	$labels = "'0시','1시','2시','3시','4시','5시','6시','7시','8시','9시','10시','11시','12시','13시','14시','15시','16시','17시','18시','19시','20시','21시','22시','23시'";
}

// 그외 일자별 현황을 보여줌
else{
	$days = $diff / 86400;
	$visitCountP = "";
	$visitCountM = "";
	$datas = array();
	$labels = "";

	for($i=0; $i<=$days; $i++){
		$dates = date("y-m-d", strtotime($date_from . " +".$i." days"));
		
		$sql  = "";
		$sql .= "	Select	IFNULL(SUM(CASE ci_pm WHEN 'P' THEN 1 ELSE 0 END),0) As p_cnt, IFNULL(SUM(CASE ci_pm WHEN 'M' THEN 1 ELSE 0 END),0) As m_cnt ";
		$sql .= "	From	df_counter_ip ";
		$sql .= "	Where	CONCAT(ci_yy, '-', ci_mm, '-', ci_dd) = '" .$dates. "' ";
		if($s_code != "")
			$sql .= " And ci_code = '".$s_code."' ";
		$row = $db->row($sql);
		
		if($visitCountP != "") $visitCountP .= ",";
		$visitCountP .= $row['p_cnt'];

		if($visitCountM != "") $visitCountM .= ",";
		$visitCountM .= $row['m_cnt'];
		
		$datas[] = array(
			"date" => $dates,
			"p_cnt" => $row['p_cnt'],
			"m_cnt" => $row['m_cnt']
		);
		
		if($labels) $labels .= ",'" .date("m.d", strtotime($dates)). "'";
		else $labels = "'" .date("m.d", strtotime($dates)). "'";
	}
}
?>

<script>
	jQuery(function($){
		$.datepicker.regional['ko'] = {
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: '오늘',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			weekHeader: 'Wk',
			dateFormat: 'yy-mm-dd',					// [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: ''
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);
		var dates = $("#date_from, #date_to").datepicker({
			'beforeShow': function(input, datepicker) {
				setTimeout(function() {
					$(datepicker.dpDiv).css('zIndex', 100);
				}, 500);
			},
			onSelect: function( selectedDate ) {
				var option = this.id == "date_from" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
				instance.settings.dateFormat ||
				$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );

				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});
	
	function setPeriod(date_from, date_to){
		$("#date_from").val( date_from );
		$("#date_to").val( date_to );
	}
</script>

<script src="/Madmin/js/Chart.js"></script>
<script>
	$(document).ready(function(){
		var ctx = document.getElementById("myChart");
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: [<?=$labels?>],
				datasets: [
					{
						label: 'MOBILE',
						data: [<?=$visitCountM?>],
						backgroundColor: 'rgba(54, 162, 235, 0.2)',
						borderColor: 'rgba(54, 162, 235, 1)',
						borderWidth: 1
					},
					{
						label: 'PC',
						data: [<?=$visitCountP?>],
						backgroundColor: 'rgba(255, 99, 132, 0.2)',
						borderColor: 'rgba(255,99,132,1)',
						borderWidth: 1
					}
				]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						},
						stacked: true
					}],
					xAxes: [{
						stacked: true,
					}]
				}
			}
		});
	});
</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						접속 통계 (방문자)
					</h3>
					<ul class="breadcrumb">
						<li>통계 현황</li>
						<li class="active">접속 통계 (방문자)</li>
					</ul>
				</div>

				<div class="box" style="width:978px;">
					<div class="panel">
						<form name="frm" action="<?=$PHP_SELF?>" method="get">
						<table class="table noMargin" cellpadding="0" cellspacing="0">
							<col width="80" /><col width="" />
							<tbody>
								<tr>
									<td height="26" align="right" style="padding-left:5px">검색조건</td>
									<td class="comALeft" style="padding-left:5px">
										<input type="text" name="date_from" id="date_from" value="<?=$date_from?>" class="form-control comACenter" style="width:70px;">
										~
										<input type="text" name="date_to" id="date_to" value="<?=$date_to?>" class="form-control comACenter" style="width:70px;">
										<button class="btn btn-info btn-sm" type="submit">검색</button>
										<?
										$to_day = date('Y-m-d');
										$yes_day = date('Y-m-d', strtotime("-1 days"));
										$week_day = date('Y-m-d', strtotime("-6 days"));
										$month_day = date('Y-m-d', strtotime("-1 months"));
										$month3_day = date('Y-m-d', strtotime("-3 months"));
										$month6_day = date('Y-m-d', strtotime("-6 months"));
										?>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$to_day?>', '<?=$to_day?>')">오늘</button>
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$yes_day?>', '<?=$to_day?>')">어제</button>
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$week_day?>', '<?=$to_day?>')">일주일</button>
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$month_day?>', '<?=$to_day?>')">1개월</button>
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$month3_day?>', '<?=$to_day?>')">3개월</button>
										<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?=$month6_day?>', '<?=$to_day?>')">6개월</button>
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>

				<div class="box comMTop10" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-area-chart"></i>
							<span>방문자수 (<?if($gtype == "time"){?>시간별<?}else{?>일자별<?}?>)</span>
						</div>
						<div class="comMTop10">
							<canvas id="myChart" width="400" height="200"></canvas>
						</div>
					</div>
				</div>
				<div class="clear"></div>

				<div class="box comMTop10 comMBottom20" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-file-text"></i>
							<span>방문자수 (<?=date("Y.m.d", strtotime($date_from))?> ~ <?=date("Y.m.d", strtotime($date_to))?>)</span>
						</div>

<?if($gtype == "time"){?>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width=""/><col width="250"/><col width="250"/><col width="250"/>
							<thead>
								<tr>
									<td>시간</td>
									<td>PC 방문자수</td>
									<td>MOBILE 방문자수</td>
									<td>전체 방문자수</td>
								</tr>
							</thead>
							<tbody>
<?
	$total_pc = 0;
	$total_mobile = 0;
	for($i=0; $i<Count($datas); $i++){
		$total_pc += $datas[$i]['p_cnt'];
		$total_mobile += $datas[$i]['m_cnt'];
?>
								<tr>
									<td><?=$i?> 시</td>
									<td><?=number_format($datas[$i]['p_cnt'])?></td>
									<td><?=number_format($datas[$i]['m_cnt'])?></td>
									<td><?=number_format($datas[$i]['p_cnt']+$datas[$i]['m_cnt'])?></td>
								</tr>
<?
	}
}
else{
?>
						<table class="table" cellpadding="0" cellspacing="0">
							<col width=""/><col width="250"/><col width="250"/><col width="250"/>
							<thead>
								<tr>
									<td>일자</td>
									<td>PC 방문자수</td>
									<td>MOBILE 방문자수</td>
									<td>전체 방문자수</td>
								</tr>
							</thead>
							<tbody>
<?
	$total_pc = 0;
	$total_mobile = 0;
	for($i=0; $i<Count($datas); $i++){
		$total_pc += $datas[$i]['p_cnt'];
		$total_mobile += $datas[$i]['m_cnt'];
?>
								<tr>
									<td><?=$datas[$i]['date']?></td>
									<td><?=number_format($datas[$i]['p_cnt'])?></td>
									<td><?=number_format($datas[$i]['m_cnt'])?></td>
									<td><?=number_format($datas[$i]['p_cnt']+$datas[$i]['m_cnt'])?></td>
								</tr>
<?
	}
}
?>
								<tr>
									<td style="color:#ff0000;">합 계</td>
									<td style="color:#ff0000;"><?=number_format($total_pc)?></td>
									<td style="color:#ff0000;"><?=number_format($total_mobile)?></td>
									<td style="color:#ff0000;"><?=number_format($total_pc+$total_mobile)?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</div>

</body>
</html>
