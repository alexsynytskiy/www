-- Perform tables

-- SECTION 1 START

ALTER TABLE  `achievements` DROP  `range` ;

ALTER TABLE  `albums` DROP  `cover` ;
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
-- ALTER TABLE  `assets` DROP  `parent_id` ;
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

-- ALTER TABLE  `news` DROP  `content_category_id` ;
-- ALTER TABLE  `news` DROP  `import_id` ;
-- ALTER TABLE  `news` DROP  `year` ;
-- ALTER TABLE  `news` DROP  `month` ;
-- ALTER TABLE  `news` DROP  `day` ;
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

DROP TABLE `relations` ;

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
-- ALTER TABLE `users` DROP salt;
ALTER TABLE `users` DROP remember_token;
ALTER TABLE `users` DROP remember_token_expires_at;
ALTER TABLE `users` DROP is_public;
ALTER TABLE `users` DROP position;
ALTER TABLE `users` DROP activation_code;
ALTER TABLE `users` DROP activated_at;
ALTER TABLE `users` DROP recover_code;
ALTER TABLE `users` DROP recover_expires_at;
ALTER TABLE `users` DROP deleted_at;
-- ALTER TABLE `users` DROP activated_at;
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

-- SECTION 6 USER END +
-- SECTION 7 PROFILE START +

ALTER TABLE  `users` ADD  `status` SMALLINT NOT NULL AFTER  `state` ,
ADD INDEX (  `status` ) ;
UPDATE `users` SET status = 2
WHERE state = "deleted" ;
UPDATE `users` SET status = 1
WHERE state = "active" ;
UPDATE `users` SET status = 0
WHERE state = "pending" OR state = "suspended";
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
(0, 'Пользователь', '2015-04-14 19:33:55', NULL, 0),
(1, 'Модератор', '2015-04-14 19:33:55', NULL, 1),
(2, 'Администратор', '2015-04-14 19:33:55', NULL, 1);

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

UPDATE  `albums` AS a SET a.user_id = NULL WHERE NOT (
    SELECT COUNT( * )
    FROM  `users` AS u
    WHERE u.id = a.user_id
) ;
ALTER TABLE  `assets` CHANGE  `assetable_type`  `assetable_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE  `assets` CHANGE  `type`  `type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
DELETE FROM  `carreers` WHERE NOT EXISTS (
    SELECT p.id
    FROM  `players` AS p
    WHERE p.id =  `carreers`.`player_id`
) ;
DELETE FROM  `championship_parts` WHERE NOT EXISTS (
    SELECT p.id
    FROM  `championships` AS p
    WHERE p.id =  `championship_parts`.`championship_id`
) ;
DELETE FROM  `claims` WHERE NOT EXISTS (
    SELECT p.id
    FROM  `users` AS p
    WHERE p.id =  `claims`.`user_id`
) ;
DELETE FROM  `claims` WHERE NOT EXISTS (
    SELECT p.id
    FROM  `comments` AS p
    WHERE p.id =  `claims`.`comment_id`
) ;

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

DELETE FROM  compositions WHERE NOT EXISTS (
    SELECT p.id
    FROM  contracts AS p
    WHERE p.id =  compositions.contract_id
) ;
DELETE FROM  contracts WHERE NOT EXISTS (
    SELECT p.id
    FROM  commands AS p
    WHERE p.id =  contracts.command_id
) ;
DELETE FROM contracts WHERE NOT EXISTS (
    SELECT p.id
    FROM  commands AS p
    WHERE p.id =  contracts.command_from_id
) ;
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

UPDATE match_events SET composition_id = NULL WHERE composition_id IS NOT NULL AND NOT EXISTS (
    SELECT p.id
    FROM  compositions AS p
    WHERE p.id =  match_events.composition_id
) ;
UPDATE match_events SET substitution_id = NULL WHERE substitution_id IS NOT NULL AND NOT EXISTS (
    SELECT p.id
    FROM  compositions AS p
    WHERE p.id =  match_events.substitution_id
) ;
DELETE FROM memberships WHERE NOT EXISTS (
    SELECT p.id
    FROM  players AS p
    WHERE p.id =  memberships.player_id
) ;
UPDATE players SET amplua_id = NULL WHERE amplua_id IS NOT NULL AND NOT EXISTS (
    SELECT p.id
    FROM  ampluas AS p
    WHERE p.id =  players.amplua_id
) ;
DELETE FROM question_users WHERE NOT EXISTS (
    SELECT p.id
    FROM  users AS p
    WHERE p.id =  question_users.user_id
) ;
DELETE FROM votes WHERE NOT EXISTS (
    SELECT p.id
    FROM  users AS p
    WHERE p.id =  votes.user_id
) ;
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
  `commentable_type` varchar(50) NOT NULL,
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
GROUP BY albums.id

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