--
-- Tabellenstruktur für Tabelle `fcm_nutzer`
--

CREATE TABLE `fcm_nutzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes für die Tabelle `fcm_nutzer`
--
ALTER TABLE `fcm_nutzer`
  ADD INDEX `token` (`token`),
  ADD INDEX `vorlesung_id` (`vorlesung_id`);


--
-- Tabellenstruktur für Tabelle `fcm_verlegungen`
--

CREATE TABLE `fcm_verlegungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `verlegung_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes für die Tabelle `fcm_verlegungen`
--
ALTER TABLE `fcm_verlegungen`
  ADD INDEX `verlegung_id` (`verlegung_id`),
  ADD INDEX `vorlesung_id` (`vorlesung_id`);