CREATE TABLE `df_site_member` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`wdate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	`f_member_type` ENUM('P','O') NOT NULL COMMENT '회원 유형 (P=개인, O=단체)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_user_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '이름' COLLATE 'utf8mb4_0900_ai_ci',
	`f_birth_date` VARCHAR(50) NULL DEFAULT NULL COMMENT '생년월일' COLLATE 'utf8mb4_0900_ai_ci',
	`f_gender` ENUM('M','F') NULL DEFAULT NULL COMMENT '성별' COLLATE 'utf8mb4_0900_ai_ci',
	`f_tel` VARCHAR(15) NULL DEFAULT NULL COMMENT '전화번호' COLLATE 'utf8mb4_0900_ai_ci',
	`f_mobile` VARCHAR(15) NULL DEFAULT NULL COMMENT '휴대전화번호' COLLATE 'utf8mb4_0900_ai_ci',
	`f_affiliation_flag` ENUM('N','Y') NOT NULL DEFAULT 'N' COMMENT '소속단체 여부 (N=없음, Y=있음)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_affiliation_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '소속단체 이름' COLLATE 'utf8mb4_0900_ai_ci',
	`f_org_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '단체명' COLLATE 'utf8mb4_0900_ai_ci',
	`f_org_phone` VARCHAR(15) NULL DEFAULT NULL COMMENT '단체 전화번호' COLLATE 'utf8mb4_0900_ai_ci',
	`f_contact_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '담당자명' COLLATE 'utf8mb4_0900_ai_ci',
	`f_contact_phone` VARCHAR(15) NULL DEFAULT NULL COMMENT '담당자 연락처' COLLATE 'utf8mb4_0900_ai_ci',
	`f_zip` VARCHAR(10) NULL DEFAULT NULL COMMENT '우편번호' COLLATE 'utf8mb4_0900_ai_ci',
	`f_address1` VARCHAR(255) NULL DEFAULT NULL COMMENT '기본주소' COLLATE 'utf8mb4_0900_ai_ci',
	`f_address2` VARCHAR(255) NULL DEFAULT NULL COMMENT '상세주소' COLLATE 'utf8mb4_0900_ai_ci',
	`f_user_id` VARCHAR(50) NOT NULL COMMENT '아이디(4~12자 영문/숫자)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_password` VARCHAR(500) NOT NULL COMMENT '비밀번호(해시)' COLLATE 'utf8mb4_0900_ai_ci',
	`f_email` VARCHAR(255) NOT NULL COMMENT '이메일' COLLATE 'utf8mb4_0900_ai_ci',
	`f_email_consent` ENUM('N','Y') NOT NULL DEFAULT 'Y' COMMENT '이메일 수신 동' COLLATE 'utf8mb4_0900_ai_ci',
	`is_out` TINYINT NULL DEFAULT '1' COMMENT '회원 탈퇴 여부 1: 미탈퇴, 2: 탈퇴',
	PRIMARY KEY (`idx`) USING BTREE,
	UNIQUE INDEX `f_user_id` (`f_user_id`) USING BTREE
)
COMMENT='개인/단체 회원 통합 테이블'
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=19
;
