$(document).ready(function(){
    $('#btn_change_pw').on('click', function(e){
        e.preventDefault();
        if(!$('#new_password').val().trim()){
            alert('비밀번호를 입력해 주세요.');
            $('#new_password').focus();
            return;
        }
        if(!$('#new_password_chk').val().trim()){
            alert('비밀번호 확인을 입력해 주세요.');
            $('#new_password_chk').focus();
            return;
        }
        if($('#new_password').val() !== $('#new_password_chk').val()){
            alert('비밀번호가 일치하지 않습니다.');
            $('#new_password_chk').focus();
            return;
        }
        var fd=new FormData();
        fd.append('mode','reset_password');
        fd.append('password',$('#new_password').val());
        fd.append('password_chk',$('#new_password_chk').val());
        fetch('/controller/account_recovery.php',{method:'POST',body:fd})
            .then(r=>r.json())
            .then(function(res){
                alert(res.msg);
                if(res.result==='ok'){
                    location.href = res.redirect;
                }
            })
            .catch(function(){
                alert('요청 중 오류가 발생했습니다.');
            });
    });
});