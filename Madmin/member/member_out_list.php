<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_member_out';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$page_set = 20;
$block_set = 10;

$total = $db->single("SELECT COUNT(*) FROM {$this_table}");
$pageCnt = (int)(($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = $pageCnt > 0 ? $pageCnt : 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT o.*, m.f_user_name FROM {$this_table} o LEFT JOIN df_site_member m ON o.f_user_id = m.f_user_id ORDER BY o.idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql);
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>회원 관리</h3>
        <ul class="breadcrumb">
            <li>회원 관리</li>
            <li class="active">탈퇴회원 조회</li>
        </ul>
    </div>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="60" />
                    <col width="150" />
                    <col width="150" />
                    <col />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>탈퇴사유</th>
                        <th>탈퇴일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $row): ?>
                            <tr>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td><?= htmlspecialchars($row['f_user_id'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['reason'], ENT_QUOTES)) ?></td>
                                <td><?= substr($row['wdate'], 0, 10) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" height="50" class="comACenter">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFCenter comACenter" style="width:100%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, ''); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
</html>