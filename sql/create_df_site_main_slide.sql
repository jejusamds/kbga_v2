CREATE TABLE `df_site_main_slide` (
  `idx` INT AUTO_INCREMENT PRIMARY KEY,
  `wdate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `top_contents_pc` VARCHAR(500),
  `top_contents_m` VARCHAR(500),
  `middle_contents_pc` MEDIUMTEXT,
  `middle_contents_m` MEDIUMTEXT,
  `bottom_contents_pc` MEDIUMTEXT,
  `bottom_contents_m` MEDIUMTEXT,
  `thumbnail_pc` VARCHAR(500) COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `thumbnail_m` VARCHAR(500) COMMENT 'pc: 1920 * 998, m: 1080 * 1920',
  `prior` BIGINT
) ENGINE=InnoDB;