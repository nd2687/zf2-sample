DROP TABLE IF EXISTS `sample_test`.`business_classification`;
CREATE TABLE `sample_test`.`business_classification` (
  `id`                          int(4) NOT NULL,
  `name`                        varchar(30)  NOT NULL,
  `parent_id`                   int(4),
  `created_at`                  datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`                  datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sample_test`.`business_classification` (id, name, parent_id) VALUES
(1,'農林水産・鉱業',0),
(2,'建設',0),
(3,'自動車、輸送機器',0),
(4,'電気・電子機器',0),
(5,'民生用エレクトロニクス／家電',4),
(6,'コンピュータ、周辺機器製造',4),
(7,'コンピュータ製造',6),
(8,'周辺機器製造',6),
(9,'その他コンピュータ関連製造',6),
(10,'通信機器製造',4),
(11,'電子部品製造',4),
(12,'半導体デバイス',10),
(13,'半導体以外の電子部品',10),
(14,'その他電気・電子機器製造',4),
(15,'機械、重電',0),
(16,'素材',0),
(17,'食品、医療、化粧品',0),
(18,'その他製造',0),
(19,'エネルギー',0),
(20,'卸売・小売業・商業(商社含む)',0),
(21,'商社',20),
(22,'コンピュータ関連販売',20),
(23,'コンピュータ・ディーラー',22),
(24,'コンピュータ関連通販',22),
(25,'コンピュータ関連小売り',22),
(26,'その他コンピュータ関連販売',22),
(27,'上記以外の流通／小売り',20),
(28,'卸',27),
(29,'百貨店',27),
(30,'スーパーマーケット',27),
(31,'コンビニエンスストア',27),
(32,'薬局・ドラッグストア',27),
(33,'その他流通／小売り',27),
(34,'金融・証券・保険',0),
(35,'不動産',0),
(36,'通信サービス',0),
(37,'通信事業者',36),
(38,'プロバイダ',36),
(39,'ＩＤＣ、ＡＳＰ',36),
(40,'その他通信サービス',36),
(41,'情報処理、SI、ソフトウェア',0),
(42,'ソフトハウス（パッケージ中心）',41),
(43,'ソフトハウス（受託開発中心）',41),
(44,'情報処理サービス',41),
(45,'コンサルティング',41),
(46,'システム・インテグレーター（ＳＩ）',41),
(47,'ネットワーク・インテグレーター',41),
(48,'ＶＡＲ',41),
(49,'その他情報関連サービス',41),
(50,'運輸',0),
(51,'コンサル・会計・法律関連',0),
(52,'放送・広告・出版・マスコミ',0),
(53,'公務員',0),
(54,'教育・教育学習支援関係',0),
(55,'医療',0),
(56,'介護・福祉',0),
(57,'飲食店・宿泊',0),
(58,'人材サービス',0),
(59,'旅行',0),
(60,'その他',0);