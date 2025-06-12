<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>

<?
if ($_SESSION['admin_part'] != "0") {
	error("접근 권한이 없습니다.");
}

if (!$date_from) $date_from = date("Y-m-d");
if (!$date_to) $date_to = date("Y-m-d");
?>

<script>
	jQuery(function($) {
		$.datepicker.regional['ko'] = {
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: '오늘',
			monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			dayNames: ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
			weekHeader: 'Wk',
			dateFormat: 'yy-mm-dd', // [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
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
			onSelect: function(selectedDate) {
				var option = this.id == "date_from" ? "minDate" : "maxDate",
					instance = $(this).data("datepicker"),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings);

				dates.not(this).datepicker("option", option, date);
			}
		});
	});

	function setPeriod(date_from, date_to) {
		$("#date_from").val(date_from);
		$("#date_to").val(date_to);
	}

	$(document).on("mouseenter", ".statTable tbody tr", function() {
		$(this).css("background-color", "#f0f0f0");
	});
	$(document).on("mouseleave", ".statTable tbody tr", function() {
		$(this).css("background-color", "#ffffff");
	});

	$(document).on("click", ".statTable tbody tr", function() {
		var domain = $(this).attr("domain");
		var pm = $(this).attr("pm");

		location.href = 'stat_url_view.php?s_code=<?= $s_code ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>&domain=' + domain + '&pm=' + pm;
	});
</script>

<style>
	.statTable tbody tr {
		cursor: pointer;
	}
</style>

<div class="pageWrap">
	<div class="page-heading">
		<h3>
			접속경로통계
		</h3>
		<ul class="breadcrumb">
			<li>통계 현황</li>
			<li class="active">접속경로통계</li>
		</ul>
	</div>

	<div class="box" style="width:978px;">
		<div class="panel">
			<form name="frm" action="<?= $PHP_SELF ?>" method="get">
				<table class="table noMargin" cellpadding="0" cellspacing="0">
					<col width="80" />
					<col width="" />
					<tbody>
						<tr>
							<td height="26" align="right" style="padding-left:5px">검색조건</td>
							<td class="comALeft" style="padding-left:5px">
								<input type="text" name="date_from" id="date_from" value="<?= $date_from ?>" class="form-control comACenter" style="width:70px;">
								~
								<input type="text" name="date_to" id="date_to" value="<?= $date_to ?>" class="form-control comACenter" style="width:70px;">
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
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $to_day ?>', '<?= $to_day ?>')">오늘</button>
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $yes_day ?>', '<?= $to_day ?>')">어제</button>
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $week_day ?>', '<?= $to_day ?>')">일주일</button>
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $month_day ?>', '<?= $to_day ?>')">1개월</button>
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $month3_day ?>', '<?= $to_day ?>')">3개월</button>
								<button class="btn btn-primary btn-xs" type="button" onclick="setPeriod('<?= $month6_day ?>', '<?= $to_day ?>')">6개월</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>

	<div class="box comMTop10 comMBottom20 comFLeft" style="width:480px;">
		<div class="panel">
			<div class="title">
				<i class="fa fa-file-text"></i>
				<span>PC (<?= date("Y.m.d", strtotime($date_from)) ?> ~ <?= date("Y.m.d", strtotime($date_to)) ?>)</span>
			</div>

			<table class="table statTable" cellpadding="0" cellspacing="0">
				<col width="" />
				<col width="100" />
				<col width="80" />
				<thead>
					<tr>
						<td>접속경로</td>
						<td>유입코드</td>
						<td>접속수</td>
					</tr>
				</thead>
				<tbody>
					<?
					$sql  = "";
					$sql .= "	Select	SUM(cu_hit) As count, ";
					$sql .= "			SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(LOWER(cu_url),'https://',''),'http://',''),'/',1),'?',1) As domain, cu_code ";
					$sql .= "	From	df_counter_url ";
					$sql .= "	Where	cu_pm = 'P' ";
					// $sql .= "	And		cu_url != '' ";
					$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') >= '" . $date_from . "' ";
					$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') <= '" . $date_to . "' ";
					if ($s_code != "")
						$sql .= " And cu_code = '" . $s_code . "' ";
					$sql .= "	Group by	domain, cu_code ";
					$sql .= "	Order by	count Desc ";
					$sql .= "	Limit	30 ";
					$row = $db->query($sql);
					for ($i = 0; $i < count($row); $i++) {
					?>
						<tr domain="<?= $row[$i]['domain'] ?>" pm="P">
							<td><?= $row[$i]['domain'] ?></td>
							<td><?= $row[$i]['cu_code'] ?></td>
							<td><?= number_format($row[$i]['count']) ?></td>
						</tr>
					<?
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="box comMTop10 comMBottom20 comFLeft" style="width:480px; margin-left:18px;">
		<div class="panel">
			<div class="title">
				<i class="fa fa-file-text"></i>
				<span>MOBILE (<?= date("Y.m.d", strtotime($date_from)) ?> ~ <?= date("Y.m.d", strtotime($date_to)) ?>)</span>
			</div>

			<table class="table statTable" cellpadding="0" cellspacing="0">
				<col width="" />
				<col width="100" />
				<col width="80" />
				<thead>
					<tr>
						<td>접속경로</td>
						<td>유입코드</td>
						<td>접속수</td>
					</tr>
				</thead>
				<tbody>
					<?
					$sql  = "";
					$sql .= "	Select	SUM(cu_hit) As count, ";
					$sql .= "			SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(LOWER(cu_url),'https://',''),'http://',''),'/',1),'?',1) As domain, cu_code ";
					$sql .= "	From	df_counter_url ";
					$sql .= "	Where	cu_pm = 'm' ";
					// $sql .= "	And		cu_url != '' ";
					$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') >= '" . $date_from . "' ";
					$sql .= "	And		DATE_FORMAT(FROM_UNIXTIME(cu_uptime),'%Y-%m-%d') <= '" . $date_to . "' ";
					if ($s_code != "")
						$sql .= " And cu_code = '" . $s_code . "' ";
					$sql .= "	Group by	domain, cu_code ";
					$sql .= "	Order by	count Desc ";
					$sql .= "	Limit	30 ";
					$row = $db->query($sql);
					for ($i = 0; $i < count($row); $i++) {
					?>
						<tr domain="<?= $row[$i]['domain'] ?>" pm="M">
							<td><?= $row[$i]['domain'] ?></td>
							<td><?= $row[$i]['cu_code'] ?></td>
							<td><?= number_format($row[$i]['count']) ?></td>
						</tr>
					<?
					}
					?>
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