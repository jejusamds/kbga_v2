<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_main_slide';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;

switch ($mode) {
    case 'insert':
        $prior = date('ymdHis');
        $params = [
            'top_pc' => $_POST['top_contents_pc'] ?? '',
            'top_m' => $_POST['top_contents_m'] ?? '',
            'mid_pc' => $_POST['middle_contents_pc'] ?? '',
            'mid_m' => $_POST['middle_contents_m'] ?? '',
            'bot_pc' => $_POST['bottom_contents_pc'] ?? '',
            'bot_m' => $_POST['bottom_contents_m'] ?? '',
            'prior' => $prior,
            'media_type' => $_POST['media_type'] ?? 'image',
        ];
        $thumb_pc = '';
        $thumb_m = '';
        $video_pc = '';
        $video_m = '';
        if (!empty($_FILES['thumbnail_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['thumbnail_pc']['name'], PATHINFO_EXTENSION));
            $thumb_pc = uniqid('pc_') . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail_pc']['tmp_name'], "$dir/$thumb_pc");
        }
        if (!empty($_FILES['thumbnail_m']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['thumbnail_m']['name'], PATHINFO_EXTENSION));
            $thumb_m = uniqid('m_') . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail_m']['tmp_name'], "$dir/$thumb_m");
        }
        if (!empty($_FILES['video_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['video_pc']['name'], PATHINFO_EXTENSION));
            $video_pc = uniqid('vpc_') . '.' . $ext;
            move_uploaded_file($_FILES['video_pc']['tmp_name'], "$dir/$video_pc");
        }
        if (!empty($_FILES['video_m']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['video_m']['name'], PATHINFO_EXTENSION));
            $video_m = uniqid('vm_') . '.' . $ext;
            move_uploaded_file($_FILES['video_m']['tmp_name'], "$dir/$video_m");
        }
        $params['thumb_pc'] = $thumb_pc;
        $params['thumb_m'] = $thumb_m;
        $params['video_pc'] = $video_pc;
        $params['video_m'] = $video_m;
        $db->query(
            "INSERT INTO {$table} (top_contents_pc,top_contents_m,middle_contents_pc,middle_contents_m,bottom_contents_pc,bottom_contents_m,thumbnail_pc,thumbnail_m,media_type,video_pc,video_m,prior,wdate) VALUES (:top_pc,:top_m,:mid_pc,:mid_m,:bot_pc,:bot_m,:thumb_pc,:thumb_m,:media_type,:video_pc,:video_m,:prior,NOW())",
            $params
        );
        complete('등록되었습니다.', '/Madmin/main_slide/main_slide_list.php');
        break;
    case 'update':
        $idx = (int) $_POST['idx'];
        $row = $db->row("SELECT * FROM {$table} WHERE idx=:idx", ['idx' => $idx]);
        if (!$row)
            error('잘못된 접근입니다.');
        $sets = [
            'top_contents_pc=:top_pc',
            'top_contents_m=:top_m',
            'middle_contents_pc=:mid_pc',
            'middle_contents_m=:mid_m',
            'bottom_contents_pc=:bot_pc',
            'bottom_contents_m=:bot_m',
            'media_type=:media_type'
        ];
        $params = [
            'top_pc' => $_POST['top_contents_pc'] ?? '',
            'top_m' => $_POST['top_contents_m'] ?? '',
            'mid_pc' => $_POST['middle_contents_pc'] ?? '',
            'mid_m' => $_POST['middle_contents_m'] ?? '',
            'bot_pc' => $_POST['bottom_contents_pc'] ?? '',
            'bot_m' => $_POST['bottom_contents_m'] ?? '',
            'media_type' => $_POST['media_type'] ?? 'image',
            'idx' => $idx
        ];
        if (!empty($_FILES['thumbnail_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['thumbnail_pc']['name'], PATHINFO_EXTENSION));
            $new = uniqid('pc_') . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail_pc']['tmp_name'], "$dir/$new");
            $sets[] = 'thumbnail_pc=:thumb_pc';
            $params['thumb_pc'] = $new;
        }
        if (!empty($_FILES['thumbnail_m']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['thumbnail_m']['name'], PATHINFO_EXTENSION));
            $newm = uniqid('m_') . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail_m']['tmp_name'], "$dir/$newm");
            $sets[] = 'thumbnail_m=:thumb_m';
            $params['thumb_m'] = $newm;
        }
        if (!empty($_FILES['video_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['video_pc']['name'], PATHINFO_EXTENSION));
            $newvp = uniqid('vpc_') . '.' . $ext;
            move_uploaded_file($_FILES['video_pc']['tmp_name'], "$dir/$newvp");
            $sets[] = 'video_pc=:video_pc';
            $params['video_pc'] = $newvp;
        }
        if (!empty($_FILES['video_m']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/main_slide';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['video_m']['name'], PATHINFO_EXTENSION));
            $newvm = uniqid('vm_') . '.' . $ext;
            move_uploaded_file($_FILES['video_m']['tmp_name'], "$dir/$newvm");
            $sets[] = 'video_m=:video_m';
            $params['video_m'] = $newvm;
        }
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx=:idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', '/Madmin/main_slide/main_slide_list.php');
        break;
    case 'delete':
        $arr = array_filter(array_map('intval', explode('|', $_REQUEST['selidx'] ?? '')));
        foreach ($arr as $id) {
            $db->query("DELETE FROM {$table} WHERE idx=:id", ['id' => $id]);
        }
        complete('삭제되었습니다.', '/Madmin/main_slide/main_slide_list.php');
        break;
    case 'prior':
        // 순서 변경 (agency 로직 차용)
        $sql = " Select wp.* From {$table} wp Where 1 = 1 ";
        // 1단계 위로
        if ($posi == 'up') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 10단계 위로
        else if ($posi == 'upup') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 1단계 아래로
        else if ($posi == 'down') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 10단계 아래로
        else if ($posi == 'downdown') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        complete('순서를 변경하였습니다.', 'main_slide_list.php');
        break;
    default:
        error('잘못된 모드입니다.');
}