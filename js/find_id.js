$(document).ready(function () {
    var verified = false;
    $('#send_code_btn').on('click', function (e) {
        e.preventDefault();
        if (!$('#find_email').val().trim()) {
            alert('이메일을 입력해 주세요.');
            $('#find_email').focus();
            return;
        }
        var fd = new FormData();
        fd.append('mode', 'send_code_id');
        fd.append('email', $('#find_email').val().trim());
        fetch('/controller/account_recovery.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function (res) {
                alert(res.msg);
                if (res.result === 'ok') {
                    $('.email_li.code_li').show();
                    $('#send_code_btn').text('재발송');
                }
            })
            .catch(function () {
                alert('요청 중 오류가 발생했습니다.');
            });
    });

    $('#verify_code_btn').on('click', function (e) {
        e.preventDefault();
        if (!$('#find_email_code').val().trim()) {
            alert('인증번호를 입력해 주세요.');
            $('#find_email_code').focus();
            return;
        }
        var fd = new FormData();
        fd.append('mode', 'verify_code_id');
        fd.append('code', $('#find_email_code').val().trim());
        fetch('/controller/account_recovery.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function (res) {
                alert(res.msg);
                if (res.result === 'ok') {
                    verified = true;
                }
            })
            .catch(function () {
                alert('요청 중 오류가 발생했습니다.');
            });
    });

    $('#btn_next').on('click', function (e) {
        if (!verified) {
            e.preventDefault();
            alert('인증을 완료해 주세요.');
        }
    });
});