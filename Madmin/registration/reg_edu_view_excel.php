<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;

$sql = "SELECT t1.*, b.subject
        FROM df_site_edu_registration t1
        LEFT JOIN df_site_bbs b ON t1.f_news_idx = b.idx
        WHERE t1.idx = '{$idx}'";
$row = $db->row($sql);
if (!$row) {
    die('잘못된 접근입니다.');
}

function printValue($val)
{
    return nl2br(safeAdminOutput($val));
}

function printType($val)
{
    switch ($val) {
        case 'P':
            return '개인';
        case 'O':
            return '단체';
        default:
            return safeAdminOutput($val);
    }
}

$filename = iconv('UTF-8', 'EUC-KR', "교육신청_상세_{$idx}.xls");

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

echo "<table border='1'>";

$rows = [
    '구분' => printType($row['f_type']),
    '교육구분' => printValue($row['f_edu_type_title']),
    '이름/기관명' => printValue($row['f_user_name']),
    '영문이름/담당자' => printValue($row['f_user_name_en']),
    '성별' => printValue($row['f_gender']),
    '생년월일' => printValue($row['f_birth_date']),
    '연락처' => printValue($row['f_tel']),
    '담당자 연락처' => printValue($row['f_contact_phone']),
    '우편번호' => printValue($row['f_zip']),
    '기본주소' => printValue($row['f_address1']),
    '상세주소' => printValue($row['f_address2']),
    '이메일' => printValue($row['f_email']),
    '파일' => printValue($row['f_issue_file']),
    //'파일명' => printValue($row['f_issue_file_name']),
    '입금자명' => printValue($row['f_payer_name']),
    '은행' => printValue($row['f_payer_bank']),
    '입금 구분' => printValue($row['f_payment_category']),
    '회원ID' => printValue($row['f_user_id']),
    '등록일' => printValue($row['reg_date'])
];

foreach ($rows as $label => $value) {
    echo "<tr><td>{$label}</td><td>{$value}</td></tr>";
}

echo "</table>";
?>