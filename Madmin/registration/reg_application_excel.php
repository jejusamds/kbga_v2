<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$filename = iconv('UTF-8', 'EUC-KR', "자격시험신청_" . date('Ymd') . ".xls");

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

$list = $db->query("SELECT * FROM df_site_application_registration ORDER BY idx DESC");

echo "<table border='1'>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>분야</th>";
echo "<th>이름</th>";
echo "<th>신청구분</th>";
echo "<th>연락처</th>";
echo "<th>이메일</th>";
echo "</tr>";

$no = count($list);
foreach ($list as $row) {
    $category = $category_map[$row['f_category']] ?? '';
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . safeAdminOutput($category) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_user_name']) . "</td>";
    echo "<td>" . safeAdminOutput($application_type_map[$row['f_application_type']]) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_tel']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_email']) . "</td>";
    echo "</tr>";
    $no--;
}

echo "</table>";
?>