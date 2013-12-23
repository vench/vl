SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

 

-- --------------------------------------------------------

--
-- Table structure for table `vl_Currency`
--

CREATE TABLE IF NOT EXISTS `vl_Currency` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NumCode` varchar(5) NOT NULL,
  `CharCode` varchar(5) NOT NULL,
  `Nominal` int(11) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Value` float(12,4) NOT NULL,
  `DateTimeUpdate` int(11) NOT NULL,
  `Enable` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `NumCode` (`NumCode`),
  KEY `CharCode` (`CharCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;