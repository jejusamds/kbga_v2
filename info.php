<?php


phpinfo();
exit;

include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";

// var_dump($_SESSION);
// exit;

// $db->query("ALTER TABLE df_site_application_registration ADD COLUMN f_user_id varchar(128)");
// $db->query("ALTER TABLE df_site_competition_registration ADD COLUMN f_user_id varchar(128)");
// $db->query("ALTER TABLE df_site_edu_registration ADD COLUMN f_user_id varchar(128)");


// exit;

// $db->query("
// CREATE TABLE `df_site_competition_registration` (
//   `idx`                  INT UNSIGNED       NOT NULL AUTO_INCREMENT COMMENT 'PK',
//   `f_competition_idx`    INT                NOT NULL             COMMENT '대회구분',
//   `f_part`               VARCHAR(50)        NOT NULL             COMMENT '참가부문',
//   `f_field`              VARCHAR(50)        NOT NULL             COMMENT '종목분야',
//   `f_event`              VARCHAR(100)       NOT NULL             COMMENT '참가종목',
//   `f_user_name`          VARCHAR(50)        NOT NULL             COMMENT '이름',
//   `f_gender`             CHAR(1)            NOT NULL             COMMENT '성별 (M/F)',
//   `f_user_name_en`       VARCHAR(100)       NOT NULL             COMMENT '영문이름',
//   `f_birth_date`         DATE               NOT NULL             COMMENT '생년월일',
//   `f_tel`                VARCHAR(20)        NOT NULL             COMMENT '연락처',
//   `f_email`              VARCHAR(100)       NOT NULL             COMMENT '이메일',
//   `f_zip`                VARCHAR(10)        NOT NULL             COMMENT '우편번호',
//   `f_address1`           VARCHAR(255)       NOT NULL             COMMENT '기본주소',
//   `f_address2`           VARCHAR(255)       NOT NULL             COMMENT '상세주소',
//   `f_payer_name`         VARCHAR(100)       NOT NULL             COMMENT '입금자명',
//   `f_payer_bank`         VARCHAR(50)        NOT NULL             COMMENT '입금은행',
//   `f_payment_category`   VARCHAR(100)       NOT NULL             COMMENT '입금구분 (중복가능)',
//   `reg_date`             DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP                   COMMENT '등록일시',
//   `mod_date`             DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
//   PRIMARY KEY (`idx`)
// ) ENGINE=InnoDB
//   DEFAULT CHARSET=utf8mb4
//   COLLATE=utf8mb4_general_ci
//   COMMENT='대회 접수 정보';
// ");

//  exit;

// $sql = "ALTER TABLE `df_site_competition_registration`
//   MODIFY `f_contact_phone` VARCHAR(20)  NULL DEFAULT '' COMMENT '연락처',
//   MODIFY `f_issue_file`    VARCHAR(255) NULL DEFAULT '' COMMENT '발급 파일명';
// ";

// $db->query($sql);
// exit;

$sql = "show tables";

$list = $db->query($sql);
foreach ($list as $row) {

    $table_name = $row['Tables_in_dbkbga8800'];
    $sql2 = "SHOW CREATE TABLE `{$table_name}`";

//echo $table_name;

    $table_info = $db->query($sql2);

    var_dump($table_info);

    echo "<br>";
    echo "<br>";
}


$table = "df_site_application_registration";
$sql = "select * from {$table} order by idx desc";
$list = $db->query($sql);
var_dump($list );
foreach ($list as $row) {
    var_dump($row);
    echo "<br>";
    echo "<br>";
}