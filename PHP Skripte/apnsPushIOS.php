<?php

//test
//$http2_server = 'https://api.development.push.apple.com:443';   
//$apple_cert = '../cert/server_certificates_bundle_sandbox.pem'; // certificate path
//prod
$http2_server = 'https://api.push.apple.com:443';   
//$apple_cert = '../cert/aps_push_prod.pem'; // certificate path
$apple_cert = '/var/www/html/cert/aps_push_prod.pem'; // certificate path

$http2ch = curl_init();
$app_bundle_id = 'iosapps.hof-university.stundenplan'; // bundle identifier from ios. must fit with projects bundle identifier


curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

// example:
// $executeThis = sendIosPush("Überschrift der Nachricht", "Inhalt der Nachricht", "PLATZ FÜR DEN TOKEN"); 

function sendIosPush($title, $body, $token) {
 
    global $http2_server;
    global $http2ch;
    global $apple_cert;
    global $app_bundle_id;

    $message = '
    {
        "aps" : {
            "alert" : {
                "title" : "' . $title . '",
                "body" : "' . $body. '"
            }
        }
    }';

    if(false) {
        error_log("----");
        error_log(print_r($message, TRUE));
        error_log("----");
    }

    // url (endpoint)
    $url = "{$http2_server}/3/device/{$token}";
    echo $url;
 
    // certificate
    $cert = realpath($apple_cert);
 
    // headers
    $headers = array(
        "apns-topic: {$app_bundle_id}",
        "User-Agent: My Sender"
    );
 
    // other curl options
    curl_setopt_array($http2ch, array(
        CURLOPT_URL => $url,
        CURLOPT_PORT => 443,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $message,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSLCERT => $cert,
        CURLOPT_HEADER => 1
    ));
 
    //go...
    $result = curl_exec($http2ch);
    if ($result === FALSE) {
        error_log(print_r("curl exception in apnsPushIOS.php", TRUE));
	throw new Exception("Curl failed: " .  curl_error($http2ch));
    }
 
    // get response
    $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
    echo ' Echo result: '.$result;
    return null;
}
?>
