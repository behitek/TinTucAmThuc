ALTER TABLE `#__content` MODIFY `attribs` text NOT NULL;

CREATE TABLE IF NOT EXISTS `#__content_meta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Value is stored in encoded format',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_id` (`content_id`,`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;