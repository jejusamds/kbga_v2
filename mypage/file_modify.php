<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('잘못된 접근입니다.');
}

$idx = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
$tableKey = $_POST['table'] ?? '';
$token = $_POST['csrf_token'] ?? '';

$tableMap = [
    'application' => [
        'table' => 'df_site_application_registration',
        'dir'   => '/userfiles/registration',
        'view'  => '/mypage/history_view_license.html'
    ],
    'education' => [
        'table' => 'df_site_edu_registration',
        'dir'   => '/userfiles/education',
        'view'  => '/mypage/history_view_education.html'
    ],
    'competition' => [
        'table' => 'df_site_competition_registration',
        'dir'   => '/userfiles/registration',
        'view'  => '/mypage/history_view_contest.html'
    ],
];

if ($idx <= 0 || !isset($tableMap[$tableKey]) || $token !== ($_SESSION['csrf_token'] ?? '')) {
    error('잘못된 요청입니다.');
}

$info  = $tableMap[$tableKey];
$table = $info['table'];
$dir   = $_SERVER['DOCUMENT_ROOT'] . $info['dir'];
$view  = $info['view'] . '?idx=' . $idx;

$row = $db->row("SELECT f_issue_file, f_issue_file_name, f_applicant_status FROM {$table} WHERE idx=:idx", ['idx' => $idx]);
if (!$row) {
    error('신청 정보를 찾을 수 없습니다.');
}

if ($row['f_applicant_status'] === 'cancle') {
    error('수정할 수 없는 상태입니다.');
}


$currentFiles = $row['f_issue_file'] ? explode(',', $row['f_issue_file']) : [];
$currentNames = $row['f_issue_file_name'] ? explode(',', $row['f_issue_file_name']) : [];

$deleteFiles = isset($_POST['delete_files']) && is_array($_POST['delete_files']) ? array_map('basename', $_POST['delete_files']) : [];

foreach ($deleteFiles as $df) {
    $key = array_search($df, $currentFiles);
    if ($key !== false) {
        unset($currentFiles[$key]);
        unset($currentNames[$key]);
        $path = $dir . '/' . $df;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

$newFiles  = [];
$newNames  = [];
if (!empty($_FILES['upfile']['name']) && is_array($_FILES['upfile']['name'])) {
    $count = count($_FILES['upfile']['name']);
    for ($i = 0; $i < $count; $i++) {
        if (empty($_FILES['upfile']['name'][$i])) continue;
        $tmp = [
            'name' => $_FILES['upfile']['name'][$i],
            'tmp_name' => $_FILES['upfile']['tmp_name'][$i],
            'error' => $_FILES['upfile']['error'][$i],
        ];
        if ($tmp['error'] !== UPLOAD_ERR_OK) continue;
        $ext = strtolower(pathinfo($tmp['name'], PATHINFO_EXTENSION));
        $new = uniqid('', true) . '.' . $ext;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        if (move_uploaded_file($tmp['tmp_name'], $dir . '/' . $new)) {
            $newFiles[] = $new;
            $newNames[] = $tmp['name'];
        }
    }
}

$finalFiles = array_merge($currentFiles, $newFiles);
$finalNames = array_merge($currentNames, $newNames);

$db->query(
    "UPDATE {$table} SET f_issue_file=:f, f_issue_file_name=:n, f_applicant_status='re' WHERE idx=:idx",
    [
        'f' => implode(',', $finalFiles),
        'n' => implode(',', $finalNames),
        'idx' => $idx,
    ]
);

complete('수정이 완료되었습니다.', $view);