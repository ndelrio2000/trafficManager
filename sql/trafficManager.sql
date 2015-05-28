SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `trafficManager`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hosts`
--

DROP TABLE IF EXISTS `hosts`;
CREATE TABLE IF NOT EXISTS `hosts` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `domainame` varchar(200) NOT NULL,
  `ipaddress` varchar(20) NOT NULL,
  `ipmonitaddress` varchar(20) NOT NULL,
  `service` varchar(20) NOT NULL,
  `failoveripaddress` varchar(20) NOT NULL,
  `failovermonitaddress` varchar(20) NOT NULL,
  `servicefailover` varchar(20) NOT NULL,
  `type` varchar(6) NOT NULL,
  `checkinterval` int(4) NOT NULL,
  `isInFailover` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logsources`
--

DROP TABLE IF EXISTS `logsources`;
CREATE TABLE IF NOT EXISTS `logsources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logtypes`
--

DROP TABLE IF EXISTS `logtypes`;
CREATE TABLE IF NOT EXISTS `logtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `managedDnsDomains`
--

DROP TABLE IF EXISTS `managedDnsDomains`;
CREATE TABLE IF NOT EXISTS `managedDnsDomains` (
  `domainame` varchar(100) NOT NULL,
  `primaryserver` varchar(50) NOT NULL,
  `seccondaryserver` varchar(50) NOT NULL,
  `minimum` int(6) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`domainame`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supportedServices`
--

DROP TABLE IF EXISTS `supportedServices`;
CREATE TABLE IF NOT EXISTS `supportedServices` (
  `name` varchar(50) NOT NULL,
  `displayName` varchar(50) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmlogs`
--

DROP TABLE IF EXISTS `tmlogs`;
CREATE TABLE IF NOT EXISTS `tmlogs` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `logtype` varchar(5) NOT NULL,
  `logsource` varchar(4) NOT NULL,
  `logmodule` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1509 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(150) NOT NULL,
  `description` varchar(100) NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
