<?php
/* 
 * MySQL - Daten
 */
$mysql_host = "localhost";
$mysql_db = "test";
$mysql_user = "";
$mysql_password = "";

try {
    $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8", $mysql_user, $mysql_password);
#    $pdo->exec("set names utf8");	// nur bei PHP < 5.4
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br />";
    die();
}