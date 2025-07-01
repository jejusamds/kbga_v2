<?php

$ssMenu_num = "1";
$ssMenu_slide = "0";
include 'include/center_sub_common.php';
include $_SERVER['DOCUMENT_ROOT'] . '/include/header.html';

/*
반응형
w_con 은 PC용
m_con 은 모바일용
*/
?>

<div id="container">
    <div id="sub_con" class="center_sub02">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_banner.html';
        ?>

        <div class="contents_con">

            <div class="notice_list_con">
                <div class="ts_con">
                    <div class="title_con">
                        <div class="text01_con">
                            <span>
                                <?= $category_map[$category]['en'] ?> PRIVATE QUALIFICATION
                            </span>
                        </div>

                        <div class="text02_con">
                            <span>
                                <?= $category_map[$category]['ko'] ?> 자격분야
                            </span>
                        </div>
                    </div>

                    <div class="search_con">
                        <form action="" method="" autocomplete="off">
                            <div class="input_con">
                                <table cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="select_td">
                                                <select name="" class="select">
                                                    <option value=""><?= $year ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="exam_notice_con">
                    <div class="title_con">
                        <span>
                            시행일정 <br class="w_br" /><?= $year ?>
                        </span>
                    </div>

                    <div class="list_con">
                        <div class="title_con w_con">
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td align="center" class="round_td">
                                            <span>
                                                회차
                                            </span>
                                        </td>
                                        <td align="center" class="division_td">
                                            <span>
                                                구분
                                            </span>
                                        </td>
                                        <td align="center" class="receipt_td">
                                            <span>
                                                접수기간
                                            </span>
                                        </td>
                                        <td align="center" class="exam_td">
                                            <span>
                                                시험일
                                            </span>
                                        </td>
                                        <td align="center" class="announcement_td">
                                            <span>
                                                합격자발표
                                            </span>
                                        </td>
                                        <td align="center" class="license_td">
                                            <span>
                                                자격증 신청
                                            </span>
                                        </td>
                                        <td align="center" class="btn_td">
                                            <span>
                                                응시접수
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="list_con">
                            <ul>
                                <?php
                                $sql = "SELECT * FROM df_site_application WHERE f_category = :f_category AND f_year = :f_year ORDER BY f_round";
                                $db->bind("f_category", $category);
                                $db->bind("f_year", $year);
                                $list = $db->query($sql);
                                if ($category && $category != 'teacher') {
                                    if (count($list) > 0) {
                                        foreach ($list as $row) {
                                            ?>
                                            <li>
                                                <div class="w_con">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" class="round_td">
                                                                    <span>
                                                                        <?= htmlspecialchars($row['f_round']) ?>
                                                                    </span>
                                                                </td>
                                                                <td align="center" class="list_td">
                                                                    <ul>
                                                                        <li>
                                                                            <table cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" class="division_td">
                                                                                            <span>
                                                                                                필기
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="receipt_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_registration_period']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="exam_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_exam_date']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="announcement_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_pass_announce']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </li>
                                                                        <li>
                                                                            <table cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" class="division_td">
                                                                                            <span>
                                                                                                실기
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="receipt_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_registration_period_2']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="exam_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_exam_date_2']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="announcement_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_pass_announce_2']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td align="center" class="license_td">
                                                                    <span>
                                                                        <?= htmlspecialchars($row['f_cert_application']) ?>
                                                                    </span>
                                                                </td>
                                                                <td align="center" class="btn_td">
                                                                    <a href="/center/center_sub_apply.php?category=<?=$category?>&year=<?=$year?>" class="a_btn">
                                                                        접수하기
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="m_con">
                                                    <div class="round_con">
                                                        <span>
                                                            1회차
                                                        </span>
                                                    </div>

                                                    <div class="nav">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0);" class="on" val="info01">
                                                                    필기
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" val="info02">
                                                                    실기
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="contents_con">
                                                        <div class="contents_div info01">
                                                            <div class="text_con">
                                                                <ul>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            접수기간
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_registration_period']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            시험일
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_exam_date']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            합격자발표
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_pass_announce']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            자격증 신청
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_cert_application']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="btn_con">
                                                                <a href="/center/center_sub_apply.php?category=<?=$category?>&year=<?=$year?>" class="a_btn">
                                                                    접수하기
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="contents_div info02">
                                                            <div class="text_con">
                                                                <ul>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            접수기간
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_registration_period_2']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            시험일
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_exam_date_2']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            합격자발표
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_pass_announce_2']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            자격증 신청
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_cert_application']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="btn_con">
                                                                <a href="/center/center_sub_apply.php?category=<?=$category?>&year=<?=$year?>" class="a_btn">
                                                                    접수하기
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php }
                                    } else { ?>
                                        <li class="none_li">
                                            <span>등록된 시험이 없습니다.</span>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    if (count($list) > 0) {
                                        foreach ($list as $row) {
                                            ?>
                                            <li>
                                                <div class="w_con">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" class="round_td">
                                                                    <span>
                                                                        <?= htmlspecialchars($row['f_round']) ?>
                                                                    </span>
                                                                </td>
                                                                <td align="center" class="list_td">
                                                                    <ul>
                                                                        <li>
                                                                            <table cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" class="division_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_type']) ?>분기
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="receipt_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_registration_period']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="exam_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_exam_date']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td align="center" class="announcement_td">
                                                                                            <span>
                                                                                                <?= htmlspecialchars($row['f_pass_announce']) ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td align="center" class="license_td">
                                                                    <span>
                                                                        <?= htmlspecialchars($row['f_cert_application']) ?>
                                                                    </span>
                                                                </td>
                                                                <td align="center" class="btn_td">
                                                                    <a href="/center/center_sub_apply.php?category=<?=$category?>&year=<?=$year?>" class="a_btn">
                                                                        접수하기
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="m_con">
                                                    <div class="round_con">
                                                        <span>
                                                            1회차
                                                        </span>
                                                    </div>

                                                    <div class="nav">
                                                        <ul class="depth_1">
                                                            <li>
                                                                <a href="javascript:void(0);" class="on">
                                                                    <?= htmlspecialchars($row['f_type']) ?>분기
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="contents_con">
                                                        <div class="contents_div">
                                                            <div class="text_con">
                                                                <ul>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            접수기간
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_registration_period']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            시험일
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_exam_date']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            합격자발표
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_pass_announce']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                    <li>
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="title_td">
                                                                                        <span>
                                                                                            자격증 신청
                                                                                        </span>
                                                                                    </td>
                                                                                    <td align="left" class="info_td">
                                                                                        <span>
                                                                                            <?= htmlspecialchars($row['f_cert_application']) ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="btn_con">
                                                                <a href="/center/center_sub_apply.php?category=<?=$category?>&year=<?=$year?>" class="a_btn">
                                                                    접수하기
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="none_li">
                                            <span>등록된 시험이 없습니다.</span>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript" language="javascript">
    <?php if ($category != 'teacher') { ?>
        // 모바일 리스트 필기/실기 클릭 시
        $(document).on("click", ".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a", function () {
            $(this).closest(".nav").find("a").removeClass("on");
            $(this).addClass("on");
            $(this).closest(".m_con").find(".contents_con .contents_div").hide();
            $(this).closest(".m_con").find(".contents_con .contents_div." + $(this).attr("val")).show();
        });

        // 초기 화면 크기 저장
        var center_sub02_initialWidth = window.innerWidth;
        var center_sub02_isFirstLoad = true;

        // 리사이즈 예외처리
        var center_sub02_resizeTimer;

        // 화면 리사이징
        $(window).resize(function () {
            // 현재 window 너비
            var currentWidth = window.innerWidth;

            // 너비가 변경되지 않은 경우(스크롤, 상태바 동작) 무시
            if (currentWidth === center_sub02_initialWidth && !center_sub02_isFirstLoad) {
                return;
            }

            clearTimeout(center_sub02_resizeTimer);

            // 시험일정 게시판 리스트 예외처리
            $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a").removeClass("on");
            $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li:first-child a").addClass("on");
            $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div").css("display", "none");
            $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div:first-child").css("display", "block");

            // 화면 너비
            if (window.innerWidth > 1024) {

            } else {

            }

            center_sub02_resizeTimer = setTimeout(function () {
                // 실제 리사이징이 발생한 경우에만 처리
                if (currentWidth !== center_sub02_initialWidth || center_sub02_isFirstLoad) {
                    // 시험일정 게시판 리스트 예외처리
                    $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a").removeClass("on");
                    $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li:first-child a").addClass("on");
                    $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div").css("display", "none");
                    $(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div:first-child").css("display", "block");

                    // 화면 너비
                    if (window.innerWidth > 1024) {

                    } else {

                    }

                    center_sub02_initialWidth = currentWidth;
                    center_sub02_isFirstLoad = false;
                }
            }, 500);
        });
    <?php } ?>
</script>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html';
?>