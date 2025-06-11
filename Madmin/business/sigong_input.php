<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

// 모드 및 파라미터 처리
$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : '';
$search_usage = isset($_GET['usage']) ? trim($_GET['usage']) : '';
$search_region = isset($_GET['region']) ? trim($_GET['region']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$param = "page=$page&usage=$search_usage&region=$search_region";
$this_table = "df_site_sigong";
$table = "sigong";

if ($idx !== '') {
    // 업데이트 모드: 기존 데이터 조회
    $mode = 'update';
    $sql = "SELECT * FROM {$this_table} WHERE idx = '{$idx}'";
    $row = $db->row($sql);
    if (!$row) {
        error("잘못된 접근입니다.", $table . "_list.php?$param");
        exit;
    }
} else {
    $mode = 'insert';
    // 빈 배열로 초기화
    $row = [
        'f_usage' => '',
        'f_region' => '',
        'f_thumbnail' => '',
        'f_address' => '',
        'f_period' => '',
        'f_scale' => '',
        'f_progress' => 0,
    ];
}
?>
<script language="JavaScript">
    // 입력 검증
    function inputCheck(f) {
        if (f.f_usage.value == '') {
            alert('용도를 선택해 주세요.');
            return false;
        }
        if (f.f_region.value == '') {
            alert('지역을 선택해 주세요.');
            return false;
        }
        if (f.f_address.value.trim() == '') {
            alert('주소를 입력해 주세요.');
            f.f_address.focus();
            return false;
        }
        var prog = parseInt(f.f_progress.value, 10);
        if (isNaN(prog) || prog < 0 || prog > 100) {
            alert('공정률은 0에서 100 사이 숫자로 입력해 주세요.');
            f.f_progress.focus();
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

        if (confirm('이 시공사업을 삭제하시겠습니까?')) {
            location.href = '/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx=' + id + '&<?= $param ?>';
        }
    }

    function deleteImage(idx, field) {
        if (!confirm('이미지를 삭제하시겠습니까?')) return;
        // AJAX 요청
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/Madmin/exec/exec.php', true);
        var formData = new FormData();
        formData.append('mode', 'delimg');
        formData.append('table', '<?= $table ?>');
        formData.append('idx', idx);
        formData.append('field', field);
        xhr.onload = function () {
            if (xhr.responseText.trim() === 'Y') {
                // 삭제 성공 시, 이미지 태그와 버튼 제거
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


    // 첨부파일 삭제
    $(document).on('click', '.btnDelFiles', function () {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx[]"]').val();
        if (old_idx != '') {
            if (!confirm('파일삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg&idx=' + old_idx + "&table=<?= $table ?>";
            $.ajax({
                type: 'post',
                url: '../exec/exec.php',
                data: params,
                dataType: 'html',
                success: function (res) {
                    console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('tr').remove();
                    }
                },
                error: function (e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        } else {
            $this.closest('tr').remove();
        }
    });

    // 첨부파일 추가
    $(document).on('click', '.btnAddFiles', function () {
        var html = '';
        html += '<tr>\n';
        html += '<th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" type="button">파일삭제</button></th>\n';
        html += '<td class="comALeft" colspan="3">\n';
        html += '<input type="hidden" name="old_idx[]" value="" />\n';
        html += '<input name="upfile[]" type="file" class="form-control" style="width:50%; margin-right:15px;">\n';
        html += '</td>\n';
        html += '</tr>\n';

        $('#tableFiles').append(html);
    });

</script>


<div class="pageWrap">
    <div class="page-heading">
        <h3>사업실적 <?= ($mode == 'insert' ? '등록' : '수정') ?></h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active">시공사업 <?= ($mode == 'insert' ? '등록' : '수정') ?></li>
        </ul>
    </div>

    <form name="frm" action="../exec/exec.php?<?= $param ?>" method="post" enctype="multipart/form-data"
        onsubmit="return inputCheck(this)">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="usage" value="<?= htmlspecialchars($search_usage) ?>">
        <input type="hidden" name="region" value="<?= htmlspecialchars($search_region) ?>">

        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%" />
                    <col width="30%" />
                    <col width="20%" />
                    <col width="30%" />

                    <tr>
                        <th>용도</th>
                        <td colspan="3" class="comALeft">
                            <label><input type="radio" name="f_usage" value="업무/근생" <?= $row['f_usage'] == '업무/근생' ? 'checked' : '' ?>> 업무/근생</label>&nbsp;
                            <label><input type="radio" name="f_usage" value="물류시설" <?= $row['f_usage'] == '물류시설' ? 'checked' : '' ?>> 물류시설</label>&nbsp;
                            <label><input type="radio" name="f_usage" value="주거시설" <?= $row['f_usage'] == '주거시설' ? 'checked' : '' ?>> 주거시설</label>&nbsp;
                            <label><input type="radio" name="f_usage" value="리모델링" <?= $row['f_usage'] == '리모델링' ? 'checked' : '' ?>> 리모델링</label>&nbsp;
                            <label><input type="radio" name="f_usage" value="기타" <?= $row['f_usage'] == '기타' ? 'checked' : '' ?>> 기타</label>
                        </td>
                    </tr>
                    <tr>
                        <th>지역</th>
                        <td colspan="3" class="comALeft">
                            <label><input type="radio" name="f_region" value="서울" <?= $row['f_region'] == '서울' ? 'checked' : '' ?>> 서울</label>&nbsp;
                            <label><input type="radio" name="f_region" value="경기" <?= $row['f_region'] == '경기' ? 'checked' : '' ?>> 경기</label>&nbsp;
                            <label><input type="radio" name="f_region" value="기타" <?= $row['f_region'] == '기타' ? 'checked' : '' ?>> 기타</label>
                        </td>
                    </tr>
                    <tr>
                        <th>썸네일 이미지</th>
                        <td colspan="3" class="comALeft">
                            <input type="file" name="f_thumbnail" class="form-control" style="width:30%; ">
                            <?php if ($mode == 'update' && $row['f_thumbnail'] != ''): ?>
                                <a href="/userfiles/<?= $table ?>/<?= $row['f_thumbnail'] ?>" target="_blank">
                                    <!-- <img src="<?= $row['f_thumbnail'] ?>" height="50" style="background:#555;" id="f_thumbnail_prev_img" /> -->
                                    <span id="f_thumbnail_prev_img"><?= $row['f_thumbnail'] ?></span>
                                </a>
                                <button class="btn btn-warning btn-xs" type="button" id="f_thumbnail_del_btn"
                                    onclick="deleteImage(<?= $idx ?>, 'f_thumbnail')" style="margin-left:10px;">이미지
                                    삭제</button>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <tr>
                        <th>주소</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_address"
                                value="<?= htmlspecialchars($row['f_address'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;" placeholder="주소를 입력하세요">
                        </td>
                    </tr>
                    <tr>
                        <th>공사기간</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_period"
                                value="<?= htmlspecialchars($row['f_period'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;" placeholder="예: 2025-01-01 ~ 2025-12-31">
                        </td>
                    </tr>
                    <tr>
                        <th>규모</th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_scale"
                                value="<?= htmlspecialchars($row['f_scale'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;" placeholder="규모를 입력하세요">
                        </td>
                    </tr>
                    <tr>
                        <th>공정률</th>
                        <td colspan="3" class="comALeft">
                            <input type="number" name="f_progress" value="<?= (int) $row['f_progress'] ?>"
                                class="form-control" style="width:20%;" min="0" max="100"> %
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-shopping-cart"></i>
                    <span>첨부 파일</span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddFiles" type="button">파일추가</button>
                </div>
                <table id="tableFiles" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="15%" />
                    <col width="35%" />
                    <col width="15%" />
                    <col width="35%" />
                    <tbody>
                        <?
                        $sqlF = " Select * From df_site_{$table}_files Where bbsidx='" . $idx . "' Order by idx Asc ";
                        $rsF = $db->query($sqlF);
                        for ($ii = 0; $ii < count($rsF); $ii++) {
                            ?>
                            <tr>
                                <th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles"
                                        type="button">파일삭제</button></th>
                                <td class="comALeft" colspan="3">
                                    <input type="hidden" name="old_idx[]" value="<?= $rsF[$ii]['idx'] ?>" />
                                    <input name="upfile[]" type="file" class="form-control"
                                        style="width:50%; margin-right:15px;">
                                    <? if ($rsF[$ii]['upfile'] != "") { ?>
                                        <a href="/userfiles/<?= $table ?>/<?= $rsF[$ii]['upfile'] ?>"
                                            target="_blank"><?= $rsF[$ii]['upfile_name'] ?></a>
                                    <? } ?>
                                </td>
                            </tr>
                        <?
                        }
                        ?>
                    </tbody>
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
                    <button class="btn btn-info btn-sm" type="submit"><?= ($mode == 'insert') ? '등록' : '저장' ?></button>
                    <?php if ($mode == "update"): ?>
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