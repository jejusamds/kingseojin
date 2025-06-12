<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = 'df_site_product';

$sel1 = $_GET['category_1'] ?? '';
$sel2 = $_GET['category_2'] ?? '';
$sel3 = $_GET['category_3'] ?? '';

$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;
$param .= "&category_1=" . $sel1 . "&category_2=" . $sel2 . "&category_3=" . $sel3;


?>
<link rel="stylesheet" href="/js/jstree/dist/themes/default/style.min.css" />
<script language="JavaScript" type="text/javascript">

    $(function () {

        $('#select_tmp').on('change', function () {
            var checked = $(this).is(':checked');
            // select_checkbox[] 모든 항목에 체크/해제
            $("input[name='select_checkbox[]']").prop('checked', checked);
        });

        window.bbsDelete = function () {
            // 선택된 값들을 '|' 로 이어 붙입니다.
            var sel = $("input[name='select_checkbox[]']:checked")
                .map(function () { return this.value; })
                .get()
                .join('|');
            if (!sel) {
                alert("삭제할 게시물을 선택하세요.");
                return;
            }
            if (confirm("선택한 게시물을 정말 삭제하시겠습니까?")) {
                location.href = "contents_save.php"
                    + "?mode=delbbs"
                    + "&seluser=" + encodeURIComponent(sel + "|")
                    + "&page=<?= $page ?>&<?= $param ?>";
            }
        };
    });

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
            $cat2.html('<option value="">선택</option>');
            $cat3.html('<option value="">선택</option>');
            if (v) loadCategories(2, v, $cat2);
        });
        $cat2.on('change', function () {
            var v = $(this).val();
            $cat3.html('<option value="">선택</option>');
            if (v) loadCategories(3, v, $cat3);
        });
    });

</script>

<style>
    .pagination {
        margin: 0 auto;
    }
</style>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            제품 관리
        </h3>
        <ul class="breadcrumb">
            <li>제품 관리</li>
            <li class="active">제품 목록</li>
        </ul>
    </div>

    <form action="contents_list.php" method="get">
        <input type="hidden" name="code" value="<?= $code ?>" />
        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <tbody>
                        <tr>
                            <td>대분류</td>
                            <td colspan="1" class="comALeft">
                                <select name="category_1" id="category_1" class="form-control">
                                    <option value="">선택</option>
                                </select>
                            </td>
                            <td>중분류</td>
                            <td colspan="1" class="comALeft">
                                <select name="category_2" id="category_2" class="form-control">
                                    <option value="">선택</option>
                                </select>
                            </td>
                            <td>소분류</td>
                            <td colspan="1" class="comALeft">
                                <select name="category_3" id="category_3" class="form-control">
                                    <option value="">선택</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>조건검색</td>
                            <td class="comALeft" style="padding-left:5px" colspan="5">
                                <select name="search_option" class="form-control" style="width:auto;">
                                    <option value="f_name" <?php if ($search_option == "f_name") { ?>selected<? } ?>>제품명
                                    </option>
                                    <!-- <option value="content" <?php if ($search_option == "content") { ?>selected<? } ?>>내용</option>
                                    <option value="name" <?php if ($search_option == "name") { ?>selected<? } ?>>작성자</option> -->
                                </select>
                                <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control"
                                    style="width:auto;" />
                                <button class="btn btn-info btn-sm" type="submit">검색</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="box comMTop20" style="width:978px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="15" />
                <col width="15" />
                <col width="50" />
                <col width="50" />
                <col width="70" />
                <col width="100" />
                <col width="30" />
                <col width="60" />
                <thead>
                    <form>
                        <tr>
                            <td><input type="checkbox" name="select_tmp" id="select_tmp" onClick=""></td>
                            <td>번호</td>
                            <td>대분류</td>
                            <td>중분류</td>
                            <td>소분류</td>
                            <td>제품명</td>
                            <td>순서</td>
                            <td>작성일</td>
                        </tr>
                    </form>
                </thead>
                <tbody>
                    <?php
                    // ──────────────────────────────────────────────
                    // 1. 페이징·검색용 변수 설정
                    // ──────────────────────────────────────────────
                    $page_set = 20;
                    $block_set = 10;
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
                    $search_option = isset($_GET['search_option']) ? $_GET['search_option'] : '';
                    $cat1 = isset($_GET['category_1']) ? (int) $_GET['category_1'] : 0;
                    $cat2 = isset($_GET['category_2']) ? (int) $_GET['category_2'] : 0;
                    $cat3 = isset($_GET['category_3']) ? (int) $_GET['category_3'] : 0;

                    // 검색어가 있으면 WHERE 절에 추가
                    $where = "WHERE 1=1 ";
                    if ($keyword) {
                        $col = $search_option;
                        $kw = $keyword;
                        $where .= " AND b.{$col} LIKE '%{$kw}%' ";
                    }
                    if ($cat1)
                        $where .= " AND c1.f_idx = {$cat1} ";
                    if ($cat2)
                        $where .= " AND c2.f_idx = {$cat2} ";
                    if ($cat3)
                        $where .= " AND b.f_cat_idx = {$cat3} ";

                    // ──────────────────────────────────────────────
                    // 2. 전체 건수 구하기
                    // ──────────────────────────────────────────────
                    $countSql = "SELECT COUNT(*) 
                                    FROM {$this_table} b
                                    LEFT JOIN df_site_category AS c3 ON b.f_cat_idx     = c3.f_idx
                                    LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx = c2.f_idx
                                    LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx = c1.f_idx
                                    {$where}
                                ";
                    $total = $db->single($countSql);
                    $pageCnt = ceil($total / $page_set);
                    if ($page > $pageCnt)
                        $page = 1;

                    // 데이터가 하나라도 있으면
                    if ($total > 0) {
                        $offset = ($page - 1) * $page_set;

                        // ──────────────────────────────────────────────
                        // 3. 제품 + 카테고리(1~3차) 한 번에 조인
                        // ──────────────────────────────────────────────
                        $offset = ($page - 1) * $page_set;

                        $dataSql = "SELECT b.*,
                                        c1.f_name AS cat1_name,
                                        c2.f_name AS cat2_name,
                                        c3.f_name AS cat3_name
                                    FROM {$this_table} b
                                    LEFT JOIN df_site_category AS c3 ON b.f_cat_idx     = c3.f_idx
                                    LEFT JOIN df_site_category AS c2 ON c3.f_parent_idx = c2.f_idx
                                    LEFT JOIN df_site_category AS c1 ON c2.f_parent_idx = c1.f_idx
                                    {$where}
                                    ORDER BY b.prior DESC
                                    LIMIT {$offset}, {$page_set}
                                ";
                        $rows = $db->query($dataSql);
                        // ──────────────────────────────────────────────
                        // 4. 테이블 ROW 출력
                        // ──────────────────────────────────────────────
                        foreach ($rows as $i => $r) {
                            // 순번 계산
                            $no = $total - $offset - $i;
                            // 수정 링크
                            $link = "contents_input.php?mode=update&idx={$r['f_idx']}&page={$page}&{$param}";
                            ?>
                            <tr>
                                <!-- 1) 체크박스 (value에 idx 담기) -->
                                <td>
                                    <input type="checkbox" name="select_checkbox[]" value="<?= $r['f_idx'] ?>">
                                </td>
                                <!-- 2) 순번 -->
                                <td><?= $no ?></td>
                                <!-- 3) 1차 카테고리 -->
                                <td class="">
                                    <?= htmlspecialchars($r['cat1_name'], ENT_QUOTES) ?>
                                </td>
                                <!-- 4) 2차 카테고리 -->
                                <td class="">
                                    <?= htmlspecialchars($r['cat2_name'], ENT_QUOTES) ?>
                                </td>
                                <!-- 5) 3차 카테고리 -->
                                <td class="">
                                    <?= htmlspecialchars($r['cat3_name'], ENT_QUOTES) ?>
                                </td>
                                <!-- 6) 제품명 (수정 링크 걸기) -->
                                <td class="">
                                    <a href="<?= $link ?>">
                                        <?= htmlspecialchars($r['f_name'], ENT_QUOTES) ?>
                                    </a>
                                </td>
                                <td style="padding:0;">
                                    <ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="contents_save.php?mode=prior&posi=upup&idx=<?= $r['f_idx'] ?>&prior=<?= $r['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/upup_icon.gif" border="0" alt="10단계 위로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="contents_save.php?mode=prior&posi=up&idx=<?= $r['f_idx'] ?>&prior=<?= $r['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/up_icon.gif" border="0" alt="1단계 위로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="contents_save.php?mode=prior&posi=down&idx=<?= $r['f_idx'] ?>&prior=<?= $r['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/down_icon.gif" border="0" alt="1단계 아래로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="contents_save.php?mode=prior&posi=downdown&idx=<?= $r['f_idx'] ?>&prior=<?= $r['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/downdown_icon.gif" border="0" alt="10단계 아래로"></a></li>
                                    </ul>
                                    <div class="clear"></div>
                                </td>
                                <!-- 7) 등록일 -->
                                <td>
                                    <?= htmlspecialchars($r['f_regdate'], ENT_QUOTES) ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        // 데이터 없을 때
                        ?>
                        <tr>
                            <td colspan="8" align="center">등록된 제품이 없습니다.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>

    <div class="box comMTop20 comMBottom20" style="width:978px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onClick="bbsDelete();">삭제</button>
            </div>
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, "&" . $param); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onClick="location.href='contents_input.php?page=<?= $page ?>&<?= $param ?>';">제품등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</div>
</div>
</div>

</body>

</html>