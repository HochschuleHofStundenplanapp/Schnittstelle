Benötigte php-Datein:
- mensa.php wird aktuell nicht benötigt!
- connect_db.php wird nicht benötigt, da diese bereits mit der passenden Konfiguration auf dem Server ist.

Erforderlich:
- classes.php 	enthält Klassen zum einfacheren umgang mit den Daten.
- docs.php 		enthält die Doku Seite, wenn diese nicht angezeigt werden soll muss der include in Zeile 73 in der client.php auskommentiert werden.
- server.php	enthält die SQL-Statments auf dem Server und gibt die angeforderten Daten zurück
- client.php	enthält die Webschnittstelle für den Benutzer, über den die Funktionen der 'server.php' aufgerufen werden. (Siehe docs.php)
