<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('잘못된 접근입니다.');
}

$idx = isset($_POST['idx']) ? (int) $_POST['idx'] : 0;
$tableKey = $_POST['table'] ?? '';
$token = $_POST['csrf_token'] ?? '';

$tableMap = [
    'application' => 'df_site_application_registration',
    'education'   => 'df_site_edu_registration',
    'competition' => 'df_site_competition_registration',
];

if (
    $idx <= 0 ||
    !isset($tableMap[$tableKey]) ||
    $token !== ($_SESSION['csrf_token'] ?? '')
) {
    error('잘못된 접근입니다.');
}

$table = $tableMap[$tableKey];
$row   = $db->row("SELECT f_user_id, f_applicant_status FROM {$table} WHERE idx=:idx", ['idx' => $idx]);
$loginId = $_SESSION['kbga_user_id'] ?? '';

if (!$row) {
    error('신청 정보를 찾을 수 없습니다.');
}

if ($row['f_user_id'] !== $loginId) {
    error('잘못된 접근입니다.');
}

if ($row['f_applicant_status'] === 'ing') {
    $db->query("UPDATE {$table} SET f_applicant_status='cancle' WHERE idx=:idx", ['idx' => $idx]);
    complete('신청취소가 완료되었습니다.', '/mypage/history.html');
} else {
    echo '<script>alert("취소가 불가능 합니다. 협회로 문의 바랍니다.");history.back();</script>';
}