<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = "df_site_sigong";
$table = "sigong";

// 검색 파라미터 (용도, 지역)
$search_usage = isset($_GET['usage']) ? trim($_GET['usage']) : '';
$search_region = isset($_GET['region']) ? trim($_GET['region']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// 페이지당 출력 개수, 블록당 페이지 수
$page_set = 15;
$block_set = 10;

// 검색 조건 SQL
$addSql = "";
if ($search_usage !== "") {
    $addSql .= " AND s.f_usage = '" . addslashes($search_usage) . "' ";
}
if ($search_region !== "") {
    $addSql .= " AND s.f_region = '" . addslashes($search_region) . "' ";
}

// 전체 글 개수 조회
$sql = "
    SELECT COUNT(*)
    FROM {$this_table} s
    WHERE 1 = 1 " . $addSql;
$total = $db->single($sql);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = 1;
}

// 리스트 조회
$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "
        SELECT *
        FROM {$this_table} s
        WHERE 1 = 1 " . $addSql . "
        ORDER BY s.idx DESC
        LIMIT " . $offset . ", " . $page_set;
    $list = $db->query($sql);
}
?>
<script language="JavaScript" type="text/javascript">
    // 체크박스 전체선택 / 해제
    function onSelect(form) {
        if (form.select_tmp.checked) {
            selectAll();
        } else {
            selectEmpty();
        }
    }
    function selectAll() {
        for (var i = 0; i < document.forms.length; i++) {
            if (document.forms[i].idx != null && document.forms[i].select_checkbox) {
                document.forms[i].select_checkbox.checked = true;
            }
        }
    }
    function onSelectAll(allChk) {
        var chks = document.querySelectorAll('.select_checkbox');
        for (var i = 0; i < chks.length; i++) {
            chks[i].checked = allChk.checked;
        }
    }
    function selectEmpty() {
        for (var i = 0; i < document.forms.length; i++) {
            if (document.forms[i].idx != null && document.forms[i].select_checkbox) {
                document.forms[i].select_checkbox.checked = false;
            }
        }
    }

    // 선택된 시공사업 항목 삭제
    function deleteEntries() {
        var selIdxArr = [];
        var chks = document.querySelectorAll('.select_checkbox');
        for (var i = 0; i < chks.length; i++) {
            if (chks[i].checked) {
                selIdxArr.push(chks[i].value);
            }
        }
        if (selIdxArr.length === 0) {
            alert("삭제할 항목을 선택하세요.");
            return;
        }
        if (confirm("선택한 항목을 삭제하시겠습니까?")) {
            // idx 값들을 |로 합침
            var selIdx = selIdxArr.join('|');
            document.location = "/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx=" + selIdx + "&page=<?= $page ?>&usage=" + encodeURIComponent('<?= $search_usage ?>') + "&region=" + encodeURIComponent('<?= $search_region ?>');
        }
    }

</script>
<style>
    .pagination {
        margin: 0 auto;
    }
</style>

<div class="pageWrap">
    <div class="page-heading">
        <h3>사업실적</h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active">시공사업</li>
        </ul>
    </div>

    <!-- 검색 폼 (용도, 지역) -->
    <form id="searchForm" action="<?= $table ?>_list.php" method="get">
        <input type="hidden" name="page" value="<?= $page ?>" />
        <div class="box comMTop20" style="width:1114px;">
            <div class="panel">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <col width="80" />
                    <col />
                    <tbody>
                        <tr>
                            <td>용도</td>
                            <td class="comALeft" style="padding-left:5px">
                                <select name="usage" class="form-control" style="width:auto; display:inline-block;"
                                    onchange="document.getElementById('searchForm').submit();">
                                    <option value="">전체</option>
                                    <option value="업무/근생" <?= $search_usage == '업무/근생' ? 'selected' : '' ?>>업무/근생</option>
                                    <option value="물류시설" <?= $search_usage == '물류시설' ? 'selected' : '' ?>>물류시설</option>
                                    <option value="주거시설" <?= $search_usage == '주거시설' ? 'selected' : '' ?>>주거시설</option>
                                    <option value="리모델링" <?= $search_usage == '리모델링' ? 'selected' : '' ?>>리모델링</option>
                                    <option value="기타" <?= $search_usage == '기타' ? 'selected' : '' ?>>기타</option>
                                </select>
                                <span style="margin-left:20px;"></span>
                                <label style="margin-left:10px;">지역</label>
                                <select name="region" class="form-control" style="width:auto; display:inline-block;"
                                    onchange="document.getElementById('searchForm').submit();">
                                    <option value="">전체</option>
                                    <option value="서울" <?= $search_region == '서울' ? 'selected' : '' ?>>서울</option>
                                    <option value="경기" <?= $search_region == '경기' ? 'selected' : '' ?>>경기</option>
                                    <option value="기타" <?= $search_region == '기타' ? 'selected' : '' ?>>기타</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- 리스트 테이블 -->
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="20" />
                <col width="20" />
                <col width="150" />
                <col width="100" />
                <col width="120" />
                <col width="60" />
                <col width="120" />
                <thead>
                    <tr>
                        <td>
                            <input type="checkbox" id="select_all" onclick="onSelectAll(this)">
                        </td>
                        <td>번호</td>
                        <td>주소</td>
                        <td>용도</td>
                        <td>공사기간</td>
                        <td>진행률</td>
                        <td>작성일</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $item): ?>
                            <form name="frm<?= $item['idx'] ?>">
                                <input type="hidden" name="idx" value="<?= $item['idx'] ?>">
                                <tr>
                                    <td>
                                        <input type="checkbox" class="select_checkbox" name="select_checkbox[]"
                                            value="<?= $item['idx'] ?>">
                                    </td>
                                    <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                    <td class="comALeft">
                                        <a
                                            href="<?= $table ?>_input.php?table=<?= $table ?>&mode=update&idx=<?= $item['idx'] ?>&page=<?= $page ?>&usage=<?= urlencode($usage) ?>&region=<?= urlencode($region) ?>">
                                            <?= htmlspecialchars($item['f_address'], ENT_QUOTES) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($item['f_usage'], ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($item['f_period'], ENT_QUOTES) ?></td>
                                    <td><?= (int) $item['f_progress'] ?>%</td>
                                    <td><?= $item['wdate'] ?></td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td height="30" colspan="7" align="center">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 하단 기능 버튼 -->
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onClick="deleteEntries();">삭제</button>
            </div>
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, "&usage=" . urlencode($search_usage) . "&region=" . urlencode($search_region)); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onClick="location.href='<?= $table ?>_input.php?page=<?= $page ?>&usage=<?= urlencode($search_usage) ?>&region=<?= urlencode($search_region) ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

</body>

</html>