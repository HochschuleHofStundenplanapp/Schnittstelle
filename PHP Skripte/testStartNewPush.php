<?php

/*
Implementation of iOS push notifications during our student project in Swift FWPM and Android FWPM in WS 2017/2018.
By Johannes Franz and Normen Krug.
Related documentation can be found in the V4 branch of "HochschuleHofHundenplanapp / iOS-App".
*/

require_once 'apnsPushIOS.php';

// token jf 08.02.2018 - 08:35

//sendIosPush("datei: testStartNewPush.php sagt:", "extern von testStartNewPush.php aufgerufen", "61a55c71301eb16a3fe373bbc3f065eca8dc065f80bf6d8e8ff0d3f0b53e887d");;
//sendIosPush("datei: testStartNewPush.php sagt:", "extern von testStartNewPush.php aufgerufen", "d2eddef0edb544ff9cf0fa6819a62674b73fe02781314a848cc6472fff8f044d");;
//token srill developer apns
//sendIosPush("datei: testStartNewPush.php sagt:", "[prod] extern von testStartNewPush.php aufgerufen", "a8050426b53c76547822786e1ac89067fdefbc9abaa1dfc2f22b161dfea29a77");;
//token srill prod apns
sendIosPush("datei: testStartNewPush.php sagt:", "[prod] extern von testStartNewPush.php aufgerufen", "2b832c83a6c3a1fb06041aaf2021ea2ba32cfe10f75073a24d1b0f3ef8abe572");
// 4dbda63618880a45de32be0e21cfc7f967ed454f0487586d9cd72b0fcd4b4e9c -- jf 17.05.2018

?>
