<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/bbs_info.inc";

$this_table = $code == 'enjoy' ? 'df_site_board_enjoy' : 'df_site_board_walk';

$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;
?>
<script language="JavaScript" type="text/javascript">
    //체크박스선택 반전
    function onSelect(form) {
        if (form.select_tmp.checked) {
            selectAll();
        } else {
            selectEmpty();
        }
    }

    //체크박스 전체선택
    function selectAll() {

        var i;

        for (i = 0; i < document.forms.length; i++) {
            if (document.forms[i].idx != null) {
                if (document.forms[i].select_checkbox) {
                    document.forms[i].select_checkbox.checked = true;
                }
            }
        }
        return;
    }

    //체크박스 선택해제
    function selectEmpty() {

        var i;

        for (i = 0; i < document.forms.length; i++) {
            if (document.forms[i].select_checkbox) {
                if (document.forms[i].idx != null) {
                    document.forms[i].select_checkbox.checked = false;
                }
            }
        }
        return;
    }

    //선택게시물 삭제
    function bbsDelete() {

        var i;
        var seluser = "";
        for (i = 0; i < document.forms.length; i++) {
            if (document.forms[i].idx != null) {
                if (document.forms[i].select_checkbox) {
                    if (document.forms[i].select_checkbox.checked)
                        seluser = seluser + document.forms[i].idx.value + "|";
                }
            }
        }

        if (seluser == "") {
            alert("삭제할 게시물을 선택하세요.");
            return;
        } else {
            if (confirm("선택한 게시물을 정말 삭제하시겠습니까?")) {
                document.location = "walk_save.php?mode=delbbs&seluser=" + seluser + "&page=<?= $page ?>&<?= $param ?>";
            }
        }
    }
</script>

<style>
    .pagination {
        margin: 0 auto;
    }
</style>

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

    <form action="walk_list.php" method="get">
        <input type="hidden" name="code" value="<?= $code ?>" />
        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <col width="80" />
                    <col width="" />
                    <tbody>
                        <tr>
                            <td>조건검색</td>
                            <td class="comALeft" style="padding-left:5px">
                                <select name="search_option" class="form-control" style="width:auto;">
                                    <option value="course_name" <?php if ($search_option == "course_name") { ?>selected<? } ?>>코스명</option>
                                    <!-- <option value="content" <?php if ($search_option == "content") { ?>selected<? } ?>>내용</option>
                                    <option value="name" <?php if ($search_option == "name") { ?>selected<? } ?>>작성자</option> -->
                                </select>
                                <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control" style="width:auto;" />
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
                <col width="25" />
                <col width="100" />
                <col width="200" />
                <col width="60" />
                <thead>
                    <form>
                        <tr>
                            <td><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></td>
                            <td>번호</td>
                            <td>지역</td>
                            <td>코스명</td>
                            <td>설명</td>
                            <td>작성일</td>
                        </tr>
                    </form>
                </thead>
                <tbody>
                    <?php

                    $page_set = 15;
                    $block_set = 10;
                    if (!$page) $page = 1;

                    $addSql  = "";

                    if ($keyword) $addSql .= " And b." . $search_option . " Like '%" . $keyword . "%' ";

                    $sql  = "";
                    $sql .= "	Select	COUNT(*) ";
                    $sql .= "	From	{$this_table} b ";
                    $sql .= "	Where	1 = 1 ";
                    $sql .= $addSql;
                    $total = $db->single($sql);
                    $pageCnt = (($total - 1) / $page_set) + 1;                        //전체 페이지의 수
                    if ($page > $pageCnt) $page = 1;

                    if ($total > 0) {
                        $sql  = "";
                        $sql .= "	Select	b.* ";
                        $sql .= "	From	{$this_table} b ";
                        $sql .= "	Where	1 = 1 ";
                        $sql .= $addSql;
                        $sql .= "	Order by	b.idx Desc ";
                        $sql .= "	Limit	" . (($page - 1) * $page_set) . "," . $page_set;
                        $row = $db->query($sql);

                        for ($i = 0; $i < count($row); $i++) {
                            $row[$i]['course_name'] = "<a href=\"walk_input.php?mode=update&idx=" . $row[$i]['idx'] . "&page=" . $page . "&" . $param . "\">" . $row[$i]['course_name'] . "</a>";
                            $row[$i]['explain'] = "<a href=\"walk_input.php?mode=update&idx=" . $row[$i]['idx'] . "&page=" . $page . "&" . $param . "\">" . $row[$i]['explain'] . "</a>";
                    ?>
                            <form name="frm<?= $no ?>">
                                <input type="hidden" name="idx" value="<?= $row[$i]['idx'] ?>">
                                <tr>
                                    <td><input type="checkbox" name="select_checkbox"></td>
                                    <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                    <td class="comALeft"><?= $row[$i]['area_1'] ?></td>
                                    <td class="comALeft"><?= $row[$i]['course_name'] ?></td>
                                    <td class="comALeft"><?= $row[$i]['explain'] ?></td>
                                    <td><?= $row[$i]['wdate'] ?></td>
                                </tr>
                            </form>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td height="30" colspan="4" align="center">등록된 글이 없습니다.</td>
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
                <button class="btn btn-default btn-sm" type="button" onClick="location.href='walk_input.php?page=<?= $page ?>&<?= $param ?>';">글등록</button>
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