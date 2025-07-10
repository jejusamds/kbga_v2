<?php
// competition_apply_controller.php — 단체접수 처리 (df_site_competition_registration)
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'].'/inc/util_lib.inc';

function auto_filter_input(string $data) {
    return SQL_Injection(RemoveXSS($data));
}

function return_json(array $ret) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

function upload_file(array $file): array {
    $orig = $file['name'];
    $tmp  = $file['tmp_name'];
    $err  = $file['error'];
    if ($err !== UPLOAD_ERR_OK) {
        return_json(['result' => 'error', 'msg' => '파일 업로드 중 오류가 발생했습니다.']);
    }
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    // $allowed = ['jpg','jpeg','png','gif','pdf'];
    // if (!in_array($ext, $allowed, true)) {
    //     return_json(['result' => 'error', 'msg' => '허용되지 않는 파일 형식입니다.']);
    // }
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/competition';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $new  = uniqid('', true) . '.' . $ext;
    $dest = $dir . '/' . $new;
    if (!move_uploaded_file($tmp, $dest)) {
        return_json(['result' => 'error', 'msg' => '파일 저장에 실패했습니다.']);
    }
    return ['saved' => $new, 'original' => $orig];
}

function upload_files(array $files): array {
    $list = [];
    if (!isset($files['name']) || !is_array($files['name'])) {
        return $list;
    }
    $cnt = count($files['name']);
    for ($i = 0; $i < $cnt; $i++) {
        if (empty($files['name'][$i])) {
            continue;
        }
        $info = [
            'name' => $files['name'][$i],
            'tmp_name' => $files['tmp_name'][$i] ?? '',
            'error' => $files['error'][$i] ?? UPLOAD_ERR_NO_FILE,
        ];
        $list[] = upload_file($info);
    }
    return $list;
}

// 1) POST + mode 체크
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['mode'] ?? '') !== 'register') {
    return_json(['result'=>'error','msg'=>'잘못된 요청입니다.']);
}

// 2) 입력값 필터링
$filtered = [];
foreach ($_POST as $k => $v) {
    $filtered[$k] = is_array($v)
        ? array_map('auto_filter_input', $v)
        : auto_filter_input($v);
}

// 3) CSRF 토큰 검증
if (empty($filtered['csrf_token']) || $filtered['csrf_token'] !== $_SESSION['csrf_token']) {
    return_json(['result'=>'error','msg'=>'잘못된 접근입니다 (CSRF).']);
}

// 4) 필수 입력 검증
$required = [
    'f_competition_idx' => '대회구분을 선택해주세요.',
    'f_part'            => '참가부문을 선택해주세요.',
    'f_field'           => '종목분야를 선택해주세요.',
    'f_event'           => '참가종목을 선택해주세요.',
    'f_user_name'       => '단체명을 입력해주세요.',
    //'f_gender'          => '성별을 선택해주세요.',
    'f_user_name_en'    => '담당자명을 입력해주세요.',
    //'f_birth_date'      => '생년월일을 입력해주세요.',
    'f_tel'             => '연락처를 입력해주세요.',
    'f_email'           => '이메일을 입력해주세요.',
    'f_zip'             => '우편번호를 입력해주세요.',
    'f_address1'        => '기본주소를 입력해주세요.',
    'f_address2'        => '상세주소를 입력해주세요.',
    'f_payer_name'      => '입금자명을 입력해주세요.',
    'f_payer_bank'      => '은행을 선택해주세요.'
];
foreach ($required as $field => $msg) {
    if (empty($filtered[$field])) {
        return_json(['result'=>'blank','field'=>$field,'msg'=>$msg]);
    }
}
if (empty($filtered['f_payment_category']) || !is_array($filtered['f_payment_category'])) {
    return_json([
        'result'=>'blank',
        'field'=>'f_payment_category',
        'msg'=>'입금 구분을 선택해주세요.'
    ]);
}
if (empty($filtered['agree_terms']) || empty($filtered['agree_privacy'])) {
    return_json([
        'result'=>'blank',
        'field'=>'agree_terms',
        'msg'=>'약관에 동의해주세요.'
    ]);
}

// 5) 데이터 가공
$birth_date  = str_replace('.', '-', $filtered['f_birth_date']); // 'YYYY-MM-DD'
$payment_cat = implode(',', $filtered['f_payment_category']);    // checkbox → comma list

$uploadSaved = [];
$uploadOriginal = [];
if (!empty($_FILES['upfile']['name'])) {
    $uploaded = upload_files($_FILES['upfile']);
    if ($uploaded) {
        $uploadSaved = array_merge($uploadSaved, array_column($uploaded, 'saved'));
        $uploadOriginal = array_merge($uploadOriginal, array_column($uploaded, 'original'));
    }
}
$uploadName = $uploadSaved ? implode(',', $uploadSaved) : null;
$uploadOrig = $uploadOriginal ? implode(',', $uploadOriginal) : null;

$f_user_id = isset($_SESSION['kbga_user_id']) && $_SESSION['kbga_user_id'] != '' ? $_SESSION['kbga_user_id'] : '';

$sql = "select f_part from df_site_competition_part where idx = :idx";
$db->bind("idx", $filtered['f_part']);
$part_title = $db->single($sql);

$sql = "select f_field from df_site_competition_field where idx = :idx";
$db->bind("idx", $filtered['f_field']);
$field_title = $db->single($sql);

$sql = "select f_event from df_site_competition_event where idx = :idx";
$db->bind("idx", $filtered['f_event']);
$event_title = $db->single($sql);

// 6) 바인딩 파라미터 준비
$params = [
    'f_competition_idx' => (int)$filtered['f_competition_idx'],
    'f_part'            => $filtered['f_part'],
    'f_field'           => $filtered['f_field'],
    'f_event'           => $filtered['f_event'],
    'f_user_name'       => $filtered['f_user_name'],
    'f_gender'          => $filtered['f_gender'],
    'f_user_name_en'    => $filtered['f_user_name_en'],
    'f_birth_date'      => $birth_date,
    'f_tel'             => $filtered['f_tel'],
    'f_email'           => $filtered['f_email'],
    'f_issue_file'      => $uploadName,
    'f_issue_file_name' => $uploadOrig,
    'f_zip'             => $filtered['f_zip'],
    'f_address1'        => $filtered['f_address1'],
    'f_address2'        => $filtered['f_address2'],
    'f_payer_name'      => $filtered['f_payer_name'],
    'f_payer_bank'      => $filtered['f_payer_bank'],
    'f_payment_category'=> $payment_cat,
    'f_user_id'         => $f_user_id,
    'f_part_title'      => $part_title,
    'f_field_title'     => $field_title,
    'f_event_title'     => $event_title,
    'f_contact_phone'   => $filtered['f_contact_phone'] ?? ''
];

// 7) INSERT 쿼리 실행
$sql = "
INSERT INTO df_site_competition_registration (
    f_applicant_type,
    f_competition_idx,
    f_part,
    f_field,
    f_event,
    f_user_name,
    f_gender,
    f_user_name_en,
    f_birth_date,
    f_tel,
    f_email,
    f_issue_file,
    f_issue_file_name,
    f_zip,
    f_address1,
    f_address2,
    f_payer_name,
    f_payer_bank,
    f_payment_category,
    f_user_id,
    f_part_title,
    f_field_title,
    f_event_title,
    f_contact_phone
) VALUES (
    'O',
    :f_competition_idx,
    :f_part,
    :f_field,
    :f_event,
    :f_user_name,
    :f_gender,
    :f_user_name_en,
    :f_birth_date,
    :f_tel,
    :f_email,
    :f_issue_file,
    :f_issue_file_name,
    :f_zip,
    :f_address1,
    :f_address2,
    :f_payer_name,
    :f_payer_bank,
    :f_payment_category,
    :f_user_id,
    :f_part_title,
    :f_field_title,
    :f_event_title,
    :f_contact_phone
)";
$db->query($sql, $params);

// 8) 결과 반환
return_json([
    'result'=>'ok',
    'msg'=>'단체 접수가 완료되었습니다.',
    'redirect'=>'/'
]);