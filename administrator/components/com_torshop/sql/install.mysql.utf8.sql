CREATE TABLE IF NOT EXISTS `#__torshop_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(250) NOT NULL COMMENT 'Title of category',
  `alias` varchar(250) NOT NULL COMMENT 'Alias of category',
  `description` TEXT NOT NULL COMMENT 'Description of category',
  `published` INT(4) NOT NULL DEFAULT 0 COMMENT 'Publish status of category'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table of shop categories';
ALTER TABLE `#__torshop_categories` ADD PRIMARY KEY (`id`);

CREATE TABLE IF NOT EXISTS `#__torshop_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(250) NOT NULL COMMENT 'Title of product',
  `alias` varchar(250) NOT NULL COMMENT 'Alias of product',
  `description` TEXT NOT NULL COMMENT 'Description of product',
  `published` INT(4) NOT NULL DEFAULT 0 COMMENT 'Publish status of product'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table of shop products';
ALTER TABLE `#__torshop_products` ADD PRIMARY KEY (`id`);