<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

// 모드 및 파라미터 처리
$idx       = isset($_GET['idx'])       ? (int) $_GET['idx']       : '';
$keyword   = isset($_GET['keyword'])   ? trim($_GET['keyword'])   : '';
$page      = isset($_GET['page'])      ? (int) $_GET['page']      : 1;

$param     = "page={$page}&keyword=" . urlencode($keyword);
$this_table = "df_site_sihang";
$table      = "sihang";
$dir        = "business";

if ($idx !== '') {
    // 업데이트 모드: 기존 데이터 조회
    $mode = 'update';
    $sql  = "SELECT * FROM {$this_table} WHERE idx = '{$idx}'";
    $row  = $db->row($sql);
    if (!$row) {
        error("잘못된 접근입니다.", "{$table}_list.php?{$param}");
        exit;
    }
} else {
    $mode = 'insert';
    // 빈 배열로 초기화
    $row = [
        'f_thumbnail' => '',
        'f_type'      => '',
        'f_name'      => '',
        'f_url'       => '',
    ];
}
?>
<script language="JavaScript">
    // 입력 검증
    function inputCheck(f) {
        if (f.f_type.value.trim() == '') {
            alert('시설 종류를 입력해 주세요.');
            f.f_type.focus();
            return false;
        }
        if (f.f_name.value.trim() == '') {
            alert('공사명을 입력해 주세요.');
            f.f_name.focus();
            return false;
        }
        if ('<?= $mode ?>' == 'insert' && f.f_thumbnail.files.length == 0) {
            alert('썸네일 이미지를 업로드해 주세요.');
            f.f_thumbnail.focus();
            return false;
        }
        return true;
    }

    function delData(id) {
        if (confirm('이 데이터를 삭제하시겠습니까?')) {
            location.href = '/Madmin/exec/exec.php'
                + '?table=<?= $table ?>'
                + '&mode=delete'
                + '&selidx=' + id
                + '&<?= $param ?>';
        }
    }

    function deleteImage(idx, field) {
        if (!confirm('이미지를 삭제하시겠습니까?')) return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/Madmin/exec/exec.php', true);
        var formData = new FormData();
        formData.append('mode', 'delimg');
        formData.append('table', '<?= $table ?>');
        formData.append('idx', idx);
        formData.append('field', field);
        xhr.onload = function () {
            if (xhr.responseText.trim() === 'Y') {
                var img = document.getElementById(field + '_prev_img');
                var btn = document.getElementById(field + '_del_btn');
                if (img) img.remove();
                if (btn) btn.remove();
            } else {
                alert('이미지 삭제에 실패했습니다.');
            }
        };
        xhr.send(formData);
    }
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>사업실적 <?= ($mode == 'insert' ? '등록' : '수정') ?></h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active">시행사업 <?= ($mode == 'insert' ? '등록' : '수정') ?></li>
        </ul>
    </div>

    <form name="frm" action="/Madmin/exec/exec.php?<?= $param ?>" method="post" enctype="multipart/form-data"
          onsubmit="return inputCheck(this)">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">

        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%" />
                    <col width="30%" />
                    <col width="20%" />
                    <col width="30%" />

                    <tr>
                        <th>썸네일 이미지</th>
                        <td colspan="3" class="comALeft">
                            <input type="file" name="f_thumbnail" class="form-control" style="width:30%;">
                            <?php if ($mode == 'update' && $row['f_thumbnail'] != ''): ?>
                                <br>
                                <a href="/userfiles/<?= $table ?>/<?= htmlspecialchars($row['f_thumbnail'], ENT_QUOTES) ?>" target="_blank">
                                    <img src="/userfiles/<?= $table ?>/<?= htmlspecialchars($row['f_thumbnail'], ENT_QUOTES) ?>"
                                         height="50" id="f_thumbnail_prev_img" />
                                </a>
                                <button class="btn btn-warning btn-xs" type="button" id="f_thumbnail_del_btn"
                                        onclick="deleteImage(<?= $idx ?>, 'f_thumbnail')" style="margin-left:10px;">
                                    이미지 삭제
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>시설 종류</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_type" value="<?= htmlspecialchars($row['f_type'], ENT_QUOTES) ?>"
                                   class="form-control" style="width:80%;" placeholder="예: 공원, 교육시설">
                        </td>
                    </tr>
                    <tr>
                        <th>공사명</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_name" value="<?= htmlspecialchars($row['f_name'], ENT_QUOTES) ?>"
                                   class="form-control" style="width:80%;" placeholder="공사명을 입력하세요">
                        </td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_url" value="<?= htmlspecialchars($row['f_url'], ENT_QUOTES) ?>"
                                   class="form-control" style="width:80%;" placeholder="링크 주소를 입력하세요">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                            onClick="location.href='<?= $table ?>_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">
                        <?= ($mode == 'insert') ? '등록' : '저장' ?>
                    </button>
                    <?php if ($mode == "update"): ?>
                        <button class="btn btn-danger btn-sm" type="button" onclick="delData('<?= $idx ?>');">
                            삭제
                        </button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>

</body>
</html>
