<?php

$host = "localhost";
$db_user = "fcmuser";
$db_password = "Wer9%GreitY100#";
$db_name = "t3_ext";

$con = mysqli_connect($host,$db_user,$db_password,$db_name);
if($con)
    echo "Connection Success....<br></>";
else
    echo "Connection Error....<br></>";

?>