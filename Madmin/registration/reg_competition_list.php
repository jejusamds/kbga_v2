<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page_set = 15;
$block_set = 10;

$sql = "SELECT COUNT(*) FROM df_site_competition_registration";
$total = $db->single($sql);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT t1.*, t2.f_title FROM df_site_competition_registration t1
            LEFT JOIN df_site_competition t2 on t1.f_competition_idx = t2.idx
            ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql);
}
?>
<style>
    .pagination {
        margin: 0 auto;
    }
</style>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 신청 내역</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">대회</li>
        </ul>
    </div>
    <table class="comMTop20" cellpadding="0" cellspacing="0" style="width:1114px;">
        <tr>
            <td width="5"></td>
            <td colspan="6" align="right">
                <button class="btn btn-success btn-xs" type="button"
                    onclick="location.href='reg_competition_excel.php?<?= $param ?>'">엑셀파일저장</button>
            </td>
            <td width="5"></td>
        </tr>
    </table>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="60" />
                <col width="200" />
                <col width="150" />
                <col width="150" />
                <col width="120" />
                <col width="150" />
                <col width="150" />
                <col width="190" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>번호</td>
                        <td>대회명</td>
                        <td>참가부문</td>
                        <td>참가분야</td>
                        <td>참가종목</td>
                        <td>이름</td>
                        <td>연락처</td>
                        <td>이메일</td>
                        <td>등록일</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $row): ?>
                            <tr>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td><?= htmlspecialchars($row['f_title'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_part_title'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_field_title'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_event_title'], ENT_QUOTES) ?></td>
                                <td><a
                                        href="reg_competition_view.php?idx=<?= $row['idx'] ?>&page=<?= $page ?>"><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($row['f_tel'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_email'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['reg_date'], ENT_QUOTES) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" align="center">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFCenter comACenter" style="width:100%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, ""); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>