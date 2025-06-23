<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$this_table = 'df_site_competition';
$table = 'competition';

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$param = "page={$page}";

if ($idx) {
    $mode = 'update';
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx'=>$idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='competition_list.php?{$param}';</script>";
        exit;
    }
} else {
    $mode = 'insert';
    $row = [
        'f_title'=>'',
        'f_date'=>'',
        'f_place'=>'',
        'f_target'=>'',
        'f_reg_period'=>'',
        'f_detail'=>'',
        'f_image'=>''
    ];
}
?>
<script language="JavaScript">
function inputCheck(f){
    if(f.f_title.value.trim()===''){alert('대회명을 입력하세요.');f.f_title.focus();return false;}
    return true;
}
function delData(id){
    if(confirm('이 항목을 삭제하시겠습니까?')){
        location.href='/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx='+id+'&<?= $param ?>';
    }
}
function deleteImage(idx, field){
    if(!confirm('이미지를 삭제하시겠습니까?')) return;
    var xhr=new XMLHttpRequest();
    xhr.open('POST','/Madmin/exec/exec.php',true);
    var fd=new FormData();
    fd.append('mode','delimg');
    fd.append('table','<?= $table ?>');
    fd.append('idx',idx);fd.append('field',field);
    xhr.onload=function(){ if(xhr.responseText.trim()==='Y'){document.getElementById(field+'_prev_img').remove();document.getElementById(field+'_del_btn').remove();}else{alert('이미지 삭제에 실패했습니다.');}};
    xhr.send(fd);
}
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 <?= $mode==='insert'?'등록':'수정' ?></h3>
        <ul class="breadcrumb">
            <li>대회 관리</li>
            <li class="active">대회 <?= $mode==='insert'?'등록':'수정' ?></li>
        </ul>
    </div>

    <form name="frm" action="/Madmin/exec/exec.php?<?= $param ?>" method="post" enctype="multipart/form-data" onsubmit="return inputCheck(this);">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%"><col width="80%">
                    <tr>
                        <th>대회명</th>
                        <td class="comALeft">
                            <input type="text" name="f_title" value="<?= htmlspecialchars($row['f_title'], ENT_QUOTES) ?>" class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>일정</th>
                        <td class="comALeft">
                            <input type="text" name="f_date" value="<?= htmlspecialchars($row['f_date'], ENT_QUOTES) ?>" class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>장소</th>
                        <td class="comALeft">
                            <input type="text" name="f_place" value="<?= htmlspecialchars($row['f_place'], ENT_QUOTES) ?>" class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>참가대상</th>
                        <td class="comALeft">
                            <input type="text" name="f_target" value="<?= htmlspecialchars($row['f_target'], ENT_QUOTES) ?>" class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>신청기간</th>
                        <td class="comALeft">
                            <input type="text" name="f_reg_period" value="<?= htmlspecialchars($row['f_reg_period'], ENT_QUOTES) ?>" class="form-control" style="width:60%;">
                        </td>
                    </tr>
                    <tr>
                        <th>세부사항</th>
                        <td class="comALeft">
                            <textarea name="f_detail" class="form-control" style="width:80%; height:120px;"><?= htmlspecialchars($row['f_detail'], ENT_QUOTES) ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일</th>
                        <td class="comALeft">
                            <input type="file" name="f_image" class="form-control" style="width:50%;">
                            <?php if($mode=='update' && $row['f_image']): ?>
                                <a href="/userfiles/competition/<?= $row['f_image'] ?>" target="_blank" id="f_image_prev_img"><?= $row['f_image'] ?></a>
                                <button class="btn btn-warning btn-xs" type="button" onclick="deleteImage(<?= $idx ?>,'f_image');" id="f_image_del_btn">이미지 삭제</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button" onClick="location.href='competition_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode=='insert'?'등록':'저장' ?></button>
                    <?php if($mode=='update'): ?>
                    <button class="btn btn-danger btn-sm" type="button" onClick="delData('<?= $idx ?>');">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>
</html>