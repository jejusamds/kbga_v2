<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_material';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
$category = $_REQUEST['f_category'] ?? '';
$query_str = '&category=' . $category;

$fields = ['f_category', 'f_subject_idx', 'f_subject', 'f_type', 'f_level', 'f_description', 'f_file', 'f_file_name'];

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
        if (!empty($_FILES['upfile']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/material';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile']['name'], PATHINFO_EXTENSION));
            $new = uniqid('', true) . '.' . $ext;
            move_uploaded_file($_FILES['upfile']['tmp_name'], $dir . '/' . $new);
            $params['f_file'] = $new;
            $params['f_file_name'] = $_FILES['upfile']['name'];
        }
        $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ", wdate) VALUES (" . implode(',', $vals) . ", NOW())";
        $db->query($sql, $params);
        complete('등록되었습니다.', "/Madmin/material/material_list.php?page={$page}{$query_str}");
        break;
    case 'update':
        $idx = (int) $_POST['idx'];
        $sets = [];
        $params = [];
        // 텍스트 필드만 우선 업데이트 항목에 포함
        foreach (['f_category', 'f_subject_idx', 'f_subject', 'f_type', 'f_level', 'f_description'] as $f) {
            $sets[] = "$f=:$f";
            $params[$f] = $_POST[$f] ?? '';
        }

        // 파일 처리
        if (!empty($_FILES['upfile']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/material';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile']['name'], PATHINFO_EXTENSION));
            $new = uniqid('', true) . '.' . $ext;
            move_uploaded_file($_FILES['upfile']['tmp_name'], $dir . '/' . $new);
            $sets[] = 'f_file=:f_file';
            $params['f_file'] = $new;
            $sets[] = 'f_file_name=:f_file_name';
            $params['f_file_name'] = $_FILES['upfile']['name'];
        } elseif (!empty($_POST['del_file'])) {
            $sets[] = 'f_file=:f_file';
            $params['f_file'] = '';
            $sets[] = 'f_file_name=:f_file_name';
            $params['f_file_name'] = '';
        }

        $params['idx'] = $idx;
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx=:idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', "/Madmin/material/material_list.php?page={$page}{$query_str}");
        break;
    case 'delete':
        $ids = array_filter(array_map('intval', explode('|', $_REQUEST['selidx'] ?? '')));
        foreach ($ids as $id) {
            $db->query("DELETE FROM {$table} WHERE idx=:id", ['id' => $id]);
        }
        complete('삭제되었습니다.', "/Madmin/material/material_list.php?page={$page}{$query_str}");
        break;
    default:
        error('잘못된 모드입니다.');
}