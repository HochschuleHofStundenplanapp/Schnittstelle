<?php

/*

Testlinks:

https://app.hof-university.de/soap/fcm_register_user.php?debug=1
muss allerdings mit einem Tool wie z.B. Advanced Rest Client
https://chrome.google.com/webstore/detail/advanced-rest-client/hgmloofddffdnphfgcellkdfbfbjeloo
per POST aufgerufen werden und die folgende Variablen mit übergeben werden:
- token (Der Token des Gerätes, zum testen einfach einen String)
- id (die SplusName's der Vorlesungen, zum testen auch einfach einen oder mehere Strings)

*/


/* we can request debug output to better find errors */
$debug=0;
if ( isset( $_REQUEST['debug'] ))
{
	$debug=1;
	mysqli_report(MYSQLI_REPORT_ALL);

	ini_set('mysql.trace_mode',  'On' );
	ini_set('mysqli.trace_mode',  'On' );

	ini_set('error_reporting', E_ALL | E_STRICT | E_DEPRECATED | E_NOTICE | E_PARSE );

	ini_set('display_errors', 'On' );
	ini_set('display_startup_errors', 'On' ) ;

	//ini_set('allow_call_time_pass_reference', 'On' );
	ini_set('html_errors', 'On' ) ;
	
	ini_set('mysql.log_queries_not_using_indexes','on');
	ini_set('mysql.log_slow_admin_statements','on');
	ini_set('mysql.slow_query_log','on');
	ini_set('mysql.log_error_verbosity','3');

	ini_set('mysqli.log_queries_not_using_indexes','on');
	ini_set('mysqli.log_slow_admin_statements','on');
	ini_set('mysqli.slow_query_log','on');
	ini_set('mysqli.log_error_verbosity','3');

// E_NOTICE ist sinnvoll um uninitialisierte oder
// falsch geschriebene Variablen zu entdecken
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_STRICT );

}


require "fcm_connect_db.php";

//BSP SQL-Injection
//$login = $this->mysqli->real_escape_string( $login ) ;

// Alle übergebeenen Parameter entwerten, um SQL-Injection aus dem Weg zu gehen
$fcm_token = htmlentities( $_POST["fcm_token"] );
// passiert hier weiter unten
$lectureJSON = $_POST["vorlesung_id"];
$lectureArray = json_decode($lectureJSON,true);


if ($debug) { echo "\nToken: $fcm_token\n\nlectureJSON: $lectureJSON\n";}

//Alle Einträge mit diesem Token in DB löschen
$sqldelete = "DELETE FROM fcm_nutzer WHERE token = \"$fcm_token\" ";
$con->query($sqldelete);

//Tokens und Vorlesungn in DB eintragen
for ($i = 0; $i < count($lectureArray); $i++) 
{
	// htmlentities wegen SQL-Injection
	// da htmlentities falsch encodet filter_var genommen
	$vorlesung_id = filter_var($lectureArray[$i]['vorlesung_id'], FILTER_SANITIZE_STRING);
	if ($debug) { echo "\nVorlesung_id: $vorlesung_id\n";}
	$sqlinsert = "INSERT INTO `fcm_nutzer`(`token`, `vorlesung_id`) VALUES (\"$fcm_token\",N'$vorlesung_id')";
	$con->query($sqlinsert);
}

// SQLi-Conncetion schließen
$con->close();

return("Funktioniert!");
?>