--
-- Tabellenstruktur für Tabelle `fcm_nutzer`
--

CREATE TABLE `fcm_nutzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vorlesung_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes für die Tabelle `fcm_nutzer`
--
ALTER TABLE `fcm_nutzer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vorlesung_id` (`vorlesung_id`),
  ADD KEY `token` (`token`);