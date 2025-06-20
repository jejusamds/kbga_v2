<?php
// Madmin/df_site_application_input.php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_application';
$table = 'application';

// GET 파라미터
$idx = filter_input(INPUT_GET, 'idx', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
$f_category = filter_input(INPUT_GET, 'f_category', FILTER_SANITIZE_STRING);
$f_year = filter_input(INPUT_GET, 'f_year', FILTER_VALIDATE_INT);

$paramArr = ['page' => $page];
if ($f_category !== null)
    $paramArr['f_category'] = $f_category;
if ($f_year !== null)
    $paramArr['f_year'] = $f_year;
$param = http_build_query($paramArr);

// 기본값 셋팅
$row = [
    'f_category' => $f_category,
    'f_year' => $f_year,
    'f_round' => '',
    'f_type' => '',
    'f_registration_period' => '',
    'f_exam_date' => '',
    'f_pass_announce' => '',
    'f_registration_period_2' => '',
    'f_exam_date_2' => '',
    'f_pass_announce_2' => '',
    'f_cert_application' => ''
];

// 수정 모드일 경우 데이터 조회
if ($idx) {
    $mode = 'update';
    $row = $db->row(
        "SELECT * FROM {$this_table} WHERE idx = :idx",
        ['idx' => $idx]
    );
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.'); location.href='{$table}_list.php?{$param}';</script>";
        exit;
    }
} else {
    $mode = 'insert';
}

// 분류 맵핑 (한글)
$category_map = [
    'makeup' => '메이크업',
    'nail' => '네일',
    'hear' => '헤어',
    'skin' => '피부',
    'half' => '반영구',
    'foreign' => '해외인증',
    'teacher' => '강사인증'
];
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>시험일정 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active">시험일정 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>

    <form action="/Madmin/application/exec.php?<?= $param ?>" method="post" onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <?php if ($idx): ?><input type="hidden" name="idx" value="<?= $idx ?>"><?php endif; ?>

        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="30%">
                    <col width="20%">
                    <col width="30%">

                    <!-- 시험 분류 -->
                    <tr>
                        <th><label for="f_category">시험 분류</label></th>
                        <td colspan="3" class="comALeft">
                            <select name="f_category" id="f_category" class="form-control" style="width:30%;">
                                <option value="">선택</option>
                                <?php foreach ($category_map as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $row['f_category'] === $key ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <!-- 년도 선택 -->
                    <tr>
                        <th><label for="f_year">년도</label></th>
                        <td colspan="3" class="comALeft">
                            <select name="f_year" id="f_year" class="form-control"
                                style="width:auto; display:inline-block;">
                                <?php for ($y = date('Y') + 2; $y >= date('Y') - 5; $y--): ?>
                                    <option value="<?= $y ?>" <?= $row['f_year'] == $y ? 'selected' : '' ?>><?= $y ?>년</option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>

                    <!-- 회차 -->
                    <tr>
                        <th><label for="f_round">회차</label></th>
                        <td colspan="3" class="comALeft">
                            <!-- <input type="number" name="f_round" id="f_round"
                                value="<?= htmlspecialchars($row['f_round'], ENT_QUOTES) ?>" class="form-control"
                                style="width:20%;"> -->
                            <select name="f_round" id="f_round" class="form-control"
                                style="width:auto; display:inline-block;">
                                <?php for ($y = 1; $y <= 10; $y++): ?>
                                    <option value="<?= $y ?>" <?= $row['f_round'] == $y ? 'selected' : '' ?>><?= $y ?>회차</option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>

                    <!-- 필기 일정 -->
                    <tr>
                        <th colspan="4">필기</th>
                    </tr>

                    <tr>
                        <th><label for="f_registration_period">접수기간</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_registration_period" id="f_registration_period"
                                value="<?= htmlspecialchars($row['f_registration_period'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;" placeholder="예: 2025.03.04~10">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_exam_date">시험일</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_exam_date" id="f_exam_date"
                                value="<?= htmlspecialchars($row['f_exam_date'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;" placeholder="예: 2025.03.15">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_pass_announce">합격자 발표</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_pass_announce" id="f_pass_announce"
                                value="<?= htmlspecialchars($row['f_pass_announce'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;" placeholder="예: 2025.03.28">
                        </td>
                    </tr>

                    <!-- 실기 일정 -->
                    <tr>
                        <th colspan="4">실기</th>
                    </tr>
                    <tr>
                        <th><label for="f_registration_period_2">접수기간</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_registration_period_2" id="f_registration_period_2"
                                value="<?= htmlspecialchars($row['f_registration_period_2'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;" placeholder="예: 2025.04.04~10">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_exam_date_2">시험일</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_exam_date_2" id="f_exam_date_2"
                                value="<?= htmlspecialchars($row['f_exam_date_2'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;" placeholder="예: 2025.04.15">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="f_pass_announce_2">합격자 발표</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_pass_announce_2" id="f_pass_announce_2"
                                value="<?= htmlspecialchars($row['f_pass_announce_2'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;" placeholder="예: 2025.04.28">
                        </td>
                    </tr>

                    <!-- 자격증 신청 -->
                    <tr>
                        <th><label for="f_cert_application">자격증 신청</label></th>
                        <td colspan="3" class="comALeft">
                            <input type="text" name="f_cert_application" id="f_cert_application"
                                value="<?= htmlspecialchars($row['f_cert_application'], ENT_QUOTES) ?>"
                                class="form-control" style="width:80%;" placeholder="예: 2025.04.01~11 (순차발송)">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='<?= $table ?>_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode === 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='/Madmin/application/exec.php?table=<?= $table ?>&mode=delete&selidx=<?= $idx ?>&<?= $param ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>


</body>

</html>