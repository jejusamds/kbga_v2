<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_main_slide';
$table = 'main_slide';

// 페이징 없이 전체 조회
$list = $db->query("SELECT * FROM {$this_table} ORDER BY prior DESC");
?>
<script>
    function onSelectAll(allChk) {
        document.querySelectorAll('.select_checkbox').forEach(ch => ch.checked = allChk.checked);
    }
    function deleteEntries() {
        var arr = []; document.querySelectorAll('.select_checkbox:checked').forEach(ch => arr.push(ch.value));
        if (arr.length === 0) { alert('삭제할 항목을 선택하세요.'); return; }
        if (confirm('선택한 항목을 삭제하시겠습니까?')) {
            location.href = '/Madmin/main_slide/main_slide_save.php?mode=delete&selidx=' + arr.join('|');
        }
    }
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>메인 화면 슬라이드 관리</h3>
        <ul class="breadcrumb">
            <li>메인 관리</li>
            <li class="active">목록</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="200" />
                    <col width="200" />
                    <col width="80" />
                    <col width="80" />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>PC 썸네일</th>
                        <th>모바일 썸네일</th>
                        <th>타입</th>
                        <th>순서</th>
                        <th>작성일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($list) > 0):
                        foreach ($list as $i => $row): ?>
                            <tr>
                                <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                                <td><?= count($list) - $i ?></td>
                                <td class="comALeft">
                                    <a href="main_slide_input.php?idx=<?= $row['idx'] ?>">
                                        <?php if ($row['thumbnail_pc']): ?>
                                            <img src="/userfiles/main_slide/<?= $row['thumbnail_pc'] ?>" style="height:50px;">
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td class="comALeft">
                                    <a href="main_slide_input.php?idx=<?= $row['idx'] ?>">
                                        <?php if ($row['thumbnail_m']): ?>
                                            <img src="/userfiles/main_slide/<?= $row['thumbnail_m'] ?>" style="height:50px;">
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?= $row['media_type'] ?></td>
                                <td>
                                    <ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="main_slide_save.php?mode=prior&posi=upup&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/upup_icon.gif" border="0" alt="10단계 위로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="main_slide_save.php?mode=prior&posi=up&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/up_icon.gif" border="0" alt="1단계 위로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="main_slide_save.php?mode=prior&posi=down&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/down_icon.gif" border="0" alt="1단계 아래로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="main_slide_save.php?mode=prior&posi=downdown&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/downdown_icon.gif" border="0" alt="10단계 아래로">
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </td>
                                <td><?= substr($row['wdate'], 0, 10) ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" height="50" class="comACenter">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onclick="deleteEntries();">삭제</button>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onclick="location.href='main_slide_input.php';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>