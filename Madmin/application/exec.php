<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_application';
$mode  = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page  = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;

// 공통 필드 목록
$fields = [
    'f_category',
    'f_year',
    'f_round',
    'f_type',
    'f_registration_period',
    'f_exam_date',
    'f_pass_announce',
    'f_registration_period_2',
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
            $chk = $db->single(
                "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r",
                ['y' => $y, 'r' => $r]
            );
            if ($chk > 0) {
                error('이미 동일한 년도와 회차의 데이터가 존재합니다.');
                exit;
            }
        }

        $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ", wdate) " .
               "VALUES (" . implode(',', $vals) . ", NOW())";
        $db->query($sql, $params);
        complete('등록되었습니다.', "/Madmin/application/application_list.php?page={$page}");
        break;

    case 'update':
        $idx = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
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
            $chk = $db->single(
                "SELECT COUNT(*) FROM {$table} WHERE f_year = :y AND f_round = :r AND idx <> :idx",
                ['y' => $y, 'r' => $r, 'idx' => $idx]
            );
            if ($chk > 0) {
                error('이미 동일한 년도와 회차의 데이터가 존재합니다.');
                exit;
            }
        }

        $params['idx'] = $idx;
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx = :idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', "/Madmin/application/application_list.php?page={$page}");
        break;

    case 'delete':
        $selidx = isset($_REQUEST['selidx']) ? $_REQUEST['selidx'] : '';
        $ids = array_filter(array_map('intval', explode('|', $selidx)));
        foreach ($ids as $id) {
            $db->query("DELETE FROM {$table} WHERE idx = :id", ['id' => $id]);
        }
        complete('삭제되었습니다.', "/Madmin/application/application_list.php?page={$page}");
        break;

    default:
        error('잘못된 모드입니다.');
        break;
}
