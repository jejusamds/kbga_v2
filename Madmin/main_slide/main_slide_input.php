<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_main_slide';
$table = 'main_slide';
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
    'thumbnail_m' => '',
    'media_type' => 'image',
    'video_pc' => '',
    'video_m' => ''
];
if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='main_slide_list.php';</script>";
        exit;
    }
    $mode = 'update';
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>메인 슬라이드 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>메인 관리</li>
            <li class="active">메인 슬라이드 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>
    <form action="/Madmin/main_slide/main_slide_save.php" method="post" enctype="multipart/form-data"
        onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>상단 문구(PC)</th>
                        <td class="comALeft"><input type="text" name="top_contents_pc"
                                value="<?= htmlspecialchars($row['top_contents_pc'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>상단 문구(Mobile)</th>
                        <td class="comALeft"><input type="text" name="top_contents_m"
                                value="<?= htmlspecialchars($row['top_contents_m'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>중간 문구(PC)</th>
                        <td class="comALeft"><textarea name="middle_contents_pc" class="form-control"
                                style="width:80%;height:60px;"><?= htmlspecialchars($row['middle_contents_pc'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>중간 문구(Mobile)</th>
                        <td class="comALeft"><textarea name="middle_contents_m" class="form-control"
                                style="width:80%;height:60px;"><?= htmlspecialchars($row['middle_contents_m'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>하단 문구(PC)</th>
                        <td class="comALeft"><textarea name="bottom_contents_pc" class="form-control"
                                style="width:80%;height:60px;"><?= htmlspecialchars($row['bottom_contents_pc'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>하단 문구(Mobile)</th>
                        <td class="comALeft"><textarea name="bottom_contents_m" class="form-control"
                                style="width:80%;height:60px;"><?= htmlspecialchars($row['bottom_contents_m'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>타입</th>
                        <td class="comALeft">
                            <label><input type="radio" name="media_type" value="image" <?= $row['media_type']==='video' ? '' : 'checked' ?>> 이미지</label>
                            <label style="margin-left:10px;"><input type="radio" name="media_type" value="video" <?= $row['media_type']==='video' ? 'checked' : '' ?>> 영상</label>
                        </td>
                    </tr>
                    <tr>
                        <th>PC 영상</th>
                        <td class="comALeft">
                            <input type="file" name="video_pc" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['video_pc']): ?>
                                <a href="/userfiles/main_slide/<?= $row['video_pc'] ?>" target="_blank"><?= $row['video_pc'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile 영상</th>
                        <td class="comALeft">
                            <input type="file" name="video_m" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['video_m']): ?>
                                <a href="/userfiles/main_slide/<?= $row['video_m'] ?>" target="_blank"><?= $row['video_m'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일(PC)</th>
                        <td class="comALeft">
                            <input type="file" name="thumbnail_pc" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['thumbnail_pc']): ?>
                                <a href="/userfiles/main_slide/<?= $row['thumbnail_pc'] ?>"
                                    target="_blank"><?= $row['thumbnail_pc'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일(Mobile)</th>
                        <td class="comALeft">
                            <input type="file" name="thumbnail_m" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['thumbnail_m']): ?>
                                <a href="/userfiles/main_slide/<?= $row['thumbnail_m'] ?>"
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
                        onclick="location.href='main_slide_list.php';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode == 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='main_slide_save.php?mode=delete&selidx=<?= $idx ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>

</html>