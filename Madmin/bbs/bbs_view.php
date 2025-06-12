<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT'] . "/inc/bbs_info.inc"; ?>
<?
$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;

$reply_update = false;
$sql = "";
$sql .= "	select 	b.* ";
$sql .= "	from 	df_site_bbs b ";
$sql .= "	where 	b.idx = '$idx'";
$bbs_row = $db->row($sql);
?>

<script language="JavaScript">

    function openImg(img) {
        var url = "bbs_openimg.php?code=<?= $code ?>&img=" + img;
        window.open(url, "openImg", "width=300,height=300,scrollbars=yes");
    }

    function deleteConfirm(idx) {
        if (confirm('선택한 글을 삭제하시겠습니까?')) {
            document.location = "bbs_save.php?mode=delete&idx=" + idx + "&page=<?= $page ?>&<?= $param ?>";
        }
    }

    function deleteConfirm2(idx) {
        if (confirm('답변을 삭제하시겠습니까?')) {
            document.location = "bbs_save.php?mode=delete&idx=" + idx + "&page=<?= $page ?>&<?= $param ?>";
        }
    }

    function deletereplyConfirm(idx) {
        if (confirm('선택한 댓글을 삭제하시겠습니까?')) {
            document.location = "bbs_save.php?mode=deletereply&idx=" + idx + "&page=<?= $page ?>&<?= $param ?>";
        }
    }
    $(document).ready(function () {
        $("#Contents img").each(function () {
            $(this).css("max-width", "750px");
            $(this).css("height", "auto");
        });
    });

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

    <div class="box comMTop20" style="width:978px;">
        <div class="panel">
            <div class="title">
                <i class="fa fa-shopping-cart"></i>
                <span>게시판 내용</span>
            </div>
            <table class="table orderInfo" cellpadding="0" cellspacing="0">
                <col width="15%" />
                <col width="35%" />
                <col width="15%" />
                <col width="35%" />
                <tr>
                    <th>이 름</th>
                    <td class="comALeft"><?= $bbs_row['name'] ?></td>
                    <th>이메일</th>
                    <td class="comALeft"><?= $bbs_row['email'] ?></td>
                </tr>
                <tr>
                    <th>작성일</th>
                    <td class="comALeft"><?= $bbs_row['wdate'] ?></td>
                    <th>조회수</th>
                    <td class="comALeft"><?= $bbs_row['count'] ?></td>
                </tr>
                <tr>
                    <th>제목</th>
                    <td class="comALeft" colspan="3"><?= $bbs_row['subject'] ?></td>
                </tr>
                <tr>
                    <th>내 용</th>
                    <td class="comALeft" id="Contents" colspan="3" style="padding-top:7px; padding-bottom:7px;">
                        <? if ($bbs_row['ctype'] == "T") { ?>
                            <?= nl2br($bbs_row['content']) ?>
                        <? } else { ?>
                            <?= $bbs_row['content'] ?>
                        <? } ?>
                    </td>
                </tr>
                <?
                $sql = " Select * From df_site_bbs Where depno>0 And parno='$idx'  order by prino desc";
                $reply = $db->row($sql);
                ;
                if ($reply) {
                    $reply_update = true;
                    $reply_idx = $reply['idx'];
                    ?>
                    <tr>
                        <th>답변내용</th>
                        <td class="comALeft" id="Contents" colspan="3" style="padding-top:7px; padding-bottom:7px;">
                            <? if ($reply['ctype'] == "T") { ?>
                                <?= nl2br($reply['content']) ?>
                            <? } else { ?>
                                <?= $reply['content'] ?>
                            <? } ?>

                        </td>
                    </tr>
                    <?
                }
                ?>


                <?
                if ($code == "Event" || $code == "DataMart") {
                    if ($bbs_row['grp'] == "예약상품") {
                        $str = "게시글기간";
                    } else {
                        $str = "이벤트기간";
                    }
                    ?>
                    <tr>
                        <th><?= $str ?></th>
                        <td class="comALeft" colspan="3"><?= $bbs_row['event_sdate'] ?> ~ <?= $bbs_row['event_edate'] ?>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div>
    </div>

    <div class="box comMTop20" style="width:978px;">
        <div class="panel">
            <div class="title">
                <i class="fa fa-shopping-cart"></i>
                <span>첨부 파일</span>
            </div>
            <table class="table orderInfo" cellpadding="0" cellspacing="0">
                <col width="15%" />
                <col width="" />
                <?
                $sqlF = " Select * From df_site_bbs_files Where bbsidx='" . $idx . "' Order by idx Asc ";
                $rsF = $db->query($sqlF);
                for ($i = 0; $i < count($rsF); $i++) {
                    ?>
                    <tr>
                        <th>첨부파일</th>
                        <td class="comALeft">
                            <a href="bbs_down.php?idx=<?= $rsF[$i]['idx'] ?>">
                                <?= $rsF[$i]['upfile_name'] ?>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div>
    </div>

    <div class="box comMTop20 comMBottom20" style="width:978px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comACenter" style="width:10%;">
                <button class="btn btn-primary btn-sm" type="button"
                    onClick="location.href='bbs_list.php?page=<?= $page ?>&<?= $param ?>';">목록</button>
            </div>
            <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                <button class="btn btn-info btn-sm" type="button"
                    onClick="location.href='bbs_input.php?mode=update&idx=<?= $idx ?>&page=<?= $page ?>&<?= $param ?>';">수정</button>
                <? 
                if ($code != 'notice') {
                    if ($reply_update) { 
                ?>
                    <button class="btn btn-info btn-sm" type="button"
                        onClick="location.href='bbs_input.php?mode=update&idx=<?= $reply_idx ?>&page=<?= $page ?>&<?= $param ?>';">답변수정</button>
                    <button class="btn btn-danger btn-sm" type="button"
                        onClick="deleteConfirm2('<?= $reply_idx ?>');">답변삭제</button>
                <? } else { ?>
                    <button class="btn btn-info btn-sm" type="button"
                        onClick="location.href='bbs_input.php?mode=reply&idx=<?= $idx ?>&page=<?= $page ?>&<?= $param ?>';">답변</button>
                <? 
                    } 
                }
                ?>
                <button class="btn btn-danger btn-sm" type="button" onClick="deleteConfirm('<?= $idx ?>');">삭제</button>
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