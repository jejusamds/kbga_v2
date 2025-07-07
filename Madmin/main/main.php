<?php include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>

<?php

// 오늘의 통계현황 - 총 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member where f_member_type = 'P' ";
$memberTotal = $db->single($sql);
// 오늘의 통계현황 - 금일 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') and  f_member_type = 'P' ";
$memberToday = $db->single($sql);
// 오늘의 통계현황 - 10일간 가입회원
$memberDay = "";
for ($i = 9; $i >= 0; $i--) {
    $selectedDate = date("Y-m-d", strtotime("-" . $i . " days"));
    $sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = '" . $selectedDate . "' ";
    $cnt = $db->single($sql);
    if ($memberDay != "")
    $memberDay .= ",";
$memberDay .= $cnt;
}

// 오늘의 통계현황 - 총 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member where f_member_type = 'O' ";
$memberTotal_o = $db->single($sql);
// 오늘의 통계현황 - 금일 가입회원
$sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') and  f_member_type = 'O' ";
$memberToday_o = $db->single($sql);
// 오늘의 통계현황 - 10일간 가입회원
$memberDay_o = "";
for ($i = 9; $i >= 0; $i--) {
    $selectedDate = date("Y-m-d", strtotime("-" . $i . " days"));
    $sql = " Select IFNULL(COUNT(*),0) From df_site_member Where DATE_FORMAT(wdate,'%Y-%m-%d') = '" . $selectedDate . "' ";
    $cnt = $db->single($sql);
    if ($memberDay_o != "")
    $memberDay_o .= ",";
$memberDay_o .= $cnt;
}

// 오늘의 통계현황 - 총 신청건수
$sql = "
    SELECT
        (SELECT COUNT(*) FROM df_site_competition_registration) +
        (SELECT COUNT(*) FROM df_site_application_registration) +
        (SELECT COUNT(*) FROM df_site_edu_registration)
";
$qnaTotal = $db->single($sql);

// 오늘의 통계현황 - 금일 신청건수
$sql = "
    SELECT
        (SELECT COUNT(*) FROM df_site_competition_registration WHERE DATE_FORMAT(reg_date,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')) +
        (SELECT COUNT(*) FROM df_site_application_registration WHERE DATE_FORMAT(wdate,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')) +
        (SELECT COUNT(*) FROM df_site_edu_registration WHERE DATE_FORMAT(reg_date,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d'))
";
$qnaToday = $db->single($sql);

// 오늘의 통계현황 - 10일간 신청건수
$qnaDay = "";
for ($i = 9; $i >= 0; $i--) {
    $selectedDate = date("Y-m-d", strtotime("-{$i} days"));
    $sql = "
        SELECT
            (SELECT COUNT(*) FROM df_site_competition_registration WHERE DATE_FORMAT(reg_date,'%Y-%m-%d') = '{$selectedDate}') +
            (SELECT COUNT(*) FROM df_site_application_registration WHERE DATE_FORMAT(wdate,'%Y-%m-%d') = '{$selectedDate}') +
            (SELECT COUNT(*) FROM df_site_edu_registration WHERE DATE_FORMAT(reg_date,'%Y-%m-%d') = '{$selectedDate}')
    ";
    $cnt = $db->single($sql);
    if ($qnaDay !== "") $qnaDay .= ",";
    $qnaDay .= $cnt;
}

// 오늘의 방문현황 - PC 총 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' ";
$visitPcTotal = $db->single($sql);
// 오늘의 방문현황 - PC 금일 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' And ci_yy = DATE_FORMAT(now(), '%y') And ci_mm = DATE_FORMAT(now(), '%c') And ci_dd = DATE_FORMAT(now(), '%e') ";
$visitPcToday = $db->single($sql);
// 오늘의 방문현황 - PC 10일간 방문수
$visitPcDay = "";
for ($i = 9; $i >= 0; $i--) {
    $selectedYear = date("y", strtotime("-" . $i . " days"));
    $selectedMonth = date("n", strtotime("-" . $i . " days"));
    $selectedDay = date("j", strtotime("-" . $i . " days"));
    $sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'P' And ci_yy = '" . $selectedYear . "' And ci_mm = '" . $selectedMonth . "' And ci_dd = '" . $selectedDay . "' ";
    $cnt = $db->single($sql);
    if ($visitPcDay != "")
        $visitPcDay .= ",";
    $visitPcDay .= $cnt;
}


// 오늘의 방문현황 - MOBILE 총 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' ";
$visitMobileTotal = $db->single($sql);
// 오늘의 방문현황 - MOBILE 금일 방문수
$sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' And ci_yy = DATE_FORMAT(now(), '%y') And ci_mm = DATE_FORMAT(now(), '%c') And ci_dd = DATE_FORMAT(now(), '%e') ";
$visitMobileToday = $db->single($sql);
// 오늘의 방문현황 - MOBILE 10일간 방문수
$visitMobileDay = "";
for ($i = 9; $i >= 0; $i--) {
    $selectedYear = date("y", strtotime("-" . $i . " days"));
    $selectedMonth = date("n", strtotime("-" . $i . " days"));
    $selectedDay = date("j", strtotime("-" . $i . " days"));
    $sql = " Select IFNULL(COUNT(*),0) From df_counter_ip Where ci_pm = 'M' And ci_yy = '" . $selectedYear . "' And ci_mm = '" . $selectedMonth . "' And ci_dd = '" . $selectedDay . "' ";
    $cnt = $db->single($sql);
    if ($visitMobileDay != "")
        $visitMobileDay .= ",";
    $visitMobileDay .= $cnt;
}


// // 오늘의 매출현황 - 금일 매출금액
// $sql = " Select";
// $sql .= " ((Select Ifnull(Sum(prd_price),0)  ";
// $sql .= " From df_shop_order  ";
// $sql .= " Where is_del='N' And status_payment In ('DC','DI','OY') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d') )) as cnt ";
// $pay_Today = $db->single($sql);
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d'))";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d'))";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d'))";
$sql .= " ) as cnt";
$pay_Today     = $db->single($sql);

// // 어제의 매출현황 - 금일 매출금액 
// $pivotDate = date("Y-m-d", strtotime("-1 day"));
// $sql = "   Select";
// $sql .= "   ((Select Ifnull(Sum(prd_price),0)  ";
// $sql .= "   From df_shop_order ";
// $sql .= "   Where is_del='N' And status_payment In ('2') ";
// $sql .= "   And DATE_FORMAT(order_date,'%Y-%m-%d') = '$pivotDate' )) as cnt ";
// $pay_Yester = $db->single($sql);
$pivotDate     = date("Y-m-d", strtotime("-1 day"));
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m-%d') = '$pivotDate')";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m-%d') = '$pivotDate')";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m-%d') = '$pivotDate')";
$sql .= " ) as cnt";
$pay_Yester    = $db->single($sql);

// // 주간 매출현황 - 주간 매출금액
// $offset = date("N") - 1;
// $pivotDate = date("Y-m-d", strtotime("-" . $offset . " day"));

// $offset = 7 - date("N");
// $pivotDate2 = date("Y-m-d", strtotime("+" . $offset . " day"));
$offsetStart   = date("N") - 1; // 오늘이 며칠째(월:1~일:7) → 월요일까지 뺀 일수
$startOfWeek   = date("Y-m-d", strtotime("-{$offsetStart} day"));
$offsetEnd     = 7 - date("N");
$endOfWeek     = date("Y-m-d", strtotime("+{$offsetEnd} day"));

// $sql = " Select";
// $sql .= " ((Select Ifnull(Sum(prd_price),0)    ";
// $sql .= " From df_shop_order ";
// $sql .= " Where is_del='N' And status_payment In ('2') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') >= DATE_FORMAT('$pivotDate','%Y-%m-%d') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') <= DATE_FORMAT('$pivotDate2','%Y-%m-%d') )) as cnt ";
// $pay_Week = $db->single($sql);
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m-%d') >= '$startOfWeek' And DATE_FORMAT(wdate,     '%Y-%m-%d') <= '$endOfWeek')";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m-%d') >= '$startOfWeek' And DATE_FORMAT(reg_date, '%Y-%m-%d') <= '$endOfWeek')";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m-%d') >= '$startOfWeek' And DATE_FORMAT(reg_date, '%Y-%m-%d') <= '$endOfWeek')";
$sql .= " ) as cnt";
$pay_Week      = $db->single($sql);

// // 지난 주간 매출현황 - 주간 매출금액
// $offset = date("N") + 6;
// $pivotDate = date("Y-m-d", strtotime("-" . $offset . " day"));
// $offset = date("N");
// $pivotDate2 = date("Y-m-d", strtotime("-" . $offset . " day"));
$offsetStart   = date("N") + 6; // 오늘이 며칠째 + 6 → 지난주 월요일까지 뺄 일수
$startPrevWeek = date("Y-m-d", strtotime("-{$offsetStart} day"));
$offsetEnd     = date("N");     // 오늘이 며칠째 → 지난주 일요일까지 뺄 일수
$endPrevWeek   = date("Y-m-d", strtotime("-{$offsetEnd} day"));

// $sql = " Select";
// $sql .= " ((Select Ifnull(Sum(prd_price),0) ";
// $sql .= " From df_shop_order ";
// $sql .= " Where is_del='N' And status_payment In ('2') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') >= DATE_FORMAT('$pivotDate','%Y-%m-%d') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m-%d') <= DATE_FORMAT('$pivotDate2','%Y-%m-%d'))) as cnt ";
// $pay_preWeek = $db->single($sql);
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m-%d') >= '$startPrevWeek' And DATE_FORMAT(wdate,     '%Y-%m-%d') <= '$endPrevWeek')";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m-%d') >= '$startPrevWeek' And DATE_FORMAT(reg_date, '%Y-%m-%d') <= '$endPrevWeek')";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m-%d') >= '$startPrevWeek' And DATE_FORMAT(reg_date, '%Y-%m-%d') <= '$endPrevWeek')";
$sql .= " ) as cnt";
$pay_preWeek   = $db->single($sql);

// // 월별 매출현황 - 월별 매출금액
// $sql = " Select";
// $sql .= " ((Select Ifnull(Sum(prd_price),0) ";
// $sql .= " From df_shop_order ";
// $sql .= " Where is_del='N' And status_payment In ('2') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m') = DATE_FORMAT(now(),'%Y-%m')) )as cnt";
// $pay_Month = $db->single($sql);
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m') = DATE_FORMAT(now(),'%Y-%m'))";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m') = DATE_FORMAT(now(),'%Y-%m'))";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m') = DATE_FORMAT(now(),'%Y-%m'))";
$sql .= " ) as cnt";
$pay_Month     = $db->single($sql);

// // 저번달 매출현황 - 저번달 매출금액
// $pivotDate = date("Y-m", strtotime(date("Y-m-01") . "-1 day"));
$prevMonth     = date("Y-m", strtotime(date("Y-m-01") . " -1 day"));

// $sql = " Select";
// $sql .= " ((Select Ifnull(Sum(prd_price),0) ";
// $sql .= " From df_shop_order ";
// $sql .= " Where is_del='N' And status_payment In ('2') ";
// $sql .= " And DATE_FORMAT(order_date,'%Y-%m') = '" . $pivotDate . "' )) as cnt  ";
// $pay_preMonth = $db->single($sql);
$sql  = "";
$sql .= "Select";
$sql .= " (";
$sql .= "  (Select Count(*) From df_site_application_registration Where DATE_FORMAT(wdate,     '%Y-%m') = '$prevMonth')";
$sql .= " + (Select Count(*) From df_site_competition_registration  Where DATE_FORMAT(reg_date, '%Y-%m') = '$prevMonth')";
$sql .= " + (Select Count(*) From df_site_edu_registration          Where DATE_FORMAT(reg_date, '%Y-%m') = '$prevMonth')";
$sql .= " ) as cnt";
$pay_preMonth  = $db->single($sql);

$category_map = [
    'makeup' => '메이크업',
    'nail' => '네일',
    'hair' => '헤어',
    'skin' => '피부',
    'half' => '반영구',
    'foreign' => '해외인증',
    'teacher' => '강사인증'
];
?>
<script type="text/javascript">
    $(function () {
        var memberValues = [<?= $memberDay ?>];
        $('#ChartMember').sparkline(memberValues, {
            type: 'bar',
            barWidth: 5,
            barSpacing: 3,
            barColor: '#65cea7',
            width: '70px',
            height: '40px'
        }
        );

        var memberValues_o = [<?= $memberDay_o ?>];
        $('#ChartMember_o').sparkline(memberValues_o, {
            type: 'bar',
            barWidth: 5,
            barSpacing: 3,
            barColor: '#65cea7',
            width: '70px',
            height: '40px'
        }
        );

        var qnaValues = [<?= $qnaDay ?>];
        $('#ChartQNA').sparkline(qnaValues, {
            type: 'bar',
            barWidth: 5,
            barSpacing: 3,
            barColor: '#5ab5de',
            width: '70px',
            height: '40px'
        }
        );

        var sampleValues = [<?= $sampleDay ?>];
        $('#ChartSAMPLE').sparkline(sampleValues, {
            type: 'bar',
            barWidth: 5,
            barSpacing: 3,
            barColor: '#f26e81',
            width: '70px',
            height: '40px'
        }
        );

        var visitPcValues = [<?= $visitPcDay ?>];
        $('#ChartVisitPc').sparkline(visitPcValues, {
            type: 'line',
            width: '70px',
            height: '30px',
            lineColor: '#55accc',
            fillColor: '#edf7f9'
        }
        );

        var visitMobileValues = [<?= $visitMobileDay ?>];
        $('#ChartVisitMobile').sparkline(visitMobileValues, {
            type: 'line',
            width: '70px',
            height: '30px',
            lineColor: '#55accc',
            fillColor: '#edf7f9'
        }
        );

        var visitTotalValues = [<?= $visitPcToday ?>, <?= $visitMobileToday ?>];
        $('#ChartVisitTotal').sparkline(visitTotalValues, {
            type: 'pie',
            width: '70px',
            height: '70px',
            sliceColors: ['#5ab5de', '#fc8675'],
            tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} {{value}} ({{percent.1}}%)',
            tooltipValueLookups: {
                names: {
                    0: 'PC',
                    1: 'MOBILE'
                }
            }
        }
        );
    });

    // 회원별 적립금내역
    function reserveList(id, name) {
        var url = "/Madmin/member/member_reserve.php?id=" + id + "&name=" + name;
        window.open(url, "reserveList", "height=800, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
    }
</script>

<div class="pageWrap">
    <div class="box comFLeft comMRight15" style="width:430px; height:145px;">
        <div class="panel">
            <div class="title">
                <div class="comFLeft">
                    <i class="fa fa-bar-chart"></i>
                    <span>오늘의 통계현황</span>
                </div>
                <div class="comFRight">
                    <?= date("Y.m.d"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="charts comMTop15">
            <div class="chart comMRight30">
                    <div id="ChartMember"></div>
                    <div style="margin-top:5px;">
                        <span style="float:left; font-size:12px; color:#989898;">개인 회원</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($memberToday) ?></span>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($memberTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="chart comMRight30">
                    <div id="ChartMember_o"></div>
                    <div style="margin-top:5px;">
                        <span style="float:left; font-size:12px; color:#989898;">단체 회원</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($memberToday_o) ?></span>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($memberTotal_o) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>


                <div class="chart  comMRight30">
                    <div id="ChartQNA"></div>
                    <div style="margin-top:5px;">
                        <span style="float:left; font-size:12px; color:#989898;">신청</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($qnaToday) ?></span>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($qnaTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>

                <!-- <div class="chart">
                    <div id="ChartSAMPLE"></div>
                    <div style="margin-top:5px;">
                        <span style="float:left; font-size:12px; color:#989898;">샘플</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($sampleToday) ?></span>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($sampleTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div> -->
            </div>
        </div>
    </div>
    <div class="box comFLeft comMRight15" style="width:315px; height:145px;">
        <div class="panel">
            <div class="title">
                <div class="comFLeft">
                    <i class="fa fa-area-chart"></i>
                    <span>오늘의 방문현황</span>
                </div>
                <div class="comFRight">
                    <?= date("Y.m.d"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="charts2 comMTop10">
                <div class="chart comMRight30">
                    <div>
                        <span style="font-size:12px; color:#989898;">PC</span><br />
                        <span style="font-size:16px; font-weight:bold;"><?= number_format($visitPcToday) ?></span>
                    </div>
                    <div id="ChartVisitPc"></div>
                    <div style="text-align:center;">
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($visitPcTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="chart comMRight30">
                    <div>
                        <span style="font-size:12px; color:#989898;">MOBILE</span><br />
                        <span style="font-size:16px; font-weight:bold;"><?= number_format($visitMobileToday) ?></span>
                    </div>
                    <div id="ChartVisitMobile"></div>
                    <div style="text-align:center;">
                        <span style="float:left; font-size:12px; color:#989898;">총</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($visitMobileTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="chart">
                    <div id="ChartVisitTotal"></div>
                    <div style="text-align:center; margin-top:3px;">
                        <span style="float:left; font-size:12px; color:#989898;">누적</span>
                        <span
                            style="float:right; font-size:12px; font-weight:bold;"><?= number_format($visitPcTotal + $visitMobileTotal) ?></span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="box comFLeft comMRight15" style="width:400px; height:145px;">
        <div class="panel">
            <div class="title">
                <div class="comFLeft">
                    <i class="fa fa-line-chart"></i>
                    <span>오늘의 신청</span>
                </div>
                <div class="comFRight">
                    <?= date("Y.m.d"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="charts3 comMTop10">
                <div class="chart comMRight30">
                    <div style="margin-bottom:10px;">
                        <span style="font-size:12px; color:#989898;">오늘</span><br />
                        <span style="font-size:14px; font-weight:bold;">
                            <?
                            if ($pay_Today > $pay_Yester) {
                                echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Today < $pay_Yester) {
                                echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Today = $pay_Yester) {
                                echo "<strong>-</strong>";
                            }
                            ?>
                        </span>
                        <span style="font-size:14px; font-weight:bold;"><?= number_format($pay_Today) ?></span>
                    </div>
                    <div>
                        <span style="font-size:12px; color:#989898;">어제</span><br />
                        <span
                            style="font-size:12px; font-weight:bold; color:#616161;"><?= number_format($pay_Yester) ?></span>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="chart comMRight30">
                    <div style="margin-bottom:10px;">
                        <span style="font-size:12px; color:#989898;">이번주</span><br />
                        <span style="font-size:14px; font-weight:bold;">
                            <?
                            if ($pay_Week > $pay_preWeek) {
                                echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Week < $pay_preWeek) {
                                echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Week = $pay_preWeek) {
                                echo "<strong>-</strong>";
                            }
                            ?>
                        </span>
                        <span style="font-size:14px; font-weight:bold;"><?= number_format($pay_Week) ?></span>
                    </div>
                    <div>
                        <span style="font-size:12px; color:#989898;">저번주</span><br />
                        <span
                            style="font-size:12px; font-weight:bold; color:#616161;"><?= number_format($pay_preWeek) ?></span>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="chart">

                    <div style="margin-bottom:10px;">
                        <span style="font-size:12px; color:#989898;">이번달</span><br />
                        <span style="font-size:14px; font-weight:bold;">
                            <?
                            if ($pay_Month > $pay_preMonth) {
                                echo "<font color=#31b0d5><strong><strong><i class='fa fa-caret-up' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Month < $pay_preMonth) {
                                echo "<font color=#ff6c60><strong><i class='fa fa-caret-down' aria-hidden='true'></i></strong></font>";
                            } else if ($pay_Month = $pay_preMonth) {
                                echo "<strong>-</strong>";
                            }
                            ?>
                        </span>
                        <span
                            style="font-size:14px; font-weight:bold;letter-spacing:-0.8px;"><?= number_format($pay_Month) ?></span>
                    </div>
                    <div>
                        <span style="font-size:12px; color:#989898;">저번달</span><br />
                        <span
                            style="font-size:12px; font-weight:bold; color:#616161;"><?= number_format($pay_preMonth) ?></span>
                    </div>

                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="box comMTop20" style="width:1180px;">
        <div class="panel">
            <div class="title">
                <i class="fa fa-edit"></i>
                <span>최근 자격시험 신청</span>
            </div>
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>분야</td>
                        <td>이름</td>
                        <td>연락처</td>
                        <td>신청일</td>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sql = "";
                    $sql .= " SELECT f_category, f_user_name, f_tel, wdate reg_date ";
                    $sql .= " FROM df_site_application_registration ";
                    $sql .= " ORDER BY idx DESC ";
                    $sql .= " LIMIT 5 ";
                    $row = $db->query($sql);
                    foreach ($row as $r) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($category_map[$r['f_category']]) ?></td>
                            <td><?= htmlspecialchars($r['f_user_name']) ?></td>
                            <td><?= htmlspecialchars($r['f_tel']) ?></td>
                            <td><?= substr($r['reg_date'], 0, 10) ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box comMTop20" style="width:1180px;">
        <div class="panel">
            <div class="title">
                <i class="fa fa-edit"></i>
                <span>최근 대회 신청</span>
            </div>
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>참가분야</td>
                        <td>이름</td>
                        <td>연락처</td>
                        <td>신청일</td>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sql = "";
                    $sql .= " SELECT f_field, f_user_name, f_tel, reg_date ";
                    $sql .= " FROM df_site_competition_registration ";
                    $sql .= " ORDER BY idx DESC ";
                    $sql .= " LIMIT 5 ";
                    $row = $db->query($sql);
                    foreach ($row as $r) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($r['f_field']) ?></td>
                            <td><?= htmlspecialchars($r['f_user_name']) ?></td>
                            <td><?= htmlspecialchars($r['f_tel']) ?></td>
                            <td><?= substr($r['reg_date'], 0, 10) ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box comMTop20" style="width:1180px;">
        <div class="panel">
            <div class="title">
                <i class="fa fa-edit"></i>
                <span>최근 교육 신청</span>
            </div>
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>구분</td>
                        <td>이름</td>
                        <td>연락처</td>
                        <td>신청일</td>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sql = "";
                    $sql .= " SELECT f_type, f_user_name, f_tel, reg_date ";
                    $sql .= " FROM df_site_edu_registration ";
                    $sql .= " ORDER BY idx DESC ";
                    $sql .= " LIMIT 5 ";
                    $row = $db->query($sql);
                    foreach ($row as $r) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($r['f_type']) == 'P' ? '개인' : '단체' ?></td>
                            <td><?= htmlspecialchars($r['f_user_name']) ?></td>
                            <td><?= htmlspecialchars($r['f_tel']) ?></td>
                            <td><?= substr($r['reg_date'], 0, 10) ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear comMBottom50"></div>

</div>
</div>

</div>
</div>

</body>

</html>