--会員テーブル

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id`                         int(4) NOT NULL AUTO_INCREMENT,
  `login_id`                   varchar(30) BINARY NOT NULL,
  `password`                   varchar(128) BINARY NOT NULL,
  `mail_address`               varchar(254) BINARY,
  `name`                       varchar(200) NOT NULL,
  `name_kana`                  varchar(200) NOT NULL,
  `post_code`                  varchar(24),
  `prefecture_code`            tinyint(2) ,
  `municipality`               varchar(556),
  `phone_number`               varchar(24),
  `birthday`                   varchar(24),
  `business_classification_id` int,
  `created_at`                 datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`                 datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE UNIQUE_MEMBER_ID (`login_id`),
  UNIQUE UNIQUE_MAIL_ADDRESS (`mail_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
