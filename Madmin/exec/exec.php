<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

// 공통 파라미터 처리
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$table = isset($_REQUEST['table']) ? trim($_REQUEST['table']) : '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
$usage = isset($_REQUEST['usage']) ? trim($_REQUEST['usage']) : '';
$region = isset($_REQUEST['region']) ? trim($_REQUEST['region']) : '';
$selidx = isset($_REQUEST['selidx']) ? $_REQUEST['selidx'] : '';
$field = isset($_REQUEST['field']) ? trim($_REQUEST['field']) : '';

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

// 테이블/디렉터리 매핑
$dir_arr = [
    'sigong' => 'business',
    'sihang' => 'business'
    ,'competition' => 'competition'
    ,'member' => 'member'
];
$dir = isset($dir_arr[$table]) ? $dir_arr[$table] : '';

if (empty($table)) {
    error("잘못된 테이블명입니다.");
    exit;
}

// 세션에 POST 데이터 저장 (insert/update 롤백 대비)
if ($mode === 'insert' || $mode === 'update') {
    $_SESSION['post_data'] = $_POST;
}

// POST 변수 이스케이프
foreach ($_POST as $key => $val) {
    if (is_string($val)) {
        ${$key} = addslashes(trim($val));
    }
}

// 파일 업로드 처리: 테이블별 userfiles 디렉터리
$upload_fields = [];
if ($mode === 'insert' || $mode === 'update') {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $table;
    foreach ($_FILES as $field_name => $fileinfo) {
        if (isset($fileinfo['tmp_name']) && $fileinfo['tmp_name'] && $fileinfo['error'] === UPLOAD_ERR_OK) {
            $orig_name = $fileinfo['name'];
            $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
            // 이미지 파일만 허용 예시
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                error("이미지 파일만 업로드 가능합니다.");
                exit;
            }
            // 유니크 파일명 생성 (원본 이름 유지가 필요하다면 별도 구현)
            $uniq_name = $field_name . "_" . time() . "." . $ext;
            $save_path = $upload_dir . "/" . $uniq_name;

            if (move_uploaded_file($fileinfo['tmp_name'], $save_path)) {
                // DB에는 “파일명”만 저장
                $upload_fields[$field_name] = $uniq_name;
            } else {
                error("파일 업로드 중 오류가 발생했습니다.");
                exit;
            }
        }
    }
}

switch ($mode) {
    case 'insert':
        // INSERT 공통 로직
        $fields = [];
        // 파일컬럼(value: 파일명) 추가
        foreach ($upload_fields as $col => $filename) {
            $fields[] = "{$col}='" . addslashes($filename) . "'";
        }
        foreach ($_POST as $key => $val) {
            if (in_array($key, ['mode', 'table', 'page', 'usage', 'region', 'idx', 'selidx', 'dir', 'field', 'keyword', 'old_idx']))
                continue;
            $fields[] = "{$key}='" . addslashes($val) . "'";
        }
        $fields[] = "wdate=NOW()";
        $sql = "INSERT INTO df_site_{$table} SET " . implode(", ", $fields);
        $db->query($sql);

        $bbsidx = $db->lastInsertId();

        if ($table == 'sigong') {
            // 3. upfile[] 업로드 처리
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/" . $table;

            $cnt = isset($_FILES['upfile']['name']) ? count($_FILES['upfile']['name']) : 0;
            for ($i = 0; $i < $cnt; $i++) {
                if (isset($_FILES['upfile']['tmp_name'][$i]) && $_FILES['upfile']['tmp_name'][$i] && $_FILES['upfile']['error'][$i] === UPLOAD_ERR_OK) {
                    $orig_name = $_FILES['upfile']['name'][$i];
                    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
                    $uniq_name = 'upfile_' . $table . '_' . time() . '_' . $i . "." . $ext;
                    $save_path = $upload_dir . "/" . $uniq_name;

                    if (move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $save_path)) {
                        // 파일 정보 df_site_sigong_files에 저장
                        $sql_file = "INSERT INTO df_site_{$table}_files SET 
                                        bbsidx = '{$bbsidx}',
                                        upfile = '{$uniq_name}',
                                        upfile_name = '{$orig_name}'";
                        $db->query($sql_file);
                    }
                }
            }
        }

        unset($_SESSION['post_data']);
        complete(
            "저장되었습니다.",
            "/Madmin/{$dir}/{$table}_list.php?page={$page}&usage={$usage}&region={$region}"
        );
        break;

    case 'update':
        // 1. 메인 테이블 업데이트 (파일컬럼은 별도 파일테이블에서 처리)
        $idx = isset($_POST['idx']) ? (int) $_POST['idx'] : 0;
        if ($idx <= 0) {
            error("잘못된 IDX입니다.");
            exit;
        }
        $fields = [];
        foreach ($_POST as $key => $val) {
            if (in_array($key, ['mode', 'table', 'page', 'usage', 'region', 'idx', 'selidx', 'dir', 'field', 'keyword', 'old_idx'])) {
                continue;
            }
            $fields[] = "{$key}='" . addslashes(trim($val)) . "'";
        }
        $sql = "UPDATE df_site_{$table} 
                       SET " . implode(", ", $fields) . " 
                     WHERE idx={$idx}";
        $db->query($sql);

        // 2. 다중 upfile 처리: old_idx[], upfile[]
        if (
            $table === 'sigong'
            && isset($_FILES['upfile']['tmp_name'])
            && is_array($_FILES['upfile']['tmp_name'])
        ) {
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$table}";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0707, true);
            }

            $cnt = count($_FILES['upfile']['tmp_name']);
            $oldIdxArr = isset($_POST['old_idx']) && is_array($_POST['old_idx'])
                ? $_POST['old_idx']
                : [];

            for ($i = 0; $i < $cnt; $i++) {
                // 새로 업로드된 파일이 있는 경우에만 처리
                if (
                    isset($_FILES['upfile']['tmp_name'][$i])
                    && $_FILES['upfile']['tmp_name'][$i]
                    && $_FILES['upfile']['error'][$i] === UPLOAD_ERR_OK
                ) {
                    $orig_name = $_FILES['upfile']['name'][$i];
                    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
                    $uniq_name = 'upfile_' . $table . '_' . time() . "_{$i}.{$ext}";
                    $save_path = "{$upload_dir}/{$uniq_name}";

                    if (move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $save_path)) {
                        $oldIdx = isset($oldIdxArr[$i]) ? (int) $oldIdxArr[$i] : 0;

                        if ($oldIdx > 0) {
                            // 기존 파일 삭제
                            $oldFile = $db->single(
                                "SELECT upfile 
                                       FROM df_site_{$table}_files 
                                      WHERE idx=:oidx",
                                ['oidx' => $oldIdx]
                            );
                            if ($oldFile) {
                                @unlink("{$upload_dir}/{$oldFile}");
                            }
                            // UPDATE 기존 레코드
                            $db->query(
                                "UPDATE df_site_{$table}_files
                                        SET upfile      = :new,
                                            upfile_name = :orig
                                      WHERE idx         = :oidx",
                                [
                                    'new' => $uniq_name,
                                    'orig' => $orig_name,
                                    'oidx' => $oldIdx
                                ]
                            );
                        } else {
                            // INSERT 신규 레코드
                            $db->query(
                                "INSERT INTO df_site_{$table}_files
                                        SET bbsidx      = :bbs,
                                            upfile      = :new,
                                            upfile_name = :orig",
                                [
                                    'bbs' => $idx,
                                    'new' => $uniq_name,
                                    'orig' => $orig_name
                                ]
                            );
                        }
                    }
                }
                // else: 파일 선택 안 됐으면 기존 old_idx만 유지(삭제 안 함)
            }
        }

        unset($_SESSION['post_data']);
        complete(
            "수정되었습니다.",
            "/Madmin/{$dir}/{$table}_list.php?page={$page}&usage={$usage}&region={$region}"
        );
        break;


    case 'delete':
        // DELETE 공통 로직
        $arr = explode("|", $selidx);
        foreach ($arr as $id) {
            $id = (int) $id;
            if ($id > 0) {
                // DB에 저장된 파일명만 읽어와 실제 경로에서 삭제
                $row = $db->row("SELECT * FROM df_site_{$table} WHERE idx={$id}");
                foreach ($row as $col => $val) {
                    if (strpos($col, 'f_') === 0 && !empty($val)) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$table}/" . $val);
                    }
                }
                $sql = "DELETE FROM df_site_{$table} WHERE idx={$id}";
                $db->query($sql);
            }
        }
        complete(
            "삭제되었습니다.",
            "/Madmin/{$dir}/{$table}_list.php?page={$page}&usage={$usage}&region={$region}"
        );
        break;

    case 'delimg':
        // 단일 이미지 컬럼 삭제
        $idx = isset($_REQUEST['idx']) ? (int) $_REQUEST['idx'] : 0;
        if ($idx <= 0) {
            echo "N_idx";
            exit;
        }
        // DB에서 파일명만 조회
        $row = $db->row("SELECT upfile, upfile_name FROM df_site_{$table}_files WHERE idx = {$idx}");
        if ($row && !empty($row['upfile'])) {
            // 실제 파일 삭제: /userfiles/{table}/{filename}
            @unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/{$table}/" . $row['upfile']);
            $sql = " Delete From df_site_{$table}_files Where idx = '" . $idx . "' ";
            $db->query($sql);
            echo "Y";
        } else {
            echo "N_db";
        }
        break;

    default:
        error("잘못된 모드입니다.");
        break;
}
?>