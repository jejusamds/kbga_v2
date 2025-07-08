<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_qualification';
$table = 'qualification';
$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$category = isset($_GET['category']) ? (int) $_GET['category'] : 1;
$param = "page={$page}&category={$category}";

$mode = 'insert';
$row = ['page_no' => 1, 'f_name' => '', 'f_type' => '', 'f_reg_no' => '', 'f_manage_org' => '', 'f_ministry' => ''];
if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='qualification_list.php?{$param}';</script>";
        exit;
    }
    $mode = 'update';
}

$category_map = [
    '1' => '한국메이크업아티스트협회',
    '2' => '한국네일자격인증협회',
    '3' => '한국피부미용전문가협회',
    '4' => 'K-뷰티업스타일협회',
    '5' => '글로벌뷰티월드연합회'
];
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>민간자격 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>민간자격 관리</li>
            <li class="active">민간자격 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>
    <form action="/Madmin/qualification/exec.php?<?= $param ?>" method="post" onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <?php if ($idx): ?><input type="hidden" name="idx" value="<?= $idx ?>"><?php endif; ?>
        <input type="hidden" name="category" value="<?= $category ?>">
        <input type="hidden" name="page_no" value="<?= $category ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>구분</th>
                        <td class="comALeft">
                            <!-- <select name="page_no" class="form-control" style="width:40%;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $row['page_no'] == $i ? 'selected' : '' ?>><?= $i ?>페이지</option>
                                <?php endfor; ?>
                            </select> -->
                            <?= $category_map[$category] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>자격명</th>
                        <td class="comALeft"><input type="text" name="f_name"
                                value="<?= htmlspecialchars($row['f_name'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>자격구분</th>
                        <td class="comALeft"><input type="text" name="f_type"
                                value="<?= htmlspecialchars($row['f_type'], ENT_QUOTES) ?>" class="form-control"
                                style="width:40%;"></td>
                    </tr>
                    <tr>
                        <th>등록번호</th>
                        <td class="comALeft"><input type="text" name="f_reg_no"
                                value="<?= htmlspecialchars($row['f_reg_no'], ENT_QUOTES) ?>" class="form-control"
                                style="width:40%;"></td>
                    </tr>
                    <tr>
                        <th>자격관리기관</th>
                        <td class="comALeft"><input type="text" name="f_manage_org"
                                value="<?= htmlspecialchars($row['f_manage_org'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>주무부처</th>
                        <td class="comALeft"><input type="text" name="f_ministry"
                                value="<?= htmlspecialchars($row['f_ministry'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='qualification_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode === 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='exec.php?table=<?= $table ?>&mode=delete&selidx=<?= $idx ?>&page=<?= $page ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>

</html>