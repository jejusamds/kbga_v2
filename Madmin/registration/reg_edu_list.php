<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$status = $_GET['status'] ?? '';
$param = "status={$status}";
$page_set = 15;
$block_set = 10;

$where = 'WHERE 1';
$params = [];
if ($status !== '') {
    $where .= ' AND f_applicant_status=:status';
    $params['status'] = $status;
}

$sql = "SELECT COUNT(*) FROM df_site_edu_registration {$where}";
$total = $db->single($sql, $params);

$status_map = [
    'ing'   => '접수중',
    'done'  => '완료',
    'cancle' => '취소',
    'hold'  => '보류',
];

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM df_site_edu_registration {$where} ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql, $params);
}
?>
<style>
    .pagination {
        margin: 0 auto;
    }
</style>
<div class="pageWrap">
    <div class="page-heading">
        <h3>교육 신청 내역</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">교육</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="page" value="<?= $page ?>">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="90" height="26" align="right" style="padding-left:5px">조건검색</td>
                            <td class="comALeft" style="padding-left:5px">
                                <select name="status" class="form-control" style="width:auto; display:inline-block;">
                                    <option value="" <?= $status === '' ? 'selected' : '' ?>>전체</option>
                                    <option value="ing" <?= $status === 'ing' ? 'selected' : '' ?>>접수중</option>
                                    <option value="done" <?= $status === 'done' ? 'selected' : '' ?>>완료</option>
                                    <option value="cancle" <?= $status === 'cancle' ? 'selected' : '' ?>>취소</option>
                                    <option value="hold" <?= $status === 'hold' ? 'selected' : '' ?>>보류</option>
                                </select>
                                <button class="btn btn-info btn-sm" type="submit">검색</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <table class="comMTop20" cellpadding="0" cellspacing="0" style="width:1114px;">
        <tr>
            <td width="5"></td>
            <td colspan="6" align="right">
                <button class="btn btn-success btn-xs" type="button" onclick="location.href='reg_edu_excel.php?<?= $param ?>'">엑셀파일저장</button>
            </td>
            <td width="5"></td>
        </tr>
    </table>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="60" />
                <col width="150" />
                <col width="150" />
                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="90" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>번호</td>
                        <td>구분</td>
                        <td>교육구분</td>
                        <td>이름</td>
                        <td>연락처</td>
                        <td>이메일</td>
                        <td>상태</td>
                        <td>등록일</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $row): ?>
                            <tr>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td><?= $row['f_type'] == 'P' ? '개인' : '단체' ?></td>
                                <td><?= htmlspecialchars($row['f_edu_type_title'], ENT_QUOTES) ?></td>
                                <td><a
                                        href="reg_edu_view.php?idx=<?= $row['idx'] ?>&page=<?= $page ?>"><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($row['f_tel'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_email'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($status_map[$row['f_applicant_status']] ?? $row['f_applicant_status'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['reg_date'], ENT_QUOTES) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" align="center">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFCenter comACenter" style="width:100%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, '&' . $param); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
</html>