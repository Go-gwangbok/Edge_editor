-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 13-12-12 18:46 
-- 서버 버전: 5.5.9
-- PHP 버전: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `edge_editor`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `cou`
--

CREATE TABLE `cou` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `pj_id` int(11) NOT NULL,
  `update_count` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 테이블의 덤프 데이터 `cou`
--


-- --------------------------------------------------------

--
-- 테이블 구조 `essay`
--

CREATE TABLE `essay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pj_id` int(11) NOT NULL,
  `prompt` text COLLATE utf8_unicode_ci NOT NULL,
  `essay` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('Writing','Tagging') COLLATE utf8_unicode_ci NOT NULL,
  `kind` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `chk` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 테이블의 덤프 데이터 `essay`
--

INSERT INTO `essay` VALUES(0, 0, '', '', '2013-12-12 18:44:19', '', '', 'Y');

-- --------------------------------------------------------

--
-- 테이블 구조 `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 테이블의 덤프 데이터 `files`
--


-- --------------------------------------------------------

--
-- 테이블 구조 `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `contents` text NOT NULL,
  `active` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 테이블의 덤프 데이터 `notice`
--

INSERT INTO `notice` VALUES(1, 'Greetings', 'Dear Editors,\n \nMy name is Dylan and I am the new managing editor with AKAon. I may have met a couple of you already. With Robin departing to Switzerland and Chris joining the army, I will be distributing the essays from now. \n \nCurrently, our engineers are backed up due to errors in the code. As such, part of my responsibilities has been to proofread the coding. For the most part, all of you have been doing a good job with it. Be sure, however, to proofread your essay coding, however. I know this is tedious and you already have a lot on your plate, but is essential that the code is entered correctly. For the most part the errors have been forgetting to close the code.\n \nAlso, I have noticed some spacing irregularities. When you close an old code and open a new code in between sentences, be sure to leave a space between the closed code and the opened code. For example:\n \n<BO1><MI1><TR>Firstly</TR>, flowers make people happy.</MI1> <SI1>Their bright colors make it impossible to feel sad.\n \nNotice also that the </TR> is placed before the comma. Additionally, when closing code, there needs to be no space between the period(.) and the closed code.\n \nI know this seems anal and it is, but the engineers need the code inserted specifically in order to properly format the software. \n \nAnyway, I''m glad to get in touch with all of you and if you ever have any questions, feel free to shoot me an email.\n \nThanks,\nDylan Irons\nEditing Manager\nAKAon', 0, '2013-12-05 13:24:00');
INSERT INTO `notice` VALUES(2, 'Proofreading', '<div><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; line-height: normal;">Hey Guys,</span><br></div><div><div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; line-height: normal;"><br></div><div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; line-height: normal;">Please proofread your codes and make any changes necessary. I did the first 100 in E1 so feel free to use those as a reference. Some of you have already started and I appreciate that. Send me a quick email when you finish a document so I can double check. Let me know if you can''t finish this by&nbsp;<span class="aBn" data-term="goog_1657063686" tabindex="0" style="border-bottom-width: 1px; border-bottom-style: dashed; border-bottom-color: rgb(204, 204, 204); position: relative; top: -2px; z-index: 0;"><span class="aQJ" style="position: relative; top: 2px; z-index: -1;">Friday</span></span>&nbsp;afternoon.&nbsp;</div><div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; line-height: normal;"><br></div><div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; line-height: normal;">Thanks,<br clear="all"><div><div dir="ltr">Dylan Irons<div>Managing Editor<br>AKAon</div></div></div></div></div>', 0, '2013-12-05 07:49:42');

-- --------------------------------------------------------

--
-- 테이블 구조 `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `disc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 테이블의 덤프 데이터 `project`
--


-- --------------------------------------------------------

--
-- 테이블 구조 `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET latin1 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 테이블의 덤프 데이터 `sessions`
--


-- --------------------------------------------------------

--
-- 테이블 구조 `tag_essay`
--

CREATE TABLE `tag_essay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(10) NOT NULL,
  `essay_id` int(11) NOT NULL,
  `pj_id` int(25) NOT NULL,
  `prompt` text COLLATE utf8_unicode_ci NOT NULL,
  `raw_txt` text COLLATE utf8_unicode_ci NOT NULL,
  `editing` text COLLATE utf8_unicode_ci NOT NULL,
  `tagging` text COLLATE utf8_unicode_ci NOT NULL,
  `critique` text COLLATE utf8_unicode_ci NOT NULL,
  `scoring` text COLLATE utf8_unicode_ci NOT NULL,
  `draft` tinyint(4) NOT NULL,
  `submit` tinyint(4) NOT NULL,
  `kind` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('Writing','Tagging','join','project') COLLATE utf8_unicode_ci NOT NULL,
  `pj_active` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `start_date` datetime NOT NULL,
  `sub_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 테이블의 덤프 데이터 `tag_essay`
--


-- --------------------------------------------------------

--
-- 테이블 구조 `usr`
--

CREATE TABLE `usr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `classify` tinyint(4) NOT NULL,
  `conf` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- 테이블의 덤프 데이터 `usr`
--

INSERT INTO `usr` VALUES(0, '', '', '', 0, 0, 0, '0000-00-00 00:00:00');
INSERT INTO `usr` VALUES(1, 'admin', 'admin@akaon.com', '1111', 0, 0, 0, '2013-12-10 18:14:59');
