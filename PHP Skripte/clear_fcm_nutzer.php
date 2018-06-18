<?php
require "fcm_connect_db.php";
$sqldelete = "DELETE FROM `fcm_nutzer` WHERE 1";
mysqli_query($con,$sqldelete);
?>