<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$status = $_GET['status'] ?? '';
$type = $_GET['type'] ?? '';
$search_field = $_GET['search_field'] ?? '';
$keyword = trim($_GET['keyword'] ?? '');
$param = "status={$status}&type={$type}&search_field={$search_field}&keyword=" . urlencode($keyword);
$page_set = 15;
$block_set = 10;

$where = 'WHERE 1';
$params = [];
if ($status !== '') {
    $where .= ' AND t1.f_applicant_status=:status';
    $params['status'] = $status;
}

if ($type !== '') {
    $where .= ' AND t1.f_applicant_type=:type';
    $params['type'] = $type;
}

if ($keyword !== '' && $search_field !== '') {
    $field_map = [
        'id'   => 'f_user_id',
        'name' => 'f_user_name',
        'tel'  => 'f_tel',
    ];
    if (isset($field_map[$search_field])) {
        $where .= " AND t1." . $field_map[$search_field] . " LIKE :keyword";
        $params['keyword'] = "%{$keyword}%";
    }
}

$sql = "SELECT COUNT(*) FROM df_site_application_registration t1 {$where}";
$total = $db->single($sql, $params);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT t1.*, t2.f_item_name, s.f_year, s.f_round, s.f_type
            FROM df_site_application_registration t1
            LEFT JOIN df_site_qualification_item t2 ON t1.f_item_idx = t2.idx
            LEFT JOIN df_site_application s ON t1.f_schedule_idx = s.idx
            {$where}
            ORDER BY t1.idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql, $params);
}

$category_map = [
    'makeup' => '메이크업',
    'nail' => '네일',
    'hair' => '헤어',
    'skin' => '피부',
    'half' => '반영구',
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
?>
<style>
    .pagination {
        margin: 0 auto;
    }
</style>

<div class="pageWrap">
    <div class="page-heading">
        <h3>자격시험 신청 내역</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">자격시험</li>
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
                                <select name="type" class="form-control" style="width:auto; display:inline-block;">
                                    <option value="" <?= $type === '' ? 'selected' : '' ?>>전체</option>
                                    <option value="P" <?= $type === 'P' ? 'selected' : '' ?>>개인</option>
                                    <option value="O" <?= $type === 'O' ? 'selected' : '' ?>>단체</option>
                                </select>
                                <select name="search_field" class="form-control" style="width:auto; display:inline-block;">
                                    <option value="id" <?= $search_field === 'id' ? 'selected' : '' ?>>아이디</option>
                                    <option value="name" <?= $search_field === 'name' ? 'selected' : '' ?>>이름</option>
                                    <option value="tel" <?= $search_field === 'tel' ? 'selected' : '' ?>>전화번호</option>
                                </select>
                                <input type="text" name="keyword" class="form-control" style="width:auto; display:inline-block;" value="<?= htmlspecialchars($keyword, ENT_QUOTES) ?>" />
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
                <button class="btn btn-success btn-xs" type="button" onclick="location.href='reg_application_excel.php?<?= $param ?>'">엑셀파일저장</button>
            </td>
            <td width="5"></td>
        </tr>
    </table>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="60" />
                <col width="60" />
                <col width="150" />
                <col width="150" />
                <col width="140" />
                <col width="130" />
                <col width="120" />
                <col width="170" />
                <col width="90" />
                <col width="190" />
                <col width="150" />
                <thead>
                    <tr>
                        <td>번호</td>
                        <td>구분</td>
                        <td>분야</td>
                        <td>자격종목</td>
                        <td>시험일정</td>
                        <td>이름</td>
                        <td>신청구분</td>
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
                                <td><?= $row['f_applicant_type'] === 'P' ? '개인' : '단체' ?></td>
                                <td><?= htmlspecialchars($category_map[$row['f_category']], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_item_name'], ENT_QUOTES) ?></td>
                                <td>
                                    <?php if (!empty($row['f_year'])): ?>
                                        <?= htmlspecialchars($row['f_year'] . '년 ' . $row['f_round'] . '회차 ' . $row['f_type'], ENT_QUOTES) ?>
                                    <?php elseif ((int)$row['f_schedule_idx'] === 0): ?>
                                        상시접수
                                    <?php else: ?>
                                        <?= htmlspecialchars($row['f_schedule_idx'], ENT_QUOTES) ?>
                                    <?php endif; ?>
                                </td>
                                <td><a
                                        href="reg_application_view.php?idx=<?= $row['idx'] ?>&page=<?= $page ?>"><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($application_type_map[$row['f_application_type']], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_tel'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_email'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($status_map[$row['f_applicant_status']] ?? $row['f_applicant_status'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['wdate'], ENT_QUOTES) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" align="center">등록된 데이터가 없습니다.</td>
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