<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$code = $_GET['code'];
$appreved_code = [
    'main',
    'best'
];
if (empty($code) || !in_array($code, $appreved_code)) {
    error("잘못된 접근입니다.");
    exit;
}

$this_table = "df_site_main_slide";
$big_mode = "main_slide";

$param = "code=" . $code . "&s_show=" . $s_show . "&s_opt=" . $s_opt . "&s_key=" . $s_key;
?>
<script language="JavaScript" type="text/javascript">
    // 선택삭제
    function bbsDelete() {
        var selidx = '';
        $('input[name=chk]').each(function () {
            if ($(this).prop('checked')) {
                if (selidx != '') selidx += '|';
                selidx += $(this).val();
            }
        });
        if (selidx == '') {
            alert('삭제할 자료를 선택해 주세요.');
            return;
        }

        if (confirm('선택한 자료를 삭제하시겠습니까?')) {
            location.href = 'exec.php?big_mode=<?= $big_mode ?>&mode=delete&selidx=' + selidx + '&page=<?= $page ?>&<?= $param ?>';
        }
    }
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            메인 슬라이드
        </h3>
        <ul class="breadcrumb">
            <li>한성 모듈러</li>
            <li class="active">메인 슬라이드 관리</li>
        </ul>
    </div>

    <div class="box comMTop10" style="width:978px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="30" />
                <col width="60" />
                <col width="120" />
                <col width="120" />
                <col width="50" />
                <col width="50" />
                <col width="50" />
                <thead>
                    <form>
                        <tr>
                            <td><input type="checkbox" id="chkAll" /></td>
                            <td>이미지</td>
                            <td>타이틀</td>
                            <td>서브 타이틀</td>
                            <td>노출 여부</td>
                            <td>순서</td>
                            <td>수정</td>
                        </tr>
                    </form>
                </thead>
                <tbody>
                    <?php

                    $addSql = " AND code = '{$code}'";

                    $sql = "";
                    $sql .= "	Select	COUNT(*) ";
                    $sql .= "	From	{$this_table} ";
                    $sql .= "	Where	1 = 1 ";
                    $sql .= $addSql;
                    $total = $db->single($sql);

                    if ($total > 0) {
                        $sql = "";
                        $sql .= "	Select	* ";
                        $sql .= "	From	{$this_table} ";
                        $sql .= "	Where	1 = 1 ";
                        $sql .= $addSql;
                        $sql .= "	Order by	prior Desc ";
                        $row = $db->query($sql);

                        for ($i = 0; $i < count($row); $i++) {
                            $row[$i]['upfile'] = $row[$i]['upfile'] == '' ? "" : $row[$i]['upfile'];
                            ?>
                            <tr>
                                <td><input type="checkbox" name="chk" value="<?= $row[$i]['idx'] ?>" /></td>
                                <td>
                                    <a href="<?= $row[$i]['upfile'] ?>" target="_blank">
                                        <img src="<?= $row[$i]['upfile'] ?>" width="100" style="background:#555555;" />
                                    </a>
                                </td>
                                <td>
                                    <a
                                        href="<?= $big_mode ?>_input.php?mode=update&idx=<?= $row[$i]['idx'] ?>&page=<?= $page ?>&<?= $param ?>">
                                        <?= $row[$i]['title'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $row[$i]['title_sub'] ?>
                                </td>
                                <td><?= $row[$i]['showset'] == 'Y' ? '노출' : '비노출' ?></td>
                                <td style="padding:0;">
                                    <ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="exec.php?big_mode=<?= $big_mode ?>&mode=prior&posi=upup&idx=<?= $row[$i]['idx'] ?>&prior=<?= $row[$i]['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/upup_icon.gif" border="0" alt="10단계 위로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="exec.php?big_mode=<?= $big_mode ?>&mode=prior&posi=up&idx=<?= $row[$i]['idx'] ?>&prior=<?= $row[$i]['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/up_icon.gif" border="0" alt="1단계 위로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="exec.php?big_mode=<?= $big_mode ?>&mode=prior&posi=down&idx=<?= $row[$i]['idx'] ?>&prior=<?= $row[$i]['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/down_icon.gif" border="0" alt="1단계 아래로"></a></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"><a
                                                href="exec.php?big_mode=<?= $big_mode ?>&mode=prior&posi=downdown&idx=<?= $row[$i]['idx'] ?>&prior=<?= $row[$i]['prior'] ?>&page=<?= $page ?>&<?= $param ?>"><img
                                                    src="../img/downdown_icon.gif" border="0" alt="10단계 아래로"></a></li>
                                    </ul>
                                    <div class="clear"></div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" type="button"
                                        onClick="location.href='<?= $big_mode ?>_input.php?mode=update&idx=<?= $row[$i]['idx'] ?>&page=<?= $page ?>&<?= $param ?>';">수정</button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td height="30" colspan="7" align="center">등록된 자료가 없습니다.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box comMTop10 comMBottom20" style="width:978px;">
        <div class="comPTop10 comPBottom10">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onClick="bbsDelete();">선택삭제</button>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <?php if ($code == 'main') { ?>
                    <button class="btn btn-default btn-sm" type="button"
                        onClick="location.href='<?= $big_mode ?>_input.php?page=<?= $page ?>&<?= $param ?>';">신규등록</button>
                    <?php
                } else {
                    $sql = "select count(1) cnt from {$this_table} where code = 'best' and showset = 'Y' ";
                    $best_cnt = $db->single($sql);
                    if ($best_cnt < 4) {
                        ?>
                        <button class="btn btn-default btn-sm" type="button"
                            onClick="location.href='<?= $big_mode ?>_input.php?page=<?= $page ?>&<?= $param ?>';">신규등록</button>
                    <?php }
                } ?>
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