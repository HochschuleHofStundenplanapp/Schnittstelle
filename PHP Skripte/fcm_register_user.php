<?php

/*
Implementation of iOS push notifications during our student project in Swift FWPM and Android FWPM in WS 2017/2018.
By Johannes Franz and Normen Krug.
Related documentation can be found in the V4 branch of "HochschuleHofHundenplanapp / iOS-App".
*/


/*

Testlinks:

https://app.hof-university.de/soap/fcm_register_user.php?debug=1&os=[0|1] // android = 0 ios = 1
muss allerdings mit einem Tool wie z.B. Advanced Rest Client
https://chrome.google.com/webstore/detail/advanced-rest-client/hgmloofddffdnphfgcellkdfbfbjeloo
per POST aufgerufen werden und die folgende Variablen mit übergeben werden:
- token (Der Token des Gerätes, zum testen einfach einen String)
- id (die SplusName's der Vorlesungen, zum testen auch einfach einen oder mehere Strings)
--> _new.php version with user agent and iOS register
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


require_once "fcm_connect_db.php";

//BSP SQL-Injection
//$login = $this->mysqli->real_escape_string( $login ) ;

// Alle übergebeenen Parameter entwerten, um SQL-Injection aus dem Weg zu gehen
// $pushToken = htmlentities( $_POST["fcm_token"] );


$language = null;


// pass os number to os variable. value is 0 for android downward compatibility
$os = htmlentities($_REQUEST['os']??0);
error_log(print_r("OS-Type: ".$os, TRUE));


// os choice: os parameter is 0 -> Android and 1 -> iOS
if(!is_null($os) && $os == 0){ // for Android (0) and if there is no os parameter (downward compatibility)
	
	// Alle übergebeenen Parameter entwerten, um SQL-Injection aus dem Weg zu gehen
	$pushToken = htmlentities( $_POST["fcm_token"]??null );
	$lectureJSON = $_POST["vorlesung_id"]??null;
	$language = $_POST["language"]??null;

	// check if values are null.
	if(!is_null($lectureJSON) && !is_null($pushToken) ){
		$lectureArray = json_decode($lectureJSON,true);	
	} else {
		error_log(print_r("Android value is null!", TRUE));
		echo "Android value is null!";
		return "Android value is null!";
	}
/*
	error_log("#---->");
	error_log(print_r('Android-Token: '.$pushToken, TRUE));
	error_log(print_r('Language: '.$language, TRUE));
	error_log("<----#");
*/
	
}else if($os == 1){ // for iOS (1)
	$entitybody = file_get_contents("php://input");

	$fullJSON = json_decode($entitybody, true);
	$lectureArray = $fullJSON["vorlesung_id"]??null;
	$pushToken = $fullJSON["fcm_token"]??null;
	$language = $fullJSON["language"]??null;
} else {
	error_log(print_r("Keine OS Zuordnung!", TRUE));
	echo "Keine OS Zuordnung!";
	return("Keine OS Zuordnung!");
}


// check if a null value is given. can happen if a user opens the scipt in a browser window.
// can prevent null entries in database
if(is_null($lectureArray) || is_null($pushToken) ){
	echo "There is a null value!"; 
	error_log(print_r("There is a null value!", TRUE));
	return "There is a null value!"; 
}


// if ($debug) { echo "\nToken:". $pushToken ."\n\n lectureJSON: ". $lectureJSON ."\n";}


//Alle Einträge mit diesem Token in DB löschen
$sqldelete = "DELETE FROM fcm_nutzer WHERE token = \"$pushToken\" AND os = \"$os\"";
$con->query($sqldelete);

//Tokens und Vorlesungn in DB eintragen
for ($i = 0; $i < count($lectureArray); $i++) 
{
	if($os == 0) { // for Android (0)
		$vorlesung_id = filter_var($lectureArray[$i]['vorlesung_id'], FILTER_SANITIZE_STRING); // android
	} else if($os == 1) { // for iOS (1)
		$vorlesung_id = filter_var($lectureArray[$i], FILTER_SANITIZE_STRING); // ios
	} else {
		return "Error selecting correct lecture!";
	}

//	if ($debug) { echo "\nVorlesung_id: $vorlesung_id\n"; }

	// this way you can add null values to cells like "language" w/o adding the letters "NULL" as a string. 
	// Also you arn't getting crazy by concatenating strings like in the previous version :)
	$stmt = $con->prepare("INSERT INTO fcm_nutzer (token, vorlesung_id, os, language) VALUES (?, ?, ?, ?)");
	// ssis stands for the sequence of string and integer variables.
	$stmt->bind_param('ssis', $pushToken, $vorlesung_id, $os, $language);

	$stmt->execute();
	// old way
	//$sqlinsert = "INSERT INTO `fcm_nutzer`(`token`, `vorlesung_id`, `os`, `language`) VALUES (\"$pushToken\",N'$vorlesung_id',N'$os',\"$language\")";
	//$con->query($sqlinsert);
}

// SQLi-Conncetion schließen
$con->close();
// error_log(print_r("DONE!", TRUE));
return("Funktioniert!");
?>