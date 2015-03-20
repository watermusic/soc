
CREATE TABLE IF NOT EXISTS `kk_spieler2014_2015` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `verein` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `vk_preis` decimal(10,2) NOT NULL,
  `ek_preis` decimal(10,2) NOT NULL,
  `kaeufer` varchar(100) NOT NULL,
  `note` decimal(10,2) NOT NULL,
  `punkte` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=505 ;