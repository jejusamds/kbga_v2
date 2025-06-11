<?php
$upfile_path = "../../userfiles/" . $code;      // 업로드파일 위치
$upfile_idx = date('Ymdhis') . rand(1, 9);     // 업로드파일명

if (!is_dir($upfile_path)) {
    echo exec("mkdir $upfile_path");
    exec("chmod 777 $upfile_path");
}

if (
    isset($_FILES['upfile'])
    && isset($_FILES['upfile']['tmp_name'])
    && is_array($_FILES['upfile']['tmp_name'])
) {
    // tmp_name 배열의 길이만큼 반복
    for ($i = 0; $i < count($_FILES['upfile']['tmp_name']); $i++) {
        // 실제 업로드된 파일인지 확인
        if (!empty($_FILES['upfile']['tmp_name'][$i])) {
            $upfile_name_tmp = $_FILES['upfile']['name'][$i];
            $ext = strtolower(
                substr($upfile_name_tmp, (strrpos($upfile_name_tmp, '.') + 1))
            );
            $upfile_tmp = $upfile_idx . "_" . $i . "." . $ext;
            $save_dir = sprintf('%s/%s', $upfile_path, $upfile_tmp);

            if (move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $save_dir)) {
                if (!empty($old_idx[$i])) {
                    // 기존 파일이 있으면 삭제 후 업데이트
                    $sql = "SELECT upfile 
                              FROM df_site_bbs_files 
                             WHERE idx='" . $old_idx[$i] . "'";
                    $upfile_old = $db->single($sql);
                    if ($upfile_old) {
                        @unlink($upfile_path . "/" . $upfile_old);
                    }

                    $sql = "";
                    $sql .= "UPDATE df_site_bbs_files
                                 SET bbsidx      = '" . $bbsidx . "',
                                     upfile      = '" . $upfile_tmp . "',
                                     upfile_name = '" . $upfile_name_tmp . "'
                               WHERE idx         = '" . $old_idx[$i] . "'";
                    $db->query($sql);
                } else {
                    // 새 파일 인서트
                    $sql = "";
                    $sql .= "INSERT INTO df_site_bbs_files
                                 SET bbsidx      = '" . $bbsidx . "',
                                     upfile      = '" . $upfile_tmp . "',
                                     upfile_name = '" . $upfile_name_tmp . "'";
                    $db->query($sql);
                }
            }
        }
    }
}
?>