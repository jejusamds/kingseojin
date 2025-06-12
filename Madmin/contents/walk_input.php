<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/bbs_info.inc";

$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;
$reply_mode = false;

$this_table = $code == 'enjoy' ? 'df_site_board_enjoy' : 'df_site_board_walk';

if ($mode == "insert" || $mode == "") {
    $mode = "insert";
    $bbs_row['memid'] = $_SESSION['admin_id'];
    $bbs_row['name'] = $_SESSION['admin_name'];
    $bbs_row['email'] = $_SESSION['email'];
    $bbs_row['wdate'] = date('Y-m-d H:i:s');
    $bbs_row['passwd'] = date('is');
    $bbs_row['count'] = 0;
    $bbs_row['ctype'] = "H";
} else if ($mode == "update") {
    $sql = "select * from {$this_table} where idx='$idx'";
    $bbs_row = $db->row($sql);

    // ② 개별 일정 조회
    $sqlOcc = " SELECT 
                DATE_FORMAT(occur_date, '%Y-%m-%d') AS occur_date,
                TIME_FORMAT(start_time, '%H:%i')       AS start_time,
                TIME_FORMAT(end_time,   '%H:%i')       AS end_time
                FROM df_site_board_walk_schedule
                WHERE walk_id = '{$idx}'
                ORDER BY occur_date, start_time";
    $occurs = $db->query($sqlOcc);
}
?>
<script src="/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css" />
<!-- Spectrum JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>
<script language="JavaScript">
    // 이벤트 기간
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
        var dates = $("#event_sdate, #event_edate").datepicker({
            'beforeShow': function(input, datepicker) {
                setTimeout(function() {
                    $(datepicker.dpDiv).css('zIndex', 100);
                }, 500);
            },
            onSelect: function(selectedDate) {
                var option = this.id == "event_sdate" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings);

                dates.not(this).datepicker("option", option, date);
            }
        });
    });
    $(document).ready(function() { // 게시글 보기 등급 전체 선택
        $("input[type=checkbox][name=allchk]").click(function() {
            $("input[type=checkbox][class=chk]").prop("checked", $(this).prop("checked"));
        });
    });


    // 첨부파일 추가
    $(document).on('click', '.btnAddFiles', function() {
        var html = '';
        html += '<tr>\n';
        html += '<th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" type="button">파일삭제</button></th>\n';
        html += '<td class="comALeft" colspan="3">\n';
        html += '<input type="hidden" name="old_idx[]" value="" />\n';
        html += '<input name="upfile[]" type="file" class="form-control" style="width:50%; margin-right:15px;">\n';
        html += '</td>\n';
        html += '</tr>\n';

        $('#tableFiles').append(html);
    });


    // 첨부파일 삭제
    $(document).on('click', '.btnDelFiles', function() {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx[]"]').val();
        if (old_idx != '') {
            if (!confirm('파일삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg&idx=' + old_idx + "&code=<?= $code ?>";
            $.ajax({
                type: 'post',
                url: 'walk_save.php',
                data: params,
                dataType: 'html',
                success: function(res) {
                    //console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('tr').remove();
                    }
                },
                error: function(e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        } else {
            $this.closest('tr').remove();
        }
    });

    $(document).on('click', '.btnDelFiles_thumb', function() {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx_thumb"]').val();
        if (old_idx != '') {
            if (!confirm('썸네일 삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg_thumb&idx=' + old_idx;
            $.ajax({
                type: 'post',
                url: 'walk_save.php',
                data: params,
                dataType: 'html',
                success: function(res) {
                    console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('tr').remove();
                    }
                },
                error: function(e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        } else {
            $this.closest('tr').remove();
        }
    });

    function inputCheck(frm) {
        if (frm.name.value == "") {
            alert("이름을 입력하세요.");
            frm.name.focus();
            return false;
        }
        if (frm.subject.value == "") {
            alert("제목을 입력하세요.");
            frm.subject.focus();
            return false;
        }
        /*
        if(frm.passwd.value == ""){
        	alert("비밀번호를 입력하세요.");
        	frm.passwd.focus();
        	return false;
        }
        */
        <? if ($code == "news") { ?>
            if (frm.event_sdate.value == "") {
                alert("이벤트 기간을 선택하세요.");
                frm.event_sdate.focus();
                return false;
            }
            if (frm.event_edate.value == "") {
                alert("이벤트 기간을 선택하세요.");
                frm.event_edate.focus();
                return false;
            }
        <? } ?>
        <? if ($code == "review") { ?>
            if (frm.prdcode.value == "") {
                alert("상품을 선택하세요.");
                frm.prdcode.focus();
                return false;
            }
        <? } ?>
    }

    function period() {
        var arrayClass = eval("document.frm.grp");

        var selidx = arrayClass.selectedIndex;
        var selvalue = arrayClass.options[selidx].value;
        console.log(selvalue);
        if (selvalue == "예약상품") {
            $("#period").html("게시글기간");
            $(".rpermi_tr").show();
        } else {
            $("#period").html("이벤트기간");
            $(".rpermi_tr").hide();
        }

    }
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            <?= $bbs_info['title'] ?>
        </h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active"><?= $bbs_info['title'] ?></li>
        </ul>
    </div>

    <form name="frm" action="walk_save.php?<?= $param ?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="ctype" value="<?= $bbs_row['ctype'] ?>">
        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-shopping-cart"></i>
                    <span>게시물 작성</span>
                </div>
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="15%" />
                    <col width="35%" />
                    <col width="15%" />
                    <col width="35%" />
                    <!--
                    <tr>
                        <th>구분</th> 
                        <td colspan="3" class="comALeft">
                            <select name="gubun" id="gubun" class="form-control" style="width:50%;">
                                <option value="전시" <?= $bbs_row['gubun'] == "전시" ? "selected" : "" ?>>전시</option>
                                <option value="교육" <?= $bbs_row['gubun'] == "교육" ? "selected" : "" ?>>교육</option>
                            </select>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <th>작성일</th>
                        <td class="comALeft" colspan="">
                            <input type="text" name="wdate" value="<?= $bbs_row['wdate'] ?>" class="form-control" style="width:88%;" />
                        </td>
                        <th>썸네일 <br />(1080 x 1620 px)</th>
                        <td class="comALeft" colspan="1">
                            <input type="file" name="image" class="form-control" style="width:50%;" />
                            <?php if ($bbs_row['image'] != "") { ?>
                                <div class="thumb_del_wrap" style="display: inline-block; margin-left:10px;">
                                    <a href="/userfiles/contents/<?= $code ?>/<?= $bbs_row['image'] ?>" target="_blank">
                                        <img src="/userfiles/contents/<?= $code ?>/<?= $bbs_row['image'] ?>" height="32" align="absmiddle" id="upfile_prev_img" />
                                    </a>
                                    <button class="btn btn-warning btn-xs comMLeft15 btnDelFiles_thumb" type="button" style="margin-left:10px;">썸네일 삭제</button>
                                    <input type="hidden" name="old_idx_thumb" value="<?= $bbs_row['idx'] ?>" />
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>광역</th>
                        <td class="comALeft" colspan="1">
                            <?php
                            $areas = [
                                '서울',
                                '경기',
                                '인천',
                                '강원',
                                '충북',
                                '충남',
                                '세종',
                                '대전',
                                '경북',
                                '대구',
                                '전북',
                                '광주',
                                '전남',
                                '경남',
                                '울산',
                                '부산',
                                '제주'
                            ];
                            ?>
                            <!-- <input type="text" name="area_1" value="<?= $bbs_row['area_1'] ?>" class="form-control" style="width:60%;" /> -->
                            <select name="area_1" class="form-control" style="width:60%;">
                                <?php foreach ($areas as $area): ?>
                                    <option value="<?php echo htmlspecialchars($area); ?>"
                                        <?php echo ($area === $bbs_row['area_1']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($area); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>기초</th>
                        <td class="comALeft" colspan="1">
                            <input type="text" name="area_2" value="<?= $bbs_row['area_2'] ?>" class="form-control" style="width:60%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>코스명</th>
                        <td class="comALeft" colspan="3">
                            <input type="text" name="course_name" value="<?= $bbs_row['course_name'] ?>" class="form-control" style="width:60%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>설명</th>
                        <td class="comALeft" colspan="1" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="explain" id="explain" class="form-control" style="width: 60%;" rows="3"><?= $bbs_row['explain'] ?></textarea>
                        </td>
                        <th>상세설명</th>
                        <td class="comALeft" colspan="1" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="explain_detail" id="explain_detail" class="form-control" style="width: 60%;" rows="3"><?= $bbs_row['explain_detail'] ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <th>아이콘 색상 설정</th>
                        <td
                            colspan="3"
                            class="comALeft"
                            id="bg_color_td"
                            style="">
                            <input
                                type="text"
                                id="bg_color_picker"
                                name="background_color"
                                value="<?= htmlspecialchars($bbs_row['background_color'] ?? 'rgb(255,255,255)') ?>"
                                class="form-control"
                                style="width:120px; cursor:pointer;"
                                readonly />
                        </td>
                    </tr>

                    <tr>
                        <th>스케줄</th>
                        <td class="comALeft" colspan="3">
                            <input type="text" name="schedule" value="<?= $bbs_row['schedule'] ?>" class="form-control" style="width:60%;" />
                        </td>
                    </tr>

                    <tr>
                        <th>스케줄 상세</th>
                        <td colspan="3">
                            <table id="schedule-detail-table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="">날짜</th>
                                        <th style="">시작 시간</th>
                                        <th style="">종료 시간</th>
                                        <th style="">액션</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($occurs as $occ): ?>
                                        <tr>
                                            <td>
                                                <input type="date"
                                                    name="occur_date[]"
                                                    value="<?= $occ['occur_date'] ?>"
                                                    class="form-control"
                                                    style="width: 100px;" />
                                            </td>
                                            <td>
                                                <input type="time"
                                                    name="start_time[]"
                                                    value="<?= $occ['start_time'] ?>"
                                                    class="form-control"
                                                    style="width: 100px;" />
                                            </td>
                                            <td>
                                                <input type="time"
                                                    name="end_time[]"
                                                    value="<?= $occ['end_time'] ?>"
                                                    class="form-control"
                                                    style="width: 100px;" />
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger remove-row">
                                                    삭제
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="button"
                                id="add-row"
                                class="btn btn-sm btn-primary">
                                일정 추가
                            </button>
                        </td>
                    </tr>


                    <tr>
                        <th>방문코스</th>
                        <td class="comALeft" colspan="3">
                            <input type="text" name="tour_course" value="<?= $bbs_row['tour_course'] ?>" class="form-control" style="width:60%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>해설</th>
                        <td class="comALeft" colspan="3" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="commentary" id="commentary" class="form-control" style="width: 60%;" rows="3"><?= $bbs_row['commentary'] ?></textarea>
                            <script type="text/javascript">
                                //<![CDATA[
                                CKEDITOR.replace('commentary', {
                                    enterMode: '2',
                                    shiftEnterMode: '3',
                                    height: 250,
                                    filebrowserImageUploadUrl: "/ckeditor/upload.php?type=Images"
                                });
                                //]]
                            </script>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-shopping-cart"></i>
                    <span>첨부 파일 (1080 x 1620 px) </span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddFiles" type="button">파일추가</button>
                </div>
                <table id="tableFiles" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="15%" />
                    <col width="35%" />
                    <col width="15%" />
                    <col width="35%" />
                    <tbody>
                        <?
                        $sqlF = " Select * From {$this_table}_files Where bbsidx='" . $idx . "' Order by idx Asc ";
                        $rsF = $db->query($sqlF);
                        for ($ii = 0; $ii < count($rsF); $ii++) {
                        ?>
                            <tr>
                                <th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" type="button">파일삭제</button></th>
                                <td class="comALeft" colspan="3">
                                    <input type="hidden" name="old_idx[]" value="<?= $rsF[$ii]['idx'] ?>" />
                                    <input name="upfile[]" type="file" class="form-control" style="width:50%; margin-right:15px;">
                                    <? if ($rsF[$ii]['upfile'] != "") { ?>
                                        <a href="/userfiles/contents/<?= $code ?>/<?= $rsF[$ii]['upfile'] ?>" target="_blank"><?= $rsF[$ii]['upfile_name'] ?></a>
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

        <div class="box comMTop20 comMBottom20" style="width:978px;">
            <div class="comPTop20 comPBottom20">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button" onClick="location.href='walk_list.php?code=<?= $code ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">확인</button>
                    <button class="btn btn-danger btn-sm" type="button" onClick="location.href='walk_list.php?code=<?= $code ?>';">취소</button>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </form>

</div>
</div>
</div>
</div>

</body>

<script>
    // ① 새 행 추가
    document.getElementById('add-row').addEventListener('click', function() {
        var tbody = document.querySelector('#schedule-detail-table tbody');
        var tr = document.createElement('tr');
        console.log(tr);
        tr.innerHTML = '' +
            '<td><input type="date" name="occur_date[]" class="form-control" style="width: 100px;"/></td>' +
            '<td><input type="time" name="start_time[]" class="form-control" style="width: 100px;"/></td>' +
            '<td><input type="time" name="end_time[]" class="form-control" style="width: 100px;"/></td>' +
            '<td><button type="button" class="btn btn-sm btn-danger remove-row">삭제</button></td>';
        tbody.appendChild(tr);
    });

    // ② 삭제 버튼 클릭 시 해당 행 제거
    document.querySelector('#schedule-detail-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    $(function() {
        $("#bg_color_picker").spectrum({
            showInput: true, // 입력창 보이기
            preferredFormat: "hex6", // "#rrggbb" 형식
            showAlpha: false, // 불투명도 패널 숨기기
            clickoutFiresChange: true, // 창 외부 클릭 시 change 이벤트 발생
            showPalette: true, // 팔레트 표시
            palette: [ // 자주 쓰는 색상 미리 지정 (선택사항)
                ["#ffffff", "#000000", "#ff0000"],
                ["#00ff00", "#0000ff", "#209343"]
            ]
        });

        // 색상 변경 시 배경 업데이트
        $("#bg_color_picker").on("move.spectrum change.spectrum", function(e, color) {
            var hexStr = color.toHexString(); // "#rrggbb" 형태
            //$("#bg_color_td").css("background-color", hexStr);
        });
    });
</script>


</html>