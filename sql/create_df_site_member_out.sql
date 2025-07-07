CREATE TABLE `df_site_member_out` (
    `idx` INT NOT NULL AUTO_INCREMENT,
    `f_user_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_0900_ai_ci' COMMENT '회원 아이디',
    `reason` TEXT NULL COLLATE 'utf8mb4_0900_ai_ci' COMMENT '탈퇴 사유',
    `wdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE='utf8mb4_0900_ai_ci';