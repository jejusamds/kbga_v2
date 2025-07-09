CREATE TABLE `df_site_edu_registration` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`f_applicant_status` ENUM('ing','done','cancle','hold') NOT NULL DEFAULT 'ing' COLLATE 'utf8_general_ci',
	`f_type` CHAR(1) NOT NULL COMMENT 'P:개인, O:단체' COLLATE 'utf8_general_ci',
	`f_news_idx` INT NOT NULL,
	`f_edu_type_idx` INT NOT NULL,
	`f_edu_type_title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`f_user_name` VARCHAR(100) NOT NULL COLLATE 'utf8_general_ci',
	`f_user_name_en` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`f_gender` ENUM('F','M') NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`f_birth_date` DATE NULL DEFAULT NULL,
	`f_tel` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`f_contact_phone` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`f_zip` VARCHAR(10) NOT NULL COLLATE 'utf8_general_ci',
	`f_address1` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`f_address2` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`f_email` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`f_issue_file` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`f_issue_file_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`f_payer_name` VARCHAR(100) NOT NULL COLLATE 'utf8_general_ci',
	`f_payer_bank` VARCHAR(100) NOT NULL COLLATE 'utf8_general_ci',
	`f_payment_category` VARCHAR(100) NOT NULL COLLATE 'utf8_general_ci',
	`reg_date` DATETIME NULL DEFAULT (CURRENT_TIMESTAMP),
	`f_user_idx` INT NULL DEFAULT NULL,
	`f_user_id` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	PRIMARY KEY (`idx`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=9
;
