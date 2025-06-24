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
            $searchSql .= ' AND f_subject LIKE :search';
            $params['search'] = $like;
            break;
        case 'description':
            $searchSql .= ' AND f_description LIKE :search';
            $params['search'] = $like;
            break;
        default:
            $searchSql .= ' AND (f_subject LIKE :search OR f_description LIKE :search2)';
            $params['search'] = $like;
            $params['search2'] = $like;
            break;
    }
}

$page = (isset($_GET['page']) && ctype_digit($_GET['page']) && $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$total = $db->single("SELECT COUNT(*) FROM df_site_material WHERE f_category=:cat{$searchSql}", $params);
$totalPages = (int) ceil($total / $perPage);


$list = $db->query(
    "SELECT * FROM df_site_material WHERE f_category = :cat {$searchSql} ORDER BY idx DESC LIMIT :offset, :perPage",
    array_merge($params, ['offset' => $offset, 'perPage' => $perPage])
);

$queryExtra = '&category=' . $category;
if ($searchQuery !== '') {
    $queryExtra = '&search_field=' . urlencode($searchField) . '&search_query=' . urlencode($searchQuery);
}

$firstPage = 1;
$lastPage = $totalPages;
$prevPage = $page > 1 ? $page - 1 : $firstPage;
$nextPage = $page < $lastPage ? $page + 1 : $lastPage;
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
                        <?php if ($list):
                            foreach ($list as $row): ?>
                                <li>
                                    <div class="data_notice_div">
                                        <div class="title_con">
                                            <div class="bar"></div>
                                            <span>뷰티 <br />
                                                디자인디렉터</span>
                                        </div>
                                        <div class="list_con">
                                            <ul>
                                                <li>
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
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; else: ?>
                            <li class="none_li"><span>등록된 게시글이 없습니다.</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="number_list_con">
                    <div class="contents_con">
                        <div class="btn_con">
                            <a href="?page=<?= $firstPage . $queryExtra ?>">
                                <img src="/img/sub/number_list_prev_btn2.svg" alt="번호목록 처음" class="fx" />
                            </a>
                            <a href="?page=<?= $prevPage . $queryExtra ?>">
                                <img src="/img/sub/number_list_prev_btn.svg" alt="번호목록 이전" class="fx" />
                            </a>
                        </div>
                        <div class="list_con">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?= $i . $queryExtra ?>"
                                    class="list_a<?= $page === $i ? ' on' : '' ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></a>
                            <?php endfor; ?>
                            <div class="bar"></div>
                        </div>
                        <div class="btn_con">
                            <a href="?page=<?= $nextPage . $queryExtra ?>">
                                <img src="/img/sub/number_list_next_btn.svg" alt="번호목록 다음" class="fx" />
                            </a>
                            <a href="?page=<?= $lastPage . $queryExtra ?>">
                                <img src="/img/sub/number_list_next_btn2.svg" alt="번호목록 끝" class="fx" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html'; ?>