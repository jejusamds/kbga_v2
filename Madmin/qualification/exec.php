<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_qualification';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;

$fields = ['page_no', 'f_name', 'f_type', 'f_reg_no', 'f_manage_org', 'f_ministry'];

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
        $cols[] = 'prior';
        $vals[] = ':prior';
        $params['prior'] = date('ymdHis');
        $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ", wdate) VALUES (" . implode(',', $vals) . ", NOW())";
        $db->query($sql, $params);
        $url = "qualification_list.php?page={$page}&category=" . (int) $_POST['category'];
        complete('등록되었습니다.', $url);
        break;
    case 'update':
        $idx = (int) $_POST['idx'];
        $sets = [];
        $params = ['idx' => $idx];
        foreach ($fields as $f) {
            $sets[] = "$f=:$f";
            $params[$f] = $_POST[$f] ?? '';
        }
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx=:idx";
        $db->query($sql, $params);
        $url = "qualification_list.php?page={$page}&category=" . (int) $_POST['category'];
        complete('수정되었습니다.', $url);
        break;
    case 'delete':
        $ids = array_filter(array_map('intval', explode('|', $_REQUEST['selidx'] ?? '')));
        foreach ($ids as $id) {
            $db->query("DELETE FROM {$table} WHERE idx=:id", ['id' => $id]);
        }
        $url = "qualification_list.php?page={$page}&category=" . (int) $_REQUEST['category'];
        complete('삭제되었습니다.', $url);
        break;
    case 'prior':
        $idx = (int) $_GET['idx'];
        $prior = $_GET['prior'] ?? 0;
        $posi = $_GET['posi'] ?? '';
        $category = (int) ($_GET['category'] ?? 1);
        $sql = " Select wp.* From {$table} wp Where 1=1 AND page_no='{$category}' ";
        if ($posi == 'up') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' AND page_no='{$category}'");
            }
        } elseif ($posi == 'upup') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "' AND page_no='{$category}'");
            }
        } elseif ($posi == 'down') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' AND page_no='{$category}'");
            }
        } elseif ($posi == 'downdown') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "' AND page_no='{$category}'");
            }
        }
        $url = "qualification_list.php?page={$page}&category={$category}";
        complete('순서를 변경하였습니다.', $url);
        break;
    default:
        error('잘못된 모드입니다.');
}