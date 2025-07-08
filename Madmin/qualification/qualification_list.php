<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_qualification';
$table = 'qualification';
$page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$category = isset($_GET['category']) ? (int)$_GET['category'] : 1;

// 페이징 없이 전체 조회
$list = $db->query("SELECT * FROM {$this_table} WHERE page_no=:c ORDER BY prior DESC", ['c'=>$category]);
$total = count($list);
?>
<script>
function onSelectAll(allChk){
    document.querySelectorAll('.select_checkbox').forEach(ch=>ch.checked=allChk.checked);
}
function deleteEntries(){
    var arr=[];document.querySelectorAll('.select_checkbox:checked').forEach(ch=>arr.push(ch.value));
    if(arr.length===0){alert('삭제할 항목을 선택하세요.');return;}
    if(confirm('선택한 항목을 삭제하시겠습니까?')){
        location.href='/Madmin/qualification/exec.php?table=<?= $table ?>&mode=delete&selidx='+arr.join('|')+'&page=<?= $page ?>&category=<?= $category ?>';
    }
}
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>민간자격 등록현황</h3>
        <ul class="breadcrumb">
            <li>민간자격 관리</li>
            <li class="active">목록</li>
        </ul>
    </div>
    <!-- <form method="get" style="margin-bottom:10px;">
        <select name="category" onchange="this.form.submit()" class="form-control" style="width:150px; display:inline-block;">
            <?php for($i=1;$i<=5;$i++): ?>
            <option value="<?= $i ?>" <?= $category==$i?'selected':'' ?>><?= $i ?>페이지</option>
            <?php endfor; ?>
        </select>
        <input type="hidden" name="page" value="<?= $page ?>">
    </form> -->
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="200" />
                    <col width="120" />
                    <col width="160" />
                    <col width="160" />
                    <col width="80" />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <td><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></td>
                        <td>번호</td>
                        <td>자격명</td>
                        <td>자격구분</td>
                        <td>등록번호</td>
                        <td>자격관리기관</td>
                        <td>주무부처</td>
                        <td>순서</td>
                    </tr>
                </thead>
                <tbody>
                <?php if ($total > 0): ?>
                    <?php foreach ($list as $i => $row): ?>
                    <tr>
                        <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                        <td><?= $total - $i ?></td>
                        <td class=""><a href="qualification_input.php?mode=update&idx=<?= $row['idx'] ?>&page=<?= $page ?>&category=<?= $category ?>"><?= htmlspecialchars($row['f_name'],ENT_QUOTES) ?></a></td>
                        <td><?= htmlspecialchars($row['f_type'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_reg_no'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_manage_org'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_ministry'],ENT_QUOTES) ?></td>
                        <td>
                            <ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
                                <li style="float:left;width:20px;height:12px;text-align:center;">
                                    <a href="exec.php?table=<?= $table ?>&mode=prior&posi=upup&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>&page=<?= $page ?>&category=<?= $category ?>">
                                        <img src="../img/upup_icon.gif" border="0" alt="10단계 위로">
                                    </a>
                                </li>
                                <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                <li style="float:left;width:20px;height:12px;text-align:center;">
                                    <a href="exec.php?table=<?= $table ?>&mode=prior&posi=up&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>&page=<?= $page ?>&category=<?= $category ?>">
                                        <img src="../img/up_icon.gif" border="0" alt="1단계 위로">
                                    </a>
                                </li>
                                <li style="float:left;width:20px;height:12px;text-align:center;">
                                    <a href="exec.php?table=<?= $table ?>&mode=prior&posi=down&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>&page=<?= $page ?>&category=<?= $category ?>">
                                        <img src="../img/down_icon.gif" border="0" alt="1단계 아래로">
                                    </a>
                                </li>
                                <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                <li style="float:left;width:20px;height:12px;text-align:center;">
                                    <a href="exec.php?table=<?= $table ?>&mode=prior&posi=downdown&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>&page=<?= $page ?>&category=<?= $category ?>">
                                        <img src="../img/downdown_icon.gif" border="0" alt="10단계 아래로">
                                    </a>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="comACenter" height="50">등록된 데이터가 없습니다.</td></tr>
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
                <button class="btn btn-default btn-sm" type="button" onclick="location.href='qualification_input.php?page=<?= $page ?>&category=<?= $category ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
</html>