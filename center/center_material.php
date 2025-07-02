<?php
$Menu = "03";
$ssMenu_num = '3';
$ssMenu_slide = '2';
include __DIR__ . '/include/center_sub_common.php';
include $_SERVER['DOCUMENT_ROOT'] . '/include/header.html';

$searchField = $_GET['search_field'] ?? '';
$searchQuery = trim($_GET['search_query'] ?? '');

$searchSql = '';
$params = ['cat' => $category];
if ($searchQuery !== '') {
    $like = "%{$searchQuery}%";
    switch ($searchField) {
        case 'subject':
            $searchSql .= ' AND qi.f_item_name LIKE :search';
            $params['search'] = $like;
            break;
        case 'description':
            $searchSql .= ' AND m.f_description LIKE :search';
            $params['search'] = $like;
            break;
        default:
            $searchSql .= ' AND (qi.f_item_name LIKE :search OR m.f_description LIKE :search2)';
            $params['search'] = $like;
            $params['search2'] = $like;
            break;
    }
}

$rows = $db->query(
    "SELECT m.*, qi.f_item_name FROM df_site_material m 
    LEFT JOIN df_site_qualification_item qi ON m.f_subject_idx = qi.idx 
    WHERE m.f_category=:cat {$searchSql} 
    ORDER BY qi.f_item_name ASC, m.idx DESC",
    $params
);

$grouped = [];
foreach ($rows as $row) {
    $key = (int) $row['f_subject_idx'];
    $name = $row['f_item_name'] ?: $row['f_subject'];
    if (!isset($grouped[$key])) {
        $grouped[$key] = ['name' => $name, 'items' => []];
    }
    $grouped[$key]['items'][] = $row;
}

$subConClass = 'center_sub0' . substr($sMenu, -1);
?>
<div id="container">
    <div id="sub_con" class="<?= $subConClass ?>">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_banner.html'; ?>
        <div class="contents_con">
            <div class="notice_list_con">
                <div class="ts_con">
                    <div class="title_con">
                        <div class="text01_con">
                            <span>REQUIRES PRACTICAL SKILLS</span>
                        </div>
                        <div class="text02_con">
                            <span>2025년 <br class="m_br" />한국메이크업아티스트협회 <br class="m_br" />자격검정 안내</span>
                        </div>
                    </div>
                    <div class="search_con">
                        <form action="" method="GET" autocomplete="off">
                            <input type="hidden" name="category" value="<?= $category ?>">
                            <div class="input_con">
                                <table cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="select_td">
                                                <select name="search_field" class="select">
                                                    <option value="" <?= $searchField === '' ? 'selected' : '' ?>>전체
                                                    </option>
                                                    <option value="subject" <?= $searchField === 'subject' ? 'selected' : '' ?>>과목</option>
                                                    <option value="description" <?= $searchField === 'description' ? 'selected' : '' ?>>설명</option>
                                                </select>
                                            </td>
                                            <td align="left" class="blank01_td">&nbsp;</td>
                                            <td align="left" class="input_td">
                                                <input type="text" name="search_query"
                                                    value="<?= htmlspecialchars($searchQuery, ENT_QUOTES) ?>"
                                                    placeholder="검색해주세요." class="input" />
                                            </td>
                                            <td align="left" class="blank02_td">&nbsp;</td>
                                            <td align="left" class="btn_td">
                                                <a href="javascript:;" onclick="this.closest('form').submit();">
                                                    <img src="/img/sub/notice_search_btn.svg" alt="검색 버튼" class="fx" />
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="data_notice_con">
                    <ul>
                        <?php if ($grouped): ?>
                            <?php foreach ($grouped as $g): ?>
                                <li class="center_material_subject_li">
                                    <div class="data_notice_div">
                                        <div class="title_con">
                                            <div class="bar"></div>
                                            <span><?= htmlspecialchars($g['name']) ?></span>
                                        </div>
                                        <div class="list_con">
                                            <ul>
                                                <?php foreach ($g['items'] as $row): ?>
                                                    <li class="center_material_items_li">
                                                        <div class="list_div">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" class="category_td">
                                                                            <span><?= htmlspecialchars($row['f_type']) ?></span>
                                                                        </td>
                                                                        <td align="center" class="level_td">
                                                                            <span><?= htmlspecialchars($row['f_level']) ?></span>
                                                                        </td>
                                                                        <td align="left" class="text_td">
                                                                            <span><?= htmlspecialchars($row['f_description']) ?></span>
                                                                        </td>
                                                                        <td align="left" class="btn_td">
                                                                            <?php if ($row['f_file_name']): ?>
                                                                                <a href="/userfiles/material/<?= htmlspecialchars($row['f_file']) ?>"
                                                                                    class="a_btn" target="_blank">자료 다운로드</a>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="none_li"><span>등록된 게시글이 없습니다.</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html'; ?>