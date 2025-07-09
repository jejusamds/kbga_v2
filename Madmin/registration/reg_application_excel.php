<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$filename = iconv('UTF-8', 'EUC-KR', "자격시험신청_" . date('Ymd') . ".xls");
$status = $_GET['status'] ?? '';

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

$category_map = [
    'makeup'  => '메이크업',
    'nail'    => '네일',
    'hair'    => '헤어',
    'skin'    => '피부',
    'half'    => '반영구',
    'foreign' => '해외인증',
    'teacher' => '강사인증',
];

$application_type_map = [
    'exam' => '시헙 접수',
    'cert' => '자격증 발급',
];

$status_map = [
    'ing'   => '접수중',
    'done'  => '완료',
    'cancle' => '취소',
    'hold'  => '보류',
];

$where = '';
$params = [];
if ($status !== '') {
    $where = 'WHERE t1.f_applicant_status = :status';
    $params['status'] = $status;
}

$list = $db->query("SELECT t1.*, t2.f_item_name, s.f_year, s.f_round, s.f_type
        FROM df_site_application_registration t1
        LEFT JOIN df_site_qualification_item t2 ON t1.f_item_idx = t2.idx
        LEFT JOIN df_site_application s ON t1.f_schedule_idx = s.idx
        {$where}
        ORDER BY t1.idx DESC", $params);

echo "<table border='1'>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>분야</th>";
echo "<th>자격종목</th>";
echo "<th>시험일정</th>";
echo "<th>이름</th>";
echo "<th>신청구분</th>";
echo "<th>연락처</th>";
echo "<th>이메일</th>";
echo "<th>상태</th>";
echo "<th>등록일</th>";
echo "</tr>";

$no = count($list);
foreach ($list as $row) {
    $category = $category_map[$row['f_category']] ?? '';
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . safeAdminOutput($category) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_item_name']) . "</td>";
    if (!empty($row['f_year'])) {
        $schedule = sprintf('%s년 %s회차 %s', $row['f_year'], $row['f_round'], $row['f_type']);
    } elseif ((int)$row['f_schedule_idx'] === 0) {
        $schedule = '상시접수';
    } else {
        $schedule = $row['f_schedule_idx'];
    }
    echo "<td>" . safeAdminOutput($schedule) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_user_name']) . "</td>";
    echo "<td>" . safeAdminOutput($application_type_map[$row['f_application_type']]) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_tel']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_email']) . "</td>";
    echo "<td>" . safeAdminOutput($status_map[$row['f_applicant_status']] ?? $row['f_applicant_status']) . "</td>";
    echo "<td>" . safeAdminOutput($row['wdate']) . "</td>";
    echo "</tr>";
    $no--;
}

echo "</table>";?>