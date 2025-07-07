<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = 'df_site_competition';
$table = 'competition';

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$param = "page={$page}";

if ($idx) {
    $mode = 'update';
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='competition_list.php?{$param}';</script>";
        exit;
    }
    $parts = $db->query("SELECT f_part FROM df_site_competition_part WHERE competition_idx=:idx ORDER BY idx ASC", ['idx' => $idx]);
    $fields = $db->query("SELECT f_field FROM df_site_competition_field WHERE competition_idx=:idx ORDER BY idx ASC", ['idx' => $idx]);
    $events = $db->query("SELECT f_event FROM df_site_competition_event WHERE competition_idx=:idx ORDER BY idx ASC", ['idx' => $idx]);
} else {
    $mode = 'insert';
    $row = [
        'f_title' => '',
        'f_date' => '',
        'f_place' => '',
        'f_target' => '',
        'f_reg_period' => '',
        'f_detail' => '',
        'f_image' => ''
    ];
    $parts = $fields = $events = [];
}
?>
<script language="JavaScript">
    function inputCheck(f) {
        if (f.f_title.value.trim() === '') { alert('대회명을 입력하세요.'); f.f_title.focus(); return false; }
        return true;
    }
    function delData(id) {
        if (confirm('이 항목을 삭제하시겠습니까?')) {
            location.href = '/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx=' + id + '&<?= $param ?>';
        }
    }
    function deleteImage(idx, field) {
        if (!confirm('이미지를 삭제하시겠습니까?')) return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/Madmin/exec/exec.php', true);
        var fd = new FormData();
        fd.append('mode', 'delimg');
        fd.append('table', '<?= $table ?>');
        fd.append('idx', idx); fd.append('field', field);
        xhr.onload = function () { if (xhr.responseText.trim() === 'Y') { document.getElementById(field + '_prev_img').remove(); document.getElementById(field + '_del_btn').remove(); } else { alert('이미지 삭제에 실패했습니다.'); } };
        xhr.send(fd);
    }
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>대회 관리</li>
            <li class="active">대회 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>

    <form name="frm" action="/Madmin/exec/exec.php?<?= $param ?>" method="post" enctype="multipart/form-data"
        onsubmit="return inputCheck(this);">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>대회명</th>
                        <td class="comALeft">
                            <input type="text" name="f_title"
                                value="<?= htmlspecialchars($row['f_title'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>일정</th>
                        <td class="comALeft">
                            <input type="text" name="f_date" value="<?= htmlspecialchars($row['f_date'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>장소</th>
                        <td class="comALeft">
                            <input type="text" name="f_place"
                                value="<?= htmlspecialchars($row['f_place'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>참가대상</th>
                        <td class="comALeft">
                            <input type="text" name="f_target"
                                value="<?= htmlspecialchars($row['f_target'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>신청기간</th>
                        <td class="comALeft">
                            <input type="text" name="f_reg_period"
                                value="<?= htmlspecialchars($row['f_reg_period'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>세부사항</th>
                        <td class="comALeft">
                            <textarea name="f_detail" class="form-control"
                                style="width:80%; height:120px;"><?= htmlspecialchars($row['f_detail'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일</th>
                        <td class="comALeft">
                            <input type="file" name="f_image" class="form-control" style="width:50%;">
                            <?php if ($mode == 'update' && $row['f_image']): ?>
                                <a href="/userfiles/competition/<?= $row['f_image'] ?>" target="_blank"
                                    id="f_image_prev_img"><?= $row['f_image'] ?></a>
                                <button class="btn btn-warning btn-xs" type="button"
                                    onclick="deleteImage(<?= $idx ?>,'f_image');" id="f_image_del_btn">이미지 삭제</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- <div style="margin-top: 30px;">
            <span style="color: red;">!!!신청서가 유입된 이후에 아래 항목들을 삭제하는 경우 신청인이 선택한 값이 유실됩니다.</span>
        </div> -->

        <div class="box comMTop20" style="width:978px;">

            <div class="panel">
                <div class="title">
                    <i class="fa fa-list"></i>
                    <span>참가부문</span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddPart" type="button">항목추가</button>
                </div>
                <table id="tablePart" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="85%">
                    <col width="15%">
                    <tbody>
                        <?php if ($mode == 'insert' && empty($parts)): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="parts[]" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelPart" type="button">삭제</button></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($parts as $p): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="parts[]"
                                        value="<?= htmlspecialchars($p['f_part'], ENT_QUOTES) ?>" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelPart" type="button">삭제</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-list"></i>
                    <span>종목분야</span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddField" type="button">항목추가</button>
                </div>
                <table id="tableField" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="85%">
                    <col width="15%">
                    <tbody>
                        <?php if ($mode == 'insert' && empty($fields)): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="fields[]" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelField" type="button">삭제</button></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($fields as $f): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="fields[]"
                                        value="<?= htmlspecialchars($f['f_field'], ENT_QUOTES) ?>" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelField" type="button">삭제</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-list"></i>
                    <span>참가종목</span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddEvent" type="button">항목추가</button>
                </div>
                <table id="tableEvent" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="85%">
                    <col width="15%">
                    <tbody>
                        <?php if ($mode == 'insert' && empty($events)): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="events[]" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelEvent" type="button">삭제</button></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($events as $e): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="events[]"
                                        value="<?= htmlspecialchars($e['f_event'], ENT_QUOTES) ?>" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelEvent" type="button">삭제</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onClick="location.href='competition_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode == 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode == 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button" onClick="delData('<?= $idx ?>');">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).on('click', '.btnAddPart', function () {
        $('#tablePart tbody').append('<tr><td class="comALeft"><input type="text" name="parts[]" class="form-control" style="width:60%;"></td><td><button class="btn btn-warning btn-xs btnDelPart" type="button">삭제</button></td></tr>');
    });
    $(document).on('click', '.btnDelPart', function () { $(this).closest('tr').remove(); });
    $(document).on('click', '.btnAddField', function () {
        $('#tableField tbody').append('<tr><td class="comALeft"><input type="text" name="fields[]" class="form-control" style="width:60%;"></td><td><button class="btn btn-warning btn-xs btnDelField" type="button">삭제</button></td></tr>');
    });
    $(document).on('click', '.btnDelField', function () { $(this).closest('tr').remove(); });
    $(document).on('click', '.btnAddEvent', function () {
        $('#tableEvent tbody').append('<tr><td class="comALeft"><input type="text" name="events[]" class="form-control" style="width:60%;"></td><td><button class="btn btn-warning btn-xs btnDelEvent" type="button">삭제</button></td></tr>');
    });
    $(document).on('click', '.btnDelEvent', function () { $(this).closest('tr').remove(); });
</script>
</body>

</html>