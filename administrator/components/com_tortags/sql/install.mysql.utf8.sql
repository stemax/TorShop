CREATE TABLE IF NOT EXISTS `#__tortags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tid` int(10) unsigned NOT NULL COMMENT 'tag id',
  `oid` int(10) unsigned NOT NULL COMMENT 'component  id',
  `item_id` int(10) unsigned NOT NULL COMMENT 'Item id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table of xref to tags';
 

CREATE TABLE IF NOT EXISTS `#__tortags_components` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `component` varchar(200) NOT NULL COMMENT 'component name',
  `table` varchar(200) NOT NULL COMMENT 'component  table',
  `title_field` varchar(200) NOT NULL COMMENT 'component  table title field',
  `description_field` varchar(200) NOT NULL COMMENT 'component  table description field',
  `created_field` varchar(200) NOT NULL COMMENT 'component  table created field',
  `url_template` varchar(200) NOT NULL COMMENT 'component  url template',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table of components';

CREATE TABLE IF NOT EXISTS `#__tortags_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'tag id',
  `title` varchar(200) NOT NULL COMMENT 'tag title',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  COMMENT='Table of tags';