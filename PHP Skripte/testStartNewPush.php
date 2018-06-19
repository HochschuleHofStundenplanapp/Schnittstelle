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

/* TESTAUFRUF */
/* Nur an ein Token einen Aufruf senden */



require_once 'apnsPushIOS.php';

// token jf 08.02.2018 - 08:35

//sendIosPush("datei: testStartNewPush.php sagt:", "extern von testStartNewPush.php aufgerufen", "61a55c71301eb16a3fe373bbc3f065eca8dc065f80bf6d8e8ff0d3f0b53e887d");;
//sendIosPush("datei: testStartNewPush.php sagt:", "extern von testStartNewPush.php aufgerufen", "d2eddef0edb544ff9cf0fa6819a62674b73fe02781314a848cc6472fff8f044d");;
//token srill developer apns
//sendIosPush("datei: testStartNewPush.php sagt:", "[prod] extern von testStartNewPush.php aufgerufen", "a8050426b53c76547822786e1ac89067fdefbc9abaa1dfc2f22b161dfea29a77");;
//token srill prod apns
sendIosPush("datei: testStartNewPush.php sagt:", "[prod] extern von testStartNewPush.php aufgerufen", "97a33dba0bb18b236ca7dec96ee51fa2992d674d655be3800e22b21f6e9bbdd6");;

?>
