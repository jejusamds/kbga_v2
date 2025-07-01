<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$convert_fields = [
    'f_registration_start',
    'f_registration_end',
    'f_registration_start_2',
    'f_registration_end_2',
];
foreach ($convert_fields as $cf) {
    if (isset($_POST[$cf]) && $_POST[$cf] !== '') {
        $_POST[$cf] = str_replace('-', '.', $_POST[$cf]);
    }
}

$table = 'df_site_application';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;

$query_str = "&category=" . $_POST[f_category];

// 공통 필드 목록
$fields = [
    'f_category',
    'f_year',
    'f_round',
    'f_type',
    'f_registration_start',
    'f_registration_end',
    'f_exam_date',
    'f_pass_announce',
    'f_registration_start_2',
    'f_registration_end_2',
    'f_exam_date_2',
    'f_pass_announce_2',
    'f_cert_application'
];

switch ($mode) {
    case 'insert':
        $cols = [];
        $vals = [];
        $params = [];
        foreach ($fields as $f) {
            $cols[] = $f;
            $vals[] = ':' . $f;
            $params[$f] = $_POST[$f] ?? '';
        }

        // ---- 년도와 회차 중복 검사 ----
        $y = $params['f_year'] ?? null;
        $r = $params['f_round'] ?? null;
        if ($y && $r) {
            if ($_POST['f_category'] != 'teacher') {
                $chk = $db->single(
                    "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r AND f_category = :f_category",
                    ['y' => $y, 'r' => $r, 'f_category' => $_POST['f_category']]
                );
            } else {
                $chk = $db->single(
                    "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r AND f_category = :f_category AND f_type = :f_type",
                    ['y' => $y, 'r' => $r, 'f_category' => $_POST['f_category'], 'f_type' => $_POST['f_type']]
                );
            }
            if ($chk > 0) {
                error('이미 동일한 년도와 회차의 데이터가 존재합니다.');
                exit;
            }
        }

        $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ", wdate) " .
            "VALUES (" . implode(',', $vals) . ", NOW())";
        $db->query($sql, $params);
        complete('등록되었습니다.', "/Madmin/application/application_list.php?page={$page}{$query_str}");
        break;

    case 'update':
        $idx = isset($_POST['idx']) ? (int) $_POST['idx'] : 0;
        if ($idx <= 0) {
            error('잘못된 접근입니다.');
            exit;
        }
        $sets = [];
        $params = [];
        foreach ($fields as $f) {
            $sets[] = "$f = :$f";
            $params[$f] = $_POST[$f] ?? '';
        }

        // ---- 년도와 회차 중복 검사 (수정 시 현재 데이터 제외) ----
        $y = $params['f_year'] ?? null;
        $r = $params['f_round'] ?? null;
        if ($y && $r) {
            if ($_POST['f_category'] != 'teacher') {
                $chk = $db->single(
                    "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r AND f_category = :f_category AND idx <> :idx",
                    ['y' => $y, 'r' => $r, 'f_category' => $_POST['f_category'], 'idx' => $idx]
                );
            } else {
                $chk = $db->single(
                    "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r AND f_category = :f_category AND f_type = :f_type AND idx <> :idx",
                    ['y' => $y, 'r' => $r, 'f_category' => $_POST['f_category'], 'f_type' => $_POST['f_type'], 'idx' => $idx]
                );
            }
            if ($chk > 0) {
                error('이미 동일한 년도와 회차의 데이터가 존재합니다.');
                exit;
            }
        }

        $params['idx'] = $idx;
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx = :idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', "/Madmin/application/application_list.php?page={$page}{$query_str}");
        break;

    case 'delete':
        $selidx = isset($_REQUEST['selidx']) ? $_REQUEST['selidx'] : '';
        $ids = array_filter(array_map('intval', explode('|', $selidx)));
        foreach ($ids as $id) {
            $db->query("DELETE FROM {$table} WHERE idx = :id", ['id' => $id]);
        }
        complete('삭제되었습니다.', "/Madmin/application/application_list.php?page={$page}{$query_str}");
        break;

    default:
        error('잘못된 모드입니다.');
        break;
}