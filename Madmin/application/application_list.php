<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

// DB 테이블명 정의 (제공된 DB 스키마 기준)
$this_table = "df_site_application";
$table = "application"; // 페이지 컨텍스트에 맞는 이름으로 변경

// --- [변경] 검색 파라미터: 년도(year), 시험 분류(category) ---
$search_year = isset($_GET['year']) ? trim($_GET['year']) : '2025'; // 이미지에 맞게 기본값을 '2025'로 설정
$search_category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// 페이지당 출력 개수, 블록당 페이지 수
$page_set = 15;
$block_set = 10;

// --- [변경] 검색 조건 SQL ---
$addSql = "";
if (!empty($search_year)) {
    $addSql .= " AND s.f_year = '{$search_year}'";
}
if (!empty($search_category)) {
    $addSql .= " AND s.f_category = '{$search_category}'";
}

// 전체 게시물 수 조회
$sql = "
    SELECT COUNT(*)
    FROM {$this_table} s
    WHERE 1 = 1 " . $addSql;
$total = $db->single($sql);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = $pageCnt > 0 ? $pageCnt : 1;
}

// 리스트 조회
$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "
        SELECT *
        FROM {$this_table} s
        WHERE 1 = 1 " . $addSql . "
        ORDER BY s.f_round ASC, s.idx ASC
        LIMIT " . $offset . ", " . $page_set;
    $list = $db->query($sql);
}

// 시험 분류 (카테고리) 배열 - DB ENUM 타입에 맞춰 정의
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
<script language="JavaScript" type="text/javascript">
    // 체크박스 관련 스크립트 (기존과 유사하게 수정)
    function onSelectAll(allChk) {
        var chks = document.querySelectorAll('.select_checkbox');
        for (var i = 0; i < chks.length; i++) {
            chks[i].checked = allChk.checked;
        }
    }

    // 선택된 항목 삭제
    function deleteEntries() {
        var selIdxArr = [];
        var chks = document.querySelectorAll('.select_checkbox:checked');

        if (chks.length === 0) {
            alert("삭제할 항목을 선택하세요.");
            return;
        }

        if (confirm("선택한 항목을 삭제하시겠습니까?")) {
            chks.forEach(function(chk) {
                selIdxArr.push(chk.value);
            });
            
            var selIdx = selIdxArr.join('|');
            // --- [변경] 삭제 후 돌아올 페이지의 파라미터 변경 ---
            var searchParams = "year=<?= $search_year ?>&category=<?= $search_category ?>";
            document.location = "/Madmin/application/exec.php?table=<?= $table ?>&mode=delete&selidx=" + selIdx + "&page=<?= $page ?>&" + searchParams;
        }
    }
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>자격시험센터</h3>
        <ul class="breadcrumb">
            <li>시험 관리</li>
            <li class="active">시험일정</li>
        </ul>
    </div>

    <form id="searchForm" action="<?= basename(__FILE__) ?>" method="get">
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
                                <select name="year" class="form-control" style="width:auto; display:inline-block;">
                                    <?php for ($y = date('Y') + 2; $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y ?>" <?= $search_year == $y ? 'selected' : '' ?>><?= $y ?>년</option>
                                    <?php endfor; ?>
                                </select>
                                
                                <label style="margin-left:20px;">시험 분류</label>
                                <select name="category" class="form-control" style="width:auto; display:inline-block; margin-left:10px;">
                                    <option value="">전체</option>
                                    <?php foreach ($category_map as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= $search_category == $key ? 'selected' : '' ?>><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit" class="btn btn-primary btn-sm" style="margin-left:10px;">검색</button>
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
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <!-- <col width="180" />
                    <col width="120" /> -->
                    <col width="120" />
                    <col width="180" />
                    <!-- <col width="120" />
                    <col width="120" /> -->
                    <col width="200" />
                    <!-- <col width="120" /> -->
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>회차</th>
                        <th>시험분류</th>
                        <th>구분</th>
                        <!-- <th>필기 접수시작</th>
                        <th>필기 접수종료</th> -->
                        <th>필기 시험일</th>
                        <th>필기 합격자발표</th>
                        <!-- <th>실기 접수시작</th>
                        <th>실기 접수종료</th> -->
                        <th>실기 시험일</th>
                        <th>실기 합격자발표</th>
                        <th>자격증 신청</th>
                        <!-- <th>작성일</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $item): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="select_checkbox" name="select_checkbox" value="<?= $item['idx'] ?>">
                                </td>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td class="">
                                    <a href="<?= $table ?>_input.php?mode=update&idx=<?= $item['idx'] ?>&page=<?= $page ?>&year=<?= $search_year ?>&category=<?= $search_category ?>">
                                        <?= htmlspecialchars($item['f_round'], ENT_QUOTES) ?>회
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= $table ?>_input.php?mode=update&idx=<?= $item['idx'] ?>&page=<?= $page ?>&year=<?= $search_year ?>&category=<?= $search_category ?>">
                                        <?= htmlspecialchars($category_map[$item['f_category']], ENT_QUOTES) ?>
                                    </a>    
                                </td>
                                <td>
                                    <?php if ($item['f_category'] == 'teacher') { ?>
                                    <?= htmlspecialchars($item['f_type'], ENT_QUOTES) ?>분기
                                    <?php } ?>
                                </td>
                                <!-- <td><?= htmlspecialchars($item['f_registration_start'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_registration_end'], ENT_QUOTES) ?></td> -->
                                <td><?= htmlspecialchars($item['f_exam_date'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_pass_announce'], ENT_QUOTES) ?></td>
                                <!-- <td><?= htmlspecialchars($item['f_registration_start_2'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_registration_end_2'], ENT_QUOTES) ?></td> -->
                                <td><?= htmlspecialchars($item['f_exam_date_2'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_pass_announce_2'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($item['f_cert_application'], ENT_QUOTES) ?></td>
                                <!-- <td><?= substr($item['wdate'], 0, 10) ?></td> -->
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td height="50" colspan="13" class="comACenter">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onClick="deleteEntries();">삭제</button>
            </div>
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, "&year=" . $search_year . "&category=" . $search_category); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onClick="location.href='<?= $table ?>_input.php?page=<?= $page ?>&year=<?= $search_year ?>&category=<?= $search_category ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

</body>
</html>