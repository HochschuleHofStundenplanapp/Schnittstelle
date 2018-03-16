<?php
/* 
 * MySQL - Daten
 */

//hier muss require und nicht require_once sein, sonst geht es bei fcm_update_and_send.php nicht
require 'passwords.php';

/* Unter Windows ist das Auflösen von localhost seit Vista sehr langsam, es wird wirklich eine DNS Abfrage get�tigt 
	
	Achtung: MS 20170215: Unter dem App Server müssen wir "localhost" verwenden. Bei 127.0.0.1 erhalten
	wir eine Fehlermeldung in der JSON-Verarbeitung...
*/
/* $mysql_host = '127.0.0.1'; unter Windows */
$mysql_host = "localhost";
$mysql_db = "t3_ext";
$mysql_user = "appuser";
$mysql_password = $appuserpassword;

try {
    $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8", $mysql_user, $mysql_password);

    // #    $pdo->exec("set names utf8");	// nur bei PHP < 5.4

} catch (PDOException $e) {
    print 'Error!: ' . $e->getMessage() . '<br />';
    die();
}
?>