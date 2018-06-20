<?php

/*
  ~ Copyright (c) 2016-2018 Hochschule Hof
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
Implementation of iOS push notifications during our student project in Swift FWPM and Android FWPM in WS 2017/2018.
By Johannes Franz and Normen Krug.
Related documentation can be found in the V4 branch of "HochschuleHofHundenplanapp / iOS-App".
*/


/* wird vom Cron-Job aus aufgerufen */

require_once 'fcm_connect_db.php';
require_once 'server.php';
require_once 'apnsPushIOS.php';

$debug=0;

error_log(print_r("Script fcm_update_and_send.php Beginn", TRUE));

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

	if($countChanges > 0){
		echo "Für die vorlesungs_id ".$vorlesung_ids[$i]." \nliegen $countChanges Änderungen vor!\n";
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
// ----------------------------------------------------------------------
function sendNotification( & $vorlesung_id, & $con, & $label) {
	global $debug;
	
	/* label: Beschreibung der Änderung, mehrsprachig, t.b.d. */

	$sql3 = "SELECT token, os FROM fcm_nutzer WHERE vorlesung_id = '".$vorlesung_id."'";
	
	$mySQLresult3 = $con->query($sql3);
	$tokenArray = array(array(), array());
	
	//Alle Tokens auslesen und in $tokens speichern
	if ($mySQLresult3->num_rows > 0) {
		
	    //output data of each row
	    $count = 0;
	    echo("count: ");
	    echo(count($tokenArray));
	    while ($row = $mySQLresult3->fetch_assoc()) {
		    echo "Token hinzufügen: $row[os] \n";
		    $tokenArray[$count][0] = $row["token"];
		    $tokenArray[$count][1] = $row["os"];  
	    	$count++;
	    }
		echo("for wird ausgeführt: $count\n");
		//Nachricht senden mit jedem Token aufrufen. Unterscheidung zwischen 0 = Android/GCM und 1 = iOS
		for($i=0; $i < $count; $i++) {
			
			if ($debug) error_log(print_r("++++ PUSH for an os type ++++ <br>", TRUE));
			echo("++++ PUSH as echo ++++<br>\n");                            
			
			// Android
			if ($tokenArray[$i][1] == 0){error_log(print_r("PUSH FCM: " . $vorlesung_id . " - ".$label." - for token: ".$tokenArray[$i][0], TRUE));
				try {
					// Token, Label
					sendGCM($tokenArray[$i][0], $label);
				} catch (Exception $e) {
					error_log(print_r("catch exception e: ".$e, TRUE));
				}
			}
			// IOS
			else if ($tokenArray[$i][1] == 1)
			{
				if ($debug) error_log(print_r("PUSH iOS: " . $vorlesung_id . " - ".$label." - for token: ".$tokenArray[$i][0], TRUE));     			
				try {
					// Titel, Body, Token
					sendIosPush("Neue Änderung für das Fach", $label, $tokenArray[$i][0]);
				} catch (Exception $e) {
					error_log(print_r("catch exception e: ".$e, TRUE));
				}
			}
			else
			{
				error_log(print_r("++++ PUSH wrong OS!! ++++<br>\n", TRUE));	     			
				exit;
			}
			echo("Token: " . $tokenArray[$i][0]."<br>");
		}
		echo "Notification an Vorlesung_id $vorlesung_id wurde gesendet!<br>\n";
	} else{
	    echo "Es sind keine Tokens für die vorlesungs_id <b>$vorlesung_id</b> vorhanden!<br>\n";
	}
	$mySQLresult3->close();
}

//SendGoogleCloudMessage
function sendGCM( & $registration_ids, & $label) {
    //titel und message der Notification
    $title =    "Neue Änderung";
    $message =  "Fach ".$label;

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
error_log(print_r("Script fcm_update_and_send.php Ende", TRUE));
?>