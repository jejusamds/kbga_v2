<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$idx = isset($_POST['idx']) ? (int) $_POST['idx'] : 0;
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$status = isset($_POST['f_applicant_status']) ? $_POST['f_applicant_status'] : 'ing';
$reason = isset($_POST['status_reason']) ? trim($_POST['status_reason']) : '';

if ($idx <= 0) {
    error('잘못된 접근입니다.');
    exit;
}

if (!in_array($status, ['ing', 'done', 'cancle', 'hold'], true)) {
    $status = 'ing';
}

$db->query(
    'UPDATE df_site_competition_registration SET f_applicant_status=:st, f_status_reason=:rs WHERE idx=:idx',
    ['st' => $status, 'rs' => $reason, 'idx' => $idx]
);

complete('신청결과가 변경되었습니다.', "reg_competition_view.php?idx={$idx}&page={$page}");