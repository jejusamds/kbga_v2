<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

$sql = "SELECT t1.*, t2.f_item_name,
        s.f_year, s.f_round, s.f_type
        FROM df_site_application_registration t1
        LEFT JOIN df_site_qualification_item t2 ON t1.f_item_idx = t2.idx
        LEFT JOIN df_site_application s ON t1.f_schedule_idx = s.idx
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

function printSchedule(array $row)
{
    if (!empty($row['f_year'])) {
        return printValue(sprintf('%s년 %s회차 %s', $row['f_year'], $row['f_round'], $row['f_type']));
    }
    if ((int)$row['f_schedule_idx'] === 0) {
        return '상시접수';
    }
    return printValue($row['f_schedule_idx']);
}

$category_map = [
    'makeup'  => '메이크업',
    'nail'    => '네일',
    'hair'    => '헤어',
    'skin'    => '피부',
    'half'    => '반영구',
    'foreign' => '해외인증',
    'teacher' => '강사인증',
];

$filename = iconv('UTF-8', 'EUC-KR', "자격시험신청_상세_{$idx}.xls");

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

echo "<table border='1'>";

$rows = [
    '신청자구분'     => printType($row['f_applicant_type']),
    '자격분야'       => printValue($category_map[$row['f_category']]),
    '자격종목'       => printValue($row['f_item_name']),
    '시험일정'       => printSchedule($row),
    '이름'           => printValue($row['f_user_name']),
    '영문이름'       => printValue($row['f_user_name_en']),
    '연락처'         => printValue($row['f_tel']),
    '담당자 연락처'  => printValue($row['f_contact_phone']),
    '생년월일'       => printValue($row['f_birth_date']),
    '우편번호'       => printValue($row['f_zip']),
    '기본주소'       => printValue($row['f_address1']),
    '상세주소'       => printValue($row['f_address2']),
    '이메일'         => printValue($row['f_email']),
    '신청구분'       => printValue($row['f_application_type']),
    '발급희망 여부'  => printValue($row['f_issue_desire']),
    '사진첨부'       => printValue($row['f_issue_file']),
    '입금자명'       => printValue($row['f_payer_name']),
    '은행'           => printValue($row['f_payer_bank']),
    '입금 구분'      => printValue($row['f_payment_category']),
    '회원ID'         => printValue($row['f_user_id']),
    '등록일'         => printValue($row['reg_date'])
];

foreach ($rows as $label => $value) {
    echo "<tr><td>{$label}</td><td>{$value}</td></tr>";
}

echo "</table>";
?>