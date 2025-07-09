<?php

$ssMenu_num = "4";
$ssMenu_slide = "3";

include 'include/center_sub_common.php';
include $_SERVER['DOCUMENT_ROOT'] . '/include/header.html';

$default = [];
// 선택된 자격분야 (GET 파라미터)
$selected_category = $_GET['category'] ?? '';
if ($is_login) {
    $default = [
        'f_user_name' => htmlspecialchars($login_user_info['f_user_name'], ENT_QUOTES),
        'f_tel' => htmlspecialchars($login_user_info['f_tel'], ENT_QUOTES),
        'f_birth_date' => htmlspecialchars(str_replace('-', '.', $login_user_info['f_birth_date']), ENT_QUOTES),
        'f_zip' => htmlspecialchars($login_user_info['f_zip'], ENT_QUOTES),
        'f_address1' => htmlspecialchars($login_user_info['f_address1'], ENT_QUOTES),
        'f_address2' => htmlspecialchars($login_user_info['f_address2'], ENT_QUOTES),
        'f_email' => htmlspecialchars($login_user_info['f_email'], ENT_QUOTES),
    ];
}

$items = $db->query("SELECT idx, f_item_name, f_category FROM df_site_qualification_item ORDER BY f_item_name ASC");
$schedules = $db->query("SELECT idx, f_year, f_round, f_type, f_category, f_registration_start, f_registration_end, f_registration_start_2, f_registration_end_2 FROM df_site_application ORDER BY f_year DESC, f_round DESC");

// 접수 가능 여부 계산 함수
function is_open_period($start, $end)
{
    if (!$start || !$end) return false;
    $today = date('Y-m-d');
    return ($today >= $start && $today <= $end);
}

foreach ($schedules as &$sc) {
    $open1 = is_open_period($sc['f_registration_start'], $sc['f_registration_end']);
    $open2 = is_open_period($sc['f_registration_start_2'], $sc['f_registration_end_2']);
    $sc['is_open'] = $open1 || $open2;
}
unset($sc);


?>

<script src="/js/form-controller.js"></script>

<div id="container">
    <div id="sub_con" class="center_sub02">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/include/sub_banner.html';
        ?>

        <div class="contents_con">

            <div class="apply_con">
                <div class="title_con">
                    <div class="text01_con">
                        <span>
                            EXAM & CERTIFICATE APPLICATION
                        </span>
                    </div>

                    <div class="text02_con">
                        <span>
                            자격시험 접수 및 발급 신청
                        </span>
                    </div>
                </div>

                <div class="nav">
                    <div class="list_con">
                        <ul>
                            <li>
                                <a href="/center/center_sub_apply.php?category=<?=$category?>&type=00" class="on">
                                    개인접수
                                </a>
                            </li>
                            <li>
                                <a href="/center/center_sub_apply_o.php?category=<?=$category?>&type=00">
                                    단체접수
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="intro_con">
                    <ul>
                        <li>
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td valign="top" align="left" class="dot_td">
                                            <div class="dot"></div>
                                        </td>
                                        <td valign="top" align="left" class="text_td">
                                            <span>
                                                단체접수는 협회에서 제공하는 <br class="m_br" /><span class="color_text">[자격증신청서
                                                    양식]</span>을 다운로드 하신 후에 <br class="m_br" />세부내용을 작성하여 파일로 첨부 하시면 됩니다.
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                        <li>
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td valign="top" align="left" class="dot_td">
                                            <div class="dot"></div>
                                        </td>
                                        <td valign="top" align="left" class="text_td">
                                            <span>
                                                협회 계좌번호 <br class="m_br" />(신한은행 100-037-545315 한국미용총연합회)
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </div>

                <div class="contents_con">

                    <form id="applyForm" action="/controller/applicate_controller.php" method="post"
                        enctype="multipart/form-data" autocomplete="off">

                        <input type="hidden" name="mode" value="register" />
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>" />
                        <input type="hidden" name="f_applicant_type" value="P" />
                        <div class="write_con">
                            <div class="contents_con">
                                <div class="input_con">
                                    <div class="form01_con">
                                        <ul>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        자격분야
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <select name="f_category" id="f_category"
                                                                        data-required="y" data-label="자격분야를"
                                                                        class="select">
                                                                        <option value="" <?= $selected_category ? '' : 'selected' ?>>자격분야를 선택해주세요.</option>
                                                                        <option value="makeup" <?= $selected_category === 'makeup' ? 'selected' : '' ?>>메이크업</option>
                                                                        <option value="nail" <?= $selected_category === 'nail' ? 'selected' : '' ?>>네일</option>
                                                                        <option value="hair" <?= $selected_category === 'hair' ? 'selected' : '' ?>>헤어</option>
                                                                        <option value="skin" <?= $selected_category === 'skin' ? 'selected' : '' ?>>피부</option>
                                                                        <option value="half" <?= $selected_category === 'half' ? 'selected' : '' ?>>반영구</option>
                                                                        <option value="foreign" <?= $selected_category === 'foreign' ? 'selected' : '' ?>>해외인증</option>
                                                                        <option value="teacher" <?= $selected_category === 'teacher' ? 'selected' : '' ?>>강사인증</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        자격종목
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <select name="f_item_idx" id="f_item_idx"
                                                                        data-required="y" data-label="자격종목을"
                                                                        class="select">
                                                                        <option value="">자격종목을 선택해주세요.</option>

                                                                        <?php foreach ($items as $it): ?>
    <option value="<?= $it['idx'] ?>" data-category="<?= $it['f_category'] ?>">
        <?= htmlspecialchars($it['f_item_name'], ENT_QUOTES) ?>
    </option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td title_td04">
                                                                    <span>
                                                                        시험일정 선택
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <select name="f_schedule_idx" id="f_schedule_idx"
                                                                        data-required="y" data-label="시험일정을"
                                                                        class="select">
                                                                        <option value="">시험일정 선택을 선택해주세요.</option>
                                                                        <option value="0" data-category="" data-open="1">상시접수</option>
                                                                        <?php foreach ($schedules as $sc): ?>
    <option value="<?= $sc['idx'] ?>" data-category="<?= $sc['f_category'] ?>" data-open="<?= $sc['is_open'] ? '1' : '0' ?>" <?= $sc['is_open'] ? '' : 'disabled' ?>>
        <?= $sc['f_year'] ?>년
        <?= $sc['f_round'] ?>회차
        <?= htmlspecialchars($sc['f_type'], ENT_QUOTES) ?><?= $sc['is_open'] ? '' : ' (마감)' ?>
    </option>
<?php endforeach; ?>

                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        이름
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="text" name="f_user_name"
                                                                        id="f_user_name" placeholder="이름을 적어주세요."
                                                                        class="input" data-required="y" data-label="이름을"
                                                                        value="<?= $default['f_user_name'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        영문이름
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="text" name="f_user_name_en"
                                                                        id="f_user_name_en" placeholder="영문이름을 적어주세요."
                                                                        class="input" data-required="y"
                                                                        data-label="영문이름을" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        연락처
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="tel" name="f_tel" id="f_tel"
                                                                        maxlength="13" placeholder="000-0000-0000"
                                                                        class="input tel_input" data-required="y"
                                                                        data-validate-type="tel" data-label="연락처를"
                                                                        value="<?= $default['f_tel'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        생년월일
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="tel" name="f_birth_date"
                                                                        placeholder="0000.00.00" id="birthdate_input"
                                                                        class="input" data-required="y"
                                                                        data-label="생년월일을"
                                                                        value="<?= $default['f_birth_date'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        우편번호
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <div class="post_con">
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="input_td">
                                                                                        <input type="text" name="f_zip"
                                                                                            id="f_zip"
                                                                                            placeholder="우편번호를 적어주세요."
                                                                                            class="input"
                                                                                            readonly="readonly"
                                                                                            data-required="y"
                                                                                            data-label="우편번호를"
                                                                                            value="<?= $default['f_zip'] ?? '' ?>" />
                                                                                    </td>
                                                                                    <td align="left" class="btn_td">
        <a href="#" class="a_btn" onclick="openPostcode(); return false;">
            검색
        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        기본주소
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="text" name="f_address1" id="f_address1"
                                                                        placeholder="기본주소를 적어주세요." class="input"
                                                                        readonly="readonly" data-required="y"
                                                                        data-label="기본주소를"
                                                                        value="<?= $default['f_address1'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        상세주소
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="text" name="f_address2" id="f_address2"
                                                                        placeholder="상세주소를 적어주세요." class="input"
                                                                        data-required="y" data-label="상세주소를"
                                                                        value="<?= $default['f_address2'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        이메일
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <input type="text" name="f_email" id="f_email"
                                                                        placeholder="이메일을 적어주세요." class="input"
                                                                        data-required="y" data-validate-type="email"
                                                                        data-label="이메일을"
                                                                        value="<?= $default['f_email'] ?? '' ?>" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td">
                                                                    <span>
                                                                        신청 구분
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <div class="application_con">
                                                                        <ul>
                                                                            <li>
                                                                                <label class="radio_label">
                                                                                    <input type="radio"
                                                                                        name="f_application_type"
                                                                                        value="exam" data-required="y"
                                                                                        data-label="신청 구분을"
                                                                                        data-tag-type="clicked"
                                                                                        checked="checked" />
                                                                                    <div class="check_icon"></div>
                                                                                    <span>
                                                                                        시험접수
                                                                                    </span>
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="radio_label">
                                                                                    <input type="radio"
                                                                                        name="f_application_type"
                                                                                        value="cert" data-required="y"
                                                                                        data-label="신청 구분을"
                                                                                        data-tag-type="clicked" />
                                                                                    <div class="check_icon"></div>
                                                                                    <span>
                                                                                        자격증 발급
                                                                                    </span>
                                                                                </label>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list_div fl">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" class="title_td title_td05">
                                                                    <span>
                                                                        자격증 <br class="m_br" />발급희망 여부
                                                                    </span>
                                                                </td>
                                                                <td align="left" class="info_td">
                                                                    <div class="hope_con">
                                                                        <ul>
                                                                            <li>
                                                                                <label class="radio_label">
                                                                                    <input type="radio"
                                                                                        name="f_issue_desire" value="1"
                                                                                        data-required="y"
                                                                                        data-label="발급희망 여부를"
                                                                                        data-tag-type="clicked"
                                                                                        checked="checked" />
                                                                                    <div class="check_icon"></div>
                                                                                    <span>
                                                                                        희망
                                                                                    </span>
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="radio_label">
                                                                                    <input type="radio"
                                                                                        name="f_issue_desire" value="0"
                                                                                        data-required="y"
                                                                                        data-label="발급희망 여부를"
                                                                                        data-tag-type="clicked" />
                                                                                    <div class="check_icon"></div>
                                                                                    <span>
                                                                                        희망하지 않음
                                                                                    </span>
                                                                                </label>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="list_div fr">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" align="left" class="title_td title_td06 valign_top_td">
                                                                    <span>
                                                                        발급희망 시
                                                                    </span>
                                                                </td>
                                                                <td valign="top" align="left" class="info_td">
                                                                    <div class="file_con">
                                                                        <table cellpadding="0" cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td valign="top" align="left" class="title_td">
                                                                                        <span>
                                                                                            사진첨부
                                                                                        </span>
                                                                                    </td>
                                                                                    <td valign="top" align="left" class="info_td">
																						<!--
                                                                                        <div class="input_con">
                                                                                            <table cellpadding="0"
                                                                                                cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left"
                                                                                                            class="input_td">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                name="f_issue_file_name"
                                                                                                                placeholder="선택된 파일 없음"
                                                                                                                class="file_upload input"
                                                                                                                readonly="readonly" />
                                                                                                        </td>
                                                                                                        <td align="left"
                                                                                                            class="btn_td">
                                                                                                            <label>
                                                                                                                <span>
                                                                                                                    파일선택
                                                                                                                </span>
                                                                                                                <input
                                                                                                                    type="file"
                                                                                                                    name="f_issue_file"
                                                                                                                    class="input"
                                                                                                                    onchange="file_upload(this.value)" />
                                                                                                            </label>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
																						-->

																						<ul>
																							<li>
																								<div class="file_div">
																									<div class="input_con">
																										<table cellpadding="0" cellspacing="0">
																											<tbody>
																												<tr>
																													<td align="left" class="input_td">
																														<input type="text" name="upfile_name" placeholder="선택된 파일 없음" class="file_upload input" readonly="readonly" />
																													</td>
																													<td align="left" class="btn_td">
																														<label>
																															<span>
																																파일선택
																															</span>
                                                                               <input type="file" name="upfile[]" class="input" onchange="file_upload(this.value)" />
																														</label>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</div>

																									<div class="btn_con">
																										<ul>
																											<li>
																												<a href="javascript:void(0);" class="a_btn add_btn">
																													추가
																												</a>
																											</li>
																											<li>
																												<a href="javascript:void(0);" class="a_btn delete_btn">
																													삭제
																												</a>
																											</li>
																										</ul>
																									</div>
																								</div>
																							</li>
																						</ul>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="form02_con">
                                        <div class="form02_div">
                                            <div class="title_con">
                                                <span>
                                                    입금여부 확인
                                                </span>
                                            </div>

                                            <div class="input_con">
                                                <ul>
                                                    <li>
                                                        <div class="list_div">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="title_td">
                                                                            <span>
                                                                                입금자
                                                                            </span>
                                                                        </td>
                                                                        <td align="left" class="info_td">
                                                                            <input type="text" name="f_payer_name"
                                                                                id="f_payer_name"
                                                                                placeholder="입금자명을 적어주세요." class="input"
                                                                                data-required="y" data-label="입금자명을" />
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="list_div">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="title_td">
                                                                            <span>
                                                                                은행(입금자)
                                                                            </span>
                                                                        </td>
                                                                        <td align="left" class="info_td">
                                                                            <select name="f_payer_bank"
                                                                                id="f_payer_bank" class="select"
                                                                                data-required="y" data-label="은행을">
                                                                                <option value="">은행(입금자)를 선택해주세요.
                                                                                </option>
                                                                                <option value="농협">농협</option>
                                                                                <option value="신한">신한</option>
                                                                                <option value="국민">국민</option>
                                                                                <option value="기업">기업</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="form02_div">
                                            <div class="title_con">
                                                <span>
                                                    입금구분(중복체크가능)
                                                </span>
                                            </div>

                                            <div class="input_con">
                                                <ul>
                                                    <li>
                                                        <div class="list_div">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="title_td">
                                                                            <span>
                                                                                접수비
                                                                            </span>
                                                                        </td>
                                                                        <td align="left" class="info_td">
                                                                            <div class="title_con m_con">
                                                                                <span>
                                                                                    접수비
                                                                                </span>
                                                                            </div>

                                                                            <div class="exam_fee_con">
                                                                                <ul>
                                                                                    <li>
                                                                                        <label class="checkbox_label">
                                                                                            <input type="checkbox"
                                                                                                name="f_payment_category[]"
                                                                                                value="written"
                                                                                                data-required="y"
                                                                                                data-label="입금 구분을"
                                                                                                data-tag-type="clicked" />
                                                                                            <div class="check_icon">
                                                                                            </div>
                                                                                            <span>
                                                                                                필기
                                                                                            </span>
                                                                                        </label>
                                                                                    </li>
                                                                                    <li>
                                                                                        <label class="checkbox_label">
                                                                                            <input type="checkbox"
                                                                                                name="f_payment_category[]"
                                                                                                value="practical"
                                                                                                data-label="입금 구분을"
                                                                                                data-tag-type="clicked" />
                                                                                            <div class="check_icon">
                                                                                            </div>
                                                                                            <span>
                                                                                                실기
                                                                                            </span>
                                                                                        </label>
                                                                                    </li>
                                                                                    <li>
                                                                                        <label class="checkbox_label">
                                                                                            <input type="checkbox"
                                                                                                name="f_payment_category[]"
                                                                                                value="issuance"
                                                                                                data-label="입금 구분을"
                                                                                                data-tag-type="clicked" />
                                                                                            <div class="check_icon">
                                                                                            </div>
                                                                                            <span>
                                                                                                발급비
                                                                                            </span>
                                                                                        </label>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="agree_con">
                                    <div class="agree_div agree02">
                                        <div class="text_con">
                                            <div class="contents_con">
                                                <span>
                                                    개인정보의 수집 및 이용 목적 <br />
                                                    서비스 이용에 따른 본인식별,실명확인, 가입의사 확인,연력제한 서비스 이용 <br />
                                                    신규서비스 등 최신정보 안내 및 개인 맞춤서비스 제공을 위한 자료 <br />
                                                    기타 원활한 양질의 서비스를 제공 등 <br />
                                                    <br />
                                                    수집하는 개인정보의 항목 <br />
                                                    이름,이메일,주민등록번호,주소,연락처, 핸드폰 번호, 그 외 선택항목
                                                </span>
                                            </div>
                                        </div>

                                        <div class="check_con">
                                            <label class="checkbox_label">
                                                <input type="checkbox" name="agree_privacy" data-required="y"
                                                    data-label="개인정보 수집 및 이용에" data-tag-type="clicked" />
                                                <div class="check_icon"></div>
                                                <span>
                                                    개인정보수집 및 이용에 동의합니다.
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="btn_con">
                                <a href="javascript:void(0);" onclick="submitForm('applyForm');" class="a_btn a_btn01">
                                    접수/신청
                                </a>

                                <a href="/index_tmp.html" class="a_btn a_btn02">
                                    취소
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript" language="javascript">
    // 생년월일
    const input = document.getElementById('birthdate_input');
    input.addEventListener('input', function () {
        let value = input.value.replace(/\D/g, ''); // 숫자 이외 제거
        if (value.length > 8) value = value.slice(0, 8); // 최대 8자리

        let formatted = '';
        if (value.length <= 4) {
            formatted = value;
        } else if (value.length <= 6) {
            formatted = `${value.slice(0, 4)}.${value.slice(4)}`;
        } else {
            formatted = `${value.slice(0, 4)}.${value.slice(4, 6)}.${value.slice(6)}`;
        }

        input.value = formatted;
    });

    // 연락처
    $(document).on("keyup", ".tel_input", function () {
        addHyphen(this);
    });

    // 연락처
    function addHyphen(element) {
        var phoneNumber = element.value.replace(/[^\d]/g, '');

        var formattedPhoneNumber = '';
        for (var i = 0; i < phoneNumber.length && i < 11; i++) {
            if (i === 3 || i === 7) {
                formattedPhoneNumber += '-';
            }
            formattedPhoneNumber += phoneNumber[i];
        }

        element.value = formattedPhoneNumber;
    }

	/*
    // 사진첨부
    function file_upload(val) {
        $(".apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td .input_con > table > tbody > tr > .input_td .input").val(val).focus();
    }
	*/

	// 추가 버튼 클릭 이벤트
	$(document).on("click", ".add_btn", function(e){
		e.preventDefault(); // a 태그 기본동작 방지
		var lastRow = $(".apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td > ul > li").last(); // 마지막 li
		var newRow = lastRow.clone(); // 복제
		// input[type=file] 비우기
		newRow.find("input[type='file']").val(""); 
		// input[type=text] 비우기 (파일명 표시 input)
		newRow.find("input[name='upfile_name']").val("").attr("placeholder", "선택된 파일 없음");
		// 새로운 행을 마지막 li 뒤에 추가
		$(".apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td > ul").append(newRow);
	});

	// 삭제 버튼 클릭 이벤트
	$(document).on("click", ".delete_btn", function(e){
		e.preventDefault(); // a 태그 기본동작 방지
		if($(".apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td > ul > li").length > 1) {
			$(this).closest('.file_div').closest('li').remove();
		}
	});

	// 파일 선택 시 파일명 표시
	function file_upload(val){
		// 파일 경로에서 파일명만 추출
		var fileName = val.split('\\').pop().split('/').pop();
		
		// 현재 클릭된 파일 input의 부모 요소에서 파일명 표시 input 찾기
		var fileInput = event.target;
		var fileNameInput = $(fileInput).closest('.apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td > ul > li .file_div').find("input[name='upfile_name']");
		
		// 파일명 표시
		fileNameInput.val(fileName);
	}

	// 파일 input change 이벤트도 추가로 처리
        $(document).on("change", "input[type='file'][name^='upfile']", function(){
		var fileName = this.value.split('\\').pop().split('/').pop();
		$(this).closest('.apply_con > .contents_con .write_con > .contents_con > .input_con .list_div > table > tbody > tr > .info_td .file_con > table > tbody > tr > .info_td > ul > li .file_div').find("input[name='upfile_name']").val(fileName);
	});

    // 자격분야 변경 시 자격종목 필터링
    const categorySelect = document.getElementById('f_category');
    const itemSelect = document.getElementById('f_item_idx');
    const itemOptions = Array.from(itemSelect.querySelectorAll('option')).filter(opt => opt.value !== '');

    const itemPlaceholder = itemSelect.querySelector('option[value=""]');

    const scheduleSelect = document.getElementById('f_schedule_idx');
    const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option')).filter(opt => opt.value !== '');
    const schedulePlaceholder = scheduleSelect.querySelector('option[value=""]');

    function updateItemOptions() {
        const selected = categorySelect.value;
        // 초기화
        itemSelect.innerHTML = '';

        if (itemPlaceholder) itemSelect.appendChild(itemPlaceholder);
        itemOptions.forEach(opt => {
            if (selected && opt.dataset.category === selected) {
                itemSelect.appendChild(opt);
            }
        });
        itemSelect.value = '';

        scheduleSelect.innerHTML = '';
        if (schedulePlaceholder) scheduleSelect.appendChild(schedulePlaceholder);
        if (selected) {
            scheduleOptions.forEach(opt => {
                if (opt.dataset.category === selected || opt.dataset.category === '') {
                    scheduleSelect.appendChild(opt);
                }
            });
            scheduleSelect.disabled = false;
        } else {
            scheduleSelect.disabled = true;
        }
        scheduleSelect.value = '';
    }

    categorySelect.addEventListener('change', updateItemOptions);
    // 초기 호출 (페이지 로드 시 기본값 적용)
    updateItemOptions();

</script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function openPostcode() {
        new daum.Postcode({
            oncomplete: function (data) {
                document.getElementById('f_zip').value = data.zonecode;
                document.getElementById('f_address1').value = data.address;
                document.getElementById('f_address2').focus();
            }
        }).open();
    }

</script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/footer.html';
?>