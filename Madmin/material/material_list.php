<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = "df_site_material";
$table = "material";

$search_category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$page_set = 20;
$block_set = 10;

$addSql = "";
if (!empty($search_category)) {
    $addSql .= " AND s.f_category = '{$search_category}'";
}

$sql = "SELECT COUNT(*) FROM {$this_table} s WHERE 1=1" . $addSql;
$total = $db->single($sql);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = $pageCnt > 0 ? $pageCnt : 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM {$this_table} s WHERE 1=1" . $addSql . " ORDER BY s.idx DESC LIMIT " . $offset . ", " . $page_set;
    $list = $db->query($sql);
}

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
<script>
    function onSelectAll(allChk) {
        document.querySelectorAll('.select_checkbox').forEach(function (ch) { ch.checked = allChk.checked; });
    }
    function deleteEntries() {
        var arr = []; document.querySelectorAll('.select_checkbox:checked').forEach(function (ch) { arr.push(ch.value); });
        if (arr.length === 0) { alert('삭제할 항목을 선택하세요.'); return; }
        if (confirm('선택한 항목을 삭제하시겠습니까?')) {
            location.href = '/Madmin/material/exec.php?table=<?= $table ?>&mode=delete&selidx=' + arr.join('|') + '&page=<?= $page ?>&category=<?= $search_category ?>';
        }
    }
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>필/실기 자료 관리</h3>
        <ul class="breadcrumb">
            <li>시험 관리</li>
            <li class="active">필/실기 자료</li>
        </ul>
    </div>

    <form action="<?= basename(__FILE__) ?>" method="get" id="searchForm">
        <div class="box comMTop20" style="width:1114px;">
            <div class="panel">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <colgroup>
                        <col width="80" />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td>검색 조건</td>
                            <td class="comALeft" style="padding-left:5px">
                                <label>분류</label>
                                <select name="category" class="form-control"
                                    style="width:auto; display:inline-block; margin-left:10px;">
                                    <option value="">전체</option>
                                    <?php foreach ($category_map as $k => $v): ?>
                                        <option value="<?= $k ?>" <?= $search_category == $k ? 'selected' : '' ?>><?= $v ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm"
                                    style="margin-left:10px;">검색</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="120" />
                    <col width="80" />
                    <col width="80" />
                    <col />
                    <col width="120" />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>분류</th>
                        <th>구분</th>
                        <th>등급</th>
                        <th>설명</th>
                        <th>파일</th>
                        <th>작성일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0):
                        foreach ($list as $i => $item): ?>
                            <tr>
                                <td><input type="checkbox" class="select_checkbox" value="<?= $item['idx'] ?>"></td>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td><a
                                        href="<?= $table ?>_input.php?mode=update&idx=<?= $item['idx'] ?>&page=<?= $page ?>&category=<?= $search_category ?>"><?= htmlspecialchars($category_map[$item['f_category']], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($item['f_type'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_level'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_description'], ENT_QUOTES) ?></td>
                                <td><?php if ($item['f_file_name']): ?><a
                                            href="/userfiles/material/<?= htmlspecialchars($item['f_file']) ?>"
                                            target="_blank">다운로드</a><?php endif; ?></td>
                                <td><?= substr($item['wdate'], 0, 10) ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="comACenter" height="50">등록된 데이터가 없습니다.</td>
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
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, "&category=" . $search_category); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onclick="location.href='<?= $table ?>_input.php?page=<?= $page ?>&category=<?= $search_category ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>