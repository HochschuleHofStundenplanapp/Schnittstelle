<?php

require "fcm_connect_db.php";
$fcm_token = $_POST["fcm_token"];
$lectureJSON = $_POST["vorlesung_id"];
$lectureArray = json_decode($lectureJSON,true);

//Alle Einträge mit diesem Token in DB löschen
$sqldelete = "DELETE FROM fcm_nutzer WHERE token = \"$fcm_token\" ";
mysqli_query($con,$sqldelete);

//Tokens und Vorlesungn in DB eintragen
for ($i = 0; $i < count($lectureArray); $i++) {
    $vorlesung_id = $lectureArray[$i]['vorlesung_id'];
	$sqlinsert = "INSERT INTO `fcm_nutzer`(`token`, `vorlesung_id`) VALUES (\"$fcm_token\",\"$vorlesung_id\")";
	mysqli_query($con,$sqlinsert);
}

mysqli_close($con);
print("funktioniert!");
print($fcm_token);
print($lectureJSON);
return("Funktioniert!");
?>
