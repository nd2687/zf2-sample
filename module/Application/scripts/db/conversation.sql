--カンバセーションテーブル

DROP TABLE IF EXISTS `conversation`;
CREATE TABLE `conversation` (
  `id`              varchar(40) NOT NULL,
  `session_id`      varchar(40) NOT NULL,
  `data`  blob,
  `expired_at`      datetime NOT NULL,
  `created_at`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
);
