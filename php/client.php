<?php
// SoapClient
try {
    $options = array(
        "location" => "http://localhost/server.php",
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
    switch (filter_input(INPUT_GET, 'f')) {        
        case "MSchedule":
            $stgnr = filter_input(INPUT_GET, 'stg');
            $sem = filter_input(INPUT_GET, 'sem');           
            if (!empty($sem) && !empty($stgnr)) {
                $response = $client->getMergedSchedule($stgnr, $sem);
            } else {                
                $response = array();
            }
            break;
        case "Schedule":
            $stgnr = filter_input(INPUT_GET, 'stg');
            $sem = filter_input(INPUT_GET, 'sem');           
            if (!empty($sem) && !empty($stgnr)) {
                $response = $client->getSchedule($stgnr, $sem);
            } else {                
                $response = array();
            }
            break;
            
        case "Courses":
            $response = $client->getCourses();
            break;    
        
        case "Changes":
            $stgnr = filter_input(INPUT_GET, 'stg');            
            $sem = filter_input(INPUT_GET, 'sem');
            if (!empty($sem) && !empty($stgnr)) {
                $response = $client->getChanges($stgnr, $sem);
            } else {
            $response = array();        
            }
            break;
                
        case "Menu":
            $response = $client->getMenu();
            break;   

        default:
            $response = array();
            break;                
    }
    print_r(getJSON($response));
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