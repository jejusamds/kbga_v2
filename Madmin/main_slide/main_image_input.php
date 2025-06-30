<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_main_image';

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$mode = 'insert';
$row = [
    'top_contents_pc' => '',
    'top_contents_m' => '',
    'middle_contents_pc' => '',
    'middle_contents_m' => '',
    'bottom_contents_pc' => '',
    'bottom_contents_m' => '',
    'thumbnail_pc' => '',
    'thumbnail_m' => ''
];
if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='main_image_list.php';</script>";
        exit;
    }
    $mode = 'update';
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>메인 이미지 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>메인 관리</li>
            <li class="active">메인 슬라이드 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>
    <form action="/Madmin/main_slide/main_image_save.php" method="post" enctype="multipart/form-data"
        onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>썸네일(PC)</th>
                        <td class="comALeft">
                            <input type="file" name="thumbnail_pc" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['thumbnail_pc']): ?>
                                <a href="/userfiles/main_image/<?= $row['thumbnail_pc'] ?>"
                                    target="_blank"><?= $row['thumbnail_pc'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일(Mobile)</th>
                        <td class="comALeft">
                            <input type="file" name="thumbnail_m" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['thumbnail_m']): ?>
                                <a href="/userfiles/main_image/<?= $row['thumbnail_m'] ?>"
                                    target="_blank"><?= $row['thumbnail_m'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='main_image_list.php';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode == 'update'): ?>
                        <!-- <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='main_image_save.php?mode=delete&selidx=<?= $idx ?>';">삭제</button> -->
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>

</html>