<?php
/* 
 * MySQL - Daten
 */
 

// Unter Windows ist das Auflösen von localhost seit Vista sehr langsam, es wird wirklich eine DNS Abfrage getätigt 
//$mysql_host = 'localhost';
$mysql_host = '127.0.0.1';
$mysql_db = 'test';
$mysql_user = '';
$mysql_password = '';


try {
    $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8", $mysql_user, $mysql_password);
} catch (PDOException $e) {
    print 'Error!: ' . $e->getMessage() . '<br />';
    die();
}