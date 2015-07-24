-- Perform tables

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- SECTION 1 START

ALTER TABLE  `achievements` DROP  `range` ;

-- ALTER TABLE  `albums` DROP  `cover` ;
ALTER TABLE  `albums` DROP  `is_freeze_frames` ;

ALTER TABLE  `ampluas` DROP  `notes` ;
UPDATE  `ampluas` SET  `parent_id` =  '2' WHERE  `ampluas`.`id` =8;
ALTER TABLE  `ampluas` CHANGE  `parent_id`  `line` INT( 2 ) NULL DEFAULT NULL ;

ALTER TABLE  `arbiters` DROP  `notes` ;
ALTER TABLE  `arbiters` DROP  `import_id` ;
ALTER TABLE  `arbiters` DROP  `city_id` ;

ALTER TABLE  `assets` DROP  `content_type` ;
ALTER TABLE  `assets` DROP  `size` ;
ALTER TABLE  `assets` DROP  `notes` ;
ALTER TABLE  `assets` DROP  `created_at` ;
ALTER TABLE  `assets` DROP  `updated_at` ;
ALTER TABLE  `assets` DROP  `width` ;
ALTER TABLE  `assets` DROP  `height` ;
ALTER TABLE  `assets` DROP  `type` ;
ALTER TABLE  `assets` DROP  `user_id` ;

-- SECTION 1 END
-- SECTION 2 START

ALTER TABLE  `banned_ips` DROP  `kind` ;
ALTER TABLE  `banned_ips` DROP  `banned_users_count` ;

DROP TABLE `banners` ;

ALTER TABLE  `championships` DROP  `notes` ;
ALTER TABLE  `championships` DROP  `import_id` ;
ALTER TABLE  `championships` DROP  `country_id` ;

ALTER TABLE  `championship_parts` DROP  `import_id` ;

DROP TABLE `cities` ;

ALTER TABLE  `claims` DROP  `count` ;
ALTER TABLE  `claims` CHANGE  `user_id`  `user_id` INT( 11 ) NULL ;

ALTER TABLE  `coaches` DROP  `import_id` ;

ALTER TABLE  `commands` DROP  `slug` ;
ALTER TABLE  `commands` DROP  `is_visible` ;
ALTER TABLE  `commands` DROP  `records` ;
ALTER TABLE  `commands` DROP  `peoples` ;
ALTER TABLE  `commands` DROP  `city_id` ;
ALTER TABLE  `commands` DROP  `import_id` ;
ALTER TABLE  `commands` DROP  `is_active` ;

-- SECTION 2 END
-- SECTION 3 START

ALTER TABLE  `comments` DROP  `user_name` ;
ALTER TABLE  `comments` DROP  `user_email` ;
ALTER TABLE  `comments` DROP  `cached_level` ;
ALTER TABLE  `comments` DROP  `lft` ;
ALTER TABLE  `comments` DROP  `rgt` ;
ALTER TABLE  `comments` DROP  `content_html` ;

ALTER TABLE  `compositions` DROP  `points` ;

ALTER TABLE  `contracts` DROP  `import_id` ;
ALTER TABLE  `contracts` DROP  `debut_command_id` ;
ALTER TABLE  `contracts` DROP  `is_main` ;

DROP TABLE `content_categories` ;

DROP TABLE `content_categories_posts` ;

ALTER TABLE  `countries` DROP  `import_id` ;
ALTER TABLE  `countries` DROP  `notes` ;

DROP TABLE `dictionaries` ;

DROP TABLE `events` ;

DROP TABLE `events_posts` ;

ALTER TABLE  `forwards` DROP  `updated_at` ;
ALTER TABLE  `forwards` DROP  `created_at` ;

DROP TABLE `internal_banners` ;

ALTER TABLE  `leagues` DROP  `notes` ;

-- SECTION 3 END
-- SECTION 4 START

ALTER TABLE  `matches` DROP  `import_id` ;

ALTER TABLE  `match_event_types` DROP  `import_id` ;

DROP TABLE `news` ;

DROP TABLE `personal_infos` ;

ALTER TABLE  `players` DROP  `import_id` ;
ALTER TABLE  `players` DROP  `delta` ;
ALTER TABLE  `players` DROP  `city_id` ;

ALTER TABLE  `posts` DROP  `year` ;
ALTER TABLE  `posts` DROP  `month` ;
ALTER TABLE  `posts` DROP  `day` ;
ALTER TABLE  `posts` DROP  `is_sended` ;
ALTER TABLE  `posts` DROP  `import_id` ;
ALTER TABLE  `posts` DROP  `kind` ;
ALTER TABLE  `posts` DROP  `delta` ;
ALTER TABLE  `posts` DROP  `is_external` ;
ALTER TABLE  `posts` DROP  `external_link` ;

-- SECTION 4 END +
-- SECTION 5 START +

DROP TABLE `post_attachments` ;

ALTER TABLE  `questions` DROP  `import_id` ;

DROP TABLE `rotator_banners` ;

DROP TABLE `schema_migrations` ;

ALTER TABLE  `seasons` DROP  `import_id` ;

DROP TABLE `settings` ;

ALTER TABLE  `sources` DROP  `email` ;

ALTER TABLE  `stadia` DROP  `import_id` ;
ALTER TABLE  `stadia` DROP  `notes` ;
ALTER TABLE  `stadia` DROP  `city_id` ;

DROP TABLE `static_categories` ;

DROP TABLE `static_contents` ;

DROP TABLE `static_pages` ;

RENAME TABLE  `subscriptions` TO  `subscribe_emails` ;

ALTER TABLE  `taggings` DROP  `created_at` ;

ALTER TABLE  `tags` DROP  `is_public` ;

ALTER TABLE  `tournaments` DROP  `position` ;

ALTER TABLE  `transfers` DROP  `import_id` ;
ALTER TABLE  `transfers` DROP  `is_dynamic` ;

ALTER TABLE  `transfer_types` DROP  `abr` ;

-- SECTION 5 END +
-- SECTION 6 USER START +

ALTER TABLE  `users` CHANGE  `login`  `username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `users` DROP remember_token;
ALTER TABLE `users` DROP remember_token_expires_at;
ALTER TABLE `users` DROP is_public;
ALTER TABLE `users` DROP position;
ALTER TABLE `users` DROP activation_code;
ALTER TABLE `users` DROP activated_at;
ALTER TABLE `users` DROP recover_code;
ALTER TABLE `users` DROP recover_expires_at;
ALTER TABLE `users` DROP deleted_at;
ALTER TABLE `users` DROP is_deliver;

ALTER TABLE  `users` ADD  `auth_key` VARCHAR( 32 ) NOT NULL AFTER  `crypted_password` ;
ALTER TABLE  `users` CHANGE  `role`  `role_id` TINYINT( 4 ) NULL DEFAULT  '0';
ALTER TABLE  `users` ADD  `new_email` VARCHAR( 255 ) NULL AFTER  `email` ;
ALTER TABLE  `users` CHANGE  `crypted_password`  `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE  `users` ADD  `api_key` VARCHAR( 255 ) NULL AFTER  `auth_key` ;
ALTER TABLE  `users` CHANGE  `last_logged_at`  `login_time` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `users` CHANGE  `last_login_ip` `login_ip` VARCHAR( 255 ) NULL DEFAULT NULL ;
ALTER TABLE  `users` CHANGE  `registration_ip` `create_ip` VARCHAR( 255 ) NULL DEFAULT NULL ;
ALTER TABLE  `users` CHANGE  `created_at`  `create_time` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `users` CHANGE  `updated_at`  `update_time` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `users` ADD  `ban_time` TIMESTAMP NULL AFTER  `create_ip` ;
ALTER TABLE  `users` ADD  `ban_reason` VARCHAR( 255 ) NULL AFTER  `ban_time` ;
ALTER TABLE  `users` CHANGE  `auth_key`  `auth_key` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE  `users` CHANGE  `role_id`  `role_id` INT NULL DEFAULT  '0';

UPDATE `users` SET role_id = 0
WHERE role_id IS NULL ;
UPDATE `users` SET role_id = 3
WHERE role_id = 2 ;
UPDATE `users` SET role_id = 2
WHERE role_id = 1 ;
UPDATE `users` SET role_id = 1
WHERE role_id = 0 ;

-- SECTION 6 USER END +
-- SECTION 7 PROFILE START +

ALTER TABLE  `users` ADD  `status` SMALLINT NOT NULL AFTER  `state` ,
ADD INDEX (  `status` ) ;
-- deleted забанен навсегда
-- active
-- suspended - забанен на время
-- pending не подтвержденный
-- const STATUS_INACTIVE = 0;
-- const STATUS_ACTIVE = 1
-- const STATUS_UNCONFIRMED_EMAIL = 2;
UPDATE `users` SET status = 0
WHERE state = "deleted" ;
UPDATE `users` SET status = 1
WHERE state = "active" ;
UPDATE `users` SET status = 2
WHERE state = "pending" ;
UPDATE `users` SET status = 1, ban_time = NOW() + INTERVAL 7 DAY
WHERE state = "suspended" ;

ALTER TABLE  `users` DROP  `state` ;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `profile_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32786 ;

INSERT INTO profile (user_id, create_time,full_name, description)
SELECT DISTINCT id, CURRENT_TIMESTAMP, name, description FROM users;
ALTER TABLE `users`
  DROP `name`,
  DROP `description`;

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  `can_admin` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;
INSERT INTO `role` (`id`, `name`, `create_time`, `update_time`, `can_admin`) VALUES
(1, 'Пользователь', '2015-04-14 19:33:55', NULL, 0),
(2, 'Модератор', '2015-04-14 19:33:55', NULL, 1),
(3, 'Администратор', '2015-04-14 19:33:55', NULL, 1);

CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_attributes` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_auth_provider_id` (`provider_id`),
  KEY `user_auth_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `consume_time` timestamp NULL DEFAULT NULL,
  `expire_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_key_key` (`key`),
  KEY `user_key_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- SECTION 7 PROFILE END +
-- SECTION 8 START +

ALTER TABLE `matches`  ADD `arbiter_assistant_3_id` INT NULL AFTER `arbiter_assistant_2_id`,  ADD INDEX (`arbiter_assistant_3_id`) ;
ALTER TABLE `matches`  ADD `arbiter_assistant_4_id` INT NULL AFTER `arbiter_assistant_3_id`,  ADD INDEX (`arbiter_assistant_4_id`) ;

-- 21.05.2015
ALTER TABLE `posts` DROP `is_cover`;
ALTER TABLE  `posts` CHANGE  `is_video`  `with_video` TINYINT( 1 ) NULL DEFAULT  '0';
ALTER TABLE  `posts` ADD  `with_photo` TINYINT NOT NULL DEFAULT  '0' AFTER  `with_video` ,
ADD INDEX (  `with_photo` ) ;
ALTER TABLE  `posts` ADD  `is_pin` TINYINT NOT NULL DEFAULT  '0' AFTER  `is_top` ,
ADD INDEX (  `is_pin` ) ;
ALTER TABLE `posts` DROP `photo_id`;
ALTER TABLE  `posts` ADD INDEX (  `allow_comment` ) ;
ALTER TABLE  `posts` ADD INDEX (  `is_yandex_rss` ) ;
ALTER TABLE  `posts` ADD INDEX (  `updated_at` ) ;
ALTER TABLE  `posts` ADD INDEX (  `comments_count` ) ;

-- SECTION 8 END +
-- SECTION 9 START +

ALTER TABLE  `assets` CHANGE  `assetable_type`  `assetable_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE  `assets` CHANGE  `thumbnail`  `thumbnail` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE  `posts` CHANGE  `content_category_id`  `content_category_id` INT( 2 ) NULL DEFAULT NULL ;

-- SECTION 9 END +
-- SECTION 10 COMMENTS START +

DELETE FROM comments WHERE user_id = 0 ;
DELETE FROM  `comments` WHERE NOT EXISTS (
    SELECT p.id
    FROM  `users` AS p
    WHERE p.id =  `comments`.`user_id`
) ;

-- magic 13 times repeat
DROP TABLE IF EXISTS backup_comments;
CREATE TABLE backup_comments SELECT id FROM comments
WHERE parent_id IS NOT NULL AND parent_id NOT IN ( SELECT id FROM comments) ;
DELETE c FROM comments c
INNER JOIN backup_comments b ON b.id = c.id ;
DROP TABLE backup_comments ;
-- end magic queries

-- SECTION 10 COMMENTS END +
-- SECTION 11 START +

-- DELETE FROM  compositions WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  contracts AS p
--     WHERE p.id =  compositions.contract_id
-- ) ;
-- DELETE FROM  contracts WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  commands AS p
--     WHERE p.id =  contracts.command_id
-- ) ;
-- DELETE FROM contracts WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  commands AS p
--     WHERE p.id =  contracts.command_from_id
-- ) ;
ALTER TABLE  `contracts` CHANGE  `command_from_id`  `command_from_id` INT( 11 ) NULL DEFAULT NULL ;
UPDATE matches SET arbiter_assistant_2_id = NULL WHERE arbiter_assistant_2_id IS NOT NULL AND NOT EXISTS (
    SELECT p.id
    FROM  arbiters AS p
    WHERE p.id =  matches.arbiter_assistant_2_id
) ;
UPDATE matches SET championship_part_id = NULL WHERE championship_part_id IS NOT NULL AND NOT EXISTS (
    SELECT p.id
    FROM  championship_parts AS p
    WHERE p.id =  matches.championship_part_id
) ;

-- SECTION 11 END +
-- SECTION 12 START +

-- magic query ONCE
DROP TABLE IF EXISTS backup_questions;
CREATE TABLE backup_questions SELECT id FROM questions
WHERE parent_id IS NOT NULL AND parent_id NOT IN ( SELECT id FROM questions) ;
DELETE c FROM questions c
INNER JOIN backup_questions b ON b.id = c.id ;
DROP TABLE backup_questions ;
-- end magic query

-- UPDATE match_events SET composition_id = NULL WHERE composition_id IS NOT NULL AND NOT EXISTS (
--     SELECT p.id
--     FROM  compositions AS p
--     WHERE p.id =  match_events.composition_id
-- ) ;
-- UPDATE match_events SET substitution_id = NULL WHERE substitution_id IS NOT NULL AND NOT EXISTS (
--     SELECT p.id
--     FROM  compositions AS p
--     WHERE p.id =  match_events.substitution_id
-- ) ;
-- DELETE FROM memberships WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  players AS p
--     WHERE p.id =  memberships.player_id
-- ) ;
-- UPDATE players SET amplua_id = NULL WHERE amplua_id IS NOT NULL AND NOT EXISTS (
--     SELECT p.id
--     FROM  ampluas AS p
--     WHERE p.id =  players.amplua_id
-- ) ;
-- DELETE FROM question_users WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  users AS p
--     WHERE p.id =  question_users.user_id
-- ) ;
-- DELETE FROM votes WHERE NOT EXISTS (
--     SELECT p.id
--     FROM  users AS p
--     WHERE p.id =  votes.user_id
-- ) ;
UPDATE assets SET  `assetable_type` =  'team' WHERE  `assetable_type` LIKE  'command' ;

ALTER TABLE  `forwards` ADD  `team_id` INT NOT NULL AFTER  `id` ,
ADD INDEX (  `team_id` ) ;
ALTER TABLE  `forwards` ADD  `player_id` INT NOT NULL AFTER  `team_id` ,
ADD INDEX (  `player_id` ) ;
ALTER TABLE `forwards`
  DROP `name`,
  DROP `command`;
UPDATE `forwards` SET `player_id` = 3371,`team_id` = 21 WHERE `forwards`.`id` = 50;
UPDATE `forwards` SET `player_id` = 2624,`team_id` = 10 WHERE `forwards`.`id` = 51;
UPDATE `forwards` SET `player_id` = 2489,`team_id` = 13 WHERE `forwards`.`id` = 63;
UPDATE `forwards` SET `player_id` = 1858,`team_id` = 1 WHERE `forwards`.`id` = 64;
UPDATE `forwards` SET `player_id` = 1901,`team_id` = 1 WHERE `forwards`.`id` = 65;
UPDATE `forwards` SET `player_id` = 4518,`team_id` = 3 WHERE `forwards`.`id` = 69;
UPDATE `forwards` SET `player_id` = 3213,`team_id` = 10 WHERE `forwards`.`id` = 71;
UPDATE `forwards` SET `player_id` = 1911,`team_id` = 10 WHERE `forwards`.`id` = 73;
UPDATE `forwards` SET `player_id` = 2845,`team_id` = 16 WHERE `forwards`.`id` = 74;
UPDATE `forwards` SET `player_id` = 2286,`team_id` = 13 WHERE `forwards`.`id` = 75;

-- SECTION 12 END +
-- SECTION 13 INDEXES START +

ALTER TABLE  `users` ADD INDEX (  `role_id` ) ;
ALTER TABLE  `users` ADD INDEX (  `status` ) ;
ALTER TABLE  `users` ADD INDEX (  `create_time` ) ;
ALTER TABLE  `users` ADD INDEX (  `update_time` ) ;
ALTER TABLE  `users` ADD INDEX (  `ban_time` ) ;

ALTER TABLE  `albums` ADD INDEX (  `is_public` ) ;
ALTER TABLE  `ampluas` ADD INDEX (  `line` ) ;
ALTER TABLE  `assets` ADD INDEX (  `assetable_id` ) ;
ALTER TABLE  `carreers` ADD INDEX (  `season_id` ) ;
ALTER TABLE  `carreers` ADD INDEX (  `command_id` ) ;

ALTER TABLE  `claims` ADD INDEX (  `comment_author` ) ;
ALTER TABLE  `claims` ADD INDEX (  `created_at` ) ;
ALTER TABLE  `claims` ADD INDEX (  `updated_at` ) ;

ALTER TABLE  `comments` ADD INDEX (  `parent_id` ) ;
ALTER TABLE  `compositions` ADD INDEX (  `contract_type` ) ;
ALTER TABLE  `compositions` ADD INDEX (  `command_id` ) ;
ALTER TABLE  `contracts` ADD INDEX (  `season_id` ) ;
ALTER TABLE  `contracts` ADD INDEX (  `amplua_id` ) ;
ALTER TABLE  `contracts` ADD INDEX (  `contractable_type` ) ;

ALTER TABLE  `matches` ADD INDEX (  `is_visible` ) ;
ALTER TABLE  `matches` ADD INDEX (  `league_id` ) ;
ALTER TABLE  `matches` ADD INDEX (  `arbiter_main_id` ) ;
ALTER TABLE  `matches` ADD INDEX (  `arbiter_assistant_1_id` ) ;
ALTER TABLE  `matches` ADD INDEX (  `arbiter_assistant_2_id` ) ;
ALTER TABLE  `matches` ADD INDEX (  `arbiter_reserve_id` ) ;

ALTER TABLE  `match_events` ADD INDEX (  `position` ) ;
ALTER TABLE  `memberships` ADD INDEX (  `amplua_id` ) ;
ALTER TABLE  `posts` ADD INDEX (  `is_public` ) ;
ALTER TABLE  `posts` ADD INDEX (  `title` ) ;
ALTER TABLE  `questions` ADD INDEX (  `created_at` ) ;
ALTER TABLE  `question_ips` ADD INDEX (  `ip` ) ;

ALTER TABLE  `sources` ADD INDEX (  `name` ) ;
ALTER TABLE  `sources` ADD INDEX (  `url` ) ;
ALTER TABLE  `tournaments` ADD INDEX (  `season_id` ) ;
ALTER TABLE  `transfers` ADD INDEX (  `transfer_type_id` ) ;
ALTER TABLE  `transfers` ADD INDEX (  `created_at` ) ;

ALTER TABLE  `assets` ADD INDEX (  `comments_count` ) ;
ALTER TABLE  `assets` ADD INDEX (  `thumbnail` ) ;

-- SECTION 13 INDEXES END +
-- SECTION 14 NEW START

ALTER TABLE  `tournaments` ADD  `penalty_points` INT NOT NULL DEFAULT  '0' AFTER  `points` ,
ADD INDEX (  `penalty_points` ) ;
ALTER TABLE  `tournaments` ADD  `weight` INT NOT NULL DEFAULT  '0' AFTER  `league_id` ,
ADD INDEX (  `weight` ) ;
ALTER TABLE  `seasons` ADD  `window` TINYINT NOT NULL DEFAULT  '0',
ADD INDEX (  `window` ) ;
UPDATE  `seasons` SET `window` = 1 WHERE `name` LIKE  '%/%' ;

DELETE FROM `contracts`
WHERE `contractable_type` = '' ;
DELETE c.* FROM `compositions` c
INNER JOIN contracts ON c.contract_id = contracts.id
WHERE contracts.contractable_type = 'coach' ;
DELETE FROM `contracts`
WHERE contracts.contractable_type = 'coach' ;
ALTER TABLE  `contracts` DROP  `contractable_type` ;
ALTER TABLE  `contracts` CHANGE  `contractable_id`  `player_id` INT( 11 ) NOT NULL DEFAULT  '0';

ALTER TABLE `subscribe_emails`
  DROP `type`,
  DROP `user_id`;
ALTER TABLE  `subscribe_emails` ADD UNIQUE (
`email`
);

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text,
  `size` int(11) DEFAULT '0',
  `region` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `size` (`size`,`region`,`weight`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `matches` ADD  `home_ball_possession` INT NULL AFTER  `guest_goals` ,
ADD INDEX (  `home_ball_possession` ) ;
ALTER TABLE  `matches` ADD  `guest_ball_possession` INT NULL AFTER  `home_ball_possession` ,
ADD INDEX (  `guest_ball_possession` ) ;

DROP TABLE question_ips;
CREATE TABLE IF NOT EXISTS `top_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `coaches` DROP  `city_id` ;
CREATE TABLE IF NOT EXISTS `team_coaches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `is_main` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`,`season_id`,`coach_id`,`is_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE  `team_coaches` CHANGE  `is_main`  `is_main` TINYINT( 4 ) NULL DEFAULT  '0';

-- SECTION 14 NEW END


-- SECTION 15 START

CREATE TABLE IF NOT EXISTS `comment_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commentable_id` int(11) NOT NULL,
  `commentable_type` varchar(20) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `commentable_id` (`commentable_id`,`commentable_type`,`count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO comment_counts
(`commentable_id`, `commentable_type`, `count`)
SELECT p.id, 'post', p.comments_count
FROM posts p ;
UPDATE comment_counts SET `count` = 0 WHERE `count` < 0 ;

INSERT INTO comment_counts
(`commentable_id`, `commentable_type`, `count`)
SELECT t.id, 'transfer', t.comments_count
FROM transfers t ;

INSERT INTO comment_counts
(`commentable_id`, `commentable_type`, `count`)
SELECT m.id, 'match', m.comments_count
FROM matches m ;

INSERT INTO comment_counts
(`commentable_id`, `commentable_type`, `count`)
SELECT a.id, 'asset', a.comments_count
FROM assets a ;

INSERT INTO comment_counts
(`commentable_id`, `commentable_type`, `count`)
SELECT albums.id, 'album', SUM(assets.comments_count) as `sum` FROM albums
INNER JOIN assets ON assets.assetable_id = albums.id AND assets.assetable_type = 'album'
GROUP BY albums.id ;

ALTER TABLE  `assets` DROP  `comments_count` ;
ALTER TABLE  `matches` DROP  `comments_count` ;
ALTER TABLE  `transfers` DROP  `comments_count` ;
ALTER TABLE  `posts` DROP  `comments_count` ;

CREATE TABLE IF NOT EXISTS `top200_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- NEW 
CREATE TABLE IF NOT EXISTS `selected_blogs` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`post_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text,
  `is_public` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cached_tag_list` varchar(255) DEFAULT NULL,
  `is_pin` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`title`,`is_public`,`created_at`,`updated_at`,`is_pin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- SECTION 15 END

CREATE TABLE IF NOT EXISTS `main_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- NEW 1
UPDATE `relations` SET relation_type_id = 1
WHERE relation_type_id = 3 ;
UPDATE `relations` SET relation_type_id = 1
WHERE relation_type_id = 4 ;

-- NEW 2
ALTER TABLE  `posts` ADD FULLTEXT (
`content`
);
UPDATE assets AS a1
INNER JOIN assets AS a2 ON a1.parent_id = a2.id
SET a1.assetable_id = a2.assetable_id, a1.assetable_type = a2.assetable_type
WHERE a1.assetable_id IS NULL ;

UPDATE assets
INNER JOIN albums ON assets.id = albums.cover
SET assets.assetable_type = 'album_cover' ;

UPDATE assets AS a1
INNER JOIN assets AS a2 ON a1.parent_id = a2.id
SET a1.assetable_type = 'album_cover'
WHERE a2.assetable_type = 'album_cover' ;

ALTER TABLE  `albums` DROP  `cover` ;

-- NEW 3
CREATE TABLE IF NOT EXISTS `cache_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_name` varchar(100) DEFAULT NULL,
  `content` longtext,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `machine_name` (`machine_name`),
  KEY `created_at` (`created_at`,`updated_at`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `main_info`
--
INSERT INTO `main_info` (`id`, `name`, `title`, `content`) VALUES
(1, 'leadership', 'Руководство', '<p><strong>Президент</strong>: <br>Игорь Суркис</p><p><strong>Генеральный директор</strong>: <br>Резо Чохонелидзе</p><p><strong>Вице-президенты:</strong><br>Леонид Ашкенази<br>Михаил Петрашенко<br>Евгений Рашутин<br>Владимир Старовойт<br>Алексей Паламарчук<br>Алексей Семененко</p><p><strong>Первый вице-президент</strong>: <br>Виталий Сивков</p><p><strong>Директор по связям с общественностью:<br></strong>Николай Несенюк</p><p><strong>Руководитель службы пресс-атташе:<br></strong>Алексей Семененко</p><p><strong>Директор медиа-центра:<br></strong>Сергей Полховский</p>'),
(2, 'general_info', 'Основная информация', '<p><strong>Адрес</strong>: <br>Украина, г. Киев, ул. Грушевского, 3\r\n</p><p><strong>Телефон</strong>: <br>(+38 044) 536-00-08<br>(+38 044) 536-00-03 <br>Факс (+38 044) 278-41-35<br><strong>Официальный веб-сайт:</strong> <br><a href="http://www.fcdynamo.kiev.ua/" target="new">http://www.fcdynamo.kiev.ua</a><br><br>Выступает на стадионе<strong> НСК "Олимпийский"</strong>:<br><strong>Адрес:</strong> <br>г. Киев, ул. Красноармейская, 50<br><strong>Построен:</strong> <br>1923<br>\r\n<strong>Реконструкции</strong>: <br>1941, 1968, 1980, 1999, 2011<br><strong>Количество мест:</strong> <br>70,050 в т. ч. индивидуальных: 70,050<br><strong>Искусственное освещение:</strong> 1800 люкс<br><strong>Электроподогрев поля:</strong> есть<br><strong>Состояние стадиона: </strong>хорошее<br><strong>Соответствие требованиям УЕФА:</strong> +\r\n</p><p><strong>Домашний стадион</strong>: <br>"Динамо" им. В. Лобановского<br><strong>Построен:</strong> <br>1934<br><strong>Реконструкции:</strong> <br>1956, 1980<br><strong>Количество мест:</strong> <br>16863<br><strong>в т. ч. индивидуальных:</strong> <br>16863<br><strong>Искусственное освещение:</strong> <br>1400 люкс<br><strong>Электроподогрев поля:</strong> <br>есть<br><strong>Состояние стадиона:</strong> <br>хорошее<br><strong>Соответствие требованиям УЕФА:</strong> +\r\n</p>'),
(3, 'other_info', 'Другая информация', '<p>В структуру клуба входят команды: <strong>"Динамо"</strong> (высшая лига), <strong>U-21 </strong>(молодежное первенство), <strong>"Динамо-2" (1-я лига), U-19.</strong><br><br>Команда "Динамо" Киев - участник всех 54-х чемпионатов СССР в высшей лиге (1936-1991)<br><br><strong>Самая крупная победа</strong>:<br>- в чемпионатах СССР: 8:0 ("Черноморец" Одесса, 1977, "Торпедо" Кутаиси, 1983);<br>- в чемпионатах Украины - 9:0 ("Ильичевец", 2010/11);<br>- в еврокубках - 8:0 ("Барри Таун", Уэльс, Лига чемпионов 1998/99). <br><br><strong>Самое крупное поражение:</strong><br>- в чемпионатах СССР - 0:7 ("Динамо" Москва, 1940, ЦДКА, 1945);<br>- в чемпионатах Украины - 0:3 ("Металлист" Харьков, 2013/14);<br>- в еврокубках - 0:5 ("Бенфика", Кубок чемпионов 1991/92, "Ювентус", Лига чемпионов, 2002/03)</p>'),
(4, 'cups', 'Кубки', '<table class="dynamoDate">\r\n<tbody>\r\n<tr>\r\n <td><strong>1936(о)</strong>\r\n  </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1937</strong>\r\n </td>\r\n <td>Третий призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1939</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1952</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1954</strong>\r\n </td>\r\n <td>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1960</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1961</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1964</strong>\r\n </td>\r\n <td>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1965</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1966</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1967</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1968</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1969</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1971</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1972</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1973</strong>\r\n </td>\r\n <td>Финалист Кубка СССР<br>Четвертьфиналист Кубка (Лиги) Чемпионов\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1974</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1975</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка обладателей Кубков<br>Обладатель Суперкубка УЕФА\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1976</strong>\r\n </td>\r\n <td>Четвертьфиналист Кубка (Лиги) Чемпионов\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1976(о)</strong>\r\n  </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1977</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Полуфиналист Кубка (Лиги) Чемпионов\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1978</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР<br>Обладатель Кубка СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1979</strong>\r\n </td>\r\n <td>Третий призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1980</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1981</strong>\r\n </td>\r\n <td>Чемпион СССР\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1982</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР<br>Обладатель Кубка СССР<br>Четвертьфиналист Кубка (Лиги) Чемпионов\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1983</strong>\r\n </td>\r\n <td>Четвертьфиналист Кубка (Лиги) Чемпионов\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1985</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1986</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка обладателей Кубков\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1987</strong>\r\n </td>\r\n <td>Обладатель Кубка СССР<br>Полуфиналист Кубка (Лиги) Чемпионов\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1988</strong>\r\n </td>\r\n <td>Второй призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1989</strong>\r\n </td>\r\n <td>Третий призер чемпионата СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1990</strong>\r\n </td>\r\n <td>Чемпион СССР<br>Обладатель Кубка СССР\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1992</strong>\r\n </td>\r\n <td>Второй призер чемпионата Украины\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1993</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1994</strong>\r\n </td>\r\n <td>Чемпион Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1995</strong>\r\n </td>\r\n <td>Чемпион Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1996</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины<br>Обладатель Кубка чемпионов Содружества\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1997</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка чемпионов Содружества\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1998</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины<br>Четвертьфиналист Кубка (Лиги) Чемпионов<br>Обладатель Кубка чемпионов Содружества\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1999</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины<br>Полуфиналист Кубка (Лиги) Чемпионов\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2000</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2001</strong>\r\n </td>\r\n <td>Чемпион Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2002</strong>\r\n </td>\r\n <td>Второй призер чемпионата Украины<br>Финалист Кубка Украины<br>Обладатель Кубка чемпионов Содружества\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2003</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины<br>Обладатель Кубка Лобановского\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2004</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Суперкубка Украины<br>Обладатель Кубка Лобановского\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2005</strong>\r\n </td>\r\n <td>Второй призер чемпионата Украины<br>Обладатель Кубка Украины\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2006</strong>\r\n </td>\r\n <td>Второй призер чемпионата Украины<br>Обладатель Кубка Украины<br>Обладатель Суперкубка Украины\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2007</strong>\r\n </td>\r\n <td>Чемпион Украины<br>Обладатель Кубка Украины<br>Обладатель Суперкубка Украины\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>2008</strong>\r\n </td>\r\n <td>Второй призер чемпионата Украины<br>Финалист Кубка Украины<br>Обладатель Кубка Первого канала\r\n </td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(5, 'soviet_awards', 'Команда и ее игроки завоевывали следующие призы советского футбола', '<table class="dynamoDate">\r\n<tbody>\r\n<tr>\r\n <td><strong>1959</strong>\r\n </td>\r\n <td>"Справедливой игры"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1960</strong>\r\n </td>\r\n <td>"Двумя составами"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1964</strong>\r\n </td>\r\n <td>Лучшему вратарю (Виктор Банников)<br>"Самый красивый гол" (Олег Базилевич)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1965</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"Крупного счета"<br>"За волю к победе"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1966</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"<br>"Агрессивного гостя"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1967</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"<br>"Самый красивый гол" (Анатолий Бышовец)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1968</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Агрессивного гостя"<br>"За волю к победе"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1969</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Крупного счета"<br>"Честь флага"<br>Лучшему вратарю (Евгений Рудаков)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1970</strong>\r\n </td>\r\n <td>"Честь флага"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1971</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"<br>"Агрессивного гостя"<br>"Кубок прогресса"<br>Лучшему вратарю (Евгений Рудаков)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1972</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Честь флага"<br>Лучшему бомбардиру (Олег Блохин)<br>Лучшему вратарю (Евгений Рудаков)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1973</strong>\r\n </td>\r\n <td>"Честь флага"<br>Лучшему бомбардиру (Олег Блохин)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1974</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"За лучшую разность"<br>"Честь флага"<br>Лучшему бомбардиру (Олег Блохин)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1975</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"<br>"Честь флага"<br>Лучшему бомбардиру (Олег Блохин)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1976</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Честь флага"\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1977</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"Крупного счета"<br>"Справедливой игры"<br>"За лучшую разность"<br>"Агрессивного гостя"<br>"Честь флага"<br>Лучшему бомбардиру (Олег Блохин)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1978</strong>\r\n </td>\r\n <td>"Крупного счета"<br>"Честь флага"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1979</strong>\r\n </td>\r\n <td>"За волю к победе"\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1980</strong>\r\n </td>\r\n <td>"Кубок сезона"<br>Им. Г. Федотова - самой результативной команде<br>"Двумя составами"<br>"За лучшую разность"<br>"Агрессивного гостя"<br>"Хрустальный мяч" (Олег Блохин)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1981</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Агрессивного гостя"<br>"Агрессивного гостя"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1982</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1983</strong>\r\n </td>\r\n <td>"Двумя составами"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1985</strong>\r\n </td>\r\n <td>"Кубок сезона"<br>"Двумя составами"<br>"За лучшую разность"<br>"Агрессивного гостя"<br>"Кубок прогресса"<br>"Первая высота"\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1986</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"Первая высота"<br>Лучшему вратарю (Виктор Чанов)<br>"Верность клубу" (Олег Блохин)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1987</strong>\r\n </td>\r\n <td>"Рыцарь атаки" (Александр Заваров)\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1988</strong>\r\n </td>\r\n <td>"Крупного счета"<br>"За волю к победе"<br>"Кубок прогресса"<br>"Первая высота"\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1989</strong>\r\n </td>\r\n <td>Им. Г. Федотова - самой результативной команде<br>"За благородство и мужество"<br>"Лучшему новичку" (Олег Лужный)\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1990</strong>\r\n </td>\r\n <td>"Двумя составами"<br>"Крупного счета"<br>"За лучшую разность"<br>"Первая высота"<br>Лучшему бомбардиру (Олег Протасов)<br>"Лучшему новичку" (Сергей Юран)\r\n </td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(6, 'best_soviet_players', 'Лучшие игроки СССР', '<table class="dynamoDate">\r\n<tbody>\r\n<tr>\r\n <td><strong>1966</strong>\r\n </td>\r\n <td>Андрей Биба\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1969</strong>\r\n </td>\r\n <td>Владимир Мунтян\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1971</strong>\r\n </td>\r\n <td>Евгений Рудаков\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1973</strong>\r\n </td>\r\n <td>Олег Блохин\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1974</strong>\r\n </td>\r\n <td>Олег Блохин\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1975</strong>\r\n </td>\r\n <td>Олег Блохин\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1985</strong>\r\n </td>\r\n <td>Анатолий Демьяненко\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1986</strong>\r\n </td>\r\n <td>Александр Заваров\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>1988</strong>\r\n </td>\r\n <td>Алексей Михайличенко\r\n  </td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(7, 'best_ukraine_players', 'Лучшие игроки Украины', '<table class="dynamoDate"><tbody><tr><td><strong>1992</strong></td><td>Виктор Леоненко</td></tr><tr><td><strong>1993</strong></td><td>Виктор Леоненко</td></tr><tr><td><strong>1994</strong></td><td>Виктор Леоненко</td></tr><tr><td><strong>1995</strong></td><td>Юрий Калитвинцев</td></tr><tr><td><strong>1996</strong></td><td>Сергей Ребров</td></tr><tr><td><strong>1997</strong></td><td>Андрей Шевченко</td></tr><tr><td><strong>1998</strong></td><td>Сергей Ребров</td></tr><tr><td><strong>1999</strong></td><td>Андрей Шевченко</td></tr><tr><td><strong>2001</strong></td><td>Валентин Белькевич</td></tr><tr><td><strong>2003</strong></td><td>Валентин Белькевич</td></tr><tr><td><strong>2005</strong></td><td>Александр Шовковский</td></tr></tbody></table>'),
(8, 'gold_ball', 'Приз лучшего футболиста Европы - "Золотой мяч" еженедельника "Франс Футбол"', '<table class="dynamoDate"><tbody><tr><td><strong>1975</strong></td><td>Олег Блохин</td></tr><tr><td><strong>1986</strong></td><td>Игорь Беланов</td></tr><tr><td><strong>1988</strong></td><td>Алексей Михайличенко 4-е место</td></tr><tr><td><strong>1999</strong></td><td>Андрей Шевченко 3-е место</td></tr></tbody></table>'),
(9, 'max_matches', 'Наибольшее количество матчей за клуб провели', '<table>\r\n<tbody>\r\n<tr>\r\n  <td>В чемпионате СССР:<br><strong>Олег Блохин</strong>\r\n  </td>\r\n <td>432\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td>В еврокубках:<br><strong>Олег Блохин</strong><br>\r\n </td>\r\n <td>79\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионате Украины<br>(высшая лига):<br><strong>Александр Шовковский</strong><br>\r\n </td>\r\n <td>344\r\n </td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионатах Украины<br>(первая лига):<br><strong>Сергей Федоров</strong><br>\r\n  </td>\r\n <td>182\r\n </td>\r\n</tr><tr>\r\n  <td>В чемпионатах Украины<br>(вторая лига):<br><strong>Всеволод Романенко</strong><br></td>\r\n <td>90</td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(10, 'best_forwards', 'Лучшие бомбардиры клуба', '<table>\r\n<tbody>\r\n<tr>\r\n  <td>В чемпионате СССР:<br><strong>Олег Блохин</strong>\r\n  </td>\r\n <td>211<br></td>\r\n</tr>\r\n<tr>\r\n <td>В еврокубках:<br><strong>Сергей Ребров</strong><br>\r\n </td>\r\n <td>28<br></td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионате Украины<br>(высшая лига):<br><strong>Сергей Ребров</strong><br>\r\n  </td>\r\n <td>103<br></td>\r\n</tr>\r\n<tr>\r\n <td>В чемпионатах Украины<br>(первая лига):<br><strong>Олег Венглинский</strong><br>\r\n  </td>\r\n <td>58<br></td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионатах Украины<br>(вторая лига):<br><strong>Станислав Попов</strong><br>\r\n </td>\r\n <td>17</td></tr></tbody></table>'),
(11, 'max_goals', 'Рекордсмены клуба по количеству забитых мячей за сезон', '<table>\r\n<tbody>\r\n<tr>\r\n <td>В чемпионате СССР:<br><strong>Олег Блохин (1974)</strong></td>\r\n  <td>20<br>\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>В еврокубках:<br><strong>Андрей Шевченко (1998/99)</strong><br>\r\n </td>\r\n <td>10\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>Сергей Ребров (1999/2000)</strong><br>\r\n  </td>\r\n <td>10\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионате Украины<br>(высшая лига):<br><strong>Сергей Ребров (1997/1998)</strong><br>\r\n  </td>\r\n <td>22<br>\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td><strong>Максим Шацких (2002/2003)</strong><strong><br></strong>\r\n </td>\r\n <td>22\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионатах Украины<br>(первая лига):<br><strong>Андрей Гусин (1994/95)</strong><br>\r\n  </td>\r\n <td>22<br>\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>В чемпионатах Украины<br>(вторая лига):<br><strong>Станислав Попов (2001/02)</strong><br>\r\n </td>\r\n <td>15<br><br>\r\n  </td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(12, 'image', 'Фото команды "Динамо" Киев 2014/15', '<p><img src="http://dynamomania.dev/images/store/post_attachments/559169d398b37.jpg"></p>');


CREATE TABLE IF NOT EXISTS `team_coaches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `is_main` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`,`season_id`,`coach_id`,`is_main`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

--
-- Дамп данных таблицы `team_coaches`
--

INSERT INTO `team_coaches` (`id`, `team_id`, `season_id`, `coach_id`, `is_main`) VALUES
(1, 616, 43, 41, 1),
(2, 616, 43, 42, 0),
(4, 616, 45, 41, 1),
(5, 616, 45, 42, 0),
(6, 616, 45, 47, 0),
(7, 616, 45, 48, 0),
(8, 616, 45, 49, 0),
(9, 616, 48, 52, 1),
(10, 616, 48, 42, 0),
(11, 616, 48, 49, 0),
(12, 616, 48, 48, 0),
(13, 616, 48, 50, 0),
(14, 616, 51, 52, 1),
(15, 616, 51, 42, 0),
(16, 616, 51, 50, 0),
(17, 616, 51, 49, 0),
(18, 616, 51, 48, 0),
(19, 616, 53, 52, 1),
(20, 616, 53, 48, 0),
(21, 616, 53, 42, 0),
(22, 616, 53, 59, 0),
(23, 616, 55, 50, 1),
(24, 616, 55, 55, 0),
(25, 616, 57, 65, 1),
(26, 616, 57, 64, 0),
(27, 616, 57, 66, 0),
(28, 8, 43, 24, 1),
(29, 8, 43, 31, 0),
(30, 8, 43, 33, 0),
(31, 8, 45, 24, 1),
(32, 8, 45, 31, 0),
(33, 8, 45, 33, 0),
(34, 8, 48, 51, 1),
(35, 8, 48, 31, 0),
(36, 8, 48, 53, 0),
(37, 8, 48, 54, 0),
(38, 8, 51, 51, 1),
(39, 8, 51, 53, 0),
(40, 8, 51, 31, 0),
(41, 8, 51, 54, 0),
(42, 8, 53, 51, 1),
(43, 8, 53, 53, 0),
(44, 8, 53, 31, 0),
(45, 8, 53, 54, 0),
(46, 8, 55, 52, 1),
(47, 8, 55, 31, 0),
(48, 8, 55, 42, 0),
(49, 8, 57, 67, 1),
(50, 8, 57, 42, 0),
(51, 8, 57, 31, 0),
(52, 878, 53, 50, 1),
(53, 878, 53, 55, 0),
(54, 878, 55, 59, 1),
(55, 878, 55, 60, 0),
(56, 878, 57, 65, 1),
(57, 878, 57, 64, 0),
(58, 878, 57, 66, 0),
(59, 1, 43, 38, 1),
(60, 1, 43, 35, 0),
(61, 1, 43, 36, 0),
(62, 1, 43, 40, 0),
(63, 1, 43, 8, 0),
(64, 1, 43, 43, 0),
(65, 1, 45, 44, 1),
(66, 1, 45, 46, 0),
(67, 1, 45, 36, 0),
(68, 1, 45, 8, 0),
(69, 1, 45, 5, 0),
(70, 1, 48, 38, 1),
(71, 1, 48, 35, 0),
(72, 1, 48, 5, 0),
(73, 1, 48, 43, 0),
(74, 1, 48, 47, 0),
(75, 1, 48, 36, 0),
(76, 1, 48, 8, 0),
(77, 1, 51, 38, 1),
(78, 1, 51, 43, 0),
(79, 1, 51, 36, 0),
(80, 1, 51, 35, 0),
(81, 1, 51, 8, 0),
(82, 1, 51, 47, 0),
(83, 1, 51, 5, 0),
(84, 1, 53, 56, 1),
(85, 1, 53, 47, 0),
(86, 1, 53, 5, 0),
(87, 1, 53, 57, 0),
(88, 1, 53, 3, 0),
(89, 1, 53, 58, 0),
(90, 1, 53, 35, 0),
(91, 1, 55, 47, 1),
(92, 1, 55, 63, 0),
(93, 1, 55, 62, 0),
(94, 1, 55, 5, 0),
(95, 1, 57, 47, 1),
(96, 1, 57, 63, 0),
(97, 1, 57, 62, 0),
(98, 1, 57, 5, 0);

CREATE TABLE IF NOT EXISTS `tournament_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `scored_missed_weight` int(11) NOT NULL,
  `goal_scored_weight` int(11) NOT NULL,
  `goal_missed_weight` int(11) NOT NULL,
  `win_weight` int(11) NOT NULL,
  `draw_weight` int(11) NOT NULL,
  `defeat_weight` int(11) NOT NULL,
  `cl_positions` varchar(50) NOT NULL,
  `el_positions` varchar(50) NOT NULL,
  `reduction_positions` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `tournament_settings` (`id`, `season_id`, `scored_missed_weight`, `goal_scored_weight`, `goal_missed_weight`, `win_weight`, `draw_weight`, `defeat_weight`, `cl_positions`, `el_positions`, `reduction_positions`) VALUES
(1, 43, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5', '15, 16'),
(2, 45, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5, 6', '15, 16'),
(3, 48, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5, 6', '15, 16'),
(4, 51, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5, 7', '15, 16'),
(5, 53, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5, 6', '7'),
(6, 55, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5, 7', '9, 15, 16'),
(7, 57, 4, 3, 2, 5, 1, 0, '1, 2', '3, 4, 5', '14');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;