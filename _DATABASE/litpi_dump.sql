-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2012 at 08:35 PM
-- Server version: 5.5.23
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `litpifw`
--

-- --------------------------------------------------------

--
-- Table structure for table `lit_ac_user`
--

CREATE TABLE IF NOT EXISTS `lit_ac_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_fullname` varchar(50) NOT NULL,
  `u_groupid` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`u_id`),
  KEY `u_fullname` (`u_fullname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lit_ac_user_profile`
--

CREATE TABLE IF NOT EXISTS `lit_ac_user_profile` (
  `u_id` int(11) NOT NULL,
  `up_email` varchar(50) NOT NULL,
  `up_password` text NOT NULL,
  `up_datecreated` int(10) NOT NULL,
  `up_datemodified` int(10) NOT NULL,
  PRIMARY KEY (`u_id`),
  KEY `up_email` (`up_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lit_moderator_log`
--

CREATE TABLE IF NOT EXISTS `lit_moderator_log` (
  `u_id` int(11) NOT NULL,
  `u_email` varchar(50) NOT NULL,
  `ml_id` int(11) NOT NULL AUTO_INCREMENT,
  `ml_ipaddress` int(11) NOT NULL,
  `ml_type` varchar(30) NOT NULL,
  `ml_mainid` bigint(20) NOT NULL,
  `ml_serialized_data` text NOT NULL,
  `ml_datecreated` int(10) NOT NULL,
  PRIMARY KEY (`ml_id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lit_sess`
--

CREATE TABLE IF NOT EXISTS `lit_sess` (
  `s_id` varchar(32) NOT NULL,
  `s_data` text NOT NULL,
  `s_agent` varchar(200) NOT NULL,
  `s_ipaddress` int(10) NOT NULL,
  `s_hash` varchar(32) NOT NULL,
  `s_userid` int(11) NOT NULL,
  `s_controller` varchar(30) NOT NULL,
  `s_action` varchar(15) NOT NULL,
  `s_datecreated` int(10) NOT NULL,
  `s_dateexpired` int(10) NOT NULL,
  PRIMARY KEY (`s_id`),
  KEY `s_userid` (`s_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
