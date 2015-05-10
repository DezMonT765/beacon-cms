/*
Navicat MySQL Data Transfer

Source Server         : beacons
Source Server Version : 50541
Source Host           : localhost:3306
Source Database       : beacons

Target Server Type    : MYSQL
Target Server Version : 50541
File Encoding         : 65001

Date: 2015-05-10 13:31:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('admin', '1', null, 'userGroup', null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('create_beacon', '2', null, null, null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('create_profile', '2', null, null, null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('delete_beacon', '2', null, null, null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('delete_profile', '2', null, 'app\\rbac\\CanDelete', null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('super_admin', '1', null, 'userGroup', null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('update_beacon', '2', null, null, null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('update_profile', '2', null, 'app\\rbac\\CanEdit', null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('user', '1', null, 'userGroup', null, '1430395957', '1430395957');
INSERT INTO `auth_item` VALUES ('user_update_beacon', '2', null, 'ownBeacon', null, '1430395957', '1430395957');

-- ----------------------------
-- Table structure for `auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('super_admin', 'admin');
INSERT INTO `auth_item_child` VALUES ('admin', 'create_beacon');
INSERT INTO `auth_item_child` VALUES ('admin', 'create_profile');
INSERT INTO `auth_item_child` VALUES ('admin', 'delete_beacon');
INSERT INTO `auth_item_child` VALUES ('admin', 'delete_profile');
INSERT INTO `auth_item_child` VALUES ('admin', 'update_beacon');
INSERT INTO `auth_item_child` VALUES ('user_update_beacon', 'update_beacon');
INSERT INTO `auth_item_child` VALUES ('user', 'update_profile');
INSERT INTO `auth_item_child` VALUES ('admin', 'user');
INSERT INTO `auth_item_child` VALUES ('user', 'user_update_beacon');

-- ----------------------------
-- Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES ('app\\rbac\\CanDelete', 'O:18:\"app\\rbac\\CanDelete\":3:{s:4:\"name\";s:18:\"app\\rbac\\CanDelete\";s:9:\"createdAt\";i:1430395957;s:9:\"updatedAt\";i:1430395957;}', '1430395957', '1430395957');
INSERT INTO `auth_rule` VALUES ('app\\rbac\\CanEdit', 'O:16:\"app\\rbac\\CanEdit\":3:{s:4:\"name\";s:16:\"app\\rbac\\CanEdit\";s:9:\"createdAt\";i:1430395957;s:9:\"updatedAt\";i:1430395957;}', '1430395957', '1430395957');
INSERT INTO `auth_rule` VALUES ('ownBeacon', 'O:22:\"app\\rbac\\CanEditBeacon\":3:{s:4:\"name\";s:9:\"ownBeacon\";s:9:\"createdAt\";i:1430395957;s:9:\"updatedAt\";i:1430395957;}', '1430395957', '1430395957');
INSERT INTO `auth_rule` VALUES ('userGroup', 'O:22:\"app\\rbac\\UserGroupRule\":3:{s:4:\"name\";s:9:\"userGroup\";s:9:\"createdAt\";i:1430395957;s:9:\"updatedAt\";i:1430395957;}', '1430395957', '1430395957');

-- ----------------------------
-- Table structure for `beacon_bindings`
-- ----------------------------
DROP TABLE IF EXISTS `beacon_bindings`;
CREATE TABLE `beacon_bindings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `beacon_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beacon_bindings_ibfk_1` (`beacon_id`),
  KEY `beacon_bindings_ibfk_2` (`group_id`),
  CONSTRAINT `beacon_bindings_ibfk_1` FOREIGN KEY (`beacon_id`) REFERENCES `beacons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `beacon_bindings_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of beacon_bindings
-- ----------------------------
INSERT INTO `beacon_bindings` VALUES ('5', '8', '1');
INSERT INTO `beacon_bindings` VALUES ('7', '10', '1');
INSERT INTO `beacon_bindings` VALUES ('8', '11', '3');
INSERT INTO `beacon_bindings` VALUES ('9', '12', '1');
INSERT INTO `beacon_bindings` VALUES ('10', '16', '1');
INSERT INTO `beacon_bindings` VALUES ('11', '17', '1');
INSERT INTO `beacon_bindings` VALUES ('12', '18', '3');
INSERT INTO `beacon_bindings` VALUES ('13', '20', '3');
INSERT INTO `beacon_bindings` VALUES ('14', '21', '3');
INSERT INTO `beacon_bindings` VALUES ('15', '22', '1');
INSERT INTO `beacon_bindings` VALUES ('16', '9', '3');

-- ----------------------------
-- Table structure for `beacon_pins`
-- ----------------------------
DROP TABLE IF EXISTS `beacon_pins`;
CREATE TABLE `beacon_pins` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `x` smallint(6) NOT NULL DEFAULT '0',
  `y` smallint(6) NOT NULL DEFAULT '0',
  `canvas_width` smallint(6) NOT NULL DEFAULT '0',
  `canvas_height` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `beacon_pins_ibfk_1` FOREIGN KEY (`id`) REFERENCES `beacons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of beacon_pins
-- ----------------------------
INSERT INTO `beacon_pins` VALUES ('8', 'Nice beacon', '342', '96', '800', '628');
INSERT INTO `beacon_pins` VALUES ('9', 'Leverage', '270', '6', '800', '628');
INSERT INTO `beacon_pins` VALUES ('10', 'beacon', '232', '244', '800', '628');
INSERT INTO `beacon_pins` VALUES ('11', 'text', '534', '282', '800', '628');
INSERT INTO `beacon_pins` VALUES ('12', 'billing', '88', '324', '800', '628');
INSERT INTO `beacon_pins` VALUES ('22', 'test', '573', '39', '800', '628');

-- ----------------------------
-- Table structure for `beacon_statistic`
-- ----------------------------
DROP TABLE IF EXISTS `beacon_statistic`;
CREATE TABLE `beacon_statistic` (
  `id` int(11) NOT NULL,
  `power_level` int(6) DEFAULT NULL,
  `show_count` int(11) DEFAULT NULL,
  `click_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `beacon_statistic_ibfk_1` FOREIGN KEY (`id`) REFERENCES `beacons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of beacon_statistic
-- ----------------------------

-- ----------------------------
-- Table structure for `beacons`
-- ----------------------------
DROP TABLE IF EXISTS `beacons`;
CREATE TABLE `beacons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `picture` varchar(64) DEFAULT NULL,
  `place` varchar(256) DEFAULT NULL,
  `uuid` varchar(50) NOT NULL,
  `minor` int(11) NOT NULL,
  `major` int(11) NOT NULL,
  `alias` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of beacons
-- ----------------------------
INSERT INTO `beacons` VALUES ('8', 'Nice Beacon', 'Nice beacon', 'Beacon at the see', 'hCbyxgbv-Ih3tsZQ.png', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);
INSERT INTO `beacons` VALUES ('9', 'Test', 'Leverage', 'test', 'uiXEwS6TVgRNf_As.jpg', 'at the campus', 'lsad-123zx-ww12-brak', '123', '321', null);
INSERT INTO `beacons` VALUES ('10', 'Beacons are everywhere!', 'beacon', 'the new wolrd around us', '-4cr66LghhYJiw_q.png', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);
INSERT INTO `beacons` VALUES ('11', 'This is Beacon', 'text', 'This is Beacon', 'QFZKwX.jpg', 'at the campus', 'lsad-123zx-ww12-brak', '123', '321', null);
INSERT INTO `beacons` VALUES ('12', 'My groupped beacon', 'billing', 'This is first group', 'ECBRSuE2sITWbCZY.jpg', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);
INSERT INTO `beacons` VALUES ('16', 'test', 'software', 'test', '', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);
INSERT INTO `beacons` VALUES ('17', 'test', 'eatroom', 'aaa', '', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);
INSERT INTO `beacons` VALUES ('18', 'This is Beacon Test', 'mushroom', 'Test', '', 'at the campus', 'lsad-123zx-ww12-brak', '123', '321', null);
INSERT INTO `beacons` VALUES ('20', 'Test beacon', 'classroom', 'This is test', 'EOWPXfqet_ujE2iC.jpg', 'at the campus', 'lsad-123zx-ww12-brak', '123', '321', null);
INSERT INTO `beacons` VALUES ('21', 'Test beacon', 'downshift_exclude', 'cvcvcvcv', '8ujE9Q1ajYfCtPoR.jpg', 'at the campus', 'lsad-123zx-ww12-brak', '123', '321', null);
INSERT INTO `beacons` VALUES ('22', 'Lama?', 'test', 'Lama!', 'j3POfq22RCbf1b5U.jpg', '123', 'qwer-1234-asd4-vs1qe-mm3a', '146', '312', null);

-- ----------------------------
-- Table structure for `groups`
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `uuid` varchar(64) DEFAULT NULL,
  `major` int(11) DEFAULT NULL,
  `minor` int(11) DEFAULT NULL,
  `place` varchar(256) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', 'campus1', 'Campus 1', 'qwer-1234-asd4-vs1qe-mm3a', '312', '146', '123', 'This is campus 1');
INSERT INTO `groups` VALUES ('3', 'campus2', 'Campus 2', 'lsad-123zx-ww12-brak', '321', '123', 'at the campus', 'This is campus2');

-- ----------------------------
-- Table structure for `language`
-- ----------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name_ascii` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of language
-- ----------------------------
INSERT INTO `language` VALUES ('af-ZA', 'af', 'za', 'Afrikaans', 'Afrikaans', '0');
INSERT INTO `language` VALUES ('ar-AR', 'ar', 'ar', '‏العربية‏', 'Arabic', '0');
INSERT INTO `language` VALUES ('az-AZ', 'az', 'az', 'Azərbaycan dili', 'Azerbaijani', '0');
INSERT INTO `language` VALUES ('be-BY', 'be', 'by', 'Беларуская', 'Belarusian', '0');
INSERT INTO `language` VALUES ('bg-BG', 'bg', 'bg', 'Български', 'Bulgarian', '0');
INSERT INTO `language` VALUES ('bn-IN', 'bn', 'in', 'বাংলা', 'Bengali', '0');
INSERT INTO `language` VALUES ('bs-BA', 'bs', 'ba', 'Bosanski', 'Bosnian', '0');
INSERT INTO `language` VALUES ('ca-ES', 'ca', 'es', 'Català', 'Catalan', '0');
INSERT INTO `language` VALUES ('cs-CZ', 'cs', 'cz', 'Čeština', 'Czech', '0');
INSERT INTO `language` VALUES ('cy-GB', 'cy', 'gb', 'Cymraeg', 'Welsh', '0');
INSERT INTO `language` VALUES ('da-DK', 'da', 'dk', 'Dansk', 'Danish', '0');
INSERT INTO `language` VALUES ('de-DE', 'de', 'de', 'Deutsch', 'German', '1');
INSERT INTO `language` VALUES ('el-GR', 'el', 'gr', 'Ελληνικά', 'Greek', '0');
INSERT INTO `language` VALUES ('en-GB', 'en', 'gb', 'English (UK)', 'English (UK)', '0');
INSERT INTO `language` VALUES ('en-PI', 'en', 'pi', 'English (Pirate)', 'English (Pirate)', '0');
INSERT INTO `language` VALUES ('en-UD', 'en', 'ud', 'English (Upside Down)', 'English (Upside Down)', '0');
INSERT INTO `language` VALUES ('en-US', 'en', 'us', 'English (US)', 'English (US)', '1');
INSERT INTO `language` VALUES ('eo-EO', 'eo', 'eo', 'Esperanto', 'Esperanto', '0');
INSERT INTO `language` VALUES ('es-ES', 'es', 'es', 'Español (España)', 'Spanish (Spain)', '0');
INSERT INTO `language` VALUES ('es-LA', 'es', 'la', 'Español', 'Spanish', '0');
INSERT INTO `language` VALUES ('et-EE', 'et', 'ee', 'Eesti', 'Estonian', '0');
INSERT INTO `language` VALUES ('eu-ES', 'eu', 'es', 'Euskara', 'Basque', '0');
INSERT INTO `language` VALUES ('fa-IR', 'fa', 'ir', '‏فارسی‏', 'Persian', '0');
INSERT INTO `language` VALUES ('fb-LT', 'fb', 'lt', 'Leet Speak', 'Leet Speak', '0');
INSERT INTO `language` VALUES ('fi-FI', 'fi', 'fi', 'Suomi', 'Finnish', '0');
INSERT INTO `language` VALUES ('fo-FO', 'fo', 'fo', 'Føroyskt', 'Faroese', '0');
INSERT INTO `language` VALUES ('fr-CA', 'fr', 'ca', 'Français (Canada)', 'French (Canada)', '0');
INSERT INTO `language` VALUES ('fr-FR', 'fr', 'fr', 'Français (France)', 'French (France)', '1');
INSERT INTO `language` VALUES ('fy-NL', 'fy', 'nl', 'Frysk', 'Frisian', '0');
INSERT INTO `language` VALUES ('ga-IE', 'ga', 'ie', 'Gaeilge', 'Irish', '0');
INSERT INTO `language` VALUES ('gl-ES', 'gl', 'es', 'Galego', 'Galician', '0');
INSERT INTO `language` VALUES ('he-IL', 'he', 'il', '‏עברית‏', 'Hebrew', '0');
INSERT INTO `language` VALUES ('hi-IN', 'hi', 'in', 'हिन्दी', 'Hindi', '0');
INSERT INTO `language` VALUES ('hr-HR', 'hr', 'hr', 'Hrvatski', 'Croatian', '0');
INSERT INTO `language` VALUES ('hu-HU', 'hu', 'hu', 'Magyar', 'Hungarian', '0');
INSERT INTO `language` VALUES ('hy-AM', 'hy', 'am', 'Հայերեն', 'Armenian', '0');
INSERT INTO `language` VALUES ('id-ID', 'id', 'id', 'Bahasa Indonesia', 'Indonesian', '0');
INSERT INTO `language` VALUES ('is-IS', 'is', 'is', 'Íslenska', 'Icelandic', '0');
INSERT INTO `language` VALUES ('it-IT', 'it', 'it', 'Italiano', 'Italian', '0');
INSERT INTO `language` VALUES ('ja-JP', 'ja', 'jp', '日本語', 'Japanese', '0');
INSERT INTO `language` VALUES ('ka-GE', 'ka', 'ge', 'ქართული', 'Georgian', '0');
INSERT INTO `language` VALUES ('km-KH', 'km', 'kh', 'ភាសាខ្មែរ', 'Khmer', '0');
INSERT INTO `language` VALUES ('ko-KR', 'ko', 'kr', '한국어', 'Korean', '0');
INSERT INTO `language` VALUES ('ku-TR', 'ku', 'tr', 'Kurdî', 'Kurdish', '0');
INSERT INTO `language` VALUES ('la-VA', 'la', 'va', 'lingua latina', 'Latin', '0');
INSERT INTO `language` VALUES ('lt-LT', 'lt', 'lt', 'Lietuvių', 'Lithuanian', '0');
INSERT INTO `language` VALUES ('lv-LV', 'lv', 'lv', 'Latviešu', 'Latvian', '0');
INSERT INTO `language` VALUES ('mk-MK', 'mk', 'mk', 'Македонски', 'Macedonian', '0');
INSERT INTO `language` VALUES ('ml-IN', 'ml', 'in', 'മലയാളം', 'Malayalam', '0');
INSERT INTO `language` VALUES ('ms-MY', 'ms', 'my', 'Bahasa Melayu', 'Malay', '0');
INSERT INTO `language` VALUES ('nb-NO', 'nb', 'no', 'Norsk (bokmål)', 'Norwegian (bokmal)', '0');
INSERT INTO `language` VALUES ('ne-NP', 'ne', 'np', 'नेपाली', 'Nepali', '0');
INSERT INTO `language` VALUES ('nl-NL', 'nl', 'nl', 'Nederlands', 'Dutch', '0');
INSERT INTO `language` VALUES ('nn-NO', 'nn', 'no', 'Norsk (nynorsk)', 'Norwegian (nynorsk)', '0');
INSERT INTO `language` VALUES ('pa-IN', 'pa', 'in', 'ਪੰਜਾਬੀ', 'Punjabi', '0');
INSERT INTO `language` VALUES ('pl-PL', 'pl', 'pl', 'Polski', 'Polish', '0');
INSERT INTO `language` VALUES ('ps-AF', 'ps', 'af', '‏پښتو‏', 'Pashto', '0');
INSERT INTO `language` VALUES ('pt-BR', 'pt', 'br', 'Português (Brasil)', 'Portuguese (Brazil)', '0');
INSERT INTO `language` VALUES ('pt-PT', 'pt', 'pt', 'Português (Portugal)', 'Portuguese (Portugal)', '0');
INSERT INTO `language` VALUES ('ro-RO', 'ro', 'ro', 'Română', 'Romanian', '0');
INSERT INTO `language` VALUES ('ru-RU', 'ru', 'ru', 'Русский', 'Russian', '1');
INSERT INTO `language` VALUES ('sk-SK', 'sk', 'sk', 'Slovenčina', 'Slovak', '0');
INSERT INTO `language` VALUES ('sl-SI', 'sl', 'si', 'Slovenščina', 'Slovenian', '0');
INSERT INTO `language` VALUES ('sq-AL', 'sq', 'al', 'Shqip', 'Albanian', '0');
INSERT INTO `language` VALUES ('sr-RS', 'sr', 'rs', 'Српски', 'Serbian', '0');
INSERT INTO `language` VALUES ('sv-SE', 'sv', 'se', 'Svenska', 'Swedish', '0');
INSERT INTO `language` VALUES ('sw-KE', 'sw', 'ke', 'Kiswahili', 'Swahili', '0');
INSERT INTO `language` VALUES ('ta-IN', 'ta', 'in', 'தமிழ்', 'Tamil', '0');
INSERT INTO `language` VALUES ('te-IN', 'te', 'in', 'తెలుగు', 'Telugu', '0');
INSERT INTO `language` VALUES ('th-TH', 'th', 'th', 'ภาษาไทย', 'Thai', '0');
INSERT INTO `language` VALUES ('tl-PH', 'tl', 'ph', 'Filipino', 'Filipino', '0');
INSERT INTO `language` VALUES ('tr-TR', 'tr', 'tr', 'Türkçe', 'Turkish', '0');
INSERT INTO `language` VALUES ('uk-UA', 'uk', 'ua', 'Українська', 'Ukrainian', '1');
INSERT INTO `language` VALUES ('vi-VN', 'vi', 'vn', 'Tiếng Việt', 'Vietnamese', '0');
INSERT INTO `language` VALUES ('xx-XX', 'xx', 'xx', 'Fejlesztő', 'Developer', '0');
INSERT INTO `language` VALUES ('zh-CN', 'zh', 'cn', '中文(简体)', 'Simplified Chinese (China)', '0');
INSERT INTO `language` VALUES ('zh-HK', 'zh', 'hk', '中文(香港)', 'Traditional Chinese (Hong Kong)', '0');
INSERT INTO `language` VALUES ('zh-TW', 'zh', 'tw', '中文(台灣)', 'Traditional Chinese (Taiwan)', '0');

-- ----------------------------
-- Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL,
  `language` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `translation` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`,`language`),
  KEY `language` (`language`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`language`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id`) REFERENCES `source_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES ('1', 'en-US', 'Sorry, we are unable to reset password for email provided');
INSERT INTO `message` VALUES ('1', 'ru-RU', 'К сожалению мы не можем сбросить пароль для указанного почтового адреса.');
INSERT INTO `message` VALUES ('2', 'en-US', 'Check your email for further instructions');
INSERT INTO `message` VALUES ('2', 'ru-RU', 'На ваш почтоый ящик было отправлено письмо с инструкциями к дальнейшим действиям');
INSERT INTO `message` VALUES ('3', 'en-US', 'The above error occurred while the Web server was processing your request');
INSERT INTO `message` VALUES ('3', 'ru-RU', 'В процессе обработки вашего запроса возникла ошибка');
INSERT INTO `message` VALUES ('4', 'en-US', 'Please contact us if you think this is a server error. Thank you.');
INSERT INTO `message` VALUES ('4', 'ru-RU', 'Свяжитесь с нами, если вы считаете что так быть не должно.');
INSERT INTO `message` VALUES ('5', 'en-US', 'Wrong password reset token.');
INSERT INTO `message` VALUES ('5', 'ru-RU', 'Неверный секретный код');
INSERT INTO `message` VALUES ('6', 'en-US', 'There is no user with such email.');
INSERT INTO `message` VALUES ('6', 'ru-RU', 'Пользователя с таким почтовым ящиком не существует');
INSERT INTO `message` VALUES ('7', 'en-US', 'New password wasn\'t saved.');
INSERT INTO `message` VALUES ('7', 'ru-RU', 'Пароль не был сохранен');
INSERT INTO `message` VALUES ('8', 'en-US', 'New password was saved.');
INSERT INTO `message` VALUES ('8', 'ru-RU', 'Новый пароль был сохранен');
INSERT INTO `message` VALUES ('9', 'en-US', 'Upload translation file');
INSERT INTO `message` VALUES ('9', 'ru-RU', 'Загрузить файл перевода');
INSERT INTO `message` VALUES ('10', 'en-US', 'File');
INSERT INTO `message` VALUES ('10', 'ru-RU', 'Файл');
INSERT INTO `message` VALUES ('11', 'en-US', 'I want to update data');
INSERT INTO `message` VALUES ('11', 'ru-RU', 'Я хочу обновить существующие данные');
INSERT INTO `message` VALUES ('12', 'en-US', 'Users list');
INSERT INTO `message` VALUES ('12', 'ru-RU', 'Список пользователей');
INSERT INTO `message` VALUES ('13', 'en-US', 'Create user');
INSERT INTO `message` VALUES ('13', 'ru-RU', 'Создать пользователя');
INSERT INTO `message` VALUES ('14', 'en-US', 'Manage user');
INSERT INTO `message` VALUES ('14', 'ru-RU', 'Управление пользователем');
INSERT INTO `message` VALUES ('15', 'en-US', 'Update user');
INSERT INTO `message` VALUES ('15', 'ru-RU', 'Редактирование пользователя');
INSERT INTO `message` VALUES ('16', 'en-US', 'User info');
INSERT INTO `message` VALUES ('16', 'ru-RU', 'Информация о пользователе');
INSERT INTO `message` VALUES ('17', 'en-US', 'User beacons');
INSERT INTO `message` VALUES ('17', 'ru-RU', 'Маячки пользователя');
INSERT INTO `message` VALUES ('18', 'en-US', 'Update profile');
INSERT INTO `message` VALUES ('18', 'ru-RU', 'Редактирование профиля');
INSERT INTO `message` VALUES ('19', 'en-US', 'Beacons');
INSERT INTO `message` VALUES ('19', 'ru-RU', 'Маячки');
INSERT INTO `message` VALUES ('20', 'en-US', 'Groups');
INSERT INTO `message` VALUES ('20', 'ru-RU', 'Группы');
INSERT INTO `message` VALUES ('21', 'en-US', 'Login');
INSERT INTO `message` VALUES ('21', 'ru-RU', 'Логин');
INSERT INTO `message` VALUES ('22', 'en-US', 'Register');
INSERT INTO `message` VALUES ('22', 'ru-RU', 'Регистрация');
INSERT INTO `message` VALUES ('23', 'en-US', 'My beacons');
INSERT INTO `message` VALUES ('23', 'ru-RU', 'Мои маячки');
INSERT INTO `message` VALUES ('24', 'en-US', 'My profile');
INSERT INTO `message` VALUES ('24', 'ru-RU', 'Мой профиль');
INSERT INTO `message` VALUES ('25', 'en-US', 'Hello,');
INSERT INTO `message` VALUES ('25', 'ru-RU', 'Привет, ');
INSERT INTO `message` VALUES ('26', 'en-US', 'Logout');
INSERT INTO `message` VALUES ('26', 'ru-RU', 'Выйти');
INSERT INTO `message` VALUES ('27', 'en-US', 'Translations');
INSERT INTO `message` VALUES ('27', 'ru-RU', 'Перевод');
INSERT INTO `message` VALUES ('28', 'en-US', 'Users');
INSERT INTO `message` VALUES ('28', 'ru-RU', 'Пользователи');
INSERT INTO `message` VALUES ('29', 'en-US', 'Translations list');
INSERT INTO `message` VALUES ('29', 'ru-RU', 'Предложения для перевода');
INSERT INTO `message` VALUES ('30', 'en-US', 'Translations import');
INSERT INTO `message` VALUES ('30', 'ru-RU', 'Загрузка перевода');
INSERT INTO `message` VALUES ('31', 'en-US', 'Name');
INSERT INTO `message` VALUES ('31', 'ru-RU', 'Имя');
INSERT INTO `message` VALUES ('32', 'en-US', 'Email');
INSERT INTO `message` VALUES ('32', 'ru-RU', 'Почта');
INSERT INTO `message` VALUES ('33', 'en-US', 'Groups');
INSERT INTO `message` VALUES ('33', 'ru-RU', 'Группы');
INSERT INTO `message` VALUES ('34', 'en-US', 'Role');
INSERT INTO `message` VALUES ('34', 'ru-RU', 'Роль');
INSERT INTO `message` VALUES ('35', 'en-US', 'Language');
INSERT INTO `message` VALUES ('35', 'ru-RU', 'Язык');
INSERT INTO `message` VALUES ('36', 'en-US', 'Update');
INSERT INTO `message` VALUES ('36', 'ru-RU', 'Редактировать');
INSERT INTO `message` VALUES ('37', 'en-US', 'Beacons list');
INSERT INTO `message` VALUES ('37', 'ru-RU', 'Список маячков');
INSERT INTO `message` VALUES ('38', 'en-US', 'Create beacon');
INSERT INTO `message` VALUES ('38', 'ru-RU', 'Создать маячок');
INSERT INTO `message` VALUES ('39', 'en-US', 'Beacon info');
INSERT INTO `message` VALUES ('39', 'ru-RU', 'Информация о маячке');
INSERT INTO `message` VALUES ('40', 'en-US', 'Update beacon');
INSERT INTO `message` VALUES ('40', 'ru-RU', 'Редактировать маячок');
INSERT INTO `message` VALUES ('41', 'en-US', 'Name');
INSERT INTO `message` VALUES ('41', 'ru-RU', 'Имя');
INSERT INTO `message` VALUES ('42', 'en-US', 'Group');
INSERT INTO `message` VALUES ('42', 'ru-RU', 'Группа');
INSERT INTO `message` VALUES ('43', 'en-US', 'Uuid');
INSERT INTO `message` VALUES ('43', 'ru-RU', 'Uuid');
INSERT INTO `message` VALUES ('44', 'en-US', 'Major');
INSERT INTO `message` VALUES ('44', 'ru-RU', 'Major');
INSERT INTO `message` VALUES ('45', 'en-US', 'Minor');
INSERT INTO `message` VALUES ('45', 'ru-RU', 'Minor');
INSERT INTO `message` VALUES ('46', 'en-US', 'Place');
INSERT INTO `message` VALUES ('46', 'ru-RU', 'Место расположения');
INSERT INTO `message` VALUES ('47', 'en-US', 'Title');
INSERT INTO `message` VALUES ('47', 'ru-RU', 'Заголовок');
INSERT INTO `message` VALUES ('48', 'en-US', 'Description');
INSERT INTO `message` VALUES ('48', 'ru-RU', 'Описание');
INSERT INTO `message` VALUES ('49', 'en-US', 'Picture');
INSERT INTO `message` VALUES ('49', 'ru-RU', 'Картинка');
INSERT INTO `message` VALUES ('50', 'en-US', 'System info');
INSERT INTO `message` VALUES ('50', 'ru-RU', 'Системная информация');
INSERT INTO `message` VALUES ('51', 'en-US', 'Content');
INSERT INTO `message` VALUES ('51', 'ru-RU', 'Контент');
INSERT INTO `message` VALUES ('52', 'en-US', 'Groups list');
INSERT INTO `message` VALUES ('52', 'ru-RU', 'Список групп');
INSERT INTO `message` VALUES ('53', 'en-US', 'Create group');
INSERT INTO `message` VALUES ('53', 'ru-RU', 'Создать группу');
INSERT INTO `message` VALUES ('54', 'en-US', 'Update group');
INSERT INTO `message` VALUES ('54', 'ru-RU', 'Редактировать группу');
INSERT INTO `message` VALUES ('55', 'en-US', 'Group info');
INSERT INTO `message` VALUES ('55', 'ru-RU', 'Информация о группе');
INSERT INTO `message` VALUES ('56', 'en-US', 'Manage group');
INSERT INTO `message` VALUES ('56', 'ru-RU', 'Управление группой');
INSERT INTO `message` VALUES ('57', 'en-US', 'Group beacons');
INSERT INTO `message` VALUES ('57', 'ru-RU', 'Маячки этой группы');
INSERT INTO `message` VALUES ('58', 'en-US', 'Alias');
INSERT INTO `message` VALUES ('58', 'ru-RU', 'Уникальное имя');
INSERT INTO `message` VALUES ('59', 'en-US', 'Name');
INSERT INTO `message` VALUES ('59', 'ru-RU', 'Название');
INSERT INTO `message` VALUES ('60', 'en-US', 'Description');
INSERT INTO `message` VALUES ('60', 'ru-RU', 'Описание');
INSERT INTO `message` VALUES ('61', 'en-US', 'Uuid');
INSERT INTO `message` VALUES ('61', 'ru-RU', 'Uuid');
INSERT INTO `message` VALUES ('62', 'en-US', 'Major');
INSERT INTO `message` VALUES ('62', 'ru-RU', 'Major');
INSERT INTO `message` VALUES ('63', 'en-US', 'Minor');
INSERT INTO `message` VALUES ('63', 'ru-RU', 'Minor');
INSERT INTO `message` VALUES ('64', 'en-US', 'Place');
INSERT INTO `message` VALUES ('64', 'ru-RU', 'Место расположения');
INSERT INTO `message` VALUES ('65', 'en-US', 'Update');
INSERT INTO `message` VALUES ('65', 'ru-RU', 'Редактировать');
INSERT INTO `message` VALUES ('66', 'en-US', 'Group settings');
INSERT INTO `message` VALUES ('66', 'ru-RU', 'Настройки группы');
INSERT INTO `message` VALUES ('67', 'en-US', 'Beacons default content');
INSERT INTO `message` VALUES ('67', 'ru-RU', 'Контент по умолчанию для мачков');
INSERT INTO `message` VALUES ('68', 'en-US', 'Status');
INSERT INTO `message` VALUES ('68', 'ru-RU', 'Статус');
INSERT INTO `message` VALUES ('69', 'en-US', 'Password');
INSERT INTO `message` VALUES ('69', 'ru-RU', 'Пароль');
INSERT INTO `message` VALUES ('70', 'en-US', 'Password Confirm');
INSERT INTO `message` VALUES ('70', 'ru-RU', 'Подтверждение пароля');
INSERT INTO `message` VALUES ('71', 'en-US', 'Create');
INSERT INTO `message` VALUES ('71', 'ru-RU', 'Создать');
INSERT INTO `message` VALUES ('72', 'en-US', 'Update');
INSERT INTO `message` VALUES ('72', 'ru-RU', 'Редактировать');
INSERT INTO `message` VALUES ('73', 'en-US', 'Category');
INSERT INTO `message` VALUES ('73', 'ru-RU', 'Категория');
INSERT INTO `message` VALUES ('74', 'en-US', 'Message');
INSERT INTO `message` VALUES ('74', 'ru-RU', 'Текст');
INSERT INTO `message` VALUES ('75', 'en-US', 'Translation');
INSERT INTO `message` VALUES ('75', 'ru-RU', 'Перевод');
INSERT INTO `message` VALUES ('76', 'en-US', 'Email');
INSERT INTO `message` VALUES ('76', 'ru-RU', 'Почта');
INSERT INTO `message` VALUES ('77', 'en-US', 'Password');
INSERT INTO `message` VALUES ('77', 'ru-RU', 'Пароль');
INSERT INTO `message` VALUES ('78', 'en-US', 'Forgot password ?');
INSERT INTO `message` VALUES ('78', 'ru-RU', 'Забыли пароль?');
INSERT INTO `message` VALUES ('79', 'en-US', 'Login');
INSERT INTO `message` VALUES ('79', 'ru-RU', 'Login');
INSERT INTO `message` VALUES ('80', 'en-US', 'Password Reset');
INSERT INTO `message` VALUES ('80', 'ru-RU', 'Сбросить пароль');
INSERT INTO `message` VALUES ('81', 'en-US', 'Save');
INSERT INTO `message` VALUES ('81', 'ru-RU', 'Сохранить');
INSERT INTO `message` VALUES ('82', 'en-US', 'Please enter the email address that you used to register and press submit');
INSERT INTO `message` VALUES ('82', 'ru-RU', 'Пожалуйста, введите свой почтовый адрес');
INSERT INTO `message` VALUES ('83', 'en-US', 'Beacon Map');
INSERT INTO `message` VALUES ('83', 'ru-RU', 'Карта маячков');
INSERT INTO `message` VALUES ('84', 'en-US', 'Add pin');
INSERT INTO `message` VALUES ('84', 'ru-RU', 'Добавить метку');
INSERT INTO `message` VALUES ('85', 'en-US', 'Manage beacon pin');
INSERT INTO `message` VALUES ('85', 'ru-RU', 'Управление меткой');
INSERT INTO `message` VALUES ('86', 'en-US', 'Remove pin');
INSERT INTO `message` VALUES ('86', 'ru-RU', 'Удалить метку');
INSERT INTO `message` VALUES ('87', 'ru-RU', 'Запомнить меня');

-- ----------------------------
-- Table structure for `migration`
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', '1425378410');
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', '1425379376');

-- ----------------------------
-- Table structure for `session`
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(64) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of session
-- ----------------------------
INSERT INTO `session` VALUES ('07u4mm01t3kt4ngmucci8bcgb5', '1430772509', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('1748oevpgov4t8nvqt01u739r0', '1430990914', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('2r90q3mb3jg3n7j61ef62j4p35', '1430404908', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('3sqeg17d6qvb17234terclihs7', '1430990776', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('5dghu8m0kot4kacerid089hv10', '1430426325', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('5r7o7k359u3bgol1a9ht9r2rc0', '1430411960', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('6eng7d571edlhub8cvdrel6b83', '1430815628', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('7fi5h250ndkkf1nf2ui114cf66', '1430587008', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('7jgkf3s0v4ke6kdva9l5406ne0', '1430765636', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('7jnn9bb7964gc8770qgvqph992', '1430586532', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('akpt4nmbosfusorb7e27blp8n0', '1430818735', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('chr2h6l85c6ime6md7b1mdel63', '1430852871', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('fivimcrqg2cc9k7s1lnl2ri9f4', '1430676935', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('hbv672gpr2dku8d887tk33lno3', '1430939085', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('hspkcmmej01sq9b2ojmihvggv2', '1430852849', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('j1856amd5bgujbdst2q1sdgo17', '1430765130', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('j57vggem1f2ims96mdi6o9i9h2', '1430412030', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('kkusjknk28eeuu6npvsjtjfo35', '1430853052', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('kqq4gh7v1rek9bftevk6upir04', '1430772187', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('ma1pd4s7444b9pc9k4gl96ir73', '1430853051', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('mjm6vs1rpntf6pr2p4bnbuq0c4', '1430818443', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('plk2t4feeecos6fqbglt6cf5t4', '1430853350', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('r71oqadhirou695mc5jlt8vmu3', '1430990275', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('rdbagc9r1sjesjrq9su4dteba3', '1430409353', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A31353A222F757365722F7570646174652F3139223B5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('t6p15a13ke07ms2mbnvtik1585', '1430772048', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('ucaliv341df6s2sssqm1q9hcr6', '1430765186', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('unf5c51r3v98ullaarmjgstte3', '1430772624', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('vkn2jash5usuq46ibbttn69a84', '1430412258', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);
INSERT INTO `session` VALUES ('vrjq8r3oolp00eea8tnmf4u4t1', '1430814059', 0x5F5F666C6173687C613A303A7B7D5F5F69647C693A31393B);

-- ----------------------------
-- Table structure for `source_message`
-- ----------------------------
DROP TABLE IF EXISTS `source_message`;
CREATE TABLE `source_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of source_message
-- ----------------------------
INSERT INTO `source_message` VALUES ('1', 'messages', ':unable_to_reset_password');
INSERT INTO `source_message` VALUES ('2', 'messages', ':check_your_email');
INSERT INTO `source_message` VALUES ('3', 'messages', ':error_occured');
INSERT INTO `source_message` VALUES ('4', 'messages', ':server_error');
INSERT INTO `source_message` VALUES ('5', 'messages', ':wrong_password_token');
INSERT INTO `source_message` VALUES ('6', 'messages', ':no_user_with_email');
INSERT INTO `source_message` VALUES ('7', 'messages', ':password_not_saved');
INSERT INTO `source_message` VALUES ('8', 'messages', ':password_saved');
INSERT INTO `source_message` VALUES ('9', 'translation_load', ':upload_translation_file');
INSERT INTO `source_message` VALUES ('10', 'translation_load', ':file');
INSERT INTO `source_message` VALUES ('11', 'translation_load', ':is_update');
INSERT INTO `source_message` VALUES ('12', 'user_layout', ':list_users');
INSERT INTO `source_message` VALUES ('13', 'user_layout', ':create_user');
INSERT INTO `source_message` VALUES ('14', 'user_layout', ':manage_user');
INSERT INTO `source_message` VALUES ('15', 'user_layout', ':update_user');
INSERT INTO `source_message` VALUES ('16', 'user_layout', ':view_user');
INSERT INTO `source_message` VALUES ('17', 'user_layout', ':user_beacons');
INSERT INTO `source_message` VALUES ('18', 'user_layout', ':update_profile');
INSERT INTO `source_message` VALUES ('19', 'site_layout', ':beacons');
INSERT INTO `source_message` VALUES ('20', 'site_layout', ':groups');
INSERT INTO `source_message` VALUES ('21', 'site_layout', ':login');
INSERT INTO `source_message` VALUES ('22', 'site_layout', ':register');
INSERT INTO `source_message` VALUES ('23', 'site_layout', ':my_becons');
INSERT INTO `source_message` VALUES ('24', 'site_layout', ':my_profile');
INSERT INTO `source_message` VALUES ('25', 'site_layout', ':hello');
INSERT INTO `source_message` VALUES ('26', 'site_layout', ':logout');
INSERT INTO `source_message` VALUES ('27', 'site_layout', ':translations');
INSERT INTO `source_message` VALUES ('28', 'site_layout', ':users');
INSERT INTO `source_message` VALUES ('29', 'translation_layout', ':translations_list');
INSERT INTO `source_message` VALUES ('30', 'translation_layout', ':translations_import');
INSERT INTO `source_message` VALUES ('31', 'user', ':name');
INSERT INTO `source_message` VALUES ('32', 'user', ':email');
INSERT INTO `source_message` VALUES ('33', 'user', ':groups');
INSERT INTO `source_message` VALUES ('34', 'user', ':role');
INSERT INTO `source_message` VALUES ('35', 'user', ':language');
INSERT INTO `source_message` VALUES ('36', 'user', ':update');
INSERT INTO `source_message` VALUES ('37', 'beacon_layout', ':beacons_list');
INSERT INTO `source_message` VALUES ('38', 'beacon_layout', ':beacon_create');
INSERT INTO `source_message` VALUES ('39', 'beacon_layout', ':beacon_view');
INSERT INTO `source_message` VALUES ('40', 'beacon_layout', ':beacon_update');
INSERT INTO `source_message` VALUES ('41', 'beacon', ':name');
INSERT INTO `source_message` VALUES ('42', 'beacon', ':group');
INSERT INTO `source_message` VALUES ('43', 'beacon', ':uuid');
INSERT INTO `source_message` VALUES ('44', 'beacon', ':major');
INSERT INTO `source_message` VALUES ('45', 'beacon', ':minor');
INSERT INTO `source_message` VALUES ('46', 'beacon', ':place');
INSERT INTO `source_message` VALUES ('47', 'beacon', ':title');
INSERT INTO `source_message` VALUES ('48', 'beacon', ':description');
INSERT INTO `source_message` VALUES ('49', 'beacon', ':picture');
INSERT INTO `source_message` VALUES ('50', 'beacon', ':system');
INSERT INTO `source_message` VALUES ('51', 'beacon', ':content');
INSERT INTO `source_message` VALUES ('52', 'group_layout', ':groups_list');
INSERT INTO `source_message` VALUES ('53', 'group_layout', ':group_create');
INSERT INTO `source_message` VALUES ('54', 'group_layout', ':group_update');
INSERT INTO `source_message` VALUES ('55', 'group_layout', ':group_view');
INSERT INTO `source_message` VALUES ('56', 'group_layout', ':group_manage');
INSERT INTO `source_message` VALUES ('57', 'group_layout', ':group_beacons');
INSERT INTO `source_message` VALUES ('58', 'group', ':alias');
INSERT INTO `source_message` VALUES ('59', 'group', ':name');
INSERT INTO `source_message` VALUES ('60', 'group', ':description');
INSERT INTO `source_message` VALUES ('61', 'group', ':uuid');
INSERT INTO `source_message` VALUES ('62', 'group', ':major');
INSERT INTO `source_message` VALUES ('63', 'group', ':minor');
INSERT INTO `source_message` VALUES ('64', 'group', ':place');
INSERT INTO `source_message` VALUES ('65', 'group', ':update');
INSERT INTO `source_message` VALUES ('66', 'group', ':group_settings');
INSERT INTO `source_message` VALUES ('67', 'group', ':beacon_default_content');
INSERT INTO `source_message` VALUES ('68', 'user', ':status');
INSERT INTO `source_message` VALUES ('69', 'user', ':password');
INSERT INTO `source_message` VALUES ('70', 'user', ':password_confirm');
INSERT INTO `source_message` VALUES ('71', 'general', ':create');
INSERT INTO `source_message` VALUES ('72', 'general', ':update');
INSERT INTO `source_message` VALUES ('73', 'translation', ':category');
INSERT INTO `source_message` VALUES ('74', 'translation', ':message');
INSERT INTO `source_message` VALUES ('75', 'translation', ':translation');
INSERT INTO `source_message` VALUES ('76', 'general', ':email');
INSERT INTO `source_message` VALUES ('77', 'general', ':password');
INSERT INTO `source_message` VALUES ('78', 'general', ':password_forgot');
INSERT INTO `source_message` VALUES ('79', 'general', ':login');
INSERT INTO `source_message` VALUES ('80', 'password', ':password_reset');
INSERT INTO `source_message` VALUES ('81', 'general', ':save');
INSERT INTO `source_message` VALUES ('82', 'password', ':enter_email_address');
INSERT INTO `source_message` VALUES ('83', 'beacon_layout', ':beacon_map');
INSERT INTO `source_message` VALUES ('84', 'beacon', ':add_pin');
INSERT INTO `source_message` VALUES ('85', 'beacon', ':manage_beacon_pin');
INSERT INTO `source_message` VALUES ('86', 'beacon', ':remove_pin');
INSERT INTO `source_message` VALUES ('87', 'login', ':remember');

-- ----------------------------
-- Table structure for `user_bindings`
-- ----------------------------
DROP TABLE IF EXISTS `user_bindings`;
CREATE TABLE `user_bindings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_bindings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_bindings_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_bindings
-- ----------------------------
INSERT INTO `user_bindings` VALUES ('3', '22', '1');
INSERT INTO `user_bindings` VALUES ('7', '25', '1');
INSERT INTO `user_bindings` VALUES ('8', '20', '3');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `auth_key` varchar(256) DEFAULT NULL,
  `access_token` varchar(256) DEFAULT NULL,
  `role` varchar(64) NOT NULL,
  `logged` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `language` varchar(5) NOT NULL DEFAULT 'en-US',
  `password_reset_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('19', 'Администратор', 'admin@mail.home', '$2y$13$7EHPkW04YrF5hweqArlKfud4z9HlZmTY9BVFlqeFzzFXgdb1cqd7.', 'ICm0adGxj45EM4KlCa6QskK9NMNIk_UK', null, 'super_admin', '2015-03-30 06:49:35', '1', 'en-US', null);
INSERT INTO `users` VALUES ('20', 'Galina', 'galina.tabunshchik@gmail.com', '$2y$13$TisIthXbPzeu2SRjWqIziug7pysKs.ZYdj4j/autxMaPAhT9MjpNS', '6NlgJdTZOmZaakaxvTD1IyG0ZWvAqLzd', null, 'user', '2015-03-30 06:38:38', '1', 'en-US', 'PveLojaNaB3UnF2lKHVsoVvoZwpPRInm_1430824708');
INSERT INTO `users` VALUES ('22', null, 'zesar1991@gmail.com', '$2y$13$SRizGIXphMtOr6XSK/lFUuaJAQe7FJ8K98swzaG1XSZDRlPXSuz3S', 'PhGKRx_jSJgzXHEVibVeNBCEsy72Y78T', null, 'user', '2015-03-29 19:13:42', '1', 'en-US', null);
INSERT INTO `users` VALUES ('24', null, 'yuri_goncharov@mail.ru', '$2y$13$4iUhp4.TOH8yfRa8FgCu0O4ePm7p43z/o3AM7aoDGfH4YM91DNRgC', 'ziQLWnUdJmGevOGi1Xr0S1pqLcCuk_j4', null, 'user', null, '1', 'en-US', null);
INSERT INTO `users` VALUES ('25', 'Stakeholder', 'sth@mail.mail', '$2y$13$.fvD8uPurrpy1s.AoW2w6ONLLx374a2DaZe0sTM4tvdd.6ep2vF6y', 'D8Zi7eeH_F2U4QJmk1aRkljUy6yvHc7G', null, 'user', null, '1', 'en-US', null);

-- ----------------------------
-- View structure for `not_pinned_beacons`
-- ----------------------------
DROP VIEW IF EXISTS `not_pinned_beacons`;
CREATE ALGORITHM=UNDEFINED DEFINER=`galina`@`%` SQL SECURITY DEFINER VIEW `not_pinned_beacons` AS select `b`.`id` AS `id`,`b`.`name` AS `name`,`b`.`title` AS `title`,`b`.`description` AS `description`,`b`.`picture` AS `picture`,`b`.`place` AS `place`,`b`.`uuid` AS `uuid`,`b`.`minor` AS `minor`,`b`.`major` AS `major`,`b`.`alias` AS `alias` from `beacons` `b` where (not(exists(select NULL from `beacon_pins` `bp` where (`b`.`id` = `bp`.`id`)))) ;
