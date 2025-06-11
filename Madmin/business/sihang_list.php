<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = "df_site_sihang";
$table = "sihang";
$dir = "business";  // /Madmin/business/sihang_list.php 위치

// 검색 파라미터 (키워드)
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// 페이지당 출력 개수, 블록당 페이지 수
$page_set = 15;
$block_set = 10;

// 검색 조건 SQL
$addSql = "";
if ($keyword !== "") {
    $escaped = addslashes($keyword);
    $addSql = " AND (s.f_name LIKE '%{$escaped}%' OR s.f_type LIKE '%{$escaped}%') ";
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
        LIMIT {$offset}, {$page_set}";
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
            <li class="active">시행사업</li>
        </ul>
    </div>

    <!-- 검색 폼 (키워드 + 버튼) -->
    <form id="searchForm" action="<?= $table ?>_list.php" method="get">
        <input type="hidden" name="page" value="<?= $page ?>" />
        <div class="box comMTop20" style="width:1114px;">
            <div class="panel">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <col width="80" />
                    <col />
                    <tbody>
                        <tr>
                            <td>검색어</td>
                            <td class="comALeft" style="padding-left:5px">
                                <input type="text" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES) ?>"
                                    class="form-control" style="width:auto; display:inline-block;"
                                    placeholder="공사명 또는 시설종류 검색" />
                                <button class="btn btn-info btn-sm" type="submit" style="margin-left:10px;">검색</button>
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
                <!-- <col width="100" /> -->
                <col width="150" />
                <col width="200" />
                <col width="200" />
                <col width="120" />
                <thead>
                    <tr>
                        <td>
                            <input type="checkbox" id="select_all" onclick="onSelectAll(this)">
                        </td>
                        <td>번호</td>
                        <!-- <td>썸네일</td> -->
                        <td>시설 종류</td>
                        <td>공사명</td>
                        <td>URL</td>
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
                                    <!-- <td class="comACenter">
                                        <?php if ($item['f_thumbnail']): ?>
                                            <img src="/userfiles/<?= $table ?>/<?= htmlspecialchars($item['f_thumbnail'], ENT_QUOTES) ?>"
                                                alt="썸네일" height="50" />
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td> -->
                                    <td><?= htmlspecialchars($item['f_type'], ENT_QUOTES) ?></td>
                                    <td>
                                        <a
                                            href="<?= $table ?>_input.php?table=<?= $table ?>&mode=update&idx=<?= $item['idx'] ?>&page=<?= $page ?>&keyword=<?= urlencode($keyword) ?>">
                                            <?= htmlspecialchars($item['f_name'], ENT_QUOTES) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($item['f_url']): ?>
                                            <a href="<?= htmlspecialchars($item['f_url'], ENT_QUOTES) ?>" target="_blank">
                                                <?= htmlspecialchars($item['f_url'], ENT_QUOTES) ?>
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
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
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, "&keyword=" . urlencode($keyword)); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onClick="location.href='<?= $table ?>_input.php?page=<?= $page ?>&keyword=<?= urlencode($keyword) ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

</body>

</html>