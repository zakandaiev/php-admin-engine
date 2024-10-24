CREATE TABLE IF NOT EXISTS `%prefix%_setting` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` VARCHAR(256) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `value` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `module_name` (`module`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `%prefix%_setting` (`module`, `name`, `value`) VALUES
('engine', 'language', 'en'),
('engine', 'enable_registration', 'true'),
('engine', 'enable_password_restore', 'true'),
('engine', 'moderate_comments', 'false'),
('engine', 'no_index_no_follow', 'false'),
('engine', 'name', '{"en":"%site_name%"}'),
('engine', 'description', '{"en":null}'),
('engine', 'analytics_gtag', NULL),

('backend', 'pagination_limit', '10'),
('backend', 'favicon', NULL),
('backend', 'logo', NULL),
('backend', 'logo_alt', NULL),
('backend', 'placeholder_avatar', NULL),
('backend', 'placeholder_image', NULL),

('frontend', 'pagination_limit', '10'),
('frontend', 'favicon', NULL),
('frontend', 'logo', NULL),
('frontend', 'logo_alt', NULL),
('frontend', 'placeholder_avatar', NULL),
('frontend', 'placeholder_image', NULL),

('contact', 'address', '{"en":null}'),
('contact', 'coordinate_x', NULL),
('contact', 'coordinate_y', NULL),
('contact', 'work_hours', '{"en":null}'),
('contact', 'email', '%contact_email%'),
('contact', 'phones', NULL);

CREATE TABLE IF NOT EXISTS `%prefix%_user` (
  `id` VARCHAR(32) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `phone` VARCHAR(128) DEFAULT NULL,
  `language` VARCHAR(2) DEFAULT NULL,
  `avatar` TEXT DEFAULT NULL,
  `setting` TEXT DEFAULT NULL,
  `auth_token` VARCHAR(256) DEFAULT NULL,
  `auth_ip` VARCHAR(32) DEFAULT NULL,
  `auth_date` DATETIME NULL DEFAULT NULL,
  `is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edited` DATETIME ON UPDATE CURRENT_TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `email` (`email`),
  UNIQUE `phone` (`phone`),
  UNIQUE `auth_token` (`auth_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `%prefix%_user` (`email`, `password`, `name`, `auth_token`, `auth_ip`) VALUES
('%admin_email%', '%admin_password%', 'Administrator', '%auth_token%', '%auth_ip%');

CREATE TABLE IF NOT EXISTS `%prefix%_group` (
  `id` VARCHAR(32) NOT NULL,
  `access_all` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edited` DATETIME on update CURRENT_TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_group_translation` (
  `group_id` VARCHAR(32) NOT NULL,
  `language` VARCHAR(2) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`group_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_group_user` (
  `group_id` VARCHAR(32) NOT NULL,
  `user_id` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_group_route` (
  `group_id` VARCHAR(32) NOT NULL,
  `route` VARCHAR(512) NOT NULL,
  PRIMARY KEY (`group_id`, `route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `%prefix%_group` (`id`, `access_all`) VALUES
(1, true),
(2, false),
(3, false);

INSERT INTO `%prefix%_group_translation` (`group_id`, `language`, `name`) VALUES
(1, 'en', 'Developer'),
(2, 'en', 'Administrator'),
(3, 'en', 'Moderator');

INSERT INTO `%prefix%_group_route` (`group_id`, `route`) VALUES
(2, 'any@/admin/**'),
(3, 'any@/admin/feedback'),
(3, 'any@/admin/comment'),
(3, 'any@/admin/comment/**'),
(3, 'any@/admin/menu'),
(3, 'any@/admin/menu/**'),
(3, 'any@/admin/page'),
(3, 'any@/admin/page/**'),
(3, 'any@/admin/translation'),
(3, 'any@/admin/translation/**'),
(3, 'any@/admin/upload');

INSERT INTO `%prefix%_group_user` (`group_id`, `user_id`) VALUES
(1, 1);

CREATE TABLE IF NOT EXISTS `%prefix%_form` (
  `token` VARCHAR(256) NOT NULL,
  `module` VARCHAR(128) NOT NULL,
  `action` VARCHAR(32) NOT NULL,
  `model_name` VARCHAR(128) NOT NULL,
  `item_id` VARCHAR(256) DEFAULT NULL,
  `is_match_request` BOOLEAN NOT NULL DEFAULT FALSE,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` VARCHAR(32) NOT NULL,
  PRIMARY KEY  (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;











CREATE TABLE IF NOT EXISTS `%prefix%_page` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_page_translation` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_page_category` (
  `category_id` INT UNSIGNED NOT NULL,
  `page_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`category_id`, `page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_comment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `%prefix%_custom_field` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` INT NOT NULL,
  `language` VARCHAR(8) NOT NULL,
  `name` VARCHAR(300) NOT NULL,
  `value` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `page_id_language_name` (`page_id`, `language`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `%prefix%_menu` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_menu_translation` (
  `menu_id` INT UNSIGNED NOT NULL,
  `language` VARCHAR(8) NOT NULL,
  `items` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`menu_id`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_notification` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `info` TEXT DEFAULT NULL,
  `is_read` BOOLEAN NOT NULL DEFAULT FALSE,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_feedback` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT DEFAULT NULL,
  `email` VARCHAR(200) NOT NULL,
  `subject` VARCHAR(100) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `ip` VARCHAR(32) DEFAULT NULL,
  `is_read` BOOLEAN NOT NULL DEFAULT FALSE,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `%prefix%_notification` (`user_id`, `type`, `info`) VALUES
(1, 'user_register', '{"ip":"%auth_ip%"}');

INSERT INTO `%prefix%_page` (`url`, `author`) VALUES
('home', 1);

INSERT INTO `%prefix%_page_translation` (`page_id`, `language`, `title`) VALUES
(1, 'en', 'Homepage');

CREATE TRIGGER
  `set_page_static`
BEFORE UPDATE ON
  `%prefix%_page`
FOR EACH ROW
  SET NEW.is_static =
    CASE WHEN (SELECT count(*) FROM `%prefix%_page_category` WHERE page_id=NEW.id) > 0 THEN
      false
    ELSE
      true
    END;

CREATE TRIGGER
  `moderate_comment`
BEFORE INSERT ON
  `%prefix%_comment`
FOR EACH ROW
  SET NEW.is_approved =
    CASE WHEN (SELECT value FROM `%prefix%_setting` WHERE section = 'main' AND name = 'moderate_comments') <> 'true' THEN
      true
    ELSE
      false
    END;

CREATE TRIGGER
  `clear_page_category`
AFTER DELETE ON
  `%prefix%_page`
FOR EACH ROW
  DELETE FROM `%prefix%_page_category` WHERE page_id = OLD.id OR category_id = OLD.id;

CREATE TRIGGER
  `clear_comment_add`
AFTER DELETE ON
  `%prefix%_page`
FOR EACH ROW
  DELETE FROM `%prefix%_comment` WHERE page_id = OLD.id;

CREATE TRIGGER
  `clear_page_translation`
AFTER DELETE ON
  `%prefix%_page`
FOR EACH ROW
  DELETE FROM `%prefix%_page_translation` WHERE page_id = OLD.id;

CREATE TRIGGER
  `clear_menu_translation`
AFTER DELETE ON
  `%prefix%_menu`
FOR EACH ROW
  DELETE FROM `%prefix%_menu_translation` WHERE menu_id = OLD.id;

CREATE TRIGGER
  `clear_group_route_by_group_delete`
AFTER DELETE ON
  `%prefix%_group`
FOR EACH ROW
  DELETE FROM `%prefix%_group_route` WHERE group_id = OLD.id;

CREATE TRIGGER
  `clear_group_translation_by_group_delete`
AFTER DELETE ON
  `%prefix%_group`
FOR EACH ROW
  DELETE FROM `%prefix%_group_translation` WHERE group_id = OLD.id;

CREATE TRIGGER
  `clear_group_user_by_group_delete`
AFTER DELETE ON
  `%prefix%_group`
FOR EACH ROW
  DELETE FROM `%prefix%_group_user` WHERE group_id = OLD.id;

CREATE TRIGGER
  `clear_group_user_by_user_delete`
AFTER DELETE ON
  `%prefix%_user`
FOR EACH ROW
  DELETE FROM `%prefix%_group_user` WHERE user_id = OLD.id;
