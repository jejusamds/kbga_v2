<?php

include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$filename = iconv('UTF-8', 'EUC-KR', "회원정보[" . date('Ymd') . "].xls");

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Content-charset=utf-8");
header("Content-type: application/vnd.ms-excel;");
header("Content-Description: PHP4 Generated Data");

echo "<html xmlns:v=\"urn:schemas-microsoft-com:vml\" 
	xmlns:o=\"urn:schemas-microsoft-com:office:office\" 
	xmlns:x=\"urn:schemas-microsoft-com:office:excel\" 
	xmlns=\"http://www.w3.org/TR/REC-html40\">";
echo "<head>\n";
echo "<style>\n";
echo ".xl40\n";
echo "        {mso-style-parent:style0;\n";
echo "        mso-number-format:'0_ ';\n";
echo "        text-align:center;\n";
echo "        border:.5pt solid black;\n";
echo "        background:white;\n";
echo "        mso-pattern:auto none;\n";
echo "        white-space:normal;}\n";
echo "-->\n";
echo "</style>\n";
echo "</head>\n";

$today = date('n-d');
$toyear = date('Y');

$age_syear = substr($toyear - ($age + 9), -2) + 1;
$age_eyear = substr($toyear - $age, -2) + 2;

$join_sdate = $prev_year . "-" . $prev_month . "-" . $prev_day;
$join_edate = $next_year . "-" . $next_month . "-" . $next_day;


$sql = "select * from ez_member where id!='' ";
if ($prev_year != "")
    $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate'";
if ($searchopt != "")
    $sql .= " and $searchopt like '%$keyword%'";
if ($birthday == "Y")
    $sql .= " and birthday like '%$today'";
if ($memorial == "Y")
    $sql .= " and memorial like '%$today'";
if ($age != "")
    $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
if ($address != "")
    $sql .= " and address like '%$address%'";
if ($job != "")
    $sql .= " and job = '$job'";
if ($marriage != "")
    $sql .= " and marriage = '$marriage'";
if ($sleep == "Y")
    $sql .= " and DATEDIFF(NOW(), visit_time) > 365 and DATEDIFF(NOW(), wdate) > 365";
$sql .= " order by wdate desc";
$result = mysql_query($sql) or error(mysql_error());

echo "<body>\n";
echo "<table border=1>\n";
echo "  <tr align=center style=font-weight:bold>\n";
if ($c_id == "Y")
    echo "<td bgcolor=#C0C0C0>아이디</td>\n";
if ($c_passwd == "Y")
    echo "<td bgcolor=#C0C0C0>비밀번호</td>\n";
if ($c_name == "Y")
    echo "<td bgcolor=#C0C0C0>이름</td>\n";
if ($c_resno == "Y")
    echo "<td bgcolor=#C0C0C0>주민번호</td>\n";
if ($c_email == "Y")
    echo "<td bgcolor=#C0C0C0>이메일</td>\n";
if ($c_tphone == "Y")
    echo "<td bgcolor=#C0C0C0>전화번호</td>\n";
if ($c_hphone == "Y")
    echo "<td bgcolor=#C0C0C0>휴대폰</td>\n";
if ($c_level == "Y")
    echo "<td bgcolor=#C0C0C0>회원등급</td>\n";
if ($c_post == "Y")
    echo "<td bgcolor=#C0C0C0>우편번호</td>\n";
if ($c_address == "Y")
    echo "<td bgcolor=#C0C0C0>주소</td>\n";
if ($c_recom == "Y")
    echo "<td bgcolor=#C0C0C0>추천인</td>\n";
if ($c_reemail == "Y")
    echo "<td bgcolor=#C0C0C0>이메일 수신</td>\n";
if ($c_resms == "Y")
    echo "<td bgcolor=#C0C0C0>SMS수신</td>\n";
echo "   </tr>";

while ($row = mysql_fetch_object($result)) {

    echo "<tr>\n";
    if ($c_id == "Y")
        echo "<td>$row->id</td>\n";
    if ($c_passwd == "Y")
        echo "<td>$row->passwd</td>\n";
    if ($c_name == "Y")
        echo "<td>$row->name</td>\n";
    if ($c_resno == "Y")
        echo "<td>$row->resno</td>\n";
    if ($c_email == "Y")
        echo "<td>$row->email</td>\n";
    if ($c_tphone == "Y")
        echo "<td>$row->tphone</td>\n";
    if ($c_hphone == "Y")
        echo "<td>$row->hphone</td>\n";
    if ($c_level == "Y")
        echo "<td>$row->level</td>\n";
    if ($c_post == "Y")
        echo "<td>$row->post</td>\n";
    if ($c_address == "Y")
        echo "<td>$row->address $row->address2</td>\n";
    if ($c_recom == "Y")
        echo "<td>$row->recom</td>\n";
    if ($c_reemail == "Y")
        echo "<td>$row->reemail</td>\n";
    if ($c_resms == "Y")
        echo "<td>$row->resms</td>\n";
    echo "   </tr>";

}

echo "</table>\n";
echo "</body>\n";
echo "</html>\n";

