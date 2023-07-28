CREATE TABLE IF NOT EXISTS `%prefix%setting` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`module` VARCHAR(200) NOT NULL,
	`name` VARCHAR(200) NOT NULL,
	`value` TEXT DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `module_name` (`module`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%user` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(200) NOT NULL,
	`password` VARCHAR(200) NOT NULL,
	`name` VARCHAR(200) NOT NULL,
	`phone` VARCHAR(100) DEFAULT NULL,
	`language` VARCHAR(8) DEFAULT NULL,
	`avatar` TEXT DEFAULT NULL,
	`setting` TEXT DEFAULT NULL,
	`auth_token` VARCHAR(200) DEFAULT NULL,
	`auth_ip` VARCHAR(32) DEFAULT NULL,
	`auth_date` DATETIME NULL DEFAULT NULL,
	`is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_edited` DATETIME ON UPDATE CURRENT_TIMESTAMP DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `email` (`email`),
	UNIQUE `phone` (`phone`),
	UNIQUE `auth_token` (`auth_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%user_group` (
	`group_id` INT UNSIGNED NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%group` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`access_all` BOOLEAN NOT NULL DEFAULT FALSE,
	`is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_edited` DATETIME on update CURRENT_TIMESTAMP DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%group_translation` (
	`group_id` INT UNSIGNED NOT NULL,
	`language` VARCHAR(8) NOT NULL,
	`name` VARCHAR(300) NOT NULL,
	PRIMARY KEY (`group_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%group_route` (
	`group_id` INT UNSIGNED NOT NULL,
	`route` VARCHAR(512) NOT NULL,
	PRIMARY KEY (`group_id`, `route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%page` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`url` VARCHAR(300) NOT NULL,
	`author` INT NOT NULL,
	`template` VARCHAR(100) DEFAULT NULL,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_edited` DATETIME on update CURRENT_TIMESTAMP DEFAULT NULL,
	`date_publish` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`is_category` BOOLEAN NOT NULL DEFAULT FALSE,
	`is_static` BOOLEAN NOT NULL DEFAULT FALSE,
	`no_index_no_follow` BOOLEAN NOT NULL DEFAULT FALSE,
	`allow_comment` BOOLEAN NOT NULL DEFAULT TRUE,
	`hide_comments` BOOLEAN NOT NULL DEFAULT FALSE,
	`views` BIGINT UNSIGNED NOT NULL DEFAULT 0,
	`is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	PRIMARY KEY  (`id`),
	UNIQUE `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%page_translation` (
	`page_id` INT UNSIGNED NOT NULL,
	`language` VARCHAR(8) NOT NULL,
	`title` VARCHAR(300) NOT NULL,
	`content` LONGTEXT DEFAULT NULL,
	`excerpt` TEXT DEFAULT NULL,
	`image` TEXT DEFAULT NULL,
	`seo_description` TEXT DEFAULT NULL,
	`seo_keywords` TEXT DEFAULT NULL,
	`seo_image` TEXT DEFAULT NULL,
	PRIMARY KEY (`page_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%page_category` (
	`category_id` INT UNSIGNED NOT NULL,
	`page_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`category_id`, `page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%tag` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`url` VARCHAR(100) NOT NULL,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_edited` DATETIME on update CURRENT_TIMESTAMP DEFAULT NULL,
	`is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	PRIMARY KEY  (`id`),
	UNIQUE `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%tag_translation` (
	`tag_id` INT UNSIGNED NOT NULL,
	`language` VARCHAR(8) NOT NULL,
	`name` VARCHAR(300) NOT NULL,
	PRIMARY KEY (`tag_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%page_tag` (
	`page_id` INT UNSIGNED NOT NULL,
	`tag_id` BIGINT UNSIGNED NOT NULL,
	PRIMARY KEY (`page_id`, `tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%comment` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`parent` BIGINT DEFAULT NULL,
	`page_id` INT NOT NULL,
	`author` INT NOT NULL,
	`message` TEXT NOT NULL,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_edited` DATETIME on update CURRENT_TIMESTAMP DEFAULT NULL,
	`ip` VARCHAR(32) DEFAULT NULL,
	`is_approved` BOOLEAN NOT NULL DEFAULT FALSE,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `%prefix%custom_field` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`page_id` INT NOT NULL,
	`language` VARCHAR(8) NOT NULL,
	`name` VARCHAR(300) NOT NULL,
	`value` LONGTEXT DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `page_id_language_name` (`page_id`, `language`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%form` (
	`token` VARCHAR(200) NOT NULL,
	`module` varchar(200) NOT NULL,
	`action` VARCHAR(32) NOT NULL,
	`form_name` VARCHAR(200) NOT NULL,
	`item_id` VARCHAR(200) DEFAULT NULL,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `%prefix%menu` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%menu_translation` (
	`menu_id` INT UNSIGNED NOT NULL,
	`language` VARCHAR(8) NOT NULL,
	`items` LONGTEXT DEFAULT NULL,
	PRIMARY KEY (`menu_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%notification` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`type` VARCHAR(100) NOT NULL,
	`info` TEXT DEFAULT NULL,
	`is_read` BOOLEAN NOT NULL DEFAULT FALSE,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%message` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT DEFAULT NULL,
	`email` VARCHAR(200) NOT NULL,
	`subject` VARCHAR(100) DEFAULT NULL,
	`message` TEXT NOT NULL,
	`ip` VARCHAR(32) DEFAULT NULL,
	`is_read` BOOLEAN NOT NULL DEFAULT FALSE,
	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `%prefix%setting` (`module`, `name`, `value`) VALUES
('engine', 'language', 'en'),
('engine', 'enable_registration', 'true'),
('engine', 'enable_password_restore', 'true'),
('engine', 'moderate_comments', 'false'),
('engine', 'pagination_limit', '10'),
('engine', 'no_index_no_follow', NULL),
('engine', 'name', '{"en":"%site_name%"}'),
('engine', 'description', '{"en":""}'),
('engine', 'logo_admin', NULL),
('engine', 'logo_admin_alt', NULL),
('engine', 'logo_public', NULL),
('engine', 'logo_public_alt', NULL),
('engine', 'placeholder_avatar_admin', NULL),
('engine', 'placeholder_image_admin', NULL),
('engine', 'placeholder_avatar_public', NULL),
('engine', 'placeholder_image_public', NULL),
('engine', 'favicon_admin', NULL),
('engine', 'favicon_public', NULL),
('engine', 'analytics_gtag', NULL),
('engine', 'address', '{"en":""}'),
('engine', 'coordinate_x', NULL),
('engine', 'coordinate_y', NULL),
('engine', 'hours', '{"en":""}'),
('engine', 'email', '%contact_email%'),
('engine', 'phones', NULL),
('engine', 'group_css', NULL),
('engine', 'group_js', NULL),
('engine', 'cache_db', NULL);

INSERT INTO `%prefix%user` (`email`, `password`, `name`, `auth_token`, `auth_ip`) VALUES
('%admin_email%', '%admin_password%', 'admin', '%auth_token%', '%auth_ip%');

INSERT INTO `%prefix%group` (`id`, `access_all`) VALUES
(1, true),
(2, false),
(3, false);

INSERT INTO `%prefix%group_translation` (`group_id`, `language`, `name`) VALUES
(1, 'en', 'Developer'),
(2, 'en', 'Administrator'),
(3, 'en', 'Moderator');

INSERT INTO `%prefix%group_route` (`group_id`, `route`) VALUES
(2, 'any@/upload'),
(2, 'any@/admin/**'),
(3, 'any@/upload'),
(3, 'any@/admin/message'),
(3, 'any@/admin/comment'),
(3, 'any@/admin/comment/**'),
(3, 'any@/admin/menu'),
(3, 'any@/admin/menu/**'),
(3, 'any@/admin/page'),
(3, 'any@/admin/page/**'),
(3, 'any@/admin/translation'),
(3, 'any@/admin/translation/**');

INSERT INTO `%prefix%user_group` (`user_id`, `group_id`) VALUES
(1, 1);

INSERT INTO `%prefix%notification` (`user_id`, `type`, `info`) VALUES
(1, 'user_register', '{"ip":"%auth_ip%"}');

INSERT INTO `%prefix%page` (`url`, `author`) VALUES
('home', 1);

INSERT INTO `%prefix%page_translation` (`page_id`, `language`, `title`) VALUES
(1, 'en', 'Homepage');

CREATE TRIGGER
	`set_page_static`
BEFORE UPDATE ON
	`%prefix%page`
FOR EACH ROW
	SET NEW.is_static =
		CASE WHEN (SELECT count(*) FROM `%prefix%page_category` WHERE page_id=NEW.id) > 0 THEN
			false
		ELSE
			true
		END;

CREATE TRIGGER
	`moderate_comment`
BEFORE INSERT ON
	`%prefix%comment`
FOR EACH ROW
	SET NEW.is_approved =
		CASE WHEN (SELECT value FROM `%prefix%setting` WHERE section = 'main' AND name = 'moderate_comments') <> 'true' THEN
			true
		ELSE
			false
		END;

CREATE TRIGGER
	`clear_page_category`
AFTER DELETE ON
	`%prefix%page`
FOR EACH ROW
	DELETE FROM `%prefix%page_category` WHERE page_id = OLD.id OR category_id = OLD.id;

CREATE TRIGGER
	`clear_comment_add`
AFTER DELETE ON
	`%prefix%page`
FOR EACH ROW
	DELETE FROM `%prefix%comment` WHERE page_id = OLD.id;

CREATE TRIGGER
	`clear_page_translation`
AFTER DELETE ON
	`%prefix%page`
FOR EACH ROW
	DELETE FROM `%prefix%page_translation` WHERE page_id = OLD.id;

CREATE TRIGGER
	`clear_page_tag_by_page_delete`
AFTER DELETE ON
	`%prefix%page`
FOR EACH ROW
  DELETE FROM `%prefix%page_tag` WHERE page_id = OLD.id;

CREATE TRIGGER
	`clear_page_tag_by_tag_delete`
AFTER DELETE ON
	`%prefix%tag`
FOR EACH ROW
  DELETE FROM `%prefix%page_tag` WHERE tag_id = OLD.id;

CREATE TRIGGER
	`clear_menu_translation`
AFTER DELETE ON
	`%prefix%menu`
FOR EACH ROW
	DELETE FROM `%prefix%menu_translation` WHERE menu_id = OLD.id;

CREATE TRIGGER
	`clear_group_route_by_group_delete`
AFTER DELETE ON
	`%prefix%group`
FOR EACH ROW
  DELETE FROM `%prefix%group_route` WHERE group_id = OLD.id;

CREATE TRIGGER
	`clear_user_group_by_group_delete`
AFTER DELETE ON
	`%prefix%group`
FOR EACH ROW
  DELETE FROM `%prefix%user_group` WHERE group_id = OLD.id;

CREATE TRIGGER
	`clear_user_group_by_user_delete`
AFTER DELETE ON
	`%prefix%user`
FOR EACH ROW
  DELETE FROM `%prefix%user_group` WHERE user_id = OLD.id;
