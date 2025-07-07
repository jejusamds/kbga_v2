<?php

include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$is_login = !empty($_SESSION['kbga_user_id']);

// 로그인이 필요한 목록
$login_required_pages = [
    '/community/community_sub01.html',
    '/mypage/modify.html',
    '/mypage/history.html',
    '/center/center_sub_apply.php',
    '/center/center_sub_apply_o.php',
    '/news/news_sub02_apply.html',
    '/news/news_sub02_apply02.html',
    '/competition/competition_sub03_apply.html',
    '/competition/competition_sub03_apply02.html',
];

// 로그인 상태에서 접근안되는 목록
$not_login_required_pages = [
    '/member/login.html',
    '/member/join_step01.html',
    '/member/join_step02_individual.html',
    '/member/join_step02_group.html',
    '/member/join_step03_individual.html',
    '/member/join_step03_group.html',
    
];

/**
 * 로그인 페이지로 리다이렉트
 */
function require_login(){
    global $is_login, $login_required_pages, $not_login_required_pages, $db;
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (!$is_login) {
        if (in_array($uri, $login_required_pages, true)) {
            echo "
                <script>
                alert('로그인이 필요합니다.');
                window.location.href = '/member/login.html';
                </script>
                ";
            exit;
        }
    } else {
        if (in_array($uri, $not_login_required_pages, true)) {
            echo "<script>window.location.href = '/mypage/modify.html';</script>";
            exit;
        }
    }
}
require_login();

$login_user_info = null;
if ($is_login) {
    $sql = "SELECT * FROM df_site_member WHERE f_user_id = :f_user_id AND is_out = 1";
    $db->bind("f_user_id", $_SESSION['kbga_user_id']);
    //$login_user_info = $db->row($sql, null, PDO::FETCH_OBJ);
    $login_user_info = $db->row($sql);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/Mobile_Detect.php';
$detect = new Mobile_Detect;

include $_SERVER['DOCUMENT_ROOT'] . "/inc/_df_counter.php";

function generate_csrf_token()
{
    return bin2hex(random_bytes(32));
}
$csrf_token = generate_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;



// 헤더에서 사용하는 메뉴 배열
// include 'get_menu.php';
// include 'privacy.php';