Ben�tigte php-Datein:
- mensa.php wird aktuell nicht ben�tigt!
- connect_db.php wird nicht ben�tigt, da diese bereits mit der passenden Konfiguration auf dem Server ist.

Erforderlich:
- classes.php 	enth�lt Klassen zum einfacheren umgang mit den Daten.
- docs.php 		enth�lt die Doku Seite, wenn diese nicht angezeigt werden soll muss der include in Zeile 73 in der client.php auskommentiert werden.
- server.php	enth�lt die SQL-Statments auf dem Server und gibt die angeforderten Daten zur�ck
- client.php	enth�lt die Webschnittstelle f�r den Benutzer, �ber den die Funktionen der 'server.php' aufgerufen werden. (Siehe docs.php)
