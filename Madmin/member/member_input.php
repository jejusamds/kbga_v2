<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_member';
$table = 'member';
$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$param = "page={$page}";

$mode = 'insert';
$row = [
    'f_member_type' => 'P',
    'f_user_name' => '',
    'f_birth_date' => '',
    'f_gender' => '',
    'f_tel' => '',
    'f_mobile' => '',
    'f_affiliation_flag' => 'N',
    'f_affiliation_name' => '',
    'f_org_name' => '',
    'f_org_phone' => '',
    'f_contact_name' => '',
    'f_contact_phone' => '',
    'f_zip' => '',
    'f_address1' => '',
    'f_address2' => '',
    'f_user_id' => '',
    'f_password' => '',
    'f_email' => '',
    'f_email_consent' => 'Y',
    'f_marketing_agree' => 'N',
    'wdate' => ''
];

if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
        exit;
    }
    $mode = 'update';
    $app_list = $db->query("SELECT * FROM df_site_application_registration WHERE f_user_id=:uid ORDER BY idx DESC", ['uid' => $row['f_user_id']]);
    $comp_list = $db->query("SELECT * FROM df_site_competition_registration WHERE f_user_id=:uid ORDER BY idx DESC", ['uid' => $row['f_user_id']]);
    $edu_list = $db->query("SELECT * FROM df_site_edu_registration WHERE f_user_id=:uid ORDER BY idx DESC", ['uid' => $row['f_user_id']]);
} else {
    $app_list = $comp_list = $edu_list = [];
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>회원 <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>회원 관리</li>
            <li class="active">회원 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>
    <form method="post" action="/Madmin/exec/exec.php?<?= $param ?>" onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <?php if ($idx): ?><input type="hidden" name="idx" value="<?= $idx ?>"><?php endif; ?>
        <input type="hidden" name="page" value="<?= $page ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>회원유형</th>
                        <td class="comALeft">
                            <select name="f_member_type" class="form-control" style="width:auto;">
                                <option value="P" <?= $row['f_member_type'] == 'P' ? 'selected' : '' ?>>개인</option>
                                <option value="O" <?= $row['f_member_type'] == 'O' ? 'selected' : '' ?>>단체</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td class="comALeft"><input type="text" name="f_user_name"
                                value="<?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>생년월일</th>
                        <td class="comALeft"><input type="text" name="f_birth_date"
                                value="<?= htmlspecialchars($row['f_birth_date'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>성별</th>
                        <td class="comALeft">
                            <select name="f_gender" class="form-control" style="width:auto;">
                                <option value="" <?= $row['f_gender'] == '' ? 'selected' : '' ?>>선택</option>
                                <option value="M" <?= $row['f_gender'] == 'M' ? 'selected' : '' ?>>남</option>
                                <option value="F" <?= $row['f_gender'] == 'F' ? 'selected' : '' ?>>여</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>전화번호</th>
                        <td class="comALeft"><input type="text" name="f_tel"
                                value="<?= htmlspecialchars($row['f_tel'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>휴대전화번호</th>
                        <td class="comALeft"><input type="text" name="f_mobile"
                                value="<?= htmlspecialchars($row['f_mobile'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>소속단체 여부</th>
                        <td class="comALeft">
                            <select name="f_affiliation_flag" class="form-control" style="width:auto;">
                                <option value="N" <?= $row['f_affiliation_flag'] == 'N' ? 'selected' : '' ?>>N</option>
                                <option value="Y" <?= $row['f_affiliation_flag'] == 'Y' ? 'selected' : '' ?>>Y</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>소속단체 이름</th>
                        <td class="comALeft"><input type="text" name="f_affiliation_name"
                                value="<?= htmlspecialchars($row['f_affiliation_name'], ENT_QUOTES) ?>"
                                class="form-control" style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>단체명</th>
                        <td class="comALeft"><input type="text" name="f_org_name"
                                value="<?= htmlspecialchars($row['f_org_name'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>단체 전화번호</th>
                        <td class="comALeft"><input type="text" name="f_org_phone"
                                value="<?= htmlspecialchars($row['f_org_phone'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>담당자명</th>
                        <td class="comALeft"><input type="text" name="f_contact_name"
                                value="<?= htmlspecialchars($row['f_contact_name'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>담당자 연락처</th>
                        <td class="comALeft"><input type="text" name="f_contact_phone"
                                value="<?= htmlspecialchars($row['f_contact_phone'], ENT_QUOTES) ?>"
                                class="form-control" style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>우편번호</th>
                        <td class="comALeft"><input type="text" name="f_zip"
                                value="<?= htmlspecialchars($row['f_zip'], ENT_QUOTES) ?>" class="form-control"
                                style="width:30%;"></td>
                    </tr>
                    <tr>
                        <th>기본주소</th>
                        <td class="comALeft"><input type="text" name="f_address1"
                                value="<?= htmlspecialchars($row['f_address1'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>상세주소</th>
                        <td class="comALeft"><input type="text" name="f_address2"
                                value="<?= htmlspecialchars($row['f_address2'], ENT_QUOTES) ?>" class="form-control"
                                style="width:80%;"></td>
                    </tr>
                    <tr>
                        <th>아이디</th>
                        <td class="comALeft"><input type="text" name="f_user_id"
                                value="<?= htmlspecialchars($row['f_user_id'], ENT_QUOTES) ?>" class="form-control"
                                style="width:40%;" <?= $mode == 'update' ? 'readonly' : '' ?>></td>
                    </tr>
                    <!-- <tr>
                        <th>비밀번호</th>
                        <td class="comALeft"><input type="text" name="f_password"
                                value="<?= htmlspecialchars($row['f_password'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr> -->
                    <tr>
                        <th>이메일</th>
                        <td class="comALeft"><input type="text" name="f_email"
                                value="<?= htmlspecialchars($row['f_email'], ENT_QUOTES) ?>" class="form-control"
                                style="width:60%;"></td>
                    </tr>
                    <tr>
                        <th>이메일 수신 동의</th>
                        <td class="comALeft">
                            <select name="f_email_consent" class="form-control" style="width:auto;">
                                <option value="Y" <?= $row['f_email_consent'] == 'Y' ? 'selected' : '' ?>>Y</option>
                                <option value="N" <?= $row['f_email_consent'] == 'N' ? 'selected' : '' ?>>N</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>마케팅 동의</th>
                        <td class="comALeft">
                            <select name="f_marketing_agree" class="form-control" style="width:auto;">
                                <option value="Y" <?= $row['f_marketing_agree'] == 'Y' ? 'selected' : '' ?>>Y</option>
                                <option value="N" <?= $row['f_marketing_agree'] == 'N' ? 'selected' : '' ?>>N</option>
                            </select>
                        </td>
                    </tr>
                    <?php if ($mode == 'update'): ?>
                        <tr>
                            <th>가입일</th>
                            <td class="comALeft"><?= htmlspecialchars($row['wdate']) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='member_list.php?<?= $param ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">저장</button>
                    <?php if ($mode == 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="location.href='/Madmin/exec/exec.php?table=<?= $table ?>&mode=delete&selidx=<?= $idx ?>&page=<?= $page ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
    <?php if ($mode == 'update'): ?>
        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title"><span>신청 내역</span></div>

                <h4 style="margin-top:10px;">자격증</h4>
                <table class="table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>상세</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($app_list as $a): ?>
                            <tr>
                                <td><?= $a['idx'] ?></td>
                                <td><?= $a['wdate'] ?? $a['reg_date'] ?></td>
                                <td>
                                    <button class="btn btn-xs btn-success" target="_blank" 
                                    onclick="window.open('/Madmin/registration/reg_application_view.php?idx=<?= $a['idx'] ?>','_blank');">상세</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($app_list)): ?>
                            <tr>
                                <td colspan="3" class="comACenter">신청 내역이 없습니다.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h4 style="margin-top:20px;">대회</h4>
                <table class="table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>상세</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comp_list as $c): ?>
                            <tr>
                                <td><?= $c['idx'] ?></td>
                                <td><?= $c['reg_date'] ?? $c['wdate'] ?></td>
                                <td>
                                    <button class="btn btn-xs btn-success"
                                        onclick="window.open('/Madmin/registration/reg_competition_view.php?idx=<?= $c['idx'] ?>','_blank');">상세</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($comp_list)): ?>
                            <tr>
                                <td colspan="3" class="comACenter">신청 내역이 없습니다.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h4 style="margin-top:20px;">교육</h4>
                <table class="table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>상세</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($edu_list as $e): ?>
                            <tr>
                                <td><?= $e['idx'] ?></td>
                                <td><?= $e['reg_date'] ?? $e['wdate'] ?></td>
                                <td>
                                    <button class="btn btn-xs btn-success"
                                        onclick="window.open('/Madmin/registration/reg_edu_view.php?idx=<?= $e['idx'] ?>','_blank');">상세</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($edu_list)): ?>
                            <tr>
                                <td colspan="3" class="comACenter">신청 내역이 없습니다.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>

</html>