<?php
require_once 'passwords.php';

$host = "localhost";
$db_name = "t3_ext";
$db_user = "fcmuser";
$db_password = $fcmuserpassword;

$con = new mysqli($host, $db_user, $db_password, $db_name);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* change character set to utf8 */
if (!$con->set_charset("utf8")) {
	printf("Error loading character set utf8: %s\n", $mysqli->error);
	exit();
}
?>