<?php
// SoapClient
try {
    $options = array(
        "location" => "http://localhost/project/server.php",
        "uri" => "http://test-uri",
        'encoding' => 'UTF-8',
#        'encoding' => 'ISO-8859-15',
        'soap_version' => SOAP_1_1,
        'exceptions' => true,
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
        'id'=>array('filter' => FILTER_VALIDATE_INT,'flags'  => FILTER_REQUIRE_ARRAY)
        );
    switch (filter_input(INPUT_GET, 'f')) {        
        case "MSchedule":
            $getParams = filter_input_array(INPUT_GET, $params);            
            $response = $client->getMergedSchedule($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            print_r(getJSON($response));
            break;
        case "Schedule":                
            $getParams = filter_input_array(INPUT_GET, $params);            //           
            $response = $client->getSchedule($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            print_r(getJSON($response));
            break;
        
        case "MySchedule":                
            $getParams = filter_input_array(INPUT_GET, array('id'=>array('filter' => FILTER_VALIDATE_INT,'flags'  => FILTER_REQUIRE_ARRAY,)));         
            $response = $client->getMySchedule($getParams['id']);
            print_r(getJSON($response));
            break;
            
        case "Courses":
            $getParams = filter_input_array(INPUT_GET, array('tt' => FILTER_SANITIZE_STRING));            
            $response = $client->getCourses($getParams['tt']);
            print_r(getJSON($response));
            break;    
        
        case "Changes":
            $getParams = filter_input_array(INPUT_GET, $params);                      
            $response = $client->getChanges($getParams['stg'], $getParams['sem'], $getParams['tt'], $getParams['id']);
            print_r(getJSON($response));
            break;
                
/*
 *  Mensa deaktiviert!
        case "Menu":
            $response = $client->getMenu();
            break;   
 * 
 */

        default:
            include './docs.php';
            break;                
    }    
} catch (Exception $e) {
    echo "Caught exception: ", $e->getMessage(), "\n";
}

/**
 * Fügt die nötigen Headerinformationen hinzu und erzeugt JSON-Objekt
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