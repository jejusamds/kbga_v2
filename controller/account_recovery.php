<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

function safe_json(array $arr)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr);
    exit;
}

$mode = $_POST['mode'] ?? '';
$allowed = ['send_code_id', 'verify_code_id', 'send_code_pw', 'verify_code_pw', 'reset_password'];
if (!in_array($mode, $allowed, true))
    safe_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);

$filtered = array_map(function ($v) {
    return SQL_Injection(RemoveXSS($v)); }, $_POST);

switch ($mode) {
    case 'send_code_id':
        if (empty($filtered['email']))
            safe_json(['result' => 'error', 'msg' => '이메일을 입력해 주세요.']);
        $row = $db->row("SELECT f_user_id FROM df_site_member WHERE f_email = :e", ['e' => $filtered['email']]);
        if (!$row)
            safe_json(['result' => 'error', 'msg' => '입력하신 정보가 일치하는 회원이 없습니다.']);
        $code = random_int(100000, 999999);
        $_SESSION['find_id'] = ['email' => $filtered['email'], 'code' => $code, 'user_id' => $row['f_user_id']];
        $subject = '[KBGA] 인증번호 안내';
        $content = "인증번호는 <strong>{$code}</strong> 입니다.";
        $res = send_mail('kbga', 'kblsm917@gmail.com', '', $filtered['email'], $subject, $content);
        if ($res !== true)
            safe_json(['result' => 'error', 'msg' => '이메일 발송 실패']);
        safe_json(['result' => 'ok', 'msg' => '인증번호가 발송되었습니다.']);
        break;
    case 'verify_code_id':
        if (empty($filtered['code']))
            safe_json(['result' => 'error', 'msg' => '인증번호를 입력해 주세요.']);
        if (empty($_SESSION['find_id']) || $filtered['code'] != $_SESSION['find_id']['code']) {
            safe_json(['result' => 'error', 'msg' => '인증번호가 일치하지 않습니다.']);
        }
        $_SESSION['find_id_verified'] = true;
        safe_json(['result' => 'ok', 'redirect' => '/member/find_id_end.html']);
        break;
    case 'send_code_pw':
        if (empty($filtered['user_id']) || empty($filtered['email'])) {
            safe_json(['result' => 'error', 'msg' => '필수 정보를 입력해 주세요.']);
        }
        $row = $db->row("SELECT idx FROM df_site_member WHERE f_user_id=:id AND f_email=:e", ['id' => $filtered['user_id'], 'e' => $filtered['email']]);
        if (!$row)
            safe_json(['result' => 'error', 'msg' => '입력하신 정보가 일치하는 회원이 없습니다.']);
        $code = random_int(100000, 999999);
        $_SESSION['find_pw'] = ['user_id' => $filtered['user_id'], 'code' => $code];
        $subject = '[KBGA] 인증번호 안내';
        $content = "인증번호는 <strong>{$code}</strong> 입니다.";
        $res = send_mail('kbga', 'kblsm917@gmail.com', '', $filtered['email'], $subject, $content);
        if ($res !== true)
            safe_json(['result' => 'error', 'msg' => '이메일 발송 실패']);
        safe_json(['result' => 'ok', 'msg' => '인증번호가 발송되었습니다.']);
        break;
    case 'verify_code_pw':
        if (empty($filtered['code']))
            safe_json(['result' => 'error', 'msg' => '인증번호를 입력해 주세요.']);
        if (empty($_SESSION['find_pw']) || $filtered['code'] != $_SESSION['find_pw']['code']) {
            safe_json(['result' => 'error', 'msg' => '인증번호가 일치하지 않습니다.']);
        }
        $_SESSION['find_pw_verified'] = true;
        safe_json(['result' => 'ok', 'redirect' => '/member/find_pw_end.html']);
        break;
    case 'reset_password':
        if (empty($_SESSION['find_pw_verified']) || empty($_SESSION['find_pw']['user_id'])) {
            safe_json(['result' => 'error', 'msg' => '인증이 필요합니다.']);
        }
        if (empty($filtered['password']) || empty($filtered['password_chk'])) {
            safe_json(['result' => 'error', 'msg' => '비밀번호를 입력해 주세요.']);
        }
        if ($filtered['password'] !== $filtered['password_chk']) {
            safe_json(['result' => 'error', 'msg' => '비밀번호가 일치하지 않습니다.']);
        }
        if (!preg_match('/^[!-~]{4,12}$/', $filtered['password'])) {
            safe_json(['result' => 'error', 'msg' => '비밀번호 형식이 올바르지 않습니다.']);
        }
        $hashed = password_hash($filtered['password'], PASSWORD_DEFAULT);
        $db->query("UPDATE df_site_member SET f_password=:p WHERE f_user_id=:id", ['p' => $hashed, 'id' => $_SESSION['find_pw']['user_id']]);
        unset($_SESSION['find_pw'], $_SESSION['find_pw_verified']);
        safe_json(['result' => 'ok', 'msg' => '비밀번호가 변경되었습니다.', 'redirect' => '/member/login.html']);
        break;
}
?>