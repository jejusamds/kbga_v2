-- --------------------------------------------------------
-- 호스트:                          db.kbga8800.gabia.io
-- 서버 버전:                        8.0.20 - Source distribution
-- 서버 OS:                        Linux
-- HeidiSQL 버전:                  12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 테이블 dbkbga8800.df_banner_main 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_banner_main` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT,
  `upfile_pc01` varchar(50) DEFAULT NULL,
  `upfile_pc02` varchar(50) DEFAULT NULL,
  `upfile_mo01` varchar(50) DEFAULT NULL,
  `upfile_mo02` varchar(50) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  `url_link` char(1) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `prior` bigint DEFAULT NULL,
  `showset` char(1) DEFAULT NULL,
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_counter_browser 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_counter_browser` (
  `cb_pm` varchar(1) DEFAULT NULL,
  `cb_browse` varchar(255) NOT NULL DEFAULT '',
  `cb_hit` int NOT NULL DEFAULT '0',
  `cb_uptime` int NOT NULL DEFAULT '0',
  `cb_code` varchar(50) DEFAULT NULL,
  KEY `idx_cb_code_pm` (`cb_code`,`cb_pm`),
  KEY `idx_cb_code_pm_browse` (`cb_code`,`cb_pm`,`cb_browse`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_counter_display 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_counter_display` (
  `cd_width` smallint NOT NULL DEFAULT '0',
  `cd_height` smallint NOT NULL DEFAULT '0',
  `cd_hit` int NOT NULL DEFAULT '0',
  `cd_uptime` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_counter_ip 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_counter_ip` (
  `ci_pm` varchar(1) DEFAULT NULL,
  `ci_ip` varchar(15) NOT NULL DEFAULT '',
  `ci_domain` varchar(100) NOT NULL DEFAULT '',
  `ci_yy` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `ci_mm` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `ci_dd` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `ci_ww` tinyint NOT NULL DEFAULT '0',
  `ci_hh` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `ci_hit` int NOT NULL DEFAULT '0',
  `ci_uptime` int NOT NULL DEFAULT '0',
  `ci_code` varchar(50) DEFAULT NULL,
  UNIQUE KEY `uq_ip_count` (`ci_code`,`ci_pm`,`ci_ip`,`ci_yy`,`ci_mm`,`ci_dd`,`ci_hh`) USING BTREE,
  KEY `ci_code` (`ci_code`),
  KEY `ci_ip` (`ci_ip`),
  KEY `ci_hh` (`ci_hh`),
  KEY `ci_pm` (`ci_pm`,`ci_yy`,`ci_mm`,`ci_dd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_counter_now 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_counter_now` (
  `session_id` varchar(50) NOT NULL DEFAULT '',
  `pm` varchar(1) DEFAULT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `uptime` int DEFAULT NULL,
  PRIMARY KEY (`session_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_counter_url 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_counter_url` (
  `cu_pm` varchar(1) DEFAULT NULL,
  `cu_url` mediumtext NOT NULL,
  `cu_hit` int NOT NULL DEFAULT '0',
  `cu_uptime` int NOT NULL DEFAULT '0',
  `cu_code` varchar(50) DEFAULT NULL,
  UNIQUE KEY `uq_counter_url` (`cu_code`,`cu_pm`,`cu_url`(250)) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_admin 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_admin` (
  `id` varchar(20) NOT NULL DEFAULT '',
  `passwd` varchar(128) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `resno` varchar(14) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `tphone` varchar(14) DEFAULT NULL,
  `hphone` varchar(14) DEFAULT NULL,
  `post` varchar(7) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `part` int DEFAULT NULL,
  `permi` mediumtext,
  `last` datetime DEFAULT NULL,
  `wdate` datetime DEFAULT NULL,
  `descript` mediumtext,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_agency 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_agency` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `f_type` varchar(20) NOT NULL,
  `f_name` varchar(255) DEFAULT NULL,
  `f_url` varchar(500) DEFAULT NULL,
  `f_img` varchar(255) DEFAULT NULL,
  `f_img_m` varchar(255) DEFAULT NULL,
  `f_order` bigint DEFAULT NULL,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_application 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_application` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `f_category` enum('makeup','nail','hair','skin','half','foreign','teacher') DEFAULT NULL COMMENT '시험 분류',
  `f_year` int DEFAULT NULL COMMENT '년도',
  `f_round` int DEFAULT NULL COMMENT '회차',
  `f_type` varchar(128) DEFAULT NULL COMMENT '구분',
  `f_registration_start` varchar(10) DEFAULT NULL COMMENT '필기 - 접수시작',
  `f_registration_end` varchar(10) DEFAULT NULL COMMENT '필기 - 접수종료',
  `f_exam_date` varchar(128) DEFAULT NULL COMMENT '필기 - 시험일',
  `f_pass_announce` varchar(128) DEFAULT NULL COMMENT '필기 - 합격자 발표',
  `f_registration_start_2` varchar(10) DEFAULT NULL COMMENT '실기 - 접수시작',
  `f_registration_end_2` varchar(10) DEFAULT NULL COMMENT '실기 - 접수종료',
  `f_exam_date_2` varchar(255) DEFAULT NULL COMMENT '실기 - 시험일',
  `f_pass_announce_2` varchar(255) DEFAULT NULL COMMENT '실기 - 합격자 발표',
  `f_cert_application` varchar(255) DEFAULT NULL COMMENT '자격증 신청',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='시험일정';

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_application_registration 구조 내보내기
CREATE TABLE `df_site_application_registration` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`wdate` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	`f_applicant_status` INT NOT NULL DEFAULT '1' COMMENT '1:접수완료, 2:발급완료, 3:발급보류',
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



-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_bbs 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_bbs` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT NULL,
  `parno` int unsigned DEFAULT NULL,
  `prino` int unsigned DEFAULT NULL,
  `depno` int unsigned DEFAULT '0',
  `notice` char(1) DEFAULT 'N',
  `grp` varchar(80) DEFAULT NULL,
  `grp_2` varchar(80) DEFAULT NULL,
  `grp_3` varchar(80) DEFAULT NULL,
  `memid` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` longtext,
  `faq_a` longtext,
  `ctype` enum('T','H') DEFAULT 'H',
  `privacy` enum('Y','N') DEFAULT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  `count` mediumint unsigned DEFAULT '0',
  `recom` mediumint unsigned DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `wdate` datetime DEFAULT NULL,
  `wdate_original` datetime DEFAULT NULL,
  `orderid` varchar(30) DEFAULT NULL,
  `prdcode` varchar(30) DEFAULT NULL,
  `sns_link` varchar(255) DEFAULT 'N',
  `event_sdate` varchar(10) DEFAULT NULL,
  `event_edate` varchar(10) DEFAULT NULL,
  `event_win` enum('Y','N') DEFAULT 'N',
  `event_winner` longtext,
  `rpermi` varchar(255) DEFAULT NULL,
  `upfile` varchar(255) DEFAULT NULL,
  `upfile_name` varchar(255) DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `center_name` varchar(255) DEFAULT NULL,
  `app_target` varchar(255) DEFAULT NULL COMMENT '교육소식게시판용 - 참가대상',
  `app_date` varchar(255) DEFAULT NULL COMMENT '교육소식게시판용 - 일시',
  PRIMARY KEY (`idx`),
  KEY `IX_site_bbs_01` (`idx`) USING BTREE,
  KEY `IX_site_bbs_02` (`memid`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_bbsinfo 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_bbsinfo` (
  `bbs_category` varchar(30) DEFAULT NULL,
  `bbs_order` int DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titleimg` varchar(40) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `grp` varchar(255) DEFAULT NULL,
  `grp_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `grp_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lpermi` varchar(6) DEFAULT NULL,
  `rpermi` varchar(6) DEFAULT NULL,
  `wpermi` varchar(6) DEFAULT NULL,
  `apermi` varchar(6) DEFAULT NULL,
  `cpermi` varchar(6) DEFAULT NULL,
  `bbstype` enum('BBS','PHOTO','GALLERY','MEDIA') DEFAULT NULL,
  `skintype` varchar(20) DEFAULT NULL,
  `usetype` enum('Y','N') DEFAULT NULL,
  `privacy` enum('Y','N') DEFAULT NULL,
  `upfile` enum('Y','N') DEFAULT NULL,
  `comment` enum('Y','N') DEFAULT NULL,
  `remail` enum('Y','N') DEFAULT NULL,
  `imgview` enum('Y','N') DEFAULT NULL,
  `abuse` enum('Y','N') DEFAULT NULL,
  `abtxt` mediumtext,
  `rows` smallint unsigned DEFAULT NULL,
  `lists` smallint unsigned DEFAULT NULL,
  `new` smallint unsigned DEFAULT NULL,
  `hot` smallint unsigned DEFAULT NULL,
  `editor` enum('Y','N') DEFAULT NULL,
  UNIQUE KEY `code` (`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_bbs_files 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_bbs_files` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT,
  `bbsidx` int unsigned DEFAULT NULL,
  `upfile` varchar(255) DEFAULT NULL,
  `upfile_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idx`),
  KEY `IX_site_bbs_files_01` (`bbsidx`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_competition 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_competition` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `wdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_title` varchar(255) NOT NULL,
  `f_date` varchar(255) NOT NULL,
  `f_place` varchar(255) NOT NULL,
  `f_target` varchar(255) DEFAULT NULL,
  `f_reg_period` varchar(255) DEFAULT NULL,
  `f_detail` text,
  `f_image` varchar(255) DEFAULT NULL,
  `count` int DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_competition_files 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_competition_files` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `bbsidx` int NOT NULL,
  `upfile` varchar(255) NOT NULL,
  `upfile_name` varchar(255) DEFAULT NULL,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idx`),
  KEY `bbsidx` (`bbsidx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_competition_registration 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_competition_registration` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `f_competition_idx` int NOT NULL COMMENT '대회구분',
  `f_applicant_type` char(1) COLLATE utf8mb4_general_ci NOT NULL COMMENT '접수유형 (P=개인, O=단체)',
  `f_part` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '참가부문',
  `f_field` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '종목분야',
  `f_event` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '참가종목',
  `f_user_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '이름',
  `f_gender` char(1) COLLATE utf8mb4_general_ci NOT NULL COMMENT '성별 (M/F)',
  `f_user_name_en` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '영문이름',
  `f_birth_date` date NOT NULL COMMENT '생년월일',
  `f_tel` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT '연락처',
  `f_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '이메일',
  `f_zip` varchar(10) COLLATE utf8mb4_general_ci NOT NULL COMMENT '우편번호',
  `f_address1` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT '기본주소',
  `f_address2` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT '상세주소',
  `f_payer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '입금자명',
  `f_payer_bank` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '입금은행',
  `f_payment_category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '입금구분 (중복가능)',
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `mod_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  `f_user_idx` int DEFAULT NULL,
  `f_user_id` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='대회 접수 정보';

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_content 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_content` (
  `idx` smallint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '',
  `lang` varchar(2) DEFAULT NULL,
  `isuse` enum('Y','N') DEFAULT NULL,
  `scroll` enum('Y','N') DEFAULT NULL,
  `posi_x` smallint unsigned DEFAULT NULL,
  `posi_y` smallint unsigned DEFAULT NULL,
  `size_x` smallint unsigned DEFAULT NULL,
  `size_y` smallint unsigned DEFAULT NULL,
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `wdate` date DEFAULT NULL,
  `poptype` varchar(20) DEFAULT 'pop',
  `close_bg` varchar(7) DEFAULT NULL,
  `close_align` varchar(10) DEFAULT NULL,
  `close_txt` varchar(100) DEFAULT NULL,
  `close_txt_color` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_content_mobile 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_content_mobile` (
  `idx` smallint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '',
  `lang` varchar(2) DEFAULT NULL,
  `isuse` enum('Y','N') DEFAULT NULL,
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `wdate` date DEFAULT NULL,
  `close_bg` varchar(7) DEFAULT NULL,
  `close_align` varchar(10) DEFAULT NULL,
  `close_txt` varchar(100) DEFAULT NULL,
  `close_txt_color` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_edu_registration 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_edu_registration` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `f_type` char(1) NOT NULL COMMENT 'P:개인, O:단체',
  `f_news_idx` int NOT NULL,
  `f_user_name` varchar(100) NOT NULL,
  `f_user_name_en` varchar(100) DEFAULT NULL,
  `f_gender` varchar(10) DEFAULT NULL,
  `f_birth_date` date DEFAULT NULL,
  `f_tel` varchar(20) NOT NULL,
  `f_contact_phone` varchar(20) DEFAULT NULL,
  `f_zip` varchar(10) NOT NULL,
  `f_address1` varchar(255) NOT NULL,
  `f_address2` varchar(255) NOT NULL,
  `f_email` varchar(255) NOT NULL,
  `f_issue_file` varchar(255) DEFAULT NULL,
  `f_issue_file_name` varchar(255) DEFAULT NULL,
  `f_payer_name` varchar(100) NOT NULL,
  `f_payer_bank` varchar(100) NOT NULL,
  `f_payment_category` varchar(100) NOT NULL,
  `reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `f_user_idx` int DEFAULT NULL,
  `f_user_id` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_main_image 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_main_image` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `thumbnail_pc` varchar(500) DEFAULT NULL COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `thumbnail_m` varchar(500) DEFAULT NULL COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `prior` bigint DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_main_slide 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_main_slide` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `top_contents_pc` varchar(500) DEFAULT NULL,
  `top_contents_m` varchar(500) DEFAULT NULL,
  `middle_contents_pc` mediumtext,
  `middle_contents_m` mediumtext,
  `bottom_contents_pc` mediumtext,
  `bottom_contents_m` mediumtext,
  `thumbnail_pc` varchar(500) DEFAULT NULL COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `thumbnail_m` varchar(500) DEFAULT NULL COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `prior` bigint DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_material 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_material` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `f_category` varchar(20) NOT NULL,
  `f_subject_idx` int DEFAULT NULL,
  `f_subject` varchar(255) NOT NULL,
  `f_type` varchar(10) NOT NULL,
  `f_level` varchar(50) NOT NULL,
  `f_description` varchar(255) NOT NULL,
  `f_file` varchar(255) DEFAULT NULL,
  `f_file_name` varchar(255) DEFAULT NULL,
  `wdate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_member 구조 내보내기
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


-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_page 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_page` (
  `idx` smallint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '',
  `subimg` varchar(100) DEFAULT NULL,
  `content` mediumtext,
  `addinfo` mediumtext,
  `addinfo2` mediumtext,
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_qualification_item 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_qualification_item` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `wdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_category` enum('makeup','nail','hair','skin','half','foreign','teacher') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '분야 코드',
  `f_item_name` varchar(128) NOT NULL COMMENT '자격종목명',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='자격종목 관리';

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_sigong 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_sigong` (
  `idx` int NOT NULL AUTO_INCREMENT COMMENT '고유번호',
  `wdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '작성일',
  `f_usage` varchar(50) NOT NULL COMMENT '용도(업무/근생, 물류시설, 주거시설, 리모델링, 기타)',
  `f_region` varchar(10) NOT NULL COMMENT '지역(서울, 경기, 기타)',
  `f_thumbnail` varchar(255) DEFAULT NULL COMMENT '썸네일 경로',
  `f_address` varchar(255) NOT NULL COMMENT '위치(주소)',
  `f_period` varchar(100) DEFAULT NULL COMMENT '공사기간',
  `f_scale` varchar(100) DEFAULT NULL COMMENT '규모',
  `f_progress` tinyint NOT NULL DEFAULT '0' COMMENT '공정률(0~100)',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='시공사업';

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 dbkbga8800.df_site_siteinfo 구조 내보내기
CREATE TABLE IF NOT EXISTS `df_site_siteinfo` (
  `site_name` varchar(50) DEFAULT NULL,
  `site_url` varchar(200) NOT NULL DEFAULT '',
  `site_email` varchar(120) DEFAULT NULL,
  `site_tel` varchar(16) DEFAULT NULL,
  `site_hand` varchar(16) DEFAULT NULL,
  `admin_title` varchar(250) NOT NULL DEFAULT '',
  `com_num` varchar(20) DEFAULT NULL,
  `com_name` varchar(30) DEFAULT NULL,
  `com_owner` varchar(20) DEFAULT NULL,
  `com_post` varchar(7) DEFAULT NULL,
  `com_address` varchar(120) DEFAULT NULL,
  `com_kind` varchar(50) DEFAULT NULL,
  `com_class` varchar(50) DEFAULT NULL,
  `com_tel` varchar(20) DEFAULT NULL,
  `com_fax` varchar(20) DEFAULT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `site_keyword` varchar(255) DEFAULT NULL,
  `site_intro` varchar(255) DEFAULT NULL,
  `site_image` varchar(100) DEFAULT NULL,
  `site_clip` mediumtext,
  `title_01-1` varchar(50) DEFAULT NULL,
  `title_03-1` varchar(50) DEFAULT NULL,
  `title_03-1-0` varchar(50) DEFAULT NULL,
  `title_03-1-1` varchar(50) DEFAULT NULL,
  `title_03-1-2` varchar(50) DEFAULT NULL,
  `title_03-1-3` varchar(50) DEFAULT NULL,
  `title_03-1-4` varchar(50) DEFAULT NULL,
  `title_03-1-5` varchar(50) DEFAULT NULL,
  `title_04-1` varchar(50) DEFAULT NULL,
  `title_04-2` varchar(50) DEFAULT NULL,
  `title_04-3` varchar(50) DEFAULT NULL,
  `title_05-1` varchar(50) DEFAULT NULL,
  `title_05-2` varchar(50) DEFAULT NULL,
  `title_06-1` varchar(50) DEFAULT NULL,
  `title_06-2` varchar(50) DEFAULT NULL,
  `title_06-3` varchar(50) DEFAULT NULL,
  `title_06-4` varchar(50) DEFAULT NULL,
  `g_user` varchar(100) DEFAULT NULL,
  `g_app_password` varchar(50) DEFAULT NULL,
  `g_manager_email` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;