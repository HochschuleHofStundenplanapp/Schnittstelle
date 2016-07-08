Die Beispiele wurden am "08.07.2016" mit den Daten der "dump.sql" erzeugt, unter verwendung folgender Links:

Courses:			http://localhost/project/client.php?f=Courses&tt=SS
Changes:			http://localhost/project/client.php?f=Changes&stg=Inf&sem=6&tt=SS
Menu:				http://localhost/project/client.php?f=Menu  (DEAKTIVIERT!!!)
Schedule:			http://localhost/project/client.php?f=Schedule&stg=Inf&sem=6&tt=SS
MySchedule :		http://localhost/project/client.php?f=MySchedule&id[]=1332256&id[]=1332495&id[]=1332445
MergedSchedule:		http://localhost/project/client.php?f=MSchedule&stg=Inf&sem=6&tt=SS


Der eigentliche Link der Mensa lautet:
Das URL_PATTERN enthält die darunter aufgeführten Variablen!!!

URL_PATTERN= https://www.studentenwerk-oberfranken.de/?eID=bwrkSpeiseplanRss&tx_bwrkspeiseplan_pi2%5Bbar%5D=VAR_POS&tx_bwrkspeiseplan_pi2%5Bdate%5D=VAR_DATE
VAR_POS= 340 (Hof), 370 (Münchberg) legt die Nummer fest, welche Mensa ausgewählt werden soll.
VAR_DATE= Datum der angefragten Woche, mit dem Pattern (yyyy.mm.dd)

