<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_material';
$table = 'material';

$valid_categories = ['makeup', 'nail', 'hair', 'skin', 'half', 'foreign', 'teacher'];
$category = isset($_GET['category']) ? $_GET['category'] : '';
if (!in_array($category, $valid_categories)) {
    echo "<script>alert('잘못된 접근입니다.');location.href='/Madmin';</script>";
    exit;
}

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$paramArr = ['page' => $page, 'category' => $category];
$param = http_build_query($paramArr);

$row = [
    'f_category' => $category,
    'f_subject_idx' => 0,
    'f_subject' => '',
    'f_type' => '필기',
    'f_level' => '',
    'f_description' => '',
    'f_file' => '',
    'f_file_name' => ''
];
$mode = 'insert';
if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='{$table}_list.php?{$param}';</script>";
        exit;
    }
    $mode = 'update';
}

$subjects = $db->query(
    "SELECT idx, f_item_name FROM df_site_qualification_item WHERE f_category = :cat ORDER BY f_item_name ASC",
    ['cat' => $category]
);
$subject_map = array_column($subjects, 'f_item_name', 'idx');
if ($row['f_subject_idx'] && !isset($subject_map[$row['f_subject_idx']]) && $row['f_subject']) {
    $subjects[] = ['idx' => $row['f_subject_idx'], 'f_item_name' => $row['f_subject']];
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
<div class="pageWrap">
    <div class="page-heading">
        <h3>필/실기 자료 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>시험 관리</li>
            <li class="active">필/실기 자료 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>

    <form action="/Madmin/material/exec.php?<?= $param ?>" method="post" enctype="multipart/form-data"
        onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="f_category" value="<?= $category ?>">
        <?php if ($idx): ?><input type="hidden" name="idx" value="<?= $idx ?>"><?php endif; ?>
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>분류</th>
                        <td class="comALeft"><?= $category_map[$category] ?></td>
                    </tr>
                    <tr>
                        <th><label for="f_subject_idx">과목</label></th>
                        <td class="comALeft">
                            <select name="f_subject_idx" id="f_subject_idx" class="form-control" style="width:60%;">
                                <option value="">과목 선택</option>
                                <?php foreach ($subjects as $s): ?>
                                    <option value="<?= $s['idx'] ?>" <?= (int)$s['idx'] === (int)$row['f_subject_idx'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['f_item_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="f_subject" id="f_subject" value="<?= htmlspecialchars($row['f_subject']) ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_type">구분</label></th>
                        <td class="comALeft">
                            <select name="f_type" id="f_type" class="form-control" style="width:auto;">
                                <option value="필기" <?= $row['f_type'] == '필기' ? 'selected' : '' ?>>필기</option>
                                <option value="실기" <?= $row['f_type'] == '실기' ? 'selected' : '' ?>>실기</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_level">등급</label></th>
                        <td class="comALeft"><input type="text" name="f_level" id="f_level"
                                value="<?= htmlspecialchars($row['f_level'], ENT_QUOTES) ?>" class="form-control"
                                style="width:40%;"></td>
                    </tr>
                    <tr>
                        <th><label for="f_description">설명</label></th>
                        <td class="comALeft"><input type="text" name="f_description" id="f_description"
                                value="<?= htmlspecialchars($row['f_description'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th><label for="file">파일</label></th>
                        <td class="comALeft">
                            <?php if ($row['f_file_name']): ?>
                                <a href="/userfiles/material/<?= htmlspecialchars($row['f_file']) ?>"
                                    target="_blank"><?= htmlspecialchars($row['f_file_name']) ?></a>
                                <label><input type="checkbox" name="del_file" value="1"> 삭제</label><br>
                            <?php endif; ?>
                            <input type="file" name="upfile" id="file">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='<?= $table ?>_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode === 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='/Madmin/material/exec.php?table=<?= $table ?>&mode=delete&selidx=<?= $idx ?>&<?= $param ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
<script>
    (function(){
        var select = document.getElementById('f_subject_idx');
        var hidden = document.getElementById('f_subject');
        function updateName(){
            if(!select) return;
            var txt = select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '';
            if(hidden) hidden.value = txt;
        }
        if(select){
            select.addEventListener('change', updateName);
            updateName();
        }
    })();
</script>
</body>

</html>