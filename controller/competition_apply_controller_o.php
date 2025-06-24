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

$f_user_id = isset($_SESSION['kbga_user_id']) && $_SESSION['kbga_user_id'] != '' ? $_SESSION['kbga_user_id'] : '';

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
    'f_zip'             => $filtered['f_zip'],
    'f_address1'        => $filtered['f_address1'],
    'f_address2'        => $filtered['f_address2'],
    'f_payer_name'      => $filtered['f_payer_name'],
    'f_payer_bank'      => $filtered['f_payer_bank'],
    'f_payment_category'=> $payment_cat,
    'f_user_id'         => $f_user_id
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
    f_zip,
    f_address1,
    f_address2,
    f_payer_name,
    f_payer_bank,
    f_payment_category,
    f_user_id
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
    :f_zip,
    :f_address1,
    :f_address2,
    :f_payer_name,
    :f_payer_bank,
    :f_payment_category,
    :f_user_id
)";
$db->query($sql, $params);

// 8) 결과 반환
return_json([
    'result'=>'ok',
    'msg'=>'단체 접수가 완료되었습니다.',
    'redirect'=>'/'
]);
