<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/Eadmin_check.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/site_info.inc";

$gb = $_SERVER['REQUEST_URI'];
$gb = @str_replace("/Madmin/", "", $gb);
$gb = @str_replace("set/", "", $gb);
$gb = @str_replace("page/", "", $gb);
$gb = @str_replace("bbs/", "", $gb);
$gb = @str_replace("contents/", "", $gb);
$gb = @str_replace("delivery/", "", $gb);
$gb = @str_replace("marketing/", "", $gb);
$gb = @str_replace("main_manage/", "", $gb);
$gb = substr("$gb", 0, strpos($gb, ".php"));

$menu01 = array("page_privacy");


$menu02 = array("bbs_list", "bbs_input", "bbs_view");    // 문의
$menu03 = array(
    "contents_list",
    "contents_input",
    "contents_view",
    "category_list",
);

$menu03_1 = array(
    "contents_list",
    "contents_input",
    "contents_view",
);

$menu03_2 = array(
    "category_list"
);


$menu04 = array(
    "page_popup",
    "popup_input",
    "mobile_popup",
    "mobile_popup_input",
    "banner_main",
    "banner_main_input"
);

$menu05 = array(
    "main_slide_list", "main_slide_input", "sub_slide_list", "sub_slide_input"
);


$menu99 = array("stat_visit", "stat_url", "stat_url_view");    // 통계 현황

// 이벤트
// $sql  = "";
// $sql .= "	Select 	COUNT(*) ";
// $sql .= "	From 	df_site_event ";
// $sql .= "	Where 	DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
// $leftContact = $db->single($sql);

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="shortcut icon" href="/img/favicon.ico" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= $site_info['admin_title'] ?></title>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
    <!--script type="text/javascript" src="/js/util_lib.js"></script-->

    <script>
        (function ($) {
            $(window).on("load resize", function () {
                $("#rightCont").css("height", $(window).height() + "px");
                $("#rightCont .gnb").css("width", ($(window).width() - 240) + "px");
            });
        })(jQuery);

        // 좌측 메뉴 DOWN / UP
        $(document).on("click", ".lnb-menu", function () {
            $this = $(this);
            // 다른메뉴 초기화
            $(".lnb-menu").not($this).each(function () {
                $(this).removeClass("on");
                $(this).removeClass("sel");
                $(".right i", $(this)).removeClass("fa-minus");
                $(".right i", $(this)).addClass("fa-plus");
                $(this).next(".lnb-submenu").slideUp("fast");
            });

            if ($(".right i", $this).hasClass("fa-plus")) {
                $this.addClass("sel");
                $(".right i", $this).removeClass("fa-plus");
                $(".right i", $this).addClass("fa-minus");
                $this.next(".lnb-submenu").slideDown("fast");
            } else {
                $this.removeClass("sel");
                $(".right i", $this).removeClass("fa-minus");
                $(".right i", $this).addClass("fa-plus");
                $this.next(".lnb-submenu").slideUp("fast");
            }
        });

        // 좌측 메뉴 클릭 (메뉴)
        $(document).on("click", "#leftMenu .lnb .lnb-menu.href", function () {
            location.href = $(this).attr("href");
        });
        // 좌측 메뉴 클릭 (서브메뉴)
        $(document).on("click", "#leftMenu .lnb .lnb-submenu .lnb-submenu-item", function () {
            location.href = $(this).attr("href");
        });

        // 상단 메뉴 클릭
        $(document).on("click", "#rightCont .gnb .gnb-menu .gnb-menu-list .gnb-menu-item", function () {
            if ($(this).attr("target") == "blank") window.open($(this).attr("href"));
            else location.href = $(this).attr("href");
        });

        $(document).ready(function () {
            // 좌측 메뉴 HOVER
            $(".lnb-menu").hover(
                function () {
                    // mouseenter
                    if (!$(this).hasClass("on")) {
                        $(this).addClass("sel");
                    }
                },
                function () {
                    // mouseleave
                    var $submenu = $(this).next(".lnb-submenu");
                    // 요소가 없거나 display:none 이면 sel 제거
                    if ($submenu.length === 0 || $submenu.is(":hidden")) {
                        $(this).removeClass("sel");
                    }
                }
            );

            // 좌측 서브메뉴 HOVER
            $(".lnb-submenu-item").hover(
                function () {
                    if (!$(this).hasClass("on")) {
                        $(this).addClass("sel");
                    }
                },
                function () {
                    $(this).removeClass("sel");
                }
            );

            // 상단 우측 메뉴
            $(".gnb-menu").hover(
                function () {
                    $(".gnb-menu-list", $(this)).stop().slideDown("fast");
                },
                function () {
                    $(".gnb-menu-list", $(this)).stop().slideUp("fast");
                }
            );
        });
    </script>

    <script>
        jQuery(function ($) {
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
                dateFormat: 'yy.mm.dd', // [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: true,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['ko']);
            var dates = $("#date_from, #date_to, #adm_date, #start_date, #end_date").datepicker({
                'beforeShow': function (input, datepicker) {
                    setTimeout(function () {
                        $(datepicker.dpDiv).css('zIndex', 100);
                    }, 500);
                },
                onSelect: function (selectedDate) {
                    if (!$(this).hasClass("date_input")) {
                        var option = this.id == "date_from" ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    } else {
                        var option = this.id == 'start_date' ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    }


                    dates.not(this).datepicker("option", option, date);
                }
            });

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
            var dates = $("#f_date").datepicker({
                'beforeShow': function (input, datepicker) {
                    setTimeout(function () {
                        $(datepicker.dpDiv).css('zIndex', 100);
                    }, 500);
                },
                onSelect: function (selectedDate) {
                    if (!$(this).hasClass("date_input")) {
                        var option = this.id == "date_from" ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    } else {
                        var option = this.id == 'start_date' ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    }


                    dates.not(this).datepicker("option", option, date);
                }
            });
        });

        function setPeriod(date_from, date_to) {
            $("#date_from").val(date_from);
            $("#date_to").val(date_to);
        }

        // 숫자만 입력
        function onlyNumber(obj) {
            $(obj).keyup(function () {
                $(this).val($(this).val().replace(/[^0-9]/g, ""));
            });
        }

        // 전체 선택
        $(document).on('click', '#chkAll', function () {
            $this = $(this);
            $('input[name=chk]').each(function () {
                $(this).prop('checked', $this.prop('checked'));
            });
        });
    </script>
    <style>
        .logo {
            height: 40px;
            width: 100%;
            /* 부모의 너비 */
            display: flex;
            /* Flexbox로 정렬 */
            justify-content: center;
            /* 가로 중앙 정렬 */
            align-items: center;
            /* 세로 중앙 정렬 */
            overflow: hidden;
            /* 넘치는 부분 숨김 */
            position: relative;
            /* 자식의 위치 기준 설정 */
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .logo-img {
            height: 40px;
            /* 부모 높이를 넘지 않음 */
            max-width: 100%;
            /* 부모 너비를 넘지 않음 */
            object-fit: contain;
            /* 이미지 비율 유지하며 부모 안에 맞춤 */
            display: block;
            /* 불필요한 여백 제거 */
        }
    </style>
</head>

<body>

    <div id="leftMenu">
        <div class="logo" style="background: white;">
            <a href="/Madmin/">
                <img src="/Madmin/img/main_logo_king.svg" class="logo-img" />
            </a>
        </div>
        <div class="lnb">
            <div class="lnb-div userInfo">
                <span class="left">
                    <?= $_SESSION['admin_id'] ?>
                    <span>_ <?= $_SESSION['admin_name'] ?></span>
                </span>
                <span class="right">
                    <a href="/Madmin/admin_logout.php">
                        <i class="fa fa-sign-out fa-lg"></i>
                    </a>
                </span>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu01)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-user fa-lg"></i>
                    <span>개인정보 수집·이용</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu01)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu01)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($admin_type == "privacy") { ?>on<? } ?>"
                    href="/Madmin/page/page_privacy.php?admin_type=privacy">개인정보처리방침</div>
                <div class="lnb-submenu-item <? if ($admin_type == "use") { ?>on<? } ?>"
                    href="/Madmin/page/page_privacy.php?admin_type=use">이용약관</div>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu04)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-bullhorn fa-lg"></i>
                    <span>팝업 설정</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu04)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu04)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($gb == "page_popup" || $gb == "popup_input") { ?>on<? } ?>"
                    href="/Madmin/page/page_popup.php">팝업관리 - PC</div>
                <div class="lnb-submenu-item <? if ($gb == "mobile_popup" || $gb == "mobile_popup_input") { ?>on<? } ?>"
                    href="/Madmin/page/mobile_popup.php">팝업관리 - MOBILE</div>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu05)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-desktop fa-lg"></i>
                    <span>메인 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu05)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu" style="display:<? if (in_array($gb, $menu05)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($code == 'main' || $code == 'main') { ?>on<? } ?>" href="/Madmin/main_manage/main_slide_list.php?code=main">메인 슬라이드</div>
                <div class="lnb-submenu-item <? if ($code == 'best' || $code == 'best') { ?>on<? } ?>" href="/Madmin/main_manage/main_slide_list.php?code=best">베스트 제품 슬라이드</div>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu03)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-gift fa-lg"></i>
                    <span>제품 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu03)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu03)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if (in_array($gb, $menu03_2)) { ?>on<? } ?>"
                    href="/Madmin/contents/category_list.php">카테고리 관리</div>
                <div class="lnb-submenu-item <? if (in_array($gb, $menu03_1)) { ?>on<? } ?>"
                    href="/Madmin/contents/contents_list.php">제품 관리</div>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu02)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-comments fa-lg"></i>
                    <span>게시판 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu02)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu02)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($code == 'notice') { ?>on<? } ?>"
                    href="/Madmin/bbs/bbs_list.php?code=notice">공지사항</div>
                <div class="lnb-submenu-item <? if ($code == 'review') { ?>on<? } ?>"
                    href="/Madmin/bbs/bbs_list.php?code=review">제품사용후기</div>
                <div class="lnb-submenu-item <? if ($code == 'buy') { ?>on<? } ?>"
                    href="/Madmin/bbs/bbs_list.php?code=buy">상품 및 구매후기</div>
                <div class="lnb-submenu-item <? if ($code == 'inquiry') { ?>on<? } ?>"
                    href="/Madmin/bbs/bbs_list.php?code=inquiry">견적문의</div>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu99)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-bar-chart fa-lg"></i>
                    <span>접속 현황</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu99)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu99)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($gb == "stat_visit") { ?>on<? } ?>"
                    href="/Madmin/marketing/stat_visit.php">접속 통계</div>
                <div class="lnb-submenu-item <? if ($gb == "stat_url" || $gb == "stat_url_view") { ?>on<? } ?>"
                    href="/Madmin/marketing/stat_url.php">유입 경로</div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div id="rightCont">
        <div class="gnb">

            <div class="gnb-menu">
                <a href="/Madmin/admin_logout.php">
                    <i class="fa fa-sign-out fa-lg"></i>
                    <span>로그아웃</span>
                </a>
            </div>

            <!--div class="gnb-menu">
                <i class="fa fa-bolt fa-lg"></i>
                <span>바로가기</span>
                <i class="fa fa-caret-down fa-lg"></i>

                <div class="gnb-menu-list">
                    
                <div class="gnb-menu-item" href="http://design-factory.co.kr/cms" target="blank">
                    <span class="square">■</span>
                    <span>디팩CMS 관리자</span>
                </div>
                <div class="gnb-menu-item" href="https://admin8.kcp.co.kr" target="blank">
                    <span class="square">■</span>
                    <span>KCP PG관리자</span>
                </div>
                <div class="gnb-menu-item" href="javascript:alert('심사 전 입니다.');">
                    <span class="square">■</span>
                    <span>네이버페이 관리자</span>
                </div>
                <div class="gnb-menu-item" href="javascript:alert('심사 전 입니다.');">
                    <span class="square">■</span>
                    <span>지식쇼핑 파트너존</span>
                </div>
            
                </div>
            </div-->

            <?php if ($_SESSION['admin_part'] == "0") { ?>
                <div class="gnb-menu">
                    <i class="fa fa-cog fa-lg"></i>
                    <span>관리자설정</span>
                    <i class="fa fa-caret-down fa-lg"></i>

                    <div class="gnb-menu-list">
                        <div class="gnb-menu-item" href="/Madmin/set/set_admin.php" target="">
                            <span class="square">■</span>
                            <span>관리자 설정</span>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="clear"></div>

        <div id="pageContainer">
            



