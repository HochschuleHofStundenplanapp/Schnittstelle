Datenbank-Initialisierung mit Stored Procedures
===============================================

Das Script `init_db.php` _Stored Procedures_ in der Datenbank. Diese
ermöglichen einen einfachen Aufruf, ohne ein langes SQL-Statement zu
verwenden. Letztlich haben wir damit eine schlanke Schnittstelle mehr, dafür
aber eine schönere Kapselung der einzelnen Module.

Bei Aufruf des Scripts werden vorhandene _Procedures_ automatisch ersetzt. Bei
einem Update kann man also einfach das Script erneut aufrufen.

Verwendung
----------

Anstelle der üblichen SQL-Statements verwendet der PHP-Entwickler nun die
entsprechenden _Stored Procedures_. Eine Prozedur namens `MYPROC` kann zum
Beispiel mit `CALL MYPROC()` aufgerufen wird. Wenn die Prozedur Parameter
erwartet, verwendet man `CALL MYPROC(PARAMETER)`. Diese müssen dem
entsprechenden Datentyp "entsprechen".

Ein funktionierendes Beispiel aus `server.php`:

	require_once 'connect_db.php';
	$sql = "CALL GET_COURSES(:tt)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':tt', $tt);
	$stmt->execute();

In dieser Anleitung verwende ich die Form `PROZEDUR(parameter:typ,
parameter:typ)`. Das Ergebnis wird im kurzen Text danach ausführlicher
beschrieben.

Beschreibung der Funktionen
---------------------------

`GET_COURSES(tt:MEDIUMTEXT)` gibt eine Tabelle zurück. Die Spaltennamen
entsprechen denen der Originale: `Bezeichnung`, `Bezeichnung_en`, `STGNR`,
`Fachsemester`, `Jahr`.

`GET_SCHEDULE(stgnr:MEDIUMTEXT, semester:MEDIUMTEXT, tt:MEDIUMTEXT,
given_ids:MEDIUMTEXT)` gibt den Stundenplan zu den gegebenen Parametern
zurück. `given_ids` ist optional, falls es nicht verwendet werden soll, ist
`NULL` explizit mitzugeben!
