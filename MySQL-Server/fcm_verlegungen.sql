--
-- Tabellenstruktur für Tabelle `fcm_verlegungen`
--

CREATE TABLE `fcm_verlegungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verlegung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Indizes für die Tabelle `fcm_verlegungen`
--
ALTER TABLE `fcm_verlegungen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verlegung_id` (`verlegung_id`),
  ADD KEY `vorlesung_id` (`vorlesung_id`);