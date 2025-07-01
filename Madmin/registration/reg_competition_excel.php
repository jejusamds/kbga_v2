<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$filename = iconv('UTF-8', 'EUC-KR', "대회신청_" . date('Ymd') . ".xls");

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

$list = $db->query("SELECT * FROM df_site_competition_registration ORDER BY idx DESC");

echo "<table border='1'>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>참가분야</th>";
echo "<th>이름</th>";
echo "<th>연락처</th>";
echo "<th>이메일</th>";
echo "</tr>";

$no = count($list);
foreach ($list as $row) {
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . safeAdminOutput($row['f_field']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_user_name']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_tel']) . "</td>";
    echo "<td>" . safeAdminOutput($row['f_email']) . "</td>";
    echo "</tr>";
    $no--;
}

echo "</table>";
?>