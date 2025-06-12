<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>

<?
if($_SESSION['admin_part'] != "0"){
	error("접근 권한이 없습니다.");
}

$from = date("y-m-d", strtotime($date_from));
$to = date("y-m-d", strtotime($date_to));

$visitCountP = "";
$datas = array();

for($i=0; $i<24; $i++){
	$sql  = "";
	$sql .= "	Select	IFNULL(SUM(cu_hit),0) As count ";
	$sql .= "	From	df_counter_url ";
	$sql .= "	Where	cu_pm = '".$pm."' ";
	// $sql .= "	And		cu_url != '' ";
	$sql .= "	And		SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(LOWER(cu_url),'https://',''),'http://',''),'/',1),'?',1) = '".$domain."' ";
	$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%y-%m-%d') >= '".$from."' ";
	$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%y-%m-%d') <= '".$to."' ";
	$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%k') = '".$i."' ";
	if($s_code != "")
		$sql .= " And cu_code = '".$s_code."' ";
	$count = $db->single($sql);
	
	if($visitCountP != "") $visitCountP .= ",";
	$visitCountP .= $count;
	
	$datas[$i] = array(
		"p_cnt" => $count
	);
}

$labels = "'0시','1시','2시','3시','4시','5시','6시','7시','8시','9시','10시','11시','12시','13시','14시','15시','16시','17시','18시','19시','20시','21시','22시','23시'";
?>

			<script src="/Madmin/js/Chart.js"></script>
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
				
				$(document).ready(function(){
					var ctx = document.getElementById("myChart");
					var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: [<?=$labels?>],
							datasets: [
								{
									label: '<?=$domain?>',
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
						접속경로통계
					</h3>
					<ul class="breadcrumb">
						<li><?=$domain?></li>
						<li class="active"><?=$date_from?> ~ <?=$date_to?></li>
					</ul>
				</div>

				<div class="box" style="width:978px;">
					<div class="panel">
						<form name="frm" action="<?=$PHP_SELF?>" method="get">
						<input type="hidden" name="domain" value="<?=$domain?>" />
						<input type="hidden" name="pm" value="<?=$pm?>" />
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

				<div class="box comMTop10 comMBottom10" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-area-chart"></i>
							<span>시간대별 접속현황</span>
						</div>
						<div class="comMTop10">
							<canvas id="myChart" width="400" height="200"></canvas>
						</div>
					</div>
				</div>

				<div class="box comMTop10 comMBottom10" style="width:978px;">
					<div class="panel">
						<div class="title">
							<i class="fa fa-file-text"></i>
							<span>접속경로 목록</span>
						</div>

						<table class="table statTable" cellpadding="0" cellspacing="0">
							<col width="150"/><col width=""/><col width="150"/>
							<thead>
								<tr>
									<td>접속시간</td>
									<td>접속경로</td>
									<td>유입코드</td>
								</tr>
							</thead>
							<tbody>
<?
$sql  = "";
$sql .= "	Select	DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d %H:%i') As cu_uptime, cu_url, cu_hit, cu_code ";
$sql .= "	From	df_counter_url ";
$sql .= "	Where	cu_pm = '".$pm."' ";
// $sql .= "	And		cu_url != '' ";
$sql .= "	And		SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(LOWER(cu_url),'https://',''),'http://',''),'/',1),'?',1) = '".$domain."' ";
$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') >= '".$date_from."' ";
$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') <= '".$date_to."' ";
$sql .= "	Order by	cu_uptime Asc ";
$row = $db->query($sql);
for($i=0; $i<count($row); $i++){
?>
								<tr>
									<td><?=$row[$i]['cu_uptime']?></td>
									<td class="comALeft"><a href="<?=$row[$i]['cu_url']?>" target="_blank"><?=mb_strimwidth($row[$i]['cu_url'],0,100,"..","UTF-8")?></a></td>
									<td><?=$row[$i]['cu_code']?></td>
								</tr>
<?
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

</body>
</html>
