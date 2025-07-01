<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$sql = "SELECT t1.*, c.f_title
        FROM df_site_competition_registration t1
        LEFT JOIN df_site_competition c ON t1.f_competition_idx = c.idx
        WHERE t1.idx = '{$idx}'";
$row = $db->row($sql);
if (!$row) {
    error('잘못된 접근입니다.', 'competition_list.php');
    exit;
}
// 값 출력 시 사용될 공통 함수
function printValue($val)
{
    return nl2br(safeAdminOutput($val));
}

function printType($val)
{
    switch ($val) {
        case 'P':
            return '개인';
        case 'O':
            return '단체';
        default:
            return safeAdminOutput($val);
    }
}

?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 신청 상세</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">대회</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table noMargin" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:200px;">신청자구분</td>
                    <td><?= printType($row['f_applicant_type']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">대회구분</td>
                    <td><?= printValue($row['f_title']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">참가부문</td>
                    <td><?= printValue($row['f_part']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">종목분야</td>
                    <td><?= printValue($row['f_field']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">참가종목</td>
                    <td><?= printValue($row['f_event']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">이름</td>
                    <td><?= printValue($row['f_user_name']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">성별</td>
                    <td><?= printValue($row['f_gender']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">영문이름</td>
                    <td><?= printValue($row['f_user_name_en']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">생년월일</td>
                    <td><?= printValue($row['f_birth_date']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">연락처</td>
                    <td><?= printValue($row['f_tel']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">이메일</td>
                    <td><?= printValue($row['f_email']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">우편번호</td>
                    <td><?= printValue($row['f_zip']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">기본주소</td>
                    <td><?= printValue($row['f_address1']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">상세주소</td>
                    <td><?= printValue($row['f_address2']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">입금자명</td>
                    <td><?= printValue($row['f_payer_name']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">은행</td>
                    <td><?= printValue($row['f_payer_bank']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">입금 구분</td>
                    <td><?= printValue($row['f_payment_category']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">회원ID</td>
                    <td><?= printValue($row['f_user_id']) ?></td>
                </tr>
                <tr>
                    <td style="width:200px;">등록일</td>
                    <td><?= printValue($row['reg_date']) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comACenter" style="width:10%;">
                <button class="btn btn-primary btn-sm" type="button"
                    onclick="location.href='competition_list.php?page=<?= $page ?>';">목록</button>
            </div>
            <div class="comFRight comACenter" style="width:10%;">
                <button class="btn btn-success btn-sm" type="button"
                    onclick="location.href='reg_competition_view_excel.php?idx=<?= $idx ?>';">엑셀파일저장</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>