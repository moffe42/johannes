CREATE TABLE IF NOT EXISTS `capturelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `scoutid` int(11) NOT NULL,
  `sjakid` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `useragent` text COLLATE utf8_danish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;
