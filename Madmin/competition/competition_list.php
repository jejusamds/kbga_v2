<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = "df_site_competition";
$table = "competition";
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$page_set = 15;
$block_set = 10;

$total = $db->single("SELECT COUNT(*) FROM {$this_table}");
$pageCnt = (int)(($total - 1) / $page_set) + 1;
if ($page > $pageCnt) $page = $pageCnt > 0 ? $pageCnt : 1;

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM {$this_table} ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql);
}
?>
<script language="JavaScript" type="text/javascript">
function onSelectAll(allChk){
    var chks=document.querySelectorAll('.select_checkbox');
    for(var i=0;i<chks.length;i++){chks[i].checked=allChk.checked;}
}
function deleteEntries(){
    var sel=[];
    document.querySelectorAll('.select_checkbox:checked').forEach(function(cb){sel.push(cb.value);});
    if(sel.length===0){alert('삭제할 항목을 선택하세요.');return;}
    if(confirm('선택한 항목을 삭제하시겠습니까?')){
        location.href='/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx='+sel.join('|')+'&page=<?= $page ?>';
    }
}
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 관리</h3>
        <ul class="breadcrumb">
            <li>대회 관리</li>
            <li class="active">대회 목록</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="200" />
                    <col width="150" />
                    <col width="120" />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>대회명</th>
                        <th>일정</th>
                        <th>장소</th>
                        <th>작성일</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($total > 0): ?>
                    <?php foreach ($list as $i => $row): ?>
                    <tr>
                        <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                        <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                        <td class="comALeft">
                            <a href="competition_input.php?mode=update&idx=<?= $row['idx'] ?>&page=<?= $page ?>">
                                <?= htmlspecialchars($row['f_title'], ENT_QUOTES) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($row['f_date'], ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_place'], ENT_QUOTES) ?></td>
                        <td><?= substr($row['wdate'],0,10) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" height="50" class="comACenter">등록된 데이터가 없습니다.</td></tr>
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
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, ""); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button" onClick="location.href='competition_input.php?page=<?= $page ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
</html>