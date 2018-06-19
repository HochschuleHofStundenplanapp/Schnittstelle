<?php
/*
  ~ Copyright (c) 2016 Lars Gaidzik & Lukas Mahr & Victor Dienstbier
  ~ This program is free software: you can redistribute it and/or modify
  ~ it under the terms of the GNU General Public License as published by
  ~ the Free Software Foundation, either version 3 of the License, or
  ~ (at your option) any later version.
  ~
  ~ This program is distributed in the hope that it will be useful,
  ~ but WITHOUT ANY WARRANTY; without even the implied warranty of
  ~ MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  ~ GNU General Public License for more details.
  ~
  ~ You should have received a copy of the GNU General Public License
  ~ along with this program.  If not, see <http://www.gnu.org/licenses/>.
  */

/*

Testlinks:

Soap  Wer8%GreitY99#

https://app.hof-university.de/soap/client.php?f=Courses&stg=mc&sem=6&tt=SS&debug=1
https://app.hof-university.de/soap/client.php?f=Schedule&stg=mc&sem=6&tt=SS&debug=1
https://app.hof-university.de/soap/client.php?f=MySchedule&id[]=DigM%C2%A7aheda_2%2550468%20%24%202&debug=1


https://soapuser:F%98z&12@sl-app01.hof-university.de/soap/client.php?f=Schedule&stg=mc&sem=6&tt=SS

*/

/* we can request debug output to better find errors */

/* we can request debug output to better find errors */
$debug=0;
if ( isset( $_REQUEST['debug'] ))
	$debug = htmlentities($_REQUEST['debug']);
	
if ($debug)
{
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



// SoapClient
try {
    $options = array(
//        "location" => "http://localhost/soap/server.php",
        "location" => "https://app.hof-university.de/soap/server.php",
//        "uri" => "http://localhost/soap/",
        "uri" => "https://app.hof-university.de/soap/",
        'encoding' => 'UTF-8',
//        'encoding' => 'ISO-8859-15',
        'login' => 'soapuser',
        'password' => 'F%98z&12',
        'soap_version' => SOAP_1_2,
        'exceptions' => false,
//        'exceptions' => true,
        'trace' => 1,
        'cache_wsdl' => WSDL_CACHE_NONE
    );
    $client = new SoapClient(NULL, $options);       // Client mit Optionen erstellen
} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>\n";
    echo $e->getMessage();
}


try {
// Mehrfachverzweigung, je nachdem, welche Funktion aufgerufen werden soll
    $response=null;
    $params = array(
        'stg' => FILTER_SANITIZE_STRING,
        'sem' => FILTER_SANITIZE_ENCODED,
        'tt' => FILTER_SANITIZE_STRING,
        'id' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_REQUIRE_ARRAY)
        );
    
    $jsonoutput = '';
        
    $command = filter_input(INPUT_GET, 'f') ;
    switch ( $command ) 
    {        
        case "MSchedule":
            $getParams = filter_input_array(INPUT_GET, $params);            
				    if ($debug) { echo "\nParams geparsed: "; print_r ($getParams); echo "\n\n";}    

            $response = $client->getMergedSchedule($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            $jsonoutput = (getJSON($response));
            break;
        case "Schedule":                
            $getParams = filter_input_array(INPUT_GET, $params);            //           
				    if ($debug) { echo "\nParams geparsed: "; print_r ($getParams); echo "\n\n";}    
          
            $response = $client->getSchedule($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            $jsonoutput = (getJSON($response));
            break;
        
        case "MySchedule":                
            $getParams = filter_input_array(INPUT_GET, array('id' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_REQUIRE_ARRAY,)));         
			if ($debug) { echo "\nParams geparsed: "; print_r ($getParams); echo "\n";}
			if ($debug) { print_r ("'".implode("','", $getParams['id'])."'"); echo "\n\n";}

            $response = $client->getMySchedule($getParams['id']);
            $jsonoutput = (getJSON($response));
            break;
            
        case "Courses":
            $getParams = filter_input_array(INPUT_GET, array('tt' => FILTER_SANITIZE_STRING));            
				    if ($debug) { echo "\nParams geparsed: "; print_r ($getParams); echo "\n\n";}    

            $response = $client->getCourses($getParams['tt']);
            $jsonoutput = (getJSON($response));
            break;    
        
        case "Changes":
            $getParams = filter_input_array(INPUT_GET, $params);                      
				    if ($debug) { echo "\nParams geparsed: "; print_r ($getParams); echo "\n\n";}    

            $response = $client->getChanges($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            $jsonoutput = (getJSON($response));
            break;
                
/*
 *  Mensa deaktiviert!
        case "Menu":
            $response = $client->getMenu();
            $jsonoutput = ...
            break;   
 * 
 */

        default:
            header('Content-Type: text/html');
						// #### hk die Dokumentation soll nur bei Debug angezeigt werden
            if ($debug) { include './docs.php'; }
            echo ("Not a valid function: ".$command." <br />\n");
            break;                
    }    
    
    /* nach dem Ende des Switches */
    if ( !empty( $jsonoutput ) )
    {
    	// Ausgabe der Parameter
    	print_r ( $jsonoutput ) ;
    }
    if ( $debug ) { echo "\nDEBUG: response: "; print_r ($response); echo "\n\n";
    								echo "\nDEBUG: jsonoutput: ".$jsonoutput."\n\n"; }
    
    
} catch (Exception $e) 
{
	echo "\nCaught exception: ", $e->getMessage(), "\n";
	echo "\nResponse: ", $response, "\n";
	echo "\n\n\nMore Information: ", var_dump($e), "\n";
}

/**
 * Fuegt die noetigen Headerinformationen hinzu und erzeugt JSON-Objekt
 * @param type $obj Array welches als JSON codiert werden soll
 * @return type JSON-Objekt
 */
function getJSON($obj){
    header('Content-type: application/json; charset=UTF-8');
    $json = json_encode($obj , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if($obj!=null){
        header('Content-Length: '.strlen($json));    
    }
    return $json;
}
