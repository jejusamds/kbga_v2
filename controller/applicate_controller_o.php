<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

function auto_filter_input(string $data)
{
    return SQL_Injection(RemoveXSS($data));
}

function return_json(array $ret)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

function upload_file(array $file): array
{
    $orig = $file['name'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $err = $file['error'];

    if ($err !== UPLOAD_ERR_OK) {
        return_json(['result' => 'error', 'msg' => '파일 업로드 중 오류가 발생했습니다.']);
    }
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    if (!in_array($ext, $allowed, true)) {
        return_json(['result' => 'error', 'msg' => '허용되지 않는 파일 형식입니다.']);
    }
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/registration';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $new = uniqid('', true) . '.' . $ext;
    $dest = $dir . '/' . $new;
    if (!move_uploaded_file($tmp, $dest)) {
        return_json(['result' => 'error', 'msg' => '파일 저장에 실패했습니다.']);
    }
    return ['saved' => $new, 'original' => $orig];
}

function upload_files(array $files): array
{
    $saved = [];
    if (!isset($files['name']) || !is_array($files['name'])) {
        return $saved;
    }
    $cnt = count($files['name']);
    for ($i = 0; $i < $cnt; $i++) {
        if (empty($files['name'][$i])) {
            continue;
        }
        $fileInfo = [
            'name' => $files['name'][$i],
            'tmp_name' => $files['tmp_name'][$i] ?? '',
            'size' => $files['size'][$i] ?? 0,
            'error' => $files['error'][$i] ?? UPLOAD_ERR_NO_FILE,
        ];
        $info = upload_file($fileInfo);
        $saved[] = $info['saved'];
    }
    return $saved;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$approved = ['register'];
if (empty($_POST['mode']) || !in_array($_POST['mode'], $approved, true)) {
    return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$filtered = [];
foreach ($_POST as $k => $v) {
    if (is_array($v)) {
        $filtered[$k] = array_map('auto_filter_input', $v);
    } else {
        $filtered[$k] = auto_filter_input($v);
    }
}

if (empty($filtered['csrf_token']) || $filtered['csrf_token'] !== $_SESSION['csrf_token']) {
    return_json(['result' => 'error', 'msg' => '잘못된 접근입니다 (CSRF).']);
}

$required = [
    'f_category' => '자격분야를 선택해주세요.',
    'f_item_idx' => '자격종목을 선택해주세요.',
    'f_schedule_idx' => '시험일정을 선택해주세요.',
    'f_user_name' => '단체명을 입력해주세요.',
    'f_user_name_en' => '담당자를 입력해주세요.',
    'f_tel' => '연락처를 입력해주세요.',
    'f_contact_phone' => '담당자 연락처를 입력해주세요.',
    'f_zip' => '우편번호를 입력해주세요.',
    'f_address1' => '기본주소를 입력해주세요.',
    'f_address2' => '상세주소를 입력해주세요.',
    'f_email' => '이메일을 입력해주세요.',
    'f_application_type' => '신청 구분을 선택해주세요.',
    'f_payer_name' => '입금자명을 입력해주세요.',
    'f_payer_bank' => '은행을 선택해주세요.'
];
foreach ($required as $field => $msg) {
    if ($field === 'f_schedule_idx') {
        if ($filtered[$field] === '' || $filtered[$field] === null) {
            return_json(['result' => 'blank', 'field' => $field, 'msg' => $msg]);
        }
        continue;
    }
    if (empty($filtered[$field])) {
        return_json(['result' => 'blank', 'field' => $field, 'msg' => $msg]);
    }
}
if (empty($filtered['f_payment_category']) || !is_array($filtered['f_payment_category'])) {
    return_json(['result' => 'blank', 'field' => 'f_payment_category', 'msg' => '입금 구분을 선택해주세요.']);
}
if (empty($filtered['agree_privacy'])) {
    return_json(['result' => 'blank', 'field' => 'agree_privacy', 'msg' => '개인정보 수집 및 이용에 동의해 주세요.']);
}

$payment_cat = implode(',', $filtered['f_payment_category']);

$uploadName = null;
if (!empty($_FILES['upfile']['name'])) {
    $uploaded = upload_files($_FILES['upfile']);
    if ($uploaded) {
        $uploadName = implode(',', $uploaded);
    }
}

$f_user_id = isset($_SESSION['kbga_user_id']) && $_SESSION['kbga_user_id'] != '' ? $_SESSION['kbga_user_id'] : '';

$params = [
    'f_applicant_type' => $filtered['f_applicant_type'] ?? 'P',
    'f_category' => $filtered['f_category'],
    'f_item_idx' => (int) $filtered['f_item_idx'],
    'f_schedule_idx' => (int) $filtered['f_schedule_idx'],
    'f_user_name' => $filtered['f_user_name'],
    'f_user_name_en' => $filtered['f_user_name_en'],
    'f_tel' => $filtered['f_tel'],
    'f_contact_phone' => $filtered['f_contact_phone'],
    'f_zip' => $filtered['f_zip'],
    'f_address1' => $filtered['f_address1'],
    'f_address2' => $filtered['f_address2'],
    'f_email' => $filtered['f_email'],
    'f_application_type' => $filtered['f_application_type'],
    'f_issue_file' => $uploadName,
    'f_payer_name' => $filtered['f_payer_name'],
    'f_payer_bank' => $filtered['f_payer_bank'],
    'f_payment_category' => $payment_cat,
    'f_user_id'         => $f_user_id
];

$sql = "INSERT INTO df_site_application_registration (
            f_applicant_type, f_category, f_item_idx, f_schedule_idx,
            f_user_name, f_user_name_en, f_tel, f_contact_phone,
            f_zip, f_address1, f_address2, f_email,
            f_application_type, f_issue_file,
            f_payer_name, f_payer_bank, f_payment_category, f_user_id
        ) VALUES (
            :f_applicant_type, :f_category, :f_item_idx, :f_schedule_idx,
            :f_user_name, :f_user_name_en, :f_tel, :f_contact_phone,
            :f_zip, :f_address1, :f_address2, :f_email,
            :f_application_type, :f_issue_file,
            :f_payer_name, :f_payer_bank, :f_payment_category, :f_user_id
        )";
$db->query($sql, $params);

return_json(['result' => 'ok', 'msg' => '접수가 완료되었습니다.', 'redirect' => '/']);