# ************************************************************
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

# eventi
# ------------------------------------------------------------
DROP TABLE IF EXISTS `eventi`;
CREATE TABLE `eventi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `raz_id` int(11) unsigned NOT NULL,
  `uid` varchar(255) NOT NULL,
  `txt` varchar(255) DEFAULT NULL,
  `dsc` varchar(255) DEFAULT NULL,
  `dan` date DEFAULT NULL,
  `predmet` varchar(10) DEFAULT NULL,
  `tip` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=169 DEFAULT CHARSET=latin1;

# rasporedi
# ------------------------------------------------------------
DROP TABLE IF EXISTS `rasporedi`;
CREATE TABLE `rasporedi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `raz_id` int(11) unsigned NOT NULL,
  `sat` tinyint(3) unsigned DEFAULT NULL,
  `pon` varchar(10) DEFAULT NULL,
  `uto` varchar(10) DEFAULT NULL,
  `sri` varchar(10) DEFAULT NULL,
  `cet` varchar(10) DEFAULT NULL,
  `pet` varchar(10) DEFAULT NULL,
  `sub` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

# razredi
# ------------------------------------------------------------
DROP TABLE IF EXISTS `razredi`;
CREATE TABLE `razredi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `skola_id` int(11) unsigned DEFAULT NULL,
  `raz` varchar(4) NOT NULL,
  `gen` varchar(10) DEFAULT NULL,
  `calurl` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

# skole
# ------------------------------------------------------------
DROP TABLE IF EXISTS `skole`;
CREATE TABLE `skole` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(64) DEFAULT NULL,
  `caladdr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# users
# ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` int(11) DEFAULT NULL,
  `nick` int(11) DEFAULT NULL,
  `passw` varchar(64) DEFAULT NULL,
  `skola_id` int(11) unsigned DEFAULT NULL,
  `admin` tinyint(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
