<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$param = "searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;
$reply_mode = false;

$this_table = 'df_site_product';

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

    $sql = " SELECT p.*,
            c1.f_idx   AS cat1_idx,
            c1.f_name  AS cat1_name,
            c2.f_idx   AS cat2_idx,
            c2.f_name  AS cat2_name,
            c3.f_idx   AS cat3_idx,
            c3.f_name  AS cat3_name
        FROM df_site_product AS p
            LEFT JOIN df_site_category AS c3 ON p.f_cat_idx   = c3.f_idx
            LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx = c2.f_idx
            LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx = c1.f_idx
        WHERE p.f_idx = :idx
        ";
    $params = ['idx' => $idx];
    $bbs_row = $db->row($sql, $params);
}

$sel1 = $bbs_row['cat1_idx'] ?? '';
$sel2 = $bbs_row['cat2_idx'] ?? '';
$sel3 = $bbs_row['cat3_idx'] ?? '';
?>
<script src="/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css" />
<!-- Spectrum JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>
<script language="JavaScript">

    // 첨부파일 추가
    $(document).on('click', '.btnAddFiles', function () {
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
    $(document).on('click', '.btnDelFiles', function () {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx[]"]').val();
        if (old_idx != '') {
            if (!confirm('파일삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg&idx=' + old_idx + "&code=<?= $code ?>";
            $.ajax({
                type: 'post',
                url: 'contents_save.php',
                data: params,
                dataType: 'html',
                success: function (res) {
                    console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('tr').remove();
                    }
                },
                error: function (e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        } else {
            $this.closest('tr').remove();
        }
    });

    $(document).on('click', '.btnDelFiles_thumb', function () {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx_thumb"]').val();
        if (old_idx != '') {
            if (!confirm('썸네일 삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg_thumb&idx=' + old_idx;
            $.ajax({
                type: 'post',
                url: 'contents_save.php',
                data: params,
                dataType: 'html',
                success: function (res) {
                    console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('.thumb_del_wrap').remove();
                    }
                },
                error: function (e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        }
    });

    function inputCheck(f) {
        // 1) 분류
        if (!f.category_1.value) { alert('대분류를 선택해주세요.'); f.category_1.focus(); return false; }
        if (!f.category_2.value) { alert('중분류를 선택해주세요.'); f.category_2.focus(); return false; }
        if (!f.category_3.value) { alert('소분류를 선택해주세요.'); f.category_3.focus(); return false; }

        // 2) 썸네일 (insert 모드일 때만 필수로 할 경우, mode 값을 확인해서 분기)
        <?php if ($mode === 'insert'): ?>
            if (!f.f_thumbnail.value) { alert('썸네일 파일을 업로드해주세요.'); f.f_thumbnail.focus(); return false; }
        <?php endif; ?>

        // 3) 텍스트 필드
        if (!f.f_name.value.trim()) { alert('상품명을 입력해주세요.'); f.f_name.focus(); return false; }
        if (!f.f_origin.value.trim()) { alert('원산지를 입력해주세요.'); f.f_origin.focus(); return false; }
        if (!f.f_size.value.trim()) { alert('사이즈를 입력해주세요.'); f.f_size.focus(); return false; }
        if (!f.f_material.value.trim()) { alert('재질을 입력해주세요.'); f.f_material.focus(); return false; }
        if (!f.f_price.value.trim()) { alert('판매가를 입력해주세요.'); f.f_price.focus(); return false; }

        // 4) 상세 (CKEditor)
        var details = CKEDITOR.instances.f_details.getData().trim();
        if (!details) { alert('상세정보를 입력해주세요.'); CKEDITOR.instances.f_details.focus(); return false; }
        // 에디터 값도 폼에 반영
        f.f_details.value = details;

        return true;
    }
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            제품 관리
        </h3>
        <ul class="breadcrumb">
            <li>제품 관리</li>
            <li class="active">제품 등록</li>
        </ul>
    </div>

    <form name="frm" action="contents_save.php?<?= $param ?>" method="post" enctype="multipart/form-data"
        onSubmit="return inputCheck(this)">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
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
                    <tr>
                        <th>대분류</th>
                        <td colspan="1" class="comALeft">
                            <select name="category_1" id="category_1" class="form-control" style="width:88%;">
                                <option value="">선택</option>
                            </select>
                        </td>
                        <th>중분류</th>
                        <td colspan="1" class="comALeft">
                            <select name="category_2" id="category_2" class="form-control" style="width:88%;">
                                <option value="">선택</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>소분류</th>
                        <td colspan="1" class="comALeft">
                            <select name="category_3" id="category_3" class="form-control" style="width:88%;">
                                <option value="">선택</option>
                            </select>
                        </td>
                        <th>썸네일 <br />(960 * 960px)</th>
                        <td class="comALeft" colspan="1">
                            <input type="file" name="f_thumbnail" class="form-control" style="width:30%;" />
                            <?php if ($bbs_row['f_thumbnail'] != "") { ?>
                                <div class="thumb_del_wrap" style="display: inline-block; margin-left:10px;">
                                    <a href="/userfiles/contents/product/<?= $bbs_row['f_thumbnail'] ?>" target="_blank">
                                        <img src="/userfiles/contents/product/<?= $bbs_row['f_thumbnail'] ?>" height="32"
                                            align="absmiddle" id="upfile_prev_img" />
                                    </a>
                                    <button class="btn btn-warning btn-xs comMLeft15 btnDelFiles_thumb" type="button"
                                        style="margin-left:10px;">썸네일 삭제</button>
                                    <input type="hidden" name="old_idx_thumb" value="<?= $bbs_row['f_idx'] ?>" />
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>상품명</th>
                        <td class="comALeft" colspan="3">
                            <input type="text" name="f_name" value="<?= $bbs_row['f_name'] ?>" class="form-control"
                                style="width:88%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>원산지</th>
                        <td class="comALeft" colspan="1">
                            <input type="text" name="f_origin" value="<?= $bbs_row['f_origin'] ?>" class="form-control"
                                style="width:88%;" />
                        </td>
                        <th>사이즈</th>
                        <td class="comALeft" colspan="1">
                            <input type="text" name="f_size" value="<?= $bbs_row['f_size'] ?>" class="form-control"
                                style="width:88%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>재질</th>
                        <td class="comALeft" colspan="1">
                            <input type="text" name="f_material" value="<?= $bbs_row['f_material'] ?>"
                                class="form-control" style="width:88%;" />
                        </td>
                        <th>판매가</th>
                        <td class="comALeft" colspan="1">
                            <input type="text" name="f_price" value="<?= $bbs_row['f_price'] ?>" class="form-control"
                                style="width:88%;" />
                        </td>
                    </tr>
                    <tr>
                        <th>상세 (문구)</th>
                        <td class="comALeft" colspan="3" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="f_details" id="f_details"
                                class="textarea"><?= $bbs_row['f_details'] ?></textarea>
                            <script type="text/javascript">
                                //<![CDATA[
                                CKEDITOR.replace('f_details', {
                                    enterMode: '2',
                                    shiftEnterMode: '3',
                                    height: 250,
                                    filebrowserImageUploadUrl: "/ckeditor/upload.php?type=Images"
                                });
                                //]]
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <th>상세 (이미지)</th>
                        <td class="comALeft" colspan="3" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="f_details_2" id="f_details_2"
                                class="textarea"><?= $bbs_row['f_details_2'] ?></textarea>
                            <script type="text/javascript">
                                //<![CDATA[
                                CKEDITOR.replace('f_details_2', {
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

        <div class="box comMTop20 comMBottom20" style="width:978px;">
            <div class="comPTop20 comPBottom20">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onClick="location.href='contents_list.php';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">확인</button>
                    <button class="btn btn-danger btn-sm" type="button"
                        onClick="location.href='contents_list.php';">취소</button>
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
    $(function () {
        var $cat1 = $('#category_1'),
            $cat2 = $('#category_2'),
            $cat3 = $('#category_3');

        // 콜백을 받을 수 있게 수정
        function loadCategories(depth, parentId, $target, callback) {
            $target.prop('disabled', true)
                .html('<option>로딩 중…</option>');
            $.ajax({
                url: 'contents_save.php',
                data: { mode: 'get_category', depth: depth, parent_idx: parentId },
                dataType: 'json'
            })
                .done(function (list) {
                    var opts = '<option value="">선택</option>';
                    if (Array.isArray(list)) {
                        list.forEach(function (item) {
                            opts += '<option value="' + item.idx + '">' + item.name + '</option>';
                        });
                    }
                    $target.html(opts);
                    if (typeof callback === 'function') callback();
                })
                .fail(function () {
                    $target.html('<option>로드 오류</option>');
                })
                .always(function () {
                    $target.prop('disabled', false);
                });
        }

        // 1차 → 2차 → 3차 순서로 로드하면서 selected 세팅
        loadCategories(1, '', $cat1, function () {
            $cat1.val('<?= $sel1 ?>');
            loadCategories(2, '<?= $sel1 ?>', $cat2, function () {
                $cat2.val('<?= $sel2 ?>');
                loadCategories(3, '<?= $sel2 ?>', $cat3, function () {
                    $cat3.val('<?= $sel3 ?>');
                });
            });
        });

        // 이후 사용자 변경 이벤트 핸들러는 그대로 유지
        $cat1.on('change', function () {
            var v = $(this).val();
            $cat2.html('<option>선택</option>');
            $cat3.html('<option>선택</option>');
            if (v) loadCategories(2, v, $cat2);
        });
        $cat2.on('change', function () {
            var v = $(this).val();
            $cat3.html('<option>선택</option>');
            if (v) loadCategories(3, v, $cat3);
        });
    });
</script>

</html>