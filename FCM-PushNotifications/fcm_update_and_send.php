<?php

require "fcm_connect_db.php";
require "server.php";

$sql = "SELECT vorlesung_id FROM fcm_nutzer GROUP BY vorlesung_id";
$mySQLresult = $con->query($sql);
$vorlesung_ids = array();

//Vorlesungs_ids werden in vorlesung_ids Array gespeichert 
echo"\n\nVorlesung ids: \n\n";
if($mySQLresult->num_rows >0) {
	while($row = $mySQLresult->fetch_assoc()) {
		echo "id: ";
		echo $row['vorlesung_id'];
		echo "\n";
		array_push($vorlesung_ids, $row['vorlesung_id']);
	}
}
$mySQLresult->close();

echo "Es wurden ".count($vorlesung_ids)." vorlesungs_ids durchsucht!\n";

//Es werden zu jeder vorlesung_id die verlegung_id's geholt
for ($i=0; $i < count($vorlesung_ids); $i++) { 
	$response = getChanges("","","",array($vorlesung_ids[$i]));
	$countChanges = count($response['changes']);

	echo "-------------------------------------------\n\n";

	if($countChanges > 0){
		echo "Für die vorlesungs_id ".$vorlesung_ids[$i]." \nliegen $countChanges Änderungen vor!\n";
		//var_dump($response);
	}
	else{
		echo "Für die vorlesungs_id ".$vorlesung_ids[$i]." \nliegen KEINE Änderungen vor!\n";
	}

	//Alle verlegung_id's werden ausgelesen
	echo"\nverlegung_ids:\n\n";
	for ($j = 0; $j < $countChanges; $j++) {
		$verlegung_id = $response['changes'][$j]['splusname'];
		echo "Id: ";
		echo $verlegung_id;
		echo "\n";
		
		//Überprüfen, ob Päärchen aus vorlesung_id und verlegung_id in fcm_verlegungen schon vorkommt, falls nein -> reinschreiben
		$sql2 = "SELECT * FROM fcm_verlegungen WHERE verlegung_id = '$verlegung_id' AND vorlesung_id = '$vorlesung_ids[$i]'";
		$mySQLresult2 = $con->query($sql2);
		if ($mySQLresult2->num_rows >0) {
			echo "id wurde bereits eingetragen!\n";
		}
		else {
			echo "id wird eingetragen!\n";
			$sqlinsert = "INSERT INTO fcm_verlegungen (`verlegung_id`, `vorlesung_id`) VALUES ('$verlegung_id', '$vorlesung_ids[$i]')";
			$con->query($sqlinsert);
			//Sende Notifications an vorlesungs_id
			sendNotification($vorlesung_ids[$i], $con, $response['changes'][$j]['label']);
		}
		$mySQLresult2->close();
		echo "\n";
	}
}

// SQLi-Conncetion schließen
$con->close();

// Funktionen
// --------------------------------------------------------------------------------
function sendNotification($vorlesung_id, $con, $label) {
	$sql3 = "SELECT token FROM fcm_nutzer WHERE vorlesung_id = '".$vorlesung_id."'";
	$mySQLresult3 = $con->query($sql3);
	$tokenArray = array("");

	//Alle Tokens auslesen und in $tokens speichern
	if ($mySQLresult3->num_rows > 0) {
	    //output data of each row
	    while ($row = $mySQLresult3->fetch_assoc()) {
	        if ($tokenArray[0] == '') {
				echo "Token leeres Feld: $row[token]\n";
	            $tokenArray[0] = $row[token];
	        }
	        else {
				echo "Token hinzufügen: $row[token]\n";
	            array_push($tokenArray, $row[token]);
	        }
	    }
		
		//Nachricht senden mit jedem Token aufrufen
		for($i=0; $i < count($tokenArray); $i++) {
			sendGCM($tokenArray[$i], $label);
			echo($tokenArray[$i]."<br>");
		}
		echo "Notification an Vorlesung_id $vorlesung_id wurde gesendet!<br>\n";
	} else{
	    echo "Es sind keine Tokens für die vorlesungs_id <b>$vorlesung_id</b> vorhanden!<br>\n";
	}
	$mySQLresult3->close();
}

//SendGoogleCloudMessage
function sendGCM($registration_ids, $label) {
    //titel und message der Notification
    $title =    "Neue Änderung";
    $message =  "für das Fach ".$label;

    //FCM URL
    $url = "https://fcm.googleapis.com/fcm/send";
    //$server_key = "AIzaSyAqg-02MqKHK4P9kWTFaeX18AZuKz3-oH8";
    $server_key = "AIzaSyCLUsCj9AxHYigJS_Gfu_ccC_9Y5Ii38zw";

    //prepare data
    $fields = array (
        'registration_ids' => array($registration_ids),
		'data' => array('notification_type' => 'change'),
        'notification' => array('title' => $title, 'body' => $message, 'sound' => 'default')
    );

    $fields = json_encode ( $fields ); 

    //header data
    $headers = array ('Authorization: key='.$server_key, 'Content-Type: application/json');

    //initiate curl request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    // execute curl request
    $result = curl_exec($ch);

    //close curl request
    curl_close($ch);

    echo "Token wurde an Google Firebase übermittelt: ";
    //return output
    return $result;
}
?>