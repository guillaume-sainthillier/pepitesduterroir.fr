CREATE TABLE IF NOT EXISTS `PREFIX_zproduct_extra_field` (
	`id_zproduct_extra_field` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`hook` varchar(128) NULL DEFAULT NULL,
	`popup` tinyint(1) NULL DEFAULT 0,
	`popup_width` varchar(50) NULL DEFAULT '560',
	`title_image` varchar(255) NULL DEFAULT NULL,
	`scope` varchar(50) NOT NULL,
	`categories` text DEFAULT '',
	`products` text DEFAULT '',
	`manufacturers` text DEFAULT '',
    `custom_class` varchar(50) DEFAULT NULL,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id_zproduct_extra_field`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zproduct_extra_field_lang` (
	`id_zproduct_extra_field` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`title` varchar(255),
	`content` text,
	PRIMARY KEY (`id_zproduct_extra_field`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
