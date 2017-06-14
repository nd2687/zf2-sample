DROP TABLE IF EXISTS `sample`.`mail_address_change`;
CREATE TABLE `sample`.`mail_address_change` (
  `id`                          varchar(32)         NOT NULL,
  `member_id`                   int(4)              NOT NULL,
  `mail_address`                varchar(254) BINARY NOT NULL,
  `status`                      tinyint(2)          NOT NULL,
  `created_at`                  datetime            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_at`                  datetime            NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
