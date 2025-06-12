<? 
include $_SERVER['DOCUMENT_ROOT']."/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT']."/inc/util_lib.inc";
include $_SERVER['DOCUMENT_ROOT']."/inc/Eadmin_check.inc";
include $_SERVER['DOCUMENT_ROOT']."/inc/site_info.inc";
include $_SERVER['DOCUMENT_ROOT']."/inc/oper_info.inc";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="http://hodo1934.com/include/favi.ico" rel="shortcut icon"/> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?=$site_info->admin_title?></title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="/Madmin/js/jquery.mCustomScrollbar.js"></script>
<script language="javascript" type="text/javascript" src="/Madmin/js/jquery.sparkline.js"></script>
<script src="/Madmin/js/jquery.nicescroll.js"></script>

<link rel="stylesheet" href="/Madmin/css/jquery.mCustomScrollbar.css" />
<link href="/Madmin/css/admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/Madmin/css/font-awesome.css">

<link rel="stylesheet" type="text/css" href="/css/colorbox.css" />
<script type="text/javascript" src="/js/jquery.colorbox.js"></script>

<script>
	$(document).on("click", "input[name=post_fg]", function(){
		if($(this).val() == "A"){
			$("#post_layer1").show();
			$("#post_layer2").hide();
		}
		else{
			$("#post_layer1").hide();
			$("#post_layer2").show();
		}
	});
	
	$(document).on("change", "select[name=sido]", function(){
		var val = $(this).val();
		if(val) {
			$("input[name=post_from_A]").val( $("option:selected", $(this)).attr("min") );
			$("input[name=post_to_A]").val( $("option:selected", $(this)).attr("max") );
			selSido( val );
		}
		else{
			$("select[name=gugun]").empty();
			$("select[name=gugun]").append("<option value=''>선택</option>");
			$("select[name=dong]").empty();
			$("select[name=dong]").append("<option value=''>선택</option>");
			$("input[name=post_from_A]").val("");
			$("input[name=post_to_A]").val("");
		}
	});
	$(document).on("change", "select[name=gugun]", function(){
		var val = $(this).val();
		if(val) {
			$("input[name=post_from_A]").val( $("option:selected", $(this)).attr("min") );
			$("input[name=post_to_A]").val( $("option:selected", $(this)).attr("max") );
			selGugun( val );
		}
		else{
			$("select[name=dong]").empty();
			$("select[name=dong]").append("<option value=''>선택</option>");
			$("input[name=post_from_A]").val( $("option:selected", "select[name=sido]").attr("min") );
			$("input[name=post_to_A]").val( $("option:selected", "select[name=sido]").attr("max") );
		}
	});
	$(document).on("change", "select[name=dong]", function(){
		var val = $(this).val();
		if(val) {
			$("input[name=post_from_A]").val( $("option:selected", $(this)).attr("min") );
			$("input[name=post_to_A]").val( $("option:selected", $(this)).attr("max") );
		}
		else{
			$("input[name=post_from_A]").val( $("option:selected", "select[name=gugun]").attr("min") );
			$("input[name=post_to_A]").val( $("option:selected", "select[name=gugun]").attr("max") );
		}
	});

	// 시도 선택
	function selSido(val) {
		var params = "mode=sido&val="+encodeURIComponent(val);
		$.ajax({
			type: "POST",
			url: "ajax.php",
			dataType: 'json', // Set to jsonp if you use a server on a different domain and change it's setting accordingly
			data: params, // Page parameter to make sure we load new data
			success: onLoadSido,
			complete: function(){
			}
		});
	}
	function onLoadSido(response){
		$("select[name=gugun]").empty();
		$("select[name=gugun]").append("<option value=''>선택</option>");
		if(response) {
			// Create HTML for the images.
			var html = '',
				data = response,
				i = 0, 
				length = data.length, 
				image;

			for (; i < length; i++) {
				image = data[i];
				$("select[name=gugun]").append("<option value='"+image.gugun+"' min='"+image.min+"' max='"+image.max+"'>"+image.gugun+"</option>");
			}
		}
	}

	// 구군 선택
	function selGugun(val) {
		var sido = $("select[name=sido] option:selected").val();
		var params = "mode=gugun&sido="+encodeURIComponent(sido)+"&val="+encodeURIComponent(val);
		$.ajax({
			type: "POST",
			url: "ajax.php",
			dataType: 'json', // Set to jsonp if you use a server on a different domain and change it's setting accordingly
			data: params, // Page parameter to make sure we load new data
			success: onLoadGugun,
			complete: function(){
			}
		});
	}
	function onLoadGugun(response){
		$("select[name=dong]").empty();
		$("select[name=dong]").append("<option value=''>선택</option>");
		if(response) {
			// Create HTML for the images.
			var html = '',
				data = response,
				i = 0, 
				length = data.length, 
				image;

			for (; i < length; i++) {
				image = data[i];
				$("select[name=dong]").append("<option value='"+image.dong+"' min='"+image.min+"' max='"+image.max+"'>"+image.dong+"</option>");
			}
		}
	}
	
	function closeLayer(){
		parent.$.colorbox.close();
	}
	
	function inputCheck(frm){
		var post_fg = $(':radio[name="post_fg"]:checked').val();
		
		if($.trim(frm.title.value) == "") { alert("특수지역명을 입력해 주세요"); frm.title.focus(); return false; }
		if(post_fg == "A"){
			if($.trim(frm.post_from_A.value) == "") { alert("우편번호 범위를 선택해 주세요"); return false; }
			if($.trim(frm.post_to_A.value) == "") { alert("우편번호 범위를 선택해 주세요"); return false; }
			if(parseInt(frm.post_from_A.value) > parseInt(frm.post_to_A.value)) { alert("시작 우편번호는 끝 우편번호보다 클 수 없습니다."); return false; }
		}
		else{
			if($.trim(frm.post_from_P.value) == "") { alert("우편번호 범위를 선택해 주세요"); return false; }
			if($.trim(frm.post_to_P.value) == "") { alert("우편번호 범위를 선택해 주세요"); return false; }
			if(parseInt(frm.post_from_P.value) > parseInt(frm.post_to_P.value)) { alert("시작 우편번호는 끝 우편번호보다 클 수 없습니다."); return false; }
		}
		if($.trim(frm.price.value) == "") { alert("추가 배송비를 입력해 주세요"); frm.price.focus(); return false; }
	}
</script>

<style>
	#header {width:100%; background-color:#6f717a; padding:5px 30px 5px 9px; color:#fff;}
	#post_layer2 {display:none;}
</style>

</head>
<body style="min-width:100%; overflow:hidden;">

<div id="header">
지역별 배송비 등록
</div>

<form method="post" name="frmDeliver" action="set_save.php" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="deliver_insert" />
<div id="pageContainer" style="margin:0; min-width:auto;">
	<div class="pageWrap" style="padding:0;">
		<div class="box comMTop20">
			<div class="panel">
				<table class="table orderInfo" cellpadding="0" cellspacing="0" style="margin:0;">
					<col width="20%"/><col width=""/>
					<tr>
						<th>특수지역명</th>
						<td class="comALeft"><input type="text" name="title" class="form-control" placeholder="도서, 산간 등" style="width:90%;"></td>
					</tr>
					<tr>
						<th rowspan="2">우편번호 범위</th>
						<td class="comALeft">
							<input type="radio" name="post_fg" value="A" checked> 지역등록 &nbsp; &nbsp; &nbsp;
							<input type="radio" name="post_fg" value="P"> 상세(우편번호) 등록
						</td>
					</tr>
					<tr>
						<td class="comALeft">
							<div id="post_layer1">
								<div style="padding-top:7px;">
									<select name="sido" class="form-control" style="width:auto;">
										<option value="">선택</option>
										<?
										$sql  = "";
										$sql .= "	Select	sido, Min(zipcode) As min_zip, Max(zipcode) As max_zip ";
										$sql .= "	From	df_site_zipcode ";
										$sql .= "	Group by	sido ";
										$sql .= "	Order by	sido Asc ";
										$result = mysql_query($sql) or error(mysql_error());
										while($row = mysql_fetch_object($result)){
										?>
										<option value="<?=$row->sido?>" min="<?=$row->min_zip?>" max="<?=$row->max_zip?>"><?=$row->sido?></option>
										<?
										}
										?>
									</select>
									<select name="gugun" class="form-control" style="width:auto;">
										<option value="">선택</option>
									</select>
									<select name="dong" class="form-control" style="width:auto;">
										<option value="">선택</option>
									</select>
								</div>
								<div style="padding-top:7px; padding-bottom:7px;">
									<input type="text" name="post_from_A" class="form-control comACenter" style="width:10%;" />부터&nbsp;&nbsp;
									<input type="text" name="post_to_A" class="form-control comACenter" style="width:10%;" />까지
								</div>
							</div>
							<div id="post_layer2">
								<input type="text" name="post_from_P" id="post_from_P" class="form-control comACenter" style="width:10%;" />
								<button class="btn btn-success btn-sm" type="button" onClick="execDaumPostcode('from');">우편번호 찾기</button>부터&nbsp;&nbsp;
								<input type="text" name="post_to_P" id="post_to_P" class="form-control comACenter" style="width:10%;" />
								<button class="btn btn-success btn-sm" type="button" onClick="execDaumPostcode('to');">우편번호 찾기</button>까지
							</div>
						</td>
					</tr>
					<tr>
						<th>추가 배송비</th>
						<td class="comALeft">
							<input type="text" name="price" class="form-control comARight" style="width:20%;" />원
							<span style="margin-left:20px; color:#ff0000;">
								<input type="checkbox" name="deliver_yn" value="N" /> 배송 불가 지역
							</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="comPTop20 comPBottom20 comACenter">
			<button class="btn btn-info btn-sm" type="submit"> 확 인 </button>
			<button class="btn btn-default btn-sm" type="button" onClick="closeLayer();"> 취 소 </button>
		</div>
	</div>
</div>
</form>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function execDaumPostcode(fg) {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var fullAddr_alt = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;
					fullAddr_alt = data.jibunAddress;
                }
				else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
					fullAddr_alt = data.roadAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('post_'+fg+'_P').value = data.zonecode; //5자리 새우편번호 사용
            }
        }).open();
    }
</script>

</body>
</html>