CREATE TABLE `df_site_application_registration` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`wdate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	`f_applicant_status` ENUM('ing','done','cancle','hold','re') NOT NULL DEFAULT '1' COMMENT 'ing: 접수중, done: 완료, cancle: 취소, hold: 보류',
	`f_applicant_type` ENUM('P','O') NOT NULL COMMENT '접수유형 (P=개인, O=단체)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_item_idx` INT NOT NULL COMMENT '자격종목',
	`f_category` ENUM('makeup','nail','hair','skin','half','foreign','teacher') NOT NULL COMMENT '자격분야' COLLATE 'utf8mb4_0900_ai_ci',
	`f_schedule_idx` INT NOT NULL COMMENT '시험일정 IDX (df_site_application.idx)',
	`f_user_name` VARCHAR(100) NOT NULL COMMENT '이름 (단체인 경우 단체명)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_user_name_en` VARCHAR(100) NOT NULL COMMENT '영문이름 (단체인 경우 담당자명)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_tel` VARCHAR(20) NULL DEFAULT NULL COMMENT '연락처' COLLATE 'utf8mb4_0900_ai_ci',
	`f_birth_date` VARCHAR(20) NULL DEFAULT NULL COMMENT '생년월일' COLLATE 'utf8mb4_0900_ai_ci',
	`f_contact_phone` VARCHAR(20) NULL DEFAULT NULL COMMENT '단체인 경우 담당자 연락처' COLLATE 'utf8mb4_0900_ai_ci',
	`f_zip` VARCHAR(10) NULL DEFAULT NULL COMMENT '우편번호' COLLATE 'utf8mb4_0900_ai_ci',
	`f_address1` VARCHAR(255) NULL DEFAULT NULL COMMENT '기본주소' COLLATE 'utf8mb4_0900_ai_ci',
	`f_address2` VARCHAR(255) NULL DEFAULT NULL COMMENT '상세주소' COLLATE 'utf8mb4_0900_ai_ci',
	`f_email` VARCHAR(255) NOT NULL COMMENT '이메일' COLLATE 'utf8mb4_0900_ai_ci',
	`f_application_type` ENUM('exam','cert') NOT NULL COMMENT '신청구분 (exam=시험접수, certificate=자격증발급)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_issue_desire` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '자격증 발급희망 여부 (개인만)',
	`f_issue_file` VARCHAR(2000) NULL DEFAULT NULL COMMENT '발급희망 시 파일 업로드' COLLATE 'utf8mb4_0900_ai_ci',
	`f_payer_name` VARCHAR(100) NULL DEFAULT NULL COMMENT '입금자명' COLLATE 'utf8mb4_0900_ai_ci',
	`f_payer_bank` VARCHAR(100) NULL DEFAULT NULL COMMENT '입금 은행' COLLATE 'utf8mb4_0900_ai_ci',
	`f_payment_category` VARCHAR(100) NULL DEFAULT NULL COMMENT '입금구분 (중복가능: written=필기, practical=실기, issuance=발급비)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_user_idx` INT NULL DEFAULT NULL,
	`f_user_id` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`idx`) USING BTREE
)
COMMENT='시험일정 접수 정보 테이블'
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=18
;
