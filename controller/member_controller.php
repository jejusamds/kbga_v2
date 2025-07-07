<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";

if (isset($_GET['mode']) && $_GET['mode'] === 'logout') {
    unset($_SESSION['kbga_user_id']);
    // 로그아웃 후 로그인 페이지로 리다이렉트
    header('Location: /member/login.html');
    exit;
}

/**
 * 보안 필터
 */
function auto_filter_input(string $data): string
{
    return SQL_Injection(RemoveXSS($data));
}

/**
 * JSON 응답 헬퍼
 */
function return_json(array $ret): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

$approved = ['sign_up', 'reset_csrf_token', 'check_id', 'login', 'modify_profile', 'secession'];
if (empty($_POST['mode']) || !in_array($_POST['mode'], $approved, true)) {
    //return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$filtered = array_map('auto_filter_input', $_POST);

// 이메일 분할 입력 지원
if (empty($filtered['f_email']) && !empty($filtered['f_email_id']) && !empty($filtered['f_email_domain'])) {
    $filtered['f_email'] = $filtered['f_email_id'] . '@' . $filtered['f_email_domain'];
}

if ($filtered['mode'] === 'reset_csrf_token') {
    unset($_SESSION['csrf_token']);
    return_json(['result' => 'ok', 'msg' => '토큰이 재발급되었습니다.']);
}

// CSRF 검사
if (empty($filtered['csrf_token']) || $filtered['csrf_token'] !== $_SESSION['csrf_token']) {
    //return_json(['result' => 'error', 'msg' => '잘못된 접근입니다 (CSRF).']);
}

if ($filtered['mode'] === 'check_id') {
    if (empty($filtered['f_user_id'])) {
        return_json(['result' => 'error', 'msg' => '아이디를 입력해주세요.']);
    }
    $sql = "SELECT COUNT(*) AS cnt FROM df_site_member WHERE f_user_id = :f_user_id";
    $row = $db->row($sql, ['f_user_id' => $filtered['f_user_id']]);
    $cnt = $row['cnt'];
    return_json([
        'result' => $cnt ? 'exist' : 'ok'
    ]);
}

if ($filtered['mode'] === 'sign_up') {

    if (!empty($filtered['f_honey'])) {
        return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
    }

    // 개인회원
    if ($filtered['member_type'] == 'P') {
        $required = [
            'f_user_name' => '이름을 입력해주세요.',
            'f_birth_date_1' => '생년월일을 입력해주세요.',
            'f_birth_date_2' => '생년월일을 입력해주세요.',
            'f_birth_date_3' => '생년월일을 입력해주세요.',
            'f_gender' => '성별을 선택해주세요.',
            // 'f_tel_1' => '전화번호를 입력해주세요.',
            // 'f_tel_2' => '전화번호를 입력해주세요.',
            // 'f_tel_3' => '전화번호를 입력해주세요.',
            'f_mobile_1' => '휴대전화번호를 입력해주세요.',
            'f_mobile_2' => '휴대전화번호를 입력해주세요.',
            'f_mobile_3' => '휴대전화번호를 입력해주세요.',
            'f_zip' => '주소를 입력해주세요.',
            'f_address1' => '주소를 입력해주세요.',
            'f_user_id' => '아이디를 입력해주세요.',
            'f_password' => '비밀번호를 입력해주세요.',
            'f_password_chk' => '비밀번호 확인을 입력해주세요.',
            'f_email' => '이메일을 입력해주세요.',
        ];
        foreach ($required as $field => $msg) {
            if (empty($filtered[$field])) {
                return_json([
                    'result' => 'blank',
                    'field' => $field,
                    'msg' => $msg
                ]);
            }
        }

        if ($filtered['f_password'] !== $filtered['f_password_chk']) {
            return_json(['result' => 'error', 'msg' => '비밀번호가 일치하지 않습니다.']);
        }

        if (!preg_match('/^[A-Za-z0-9]{4,11}$/', $filtered['f_user_id'])) {
            return_json([
                'result' => 'error',
                'field' => 'f_user_id',
                'msg' => '아이디는 4자 이상 12자 미만의 영문 및 숫자 조합만 가능합니다.'
            ]);
        }

        if (!preg_match('/^[!-~]{4,12}$/', $filtered['f_password'])) {
            return_json([
                'result' => 'error',
                'field' => 'f_password',
                'msg' => '비밀번호는 4~12자의 영문, 숫자, 특수문자를 사용할 수 있으며 영문은 대소문자 구분합니다.'
            ]);
        }

        // **아이디 중복 체크**
        $sql = "SELECT COUNT(*) AS cnt FROM df_site_member WHERE f_user_id = :f_user_id";
        $row = $db->row($sql, ['f_user_id' => $filtered['f_user_id']]);
        $cnt = $row['cnt'];
        if ($cnt) {
            return_json([
                'result' => 'error',
                'field' => 'f_user_id',
                'msg' => '이미 사용 중인 아이디입니다.'
            ]);
        }

        $birth_date = sprintf(
            '%04d-%02d-%02d',
            (int) $filtered['f_birth_date_1'],
            (int) $filtered['f_birth_date_2'],
            (int) $filtered['f_birth_date_3']
        );
        $tel = "{$filtered['f_tel_1']}-{$filtered['f_tel_2']}-{$filtered['f_tel_3']}";
        $mobile = "{$filtered['f_mobile_1']}-{$filtered['f_mobile_2']}-{$filtered['f_mobile_3']}";

        $params = [
            'f_member_type' => $filtered['member_type'],
            'f_user_name' => $filtered['f_user_name'],
            'f_birth_date' => $birth_date,
            'f_gender' => ($filtered['f_gender'] === 'F' ? 'F' : 'M'),
            'f_tel' => $tel,
            'f_mobile' => $mobile,
            'f_affiliation_flag' => ($filtered['f_affiliation_flag'] === 'Y' ? 'Y' : 'N'),
            'f_affiliation_name' => $filtered['f_affiliation_name'] ?? null,
            'f_zip' => $filtered['f_zip'],
            'f_address1' => $filtered['f_address1'],
            'f_address2' => $filtered['f_address2'],
            'f_user_id' => $filtered['f_user_id'],
            'f_password' => password_hash($filtered['f_password'], PASSWORD_DEFAULT),
            'f_email' => $filtered['f_email'],
            'f_email_consent' => ($filtered['f_email_consent'] === 'Y' ? 'Y' : 'N'),
        ];

        $sql = "INSERT INTO df_site_member (
                f_member_type,
                f_user_name,
                f_birth_date,
                f_gender,
                f_tel,
                f_mobile,
                f_affiliation_flag,
                f_affiliation_name,
                f_zip,
                f_address1,
                f_address2,
                f_user_id,
                f_password,
                f_email,
                f_email_consent
            ) VALUES (
                :f_member_type,
                :f_user_name,
                :f_birth_date,
                :f_gender,
                :f_tel,
                :f_mobile,
                :f_affiliation_flag,
                :f_affiliation_name,
                :f_zip,
                :f_address1,
                :f_address2,
                :f_user_id,
                :f_password,
                :f_email,
                :f_email_consent
            )
        ";

        if (!$db->query($sql, $params)) {
            return_json(['result' => 'error', 'msg' => '회원가입 중 오류가 발생했습니다.']);
        }

        return_json([
            'result' => 'ok',
            'msg' => '회원가입이 완료되었습니다.',
            'redirect' => '/member/join_step03_individual.html'
        ]);
    } else {

        // 단체회원
        $required = [
            'f_org_name' => '단체명을 입력해주세요.',
            'f_org_phone_1' => '단체 전화번호를 입력해주세요.',
            'f_org_phone_2' => '단체 전화번호를 입력해주세요.',
            'f_org_phone_3' => '단체 전화번호를 입력해주세요.',
            'f_contact_name' => '담당자명을 입력해주세요.',
            'f_contact_phone_1' => '담당자 연락처를 입력해주세요.',
            'f_contact_phone_2' => '담당자 연락처를 입력해주세요.',
            'f_contact_phone_3' => '담당자 연락처를 입력해주세요.',
            'f_zip' => '주소를 입력해주세요.',
            'f_address1' => '주소를 입력해주세요.',
            'f_user_id' => '아이디를 입력해주세요.',
            'f_password' => '비밀번호를 입력해주세요.',
            'f_password_chk' => '비밀번호 확인을 입력해주세요.',
            'f_email' => '이메일을 입력해주세요.',
        ];
        foreach ($required as $field => $msg) {
            if (empty($filtered[$field])) {
                return_json([
                    'result' => 'blank',
                    'field' => $field,
                    'msg' => $msg
                ]);
            }
        }

        if ($filtered['f_password'] !== $filtered['f_password_chk']) {
            return_json(['result' => 'error', 'msg' => '비밀번호가 일치하지 않습니다.']);
        }

        if (!preg_match('/^[A-Za-z0-9]{4,11}$/', $filtered['f_user_id'])) {
            return_json([
                'result' => 'error',
                'field' => 'f_user_id',
                'msg' => '아이디는 4자 이상 12자 미만의 영문 및 숫자 조합만 가능합니다.'
            ]);
        }

        if (!preg_match('/^[!-~]{4,12}$/', $filtered['f_password'])) {
            return_json([
                'result' => 'error',
                'field' => 'f_password',
                'msg' => '비밀번호는 4~12자의 영문, 숫자, 특수문자를 사용할 수 있으며 영문은 대소문자 구분합니다.'
            ]);
        }

        $sql = "SELECT COUNT(*) AS cnt FROM df_site_member WHERE f_user_id = :f_user_id";
        $row = $db->row($sql, ['f_user_id' => $filtered['f_user_id']]);
        $cnt = $row['cnt'];
        if ($cnt) {
            return_json([
                'result' => 'error',
                'field' => 'f_user_id',
                'msg' => '이미 사용 중인 아이디입니다.'
            ]);
        }

        $org_phone = sprintf(
            '%s-%s-%s',
            $filtered['f_org_phone_1'],
            $filtered['f_org_phone_2'],
            $filtered['f_org_phone_3']
        );
        $contact_phone = sprintf(
            '%s-%s-%s',
            $filtered['f_contact_phone_1'],
            $filtered['f_contact_phone_2'],
            $filtered['f_contact_phone_3']
        );

        $params = [
            'f_member_type' => 'O',
            'f_org_name' => $filtered['f_org_name'],
            'f_org_phone' => $org_phone,
            'f_contact_name' => $filtered['f_contact_name'],
            'f_contact_phone' => $contact_phone,
            'f_zip' => $filtered['f_zip'],
            'f_address1' => $filtered['f_address1'],
            'f_address2' => $filtered['f_address2'] ?? null,
            'f_user_id' => $filtered['f_user_id'],
            'f_password' => password_hash($filtered['f_password'], PASSWORD_DEFAULT),
            'f_email' => $filtered['f_email'],
            'f_email_consent' => ($filtered['f_email_consent'] === 'Y' ? 'Y' : 'N'),
        ];

        $sql = "
        INSERT INTO df_site_member (
            f_member_type,
            f_org_name,
            f_org_phone,
            f_contact_name,
            f_contact_phone,
            f_zip,
            f_address1,
            f_address2,
            f_user_id,
            f_password,
            f_email,
            f_email_consent
        ) VALUES (
            :f_member_type,
            :f_org_name,
            :f_org_phone,
            :f_contact_name,
            :f_contact_phone,
            :f_zip,
            :f_address1,
            :f_address2,
            :f_user_id,
            :f_password,
            :f_email,
            :f_email_consent
        )
    ";
        if (!$db->query($sql, $params)) {
            return_json(['result' => 'error', 'msg' => '단체회원가입 중 오류가 발생했습니다.']);
        }

        return_json([
            'result' => 'ok',
            'msg' => '회원가입이 완료되었습니다.',
            //'params' => $params,
            //'sql' => $sql,
            'redirect' => '/member/join_step03_group.html'
        ]);
    }
}

// ----------------------
// 로그인 처리
// ----------------------
if ($filtered['mode'] === 'login') {
    $required = [
        'f_user_id' => '아이디를 입력해주세요.',
        'f_password' => '비밀번호를 입력해주세요.',
    ];
    foreach ($required as $field => $msg) {
        if (empty($filtered[$field])) {
            return_json([
                'result' => 'blank',
                'field' => $field,
                'msg' => $msg
            ]);
        }
    }

    $sql = "SELECT f_password, f_member_type FROM df_site_member WHERE f_user_id = :f_user_id AND is_out = 1";
    $row = $db->row($sql, ['f_user_id' => $filtered['f_user_id']]);
    if (!$row) {
        return_json(['result' => 'error', 'msg' => '아이디 또는 비밀번호가 일치하지 않습니다.']);
    }

    if (!password_verify($filtered['f_password'], $row['f_password'])) {
        return_json(['result' => 'error', 'msg' => '아이디 또는 비밀번호가 일치하지 않습니다.']);
    }

    $_SESSION['kbga_user_id'] = $filtered['f_user_id'];
    $_SESSION['kbga_member_type'] = $row['f_member_type'];


    return_json([
        'result' => 'ok',
        'msg' => '',
        'redirect' => '/'  // 로그인 후 이동할 페이지
    ]);
}

if ($filtered['mode'] === 'secession') {
    if (empty($_SESSION['kbga_user_id'])) {
        return_json(['result' => 'error', 'msg' => '로그인이 필요합니다.']);
    }

    if (empty($filtered['f_password'])) {
        return_json(['result' => 'blank', 'field' => 'f_password', 'msg' => '비밀번호를 입력해주세요.']);
    }

    $row = $db->row(
        "SELECT f_password FROM df_site_member WHERE f_user_id = :f_user_id AND is_out = 1",
        ['f_user_id' => $_SESSION['kbga_user_id']]
    );
    if (!$row || !password_verify($filtered['f_password'], $row['f_password'])) {
        return_json(['result' => 'error', 'msg' => '비밀번호가 일치하지 않습니다.']);
    }

    $db->query(
        "INSERT INTO df_site_member_out (f_user_id, reason) VALUES (:f_user_id, :reason)",
        ['f_user_id' => $_SESSION['kbga_user_id'], 'reason' => $filtered['reason']]
    );
    $db->query(
        "UPDATE df_site_member SET is_out = 2 WHERE f_user_id = :f_user_id",
        ['f_user_id' => $_SESSION['kbga_user_id']]
    );

    unset($_SESSION['kbga_user_id']);
    unset($_SESSION['kbga_member_type']);

    return_json([
        'result' => 'ok',
        'msg' => '탈퇴 처리되었습니다.',
        'redirect' => '/'
    ]);
}


if ($filtered['mode'] === 'modify_profile') {

    if (!empty($filtered['f_honey'])) {
        return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
    }

    //  - f_user_name, birth, gender, tel/mobile parts, 주소, 비밀번호 확인, 이메일 등



    if (empty($filtered['f_password_old'])) {
        return_json([
            'result' => 'blank',
            'field' => 'f_password_old',
            'msg' => '기존 비밀번호를 입력해주세요.'
        ]);
    }

    $rowPass = $db->row(
        "SELECT f_password FROM df_site_member WHERE f_user_id = :f_user_id",
        ['f_user_id' => $_SESSION['kbga_user_id']]
    );
    if (!$rowPass || !password_verify($filtered['f_password_old'], $rowPass['f_password'])) {
        return_json(['result' => 'error', 'msg' => '기존 비밀번호가 일치하지 않습니다.']);
    }

    if (!empty($filtered['f_password'])) {
        // 비밀번호 확인
        if ($filtered['f_password'] !== $filtered['f_password_chk']) {
            return_json(['result' => 'error', 'msg' => '새 비밀번호가 일치하지 않습니다.']);
        }
        // 형식 검증: 4~12자 허용, 영문/숫자/특수문자 가능
        if (!preg_match('/^[!-~]{4,12}$/', $filtered['f_password'])) {
            return_json([
                'result' => 'error',
                'field' => 'f_password',
                'msg' => '비밀번호는 4~12자의 영문, 숫자, 특수문자를 사용할 수 있으며 영문은 대소문자 구분합니다.'
            ]);
        }
        $changePwd = true;
    }

    if ($_SESSION['kbga_member_type'] === 'P') {

        $setParts = [
            'f_user_name = :f_user_name',
            'f_birth_date = :f_birth_date',
            'f_gender = :f_gender',
            'f_tel = :f_tel',
            'f_mobile = :f_mobile',
            'f_zip = :f_zip',
            'f_address1 = :f_address1',
            'f_address2 = :f_address2',
            'f_email = :f_email',
            'f_email_consent = :f_email_consent',
            'f_affiliation_flag = :f_affiliation_flag',
            'f_affiliation_name = :f_affiliation_name'
        ];
        $params = [
            'f_user_name' => $filtered['f_user_name'],
            'f_birth_date' => sprintf(
                '%04d-%02d-%02d',
                $filtered['f_birth_date_1'],
                $filtered['f_birth_date_2'],
                $filtered['f_birth_date_3']
            ),
            'f_gender' => $filtered['f_gender'],
            'f_tel' => "{$filtered['f_tel_1']}-{$filtered['f_tel_2']}-{$filtered['f_tel_3']}",
            'f_mobile' => "{$filtered['f_mobile_1']}-{$filtered['f_mobile_2']}-{$filtered['f_mobile_3']}",
            'f_zip' => $filtered['f_zip'],
            'f_address1' => $filtered['f_address1'],
            'f_address2' => $filtered['f_address2'] ?? null,
            'f_email' => $filtered['f_email'],
            'f_email_consent' => ($filtered['f_email_consent'] === 'Y' ? 'Y' : 'N'),
            'f_affiliation_flag' => ($filtered['f_affiliation_flag'] === 'Y' ? 'Y' : 'N'),
            'f_affiliation_name' => $filtered['f_affiliation_name'] ?? null,
            'f_user_id' => $_SESSION['kbga_user_id']
        ];

        if ($changePwd) {
            $setParts[] = 'f_password = :f_password';
            $params['f_password'] = password_hash($filtered['f_password'], PASSWORD_DEFAULT);
        }

        $sql = "
            UPDATE df_site_member
            SET " . implode(",\n            ", $setParts) . "
            WHERE f_user_id = :f_user_id
        ";
        $result = $db->query($sql, $params);

        if ($result === false) {
            return_json(['result' => 'error', 'msg' => '회원정보 수정 중 오류가 발생했습니다.', 'sql' => $sql, 'params' => $params]);
        }
    } else {
        // 단체회원인 경우

        $org_phone = sprintf(
            '%s-%s-%s',
            $filtered['f_org_phone_1'],
            $filtered['f_org_phone_2'],
            $filtered['f_org_phone_3']
        );
        $contact_phone = sprintf(
            '%s-%s-%s',
            $filtered['f_contact_phone_1'],
            $filtered['f_contact_phone_2'],
            $filtered['f_contact_phone_3']
        );

        $setParts = [
            'f_org_name       = :f_org_name',
            'f_org_phone      = :f_org_phone',
            'f_contact_name   = :f_contact_name',
            'f_contact_phone  = :f_contact_phone',
            'f_zip            = :f_zip',
            'f_address1       = :f_address1',
            'f_address2       = :f_address2',
            'f_email          = :f_email',
            'f_email_consent  = :f_email_consent'
        ];

        $params = [
            'f_org_name' => $filtered['f_org_name'],
            'f_org_phone' => $org_phone,
            'f_contact_name' => $filtered['f_contact_name'],
            'f_contact_phone' => $contact_phone,
            'f_zip' => $filtered['f_zip'],
            'f_address1' => $filtered['f_address1'],
            'f_address2' => $filtered['f_address2'] ?? null,
            'f_email' => $filtered['f_email'],
            'f_email_consent' => ($filtered['f_email_consent'] === 'Y' ? 'Y' : 'N'),
            'f_user_id' => $_SESSION['kbga_user_id']
        ];

        if (!empty($filtered['f_password'])) {
            $setParts[] = 'f_password = :f_password';
            $params['f_password'] = password_hash($filtered['f_password'], PASSWORD_DEFAULT);
        }

        $sql = "
            UPDATE df_site_member
            SET " . implode(",\n                ", $setParts) . "
            WHERE f_user_id = :f_user_id
        ";
        $result = $db->query($sql, $params);
        if ($result === false) {
            return_json([
                'result' => 'error',
                'msg' => '회원정보 수정 중 오류가 발생했습니다.',
                'sql' => $sql,
                'params' => $params
            ]);
        }
    }

    return_json([
        'result' => 'ok',
        'msg' => '회원정보가 수정되었습니다.',
        'redirect' => '/mypage/modify.html'
    ]);
}