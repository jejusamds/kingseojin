<?php include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>
<?php


$category = $_GET['category'];
if (!isset($category) || $category == '') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$h3_title = $category == 'center' ? '메인 슬라이드 - 중앙' : '메인 슬라이드 - 하단';

$addSql = "";
if ($category == 'center') {
    $addSql = " And category = 'center' ";
} else if ($category == 'bottom') {
    $addSql = " And category = 'bottom' ";
} else {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$image_size = [
    'center' => ['1920 x 250 px', '1080 x 1068 px'],
    'bottom' => ['1920 x 647 px', '1080 x 810 px'],
];

$this_table = "df_site_sub_slide";
$big_mode = "sub_slide";

$param = "s_show=" . $s_show . "&s_opt=" . $s_opt . "&s_key=" . $s_key . "&category=" . $category;

if (isset($idx) && $idx != '') {
    $mode = 'update';
    $sql = " Select * From {$this_table} Where idx='" . $idx . "' ";
    $row = $db->row($sql);
    if (!isset($row) || !$row) {
        error("잘못된 접근입니다.", $big_mode . "_list.php?" . $param);
    }
} else {
    $mode = "insert";
}

?>
<script src="/ckeditor/ckeditor.js"></script>
<script language="JavaScript">
    <!--
    // 첨부파일 삭제
    $(document).on('click', '.btnDelFiles', function() {
        var $this = $(this);
        var pm = $this.data('pm');
        var old_idx = '<?= $idx ?>';
        var params = 'big_mode=<?= $big_mode ?>&mode=delimg&idx=' + old_idx + "&pm=" + pm;

        if (old_idx == '') return false;
        if (!confirm("썸네일 삭제는 즉시 이루어 집니다. 삭제 하시겠습니까?")) return false;
        $.ajax({
            type: 'post',
            url: 'exec.php',
            data: params,
            dataType: 'html',
            success: function(res) {
                if (res.trim() != 'Y') {
                    alert('파일 삭제에 실패했습니다.');
                    return;
                } else {
                    $this.prev('a').remove();
                    $this.remove();
                }
            },
            error: function(e) {
                alert(e.responseText);
            }
        });

    });

    // 저장
    function inputCheck(f) {
        var mode = '<?= $mode ?>';
        if (f.name.value == '') {
            alert('상품명을 입력해 주세요.');
            f.name.focus();
            return false;
        }

        if (mode == 'insert' && f.upfile.value == '') {
            alert('제품 이미지를 업로드해 주세요.');
            f.upfile.focus();
            return false;
        }
        var isImg = f.querySelector('#upfile_prev_img') != null;
        if (mode == 'update' && f.upfile.value == '' && !isImg) {
            alert('제품 이미지를 업로드해 주세요.');
            f.upfile.focus();
            return false;
        }
    }

    // 삭제
    function delData(idx) {
        if (confirm('해당 자료를 삭제하시겠습니까?')) {
            location.href = 'exec.php?big_mode=<?= $big_mode ?>&mode=delete&selidx=' + idx + '&page=<?= $page ?>&<?= $param ?>';
        }
    }
    //
    -->
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            <?=$h3_title?>
        </h3>
        <ul class="breadcrumb">
            <li>한성 모듈러</li>
            <li class="active">메인 슬라이드 등록</li>
        </ul>
    </div>

    <form name="frm" action="exec.php?<?= $param ?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)" autocomplete="off">
        <input type="hidden" name="big_mode" value="<?= $big_mode ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="category" value="<?= $category ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-television"></i>
                </div>
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="18%" />
                    <col width="33%" />
                    <col width="13%" />
                    <col width="34%" />
                    <tr>
                        <th>타이틀 - PC</th>
                        <td class="comALeft" colspan=""><input type="text" name="title" value="<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>" class="form-control" style="width:80%;" /></td>
                        <th>서브 타이틀 - PC</th>
                        <td class="comALeft" colspan=""><input type="text" name="title_sub" value="<?php echo htmlspecialchars($row['title_sub'], ENT_QUOTES); ?>" class="form-control" style="width:80%;" /></td>
                    </tr>
                    <tr>
                        <th>타이틀 - Mobile</th>
                        <td class="comALeft" colspan="" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="title_mobile" class="form-control" style="width:80%; height:70px;"><?php echo htmlspecialchars($row['title_mobile'], ENT_QUOTES); ?></textarea>
                        </td>
                        <th>서브 타이틀 - Mobile</th>
                        <td class="comALeft" colspan="" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="title_sub_mobile" class="form-control" style="width:80%; height:70px;"><?php echo htmlspecialchars($row['title_sub_mobile'], ENT_QUOTES); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>링크</th>
                        <td class="comALeft" colspan="3">
                            <input type="text" name="link" value="<?php echo htmlspecialchars($row['link'], ENT_QUOTES); ?>" class="form-control" style="width:80%;" placeholder="링크를 입력하세요">
                        </td>
                    </tr>
                    <tr>
                        <th>이미지 PC (<?=$image_size[$category][0]?>)</th>
                        <td class="comALeft" colspan="3">
                            <input name="upfile" type="file" class="form-control" style="width:60%; margin-right:15px;">
                            <?php if ($row['upfile'] != "") { ?>
                                <a href="<?= $row['upfile'] ?>" target="_blank">
                                    <img src="<?= $row['upfile'] ?>" height="32" style="background:#555555;" align="absmiddle" id="upfile_prev_img" />
                                </a>
                                <button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" data-pm="p" type="button" style="margin-left:10px;">이미지 삭제</button>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>이미지 Mobile (<?=$image_size[$category][1]?>)</th>
                        <td class="comALeft" colspan="3">
                            <input name="upfile_m" type="file" class="form-control" style="width:60%; margin-right:15px;">
                            <?php if ($row['upfile_m'] != "") { ?>
                                <a href="<?= $row['upfile_m'] ?>" target="_blank">
                                    <img src="<?= $row['upfile_m'] ?>" height="32" style="background:#555555;" align="absmiddle" id="upfile_m_prev_img" />
                                </a>
                                <button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" data-pm="m" type="button" style="margin-left:10px;">이미지 삭제</button>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>노출여부</th>
                        <td class="comALeft" colspan="3">
                            <label for="showY">
                                <input type="radio" name="showset" id="showY" value="Y" <?php if ($row['showset'] == "Y" || $mode == 'insert') echo "checked"; ?>> 노출함
                            </label>
                            <label for="showN">
                                <input type="radio" name="showset" id="showN" value="N" <?php if ($row['showset'] == "N") echo "checked"; ?>> 노출안함
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button" onClick="location.href='<?= $big_mode ?>_list.php?page=<?= $page ?>&<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">저장하기</button>
                    <?php if ($mode == "update") { ?>
                        <button class="btn btn-danger btn-sm" type="button" onClick="delData('<?= $idx ?>');">삭제</button>
                    <?php } ?>
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
    
</script>


</html>