<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_agency';
$table = 'agency';
$type = isset($_GET['type']) ? $_GET['type'] : 'cooperate';
$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$param = "page={$page}&type={$type}";

$mode = 'insert';
$row = ['f_name'=>'','f_img'=>'','f_img_m'=>''];
if($idx){
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx'=>$idx]);
    if(!$row){ echo "<script>alert('잘못된 접근입니다.');location.href='agency_list.php?{$param}';</script>"; exit; }
    $mode = 'update';
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>기관 <?= $mode==='insert'?'등록':'수정' ?></h3>
        <ul class="breadcrumb">
            <li>기관 관리</li>
            <li class="active">기관 <?= $mode==='insert'?'등록':'수정' ?></li>
        </ul>
    </div>
    <form action="/Madmin/agency/agency_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="table" value="<?=$table?>">
        <input type="hidden" name="mode" value="<?=$mode?>">
        <input type="hidden" name="idx" value="<?=$idx?>">
        <input type="hidden" name="f_type" value="<?=$type?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%"><col width="80%">
                    <tr>
                        <th>기관명</th>
                        <td class="comALeft"><input type="text" name="f_name" value="<?=htmlspecialchars($row['f_name'],ENT_QUOTES)?>" class="form-control" style="width:60%;"></td>
                    </tr>
                    <?php if ($type == 'manage') { ?>
                    <tr>
                        <th>링크 URL</th>
                        <td class="comALeft"><input type="text" name="f_url" value="<?=htmlspecialchars($row['f_url'] ?? '',ENT_QUOTES)?>" class="form-control" style="width:60%;"></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>이미지(PC)</th>
                        <td class="comALeft">
                            <input type="file" name="f_img" class="form-control" style="width:60%;">
                            <?php if($mode=='update' && $row['f_img']): ?>
                                <a href="/userfiles/agency/<?= $row['f_img'] ?>" target="_blank"><?=$row['f_img']?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>이미지(Mobile)</th>
                        <td class="comALeft">
                            <input type="file" name="f_img_m" class="form-control" style="width:60%;">
                            <?php if($mode=='update' && $row['f_img_m']): ?>
                                <a href="/userfiles/agency/<?= $row['f_img_m'] ?>" target="_blank"><?=$row['f_img']?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button" onclick="location.href='agency_list.php?<?=$param?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?=$mode==='insert'?'등록':'저장'?></button>
                    <?php if($mode=='update'): ?>
                    <button class="btn btn-danger btn-sm" type="button" onclick="if(confirm('삭제하시겠습니까?'))location.href='agency_save.php?mode=delete&selidx=<?=$idx?>&<?=$param?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>
</html>