--
-- Tabellenstruktur für Tabelle `fcm_nutzer`
--

CREATE TABLE `fcm_nutzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Tabellenstruktur für Tabelle `fcm_verlegungen`
--

CREATE TABLE `fcm_verlegungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `verlegung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;