<?php
/* 
 * MySQL - Daten
 */
$mysql_host = "localhost";
$mysql_db = "t3_ext";
$mysql_user = "appuser";
$mysql_password = "Wer8%GreitY99#";

try {
    $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8", $mysql_user, $mysql_password);
print "Success";
#    $pdo->exec("set names utf8");	// nur bei PHP < 5.4
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br />";
    die();
}

