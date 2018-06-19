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