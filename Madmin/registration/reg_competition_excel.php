<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$filename = iconv('UTF-8', 'EUC-KR', "대회신청_" . date('Ymd') . ".xls");
$status = $_GET['status'] ?? '';

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

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

$list = $db->query("SELECT t1.*, t2.f_title
        FROM df_site_competition_registration t1
        LEFT JOIN df_site_competition t2 ON t1.f_competition_idx = t2.idx
        {$where}
        ORDER BY t1.idx DESC", $params);

echo "<table border='1'>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>대회명</th>";
echo "<th>참가부문</th>";
echo "<th>참가분야</th>";
echo "<th>참가종목</th>";
echo "<th>이름</th>";
echo "<th>연락처</th>";
echo "<th>이메일</th>";
echo "<th>상태</th>";
echo "<th>등록일</th>";
echo "</tr>";

$no = count($list);
foreach ($list as $row) {
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . safeAdminOutput($row['f_title']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_part']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_field']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_event']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_user_name']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_tel']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_email']) . "</td>";
    echo "<td>" . safeAdminOutput($status_map[$row['f_applicant_status']] ?? $row['f_applicant_status']) . "</td>";
    echo "<td>" . safeAdminOutput($row['reg_date']) . "</td>";
    echo "</tr>";
    $no--;
}

echo "</table>";?>