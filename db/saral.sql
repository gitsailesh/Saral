CREATE DATABASE  IF NOT EXISTS `saral` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `saral`;

--
-- Table structure for table `audit_fld`
--

DROP TABLE IF EXISTS `audit_fld`;
CREATE TABLE `audit_fld` (
  `FieldID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TableSeqNumber` bigint(20) unsigned NOT NULL,
  `TblColumnName` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `OldValue` text COLLATE utf8_unicode_ci,
  `NewValue` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`FieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='audit fld';

--
-- Table structure for table `audit_ssn`
--

DROP TABLE IF EXISTS `audit_ssn`;
CREATE TABLE `audit_ssn` (
  `SessionID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UNKNOWN',
  `CreatedOn` datetime NOT NULL,
  PRIMARY KEY (`SessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores user session';

--
-- Table structure for table `audit_tbl`
--

DROP TABLE IF EXISTS `audit_tbl`;
CREATE TABLE `audit_tbl` (
  `TableSeqNumber` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TranSeqNumber` bigint(20) unsigned NOT NULL,
  `TableName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `PKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `PValue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`TableSeqNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='audit table';

--
-- Table structure for table `audit_trn`
--

DROP TABLE IF EXISTS `audit_trn`;
CREATE TABLE `audit_trn` (
  `TranSeqNumber` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `SessionID` bigint(20) unsigned NOT NULL,
  `UpdateComment` text COLLATE utf8_unicode_ci NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `TaskID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`TranSeqNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='audit transaction';

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `UserID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `EmailID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `CreatedOn` datetime NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

