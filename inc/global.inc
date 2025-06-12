<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);

header("Content-Type: text/html; charset=UTF-8");

// 6시간으로 
ini_set('session.gc_maxlifetime', 21600);
session_set_cookie_params(21600);
@session_start();

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
@extract($_FILES);

require($_SERVER['DOCUMENT_ROOT'] . "/lib/Db.class.php");
$db = new Db();

// 관리자 출력시 보안검증
function safeAdminOutput($input, $allowHtml = false)
{
    if ($allowHtml) {
        // HTML 태그는 허용하지만 위험한 요소 제거
        $input = preg_replace('/<(script|style|iframe|object|embed|applet|meta|link|title|base)[^>]*>.*?<\/\1>/is', '', $input);
        $input = preg_replace('/on\w+=".*?"/i', '', $input);
        $input = preg_replace('/javascript:[^"]*/i', '', $input);
        return strip_tags($input, '<b><i><u><p><a>');
    } else {
        // HTML 태그를 모두 비활성화
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

// 에러 메세지 출력
function error($error_msg, $go_url = "")
{

    if ($go_url == "")
        echo "<script>alert(\"$error_msg\");history.go(-1);</script>";
    else
        echo "<script>alert(\"$error_msg\");document.location=\"$go_url\";</script>";
    exit;
}

function errorclose($error_msg)
{

    echo "<script>alert(\"$error_msg\");self.close();</script>";

    exit;
}

function errorcolorbox($error_msg)
{

    echo "<script>alert(\"$error_msg\");parent.$.colorbox.close();</script>";

    exit;
}
// 확인 메세지 출력
function confirms($confirm_msg, $go_url = "")
{

    if ($go_url == "") {
        echo "<script>if(confirm(\"$confirm_msg\"));history.go(-1);</script>";
    } else {
        echo "
			<script>
			if(confirm(\"$confirm_msg\")){
			document.location=\"$go_url\";
			}
			else {
			history.go(-1);
			}
			</script>";
    }
    exit;
}

// 완료 경고창 출력
function comalert($com_msg, $go_url = "")
{

    if ($go_url == "")
        echo "<script>alert(\"$com_msg\");history.go(-1);</script>";
    else
        echo "<script>alert(\"$com_msg\");document.location=\"$go_url\";</script>";
}

// 완료 메세지 출력
function complete($com_msg, $go_url = "")
{

    if ($go_url == "")
        echo "<script>window.setTimeout(\"history.go(-1)\",600);</script>";
    else
        echo "<script>window.setTimeout(\"document.location='$go_url';\",600);</script>";

    echo "<body><table width=100% height=100%><tr><td align=center><font size=2>$com_msg</font></td></tr></table></body>";
}


// 전시장 예약 value value inquiry_sub01.html
$gInquiryVal_show_meeting_place[1] = '미팅장소1-global.inc';
$gInquiryVal_show_meeting_place[2] = '미팅장소2-global.inc';

$gInquiryVal_show_build_period[1] = '건축시기1-global.inc';
$gInquiryVal_show_build_period[2] = '건축시기2-global.inc';

$gInquiryVal_show_expected_size[1] = '예상평형1-global.inc';
$gInquiryVal_show_expected_size[2] = '예상평형2-global.inc';

$gInquiryVal_show_prefer_way[1] = '선호공법1-global.inc';
$gInquiryVal_show_prefer_way[2] = '선호공법2-global.inc';

// 제품구매 value value inquiry_sub02.html
$gInquiryVal_product_buy[1] = '쉼터';                                                                
$gInquiryVal_product_buy[2] = '농막';
$gInquiryVal_product_buy[3] = '주택';
$gInquiryVal_product_buy[4] = '원룸';
$gInquiryVal_product_buy[5] = '기타';

// 개발의뢰 value inquiry_sub03.html
$gInquiryVal_dev_product_dev[1] = '쉼터농막';
$gInquiryVal_dev_product_dev[2] = '주거숙박';
$gInquiryVal_dev_product_dev[3] = '상업공동시설';
$gInquiryVal_dev_product_dev[4] = '기타';

$gInquiryVal_dev_product_estimate[1] = '쉼터농막';
$gInquiryVal_dev_product_estimate[2] = '주거숙박';
$gInquiryVal_dev_product_estimate[3] = '상업공동시설';
$gInquiryVal_dev_product_estimate[4] = '기타';

// 제휴문의 value value inquiry_sub04.html
$gInquiryVal_coalition_contents[1] = '대리점 개설';
$gInquiryVal_coalition_contents[2] = '프로젝트 협업';
$gInquiryVal_coalition_contents[3] = '광고 홍보 협업';
$gInquiryVal_coalition_contents[4] = '토지, 단지개발';
$gInquiryVal_coalition_contents[5] = '기타';

$gInquiry_gubun_name = array(
    'contact_us' => '메인 문의',
    'show' => '전시장 예약',
    'buy' => '제품구매',
    'dev' => '개발의뢰',
    'coalition' => '제휴문의',
    'as' => 'A/S 신청',
    'quick' => '빠른 상담'
);

$gDelivery_gubun[1] = '쉼터농막';
$gDelivery_gubun[2] = '주거숙박';
$gDelivery_gubun[3] = '상업공동시설';

if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
    // $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    // header('HTTP/1.1 301 Moved Permanently');
    // header('Location: ' . $redirect);
    // exit();
}
