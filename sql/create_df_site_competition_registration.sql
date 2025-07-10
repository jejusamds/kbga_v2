CREATE TABLE `df_site_competition_registration` (
	`idx` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`f_applicant_status` ENUM('ing','done','cancle','hold','re') NOT NULL DEFAULT 'ing' COLLATE 'utf8mb4_general_ci',
	`f_competition_idx` INT NOT NULL COMMENT '대회구분',
	`f_applicant_type` CHAR(1) NOT NULL COMMENT '접수유형 (P=개인, O=단체)' COLLATE 'utf8mb4_general_ci',
	`f_part` VARCHAR(500) NOT NULL COMMENT '참가부문' COLLATE 'utf8mb4_general_ci',
	`f_part_title` VARCHAR(500) NOT NULL COMMENT '참가부분의 문구' COLLATE 'utf8mb4_general_ci',
	`f_field` VARCHAR(500) NOT NULL COMMENT '종목분야' COLLATE 'utf8mb4_general_ci',
	`f_field_title` VARCHAR(500) NOT NULL COMMENT '종목분야의 문구' COLLATE 'utf8mb4_general_ci',
	`f_event` VARCHAR(500) NOT NULL COMMENT '참가종목' COLLATE 'utf8mb4_general_ci',
	`f_event_title` VARCHAR(500) NOT NULL COMMENT '참가종목의 문구' COLLATE 'utf8mb4_general_ci',
	`f_user_name` VARCHAR(50) NOT NULL COMMENT '이름' COLLATE 'utf8mb4_general_ci',
	`f_gender` CHAR(1) NOT NULL COMMENT '성별 (M/F)' COLLATE 'utf8mb4_general_ci',
	`f_user_name_en` VARCHAR(100) NOT NULL COMMENT '영문이름' COLLATE 'utf8mb4_general_ci',
	`f_birth_date` DATE NOT NULL COMMENT '생년월일',
	`f_tel` VARCHAR(20) NOT NULL COMMENT '연락처' COLLATE 'utf8mb4_general_ci',
	`f_email` VARCHAR(100) NOT NULL COMMENT '이메일' COLLATE 'utf8mb4_general_ci',
	`f_zip` VARCHAR(10) NOT NULL COMMENT '우편번호' COLLATE 'utf8mb4_general_ci',
	`f_address1` VARCHAR(255) NOT NULL COMMENT '기본주소' COLLATE 'utf8mb4_general_ci',
	`f_address2` VARCHAR(255) NOT NULL COMMENT '상세주소' COLLATE 'utf8mb4_general_ci',
	`f_payer_name` VARCHAR(100) NOT NULL COMMENT '입금자명' COLLATE 'utf8mb4_general_ci',
	`f_payer_bank` VARCHAR(50) NOT NULL COMMENT '입금은행' COLLATE 'utf8mb4_general_ci',
	`f_payment_category` VARCHAR(100) NOT NULL COMMENT '입금구분 (중복가능)' COLLATE 'utf8mb4_general_ci',
	`reg_date` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP) COMMENT '등록일시',
	`mod_date` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
	`f_user_idx` INT NULL DEFAULT NULL,
	`f_user_id` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`f_issue_file` VARCHAR(500) NULL DEFAULT NULL COMMENT '업로드 파일명' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`idx`) USING BTREE
)
COMMENT='대회 접수 정보'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7
;
