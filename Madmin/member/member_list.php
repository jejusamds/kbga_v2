<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_member';
$table = 'member';
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$searchopt = $_GET['searchopt'] ?? '';
$keyword = trim($_GET['keyword'] ?? '');
$is_out = $_GET['is_out'] ?? '';
$param = "searchopt={$searchopt}&keyword=" . urlencode($keyword) . "&is_out={$is_out}";
$addSql = '';
$params = [];
if ($keyword !== '') {
    if ($searchopt === 'name') {
        $addSql .= " AND f_user_name LIKE :kw";
        $params['kw'] = "%{$keyword}%";
    } elseif ($searchopt === 'mobile') {
        $addSql .= " AND (f_mobile LIKE :kw OR f_tel LIKE :kw)";
        $params['kw'] = "%{$keyword}%";
    }
}
if ($is_out !== '') {
    $addSql .= " AND is_out = :is_out";
    $params['is_out'] = $is_out;
}

$page_set = 20;
$block_set = 10;

$total = $db->single("SELECT COUNT(*) FROM {$this_table} WHERE 1 {$addSql}", $params);
$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt)
    $page = $pageCnt > 0 ? $pageCnt : 1;

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM {$this_table} WHERE 1 {$addSql} ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql, $params);
}
?>
<script>
    function onSelectAll(allChk) {
        document.querySelectorAll('.select_checkbox').forEach(function (cb) { cb.checked = allChk.checked; });
    }
    function deleteEntries() {
        var arr = []; document.querySelectorAll('.select_checkbox:checked').forEach(function (cb) { arr.push(cb.value); });
        if (arr.length === 0) { alert('삭제할 항목을 선택하세요.'); return; }
        if (confirm('선택한 항목을 삭제하시겠습니까?')) {
            location.href = '/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx=' + arr.join('|') + '&page=<?= $page ?>';
        }
    }
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>회원 관리</h3>
        <ul class="breadcrumb">
            <li>회원 관리</li>
            <li class="active">회원 목록</li>
        </ul>
    </div>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <form name="searchForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="page" value="<?= $page ?>">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="90" height="26" align="right" style="padding-left:5px">조건검색</td>
                            <td class="comALeft" style="padding-left:5px">
                                <select name="searchopt" class="form-control" style="width:auto;">
                                    <option value="name" <?= $searchopt == 'name' ? 'selected' : '' ?>>회원명</option>
                                    <option value="mobile" <?= $searchopt == 'mobile' ? 'selected' : '' ?>>연락처</option>
                                </select>
                                <input type="text" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES) ?>"
                                    class="form-control" style="width:auto;">
                                <select name="is_out" class="form-control" style="width:auto;">
                                    <option value="" <?= $is_out === '' ? 'selected' : '' ?>>전체</option>
                                    <option value="1" <?= $is_out === '1' ? 'selected' : '' ?>>미탈퇴</option>
                                    <option value="2" <?= $is_out === '2' ? 'selected' : '' ?>>탈퇴</option>
                                </select>
                                <button class="btn btn-info btn-sm" type="submit">검색</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <table class="comMTop10" cellpadding="0" cellspacing="0" style="width:1114px;">
        <tr>
            <td width="5"></td>
            <td height="30" colspan="5" align="left" class="s01">총 <b>
                    <font color="fc3d32"><?= number_format($total) ?></font>
                </b> 명의 회원</td>
            <td colspan="6" align="right">
                <!-- <button class="btn btn-success btn-xs" type="button" onclick="location.href='inquiry_<?= $inquiry_gubun ?>_excel.php?<?= $param ?>';">엑셀파일저장</button> -->
            </td>
            <td width="5"></td>
        </tr>
    </table>
    
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="30" />
                    <col width="30" />
                    <col width="150" />
                    <col width="150" />
                    <col width="100" />
                    <col width="150" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>회원유형</th>
                        <th>가입일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $row): ?>
                            <tr>
                                <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td class=""><a
                                        href="member_input.php?idx=<?= $row['idx'] ?>&page=<?= $page ?>&<?= $param ?>"><?= htmlspecialchars($row['f_user_id'], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></td>
                                <td><?= $row['f_member_type'] === 'O' ? '단체' : '개인' ?></td>
                                <td><?= substr($row['wdate'], 0, 10) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" height="50" class="comACenter">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onclick="deleteEntries();">삭제</button>
            </div>
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, '&' . $param); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>