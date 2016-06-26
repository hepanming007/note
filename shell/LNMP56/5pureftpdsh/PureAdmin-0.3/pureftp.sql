-- This mysql script is made by M.Mastenbroek 2002 - 2005
-- For more info look at http://machiel.generaal.net
-- Version 2.0
--
-- You don't need this script when you already have the database
-- or when you use the install.php configuration script successfully.
--
-- Example how to execute this script from command line:
--
-- mysql -u root -ppassword -h 127.0.0.1 < script.mysql
--
--
--
-- Host: localhost    Database: ftpusers
---------------------------------------------------------
-- Server version	3.23 or 4.x
-- Script version	1.4.0
--
--
--
-- Create MySQL user called 'ftp'
-- Login = ftp
-- Password = tmppasswd
-- Host = 127.0.0.1
--

INSERT INTO mysql.user (Host, User, Password, Select_priv, Insert_priv, Update_priv, Delete_priv, Create_priv, Drop_priv, Reload_priv, Shutdown_priv, Process_priv, File_priv, Grant_priv, References_priv, Index_priv, Alter_priv) VALUES('localhost','ftp',PASSWORD('passwd'),'Y','Y','Y','Y','N','N','N','N','N','N','N','N','N','N');

FLUSH PRIVILEGES;

CREATE DATABASE ftpusers;

USE ftpusers;

--
-- Table structure for table 'admin'
--

CREATE TABLE admin (
  Username varchar(35) NOT NULL default '',
  Password char(32) binary NOT NULL default '',
  PRIMARY KEY  (Username)
) ENGINE=MyISAM;

--
-- Data for table 'admin'
--


INSERT INTO admin VALUES ('admin',MD5('passwd'));

--
-- Table structure for table 'users'
--

CREATE TABLE `users` (
  `User` varchar(16) NOT NULL default '',
  `Password` varchar(32) binary NOT NULL default '',
  `Uid` int(11) NOT NULL default '14',
  `Gid` int(11) NOT NULL default '5',
  `Dir` varchar(128) NOT NULL default '',
  `QuotaFiles` int(10) NOT NULL default '500',
  `QuotaSize` int(10) NOT NULL default '30',
  `ULBandwidth` int(10) NOT NULL default '80',
  `DLBandwidth` int(10) NOT NULL default '80',
  `Ipaddress` varchar(15) NOT NULL default '*',
  `Comment` tinytext,
  `Status` enum('0','1') NOT NULL default '1',
  `ULRatio` smallint(5) NOT NULL default '1',
  `DLRatio` smallint(5) NOT NULL default '1',
  PRIMARY KEY  (`User`),
  UNIQUE KEY `User` (`User`)
) ENGINE=MyISAM;

--
-- Data for table 'users'
--
