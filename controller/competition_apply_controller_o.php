<?php
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'].'/inc/util_lib.inc';

function auto_filter_input(string $data){
    return SQL_Injection(RemoveXSS($data));
}

function return_json(array $ret){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

if($_SERVER['REQUEST_METHOD']!=='POST'){
    return_json(['result'=>'error','msg'=>'잘못된 요청입니다.']);
}

$approved=['register'];
if(empty($_POST['mode'])||!in_array($_POST['mode'],$approved,true)){
    return_json(['result'=>'error','msg'=>'잘못된 요청입니다.']);
}

$filtered=[];
foreach($_POST as $k=>$v){
    $filtered[$k]=is_array($v)?array_map('auto_filter_input',$v):auto_filter_input($v);
}

if(empty($filtered['csrf_token'])||$filtered['csrf_token']!==$_SESSION['csrf_token']){
    return_json(['result'=>'error','msg'=>'잘못된 접근입니다 (CSRF).']);
}

$required=[
    'f_competition_idx'=>'대회구분을 선택해주세요.',
    'f_part'=>'참가부문을 선택해주세요.',
    'f_field'=>'종목분야를 선택해주세요.',
    'f_event'=>'참가종목을 선택해주세요.',
    'f_user_name'=>'단체명을 입력해주세요.',
    'f_user_name_en'=>'담당자를 입력해주세요.',
    'f_tel'=>'연락처를 입력해주세요.',
    'f_contact_phone'=>'담당자 연락처를 입력해주세요.',
    'f_zip'=>'우편번호를 입력해주세요.',
    'f_address1'=>'기본주소를 입력해주세요.',
    'f_address2'=>'상세주소를 입력해주세요.',
    'f_email'=>'이메일을 입력해주세요.',
    'f_payer_name'=>'입금자명을 입력해주세요.',
    'f_payer_bank'=>'은행을 선택해주세요.'
];
foreach($required as $field=>$msg){
    if(empty($filtered[$field])){
        return_json(['result'=>'blank','field'=>$field,'msg'=>$msg]);
    }
}
if(empty($filtered['f_payment_category'])||!is_array($filtered['f_payment_category'])){
    return_json(['result'=>'blank','field'=>'f_payment_category','msg'=>'입금 구분을 선택해주세요.']);
}
if(empty($filtered['agree_terms'])||empty($filtered['agree_privacy'])){
    return_json(['result'=>'blank','field'=>'agree_terms','msg'=>'약관에 동의해주세요.']);
}

$payment_cat=implode(',', $filtered['f_payment_category']);

$uploadName=null;
if(!empty($_FILES['f_issue_file']['name'])){
    $orig=$_FILES['f_issue_file']['name'];
    $tmp=$_FILES['f_issue_file']['tmp_name'];
    $err=$_FILES['f_issue_file']['error'];
    $ext=strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    $allowed=['jpg','jpeg','png','gif','pdf'];
    if($err!==UPLOAD_ERR_OK){
        return_json(['result'=>'error','msg'=>'파일 업로드 중 오류가 발생했습니다.']);
    }
    if(!in_array($ext,$allowed,true)){
        return_json(['result'=>'error','msg'=>'허용되지 않는 파일 형식입니다.']);
    }
    $dir=$_SERVER['DOCUMENT_ROOT'].'/userfiles/registration';
    if(!is_dir($dir)) mkdir($dir,0755,true);
    $new=uniqid('',true).'.'.$ext;
    if(!move_uploaded_file($tmp,$dir.'/'.$new)){
        return_json(['result'=>'error','msg'=>'파일 저장에 실패했습니다.']);
    }
    $uploadName=$new;
}

$params=[
    'f_competition_idx'=>(int)$filtered['f_competition_idx'],
    'f_part'=>$filtered['f_part'],
    'f_field'=>$filtered['f_field'],
    'f_event'=>$filtered['f_event'],
    'f_user_name'=>$filtered['f_user_name'],
    'f_user_name_en'=>$filtered['f_user_name_en'],
    'f_tel'=>$filtered['f_tel'],
    'f_contact_phone'=>$filtered['f_contact_phone'],
    'f_zip'=>$filtered['f_zip'],
    'f_address1'=>$filtered['f_address1'],
    'f_address2'=>$filtered['f_address2'],
    'f_email'=>$filtered['f_email'],
    'f_issue_file'=>$uploadName,
    'f_payer_name'=>$filtered['f_payer_name'],
    'f_payer_bank'=>$filtered['f_payer_bank'],
    'f_payment_category'=>$payment_cat
];

$sql="INSERT INTO df_site_competition_registration (
        f_competition_idx,f_part,f_field,f_event,
        f_user_name,f_user_name_en,f_tel,f_contact_phone,
        f_zip,f_address1,f_address2,f_email,f_issue_file,
        f_payer_name,f_payer_bank,f_payment_category
    ) VALUES (
        :f_competition_idx,:f_part,:f_field,:f_event,
        :f_user_name,:f_user_name_en,:f_tel,:f_contact_phone,
        :f_zip,:f_address1,:f_address2,:f_email,:f_issue_file,
        :f_payer_name,:f_payer_bank,:f_payment_category
    )";
$db->query($sql,$params);

return_json(['result'=>'ok','msg'=>'접수가 완료되었습니다.','redirect'=>'/']);