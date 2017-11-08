<?php
/*
  ~ Copyright (c) 2017 Joel Fridolin Meyer (sirjofri)
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

/* vim:ts=4:sw=4:set noet: */

/*
  ~ see also: init_db.md (german)
*/

const __VERSIONNUMBER = 3.4;

$pagehead = "<!DOCTYPE html><html><head><title>Database Initialization</title><meta charset=\"utf-8\"></head><body><h1>Database Initialization</h1><div><ul>\n";
$pagefoot = "</ul></div></body></html>";
$pagecontent = "<li>Version: ".__VERSIONNUMBER."</li>\n";

require_once 'connect_db.php';

/** debug output (or feedback output)
  *
  * this function collects all debug/feedback messages. Those will be printed on the screen.
  *
  * @param $msg the message in html/string format
  */
function debug_output($msg)
{
	global $pagecontent;
	$pagecontent .= "<li>".$msg."</li>\n";
}

/** create a stored procedure
  *
  * this function let the database system create a stored procedure with the given parameters.
  *
  * @param $call the name of the stored procedure
  * @param $implementation the correct sql statement executed by the stored procedure
  * @param $params the parameters this stored procedure should use
  */
function create_stoproc($call, $params, $implementation)
{
	global $pdo;

	$impl = rtrim($implementation, ';').';'; // make sure the implementation ends with a ;

	$sql = "DROP PROCEDURE IF EXISTS $call; CREATE PROCEDURE $call ($params) READS SQL DATA sto_proc: BEGIN $impl END sto_proc;";

	$pdo->exec($sql);

	debug_output("Stored Procedure $call created");
}

/** csp: Create Stored Procedure (append name)
  *
  * creates the stored procedure for selecting courses
  */
function csp_getCourses()
{
	// this code is mostly a copy from server.php
	$param_select = array(
		"sg.Bezeichnung",
		"sg.Bezeichnung_en",
		"sg.STGNR",
		"sp.Fachsemester",
		"sp.Jahr");
	$param_where = array("(sp.WS_SS=tt)"); // difference: tttt instead of :tt
	$param_orderby = array("sg.Bezeichnung");
	
	$sql = "SELECT DISTINCT ".implode(' , ', $param_select)
	      ." FROM Stundenplan_WWW AS sp INNER JOIN Studiengaenge  AS sg ON sg.STGNR = sp.STGNR "
	      ." WHERE ".implode(' AND ', $param_where)
	      ." ORDER BY ".implode(' , ', $param_orderby);
	// end of copy

	$sproc_params = "IN tt MEDIUMTEXT";

	create_stoproc("GET_COURSES", $sproc_params, $sql);
}

function csp_getSchedule()
{
	// code copied from server.php (and adjusted by sirjofri)
	$param_select = array(
		"sp.id",
		"sp.Bezeichnung label",
		"IF (sp.Anzeigen_int=0 , sp.InternetName, '') docent",
		"sp.LV_Kurz type",
		"sp.VArt style",
		"sp.Gruppe 'group'",
		"DATE_FORMAT(sp.AnfDatum, '%H:%i') starttime",
		"DATE_FORMAT(sp.Enddatum, '%H:%i') endtime",
		"DATE_FORMAT(sp.AnfDatum, '%d.%m.%Y') startdate",
		"DATE_FORMAT(sp.Enddatum, '%d.%m.%Y') enddate",
		"sp.Tag_lang day",
		"sp.RaumNr room",
		"sp.SplusName splusname",
		"sp.Kommentar comment",
		"sp.SP sp");
	$param_where = array("(sg.STGNR = stgnr)","(sp.Fachsemester = semester)", "(sp.WS_SS = tt)");
	$param_orderby=array("sp.Tag_Nr", "starttime");

	// IF no ids
	$sql = "IF given_ids=NULL THEN ";

	// SELECT without ids
	$sql .="SELECT ".implode(' , ', $param_select)
	      ." FROM Stundenplan_WWW AS sp JOIN Studiengaenge AS sg ON sg.STGNR = sp.STGNR "
	      ." WHERE ".implode(' AND ', $param_where)
	      ." ORDER BY ".implode(' , ', $param_orderby).";"; // Note: ';' at the end of sql statement

	// ELSE
	$sql .= " ELSE ";

	// überschreiben des Parameters
	array_push($param_where, "sp.SplusName IN (given_ids)");

	// SELECT with ids
	$sql .="SELECT ".implode(' , ', $param_select)
	      ." FROM Stundenplan_WWW AS sp JOIN Studiengaenge AS sg ON sg.STGNR = sp.STGNR "
	      ." WHERE ".implode(' AND ', $param_where)
	      ." ORDER BY ".implode(' , ', $param_orderby).";"; // Note: see above

	$sql .= " END IF;";

	$sproc_params = array("IN stgnr MEDIUMTEXT",
	                      "IN semester MEDIUMTEXT",
	                      "IN tt MEDIUMTEXT",
	                      "IN given_ids MEDIUMTEXT");

	create_stoproc("GET_SCHEDULE", implode(", ", $sproc_params), $sql);
}

function csp_getMySchedule()
{
	$param_select = array(
	   "sp.id",
	   "sp.Bezeichnung label",
	   "IF (sp.Anzeigen_int=0 , sp.InternetName, '') docent",
	   "sp.LV_Kurz type",
	   "sp.VArt style",
	   "sp.Gruppe 'group'",
	   "DATE_FORMAT(sp.AnfDatum, '%H:%i') starttime",
	   "DATE_FORMAT(sp.Enddatum, '%H:%i') endtime",
	   "DATE_FORMAT(sp.AnfDatum, '%d.%m.%Y') startdate",
	   "DATE_FORMAT(sp.Enddatum, '%d.%m.%Y') enddate",
	   "sp.Tag_lang day",
	   "sp.RaumNr room",
	   "sp.SplusName splusname",
	   "sp.Kommentar comment",
	   "sp.SP sp");
	$param_where = array("sp.SplusName IN (given_ids)"); // parameter given_ids
	$param_orderby = array("sp.Tag_Nr", "starttime");

	$sql = "SELECT ".implode(' , ', $param_select)
	      ." FROM Stundenplan_WWW as sp "
	      ." WHERE ".implode(' AND ', $param_where)
	      ." ORDER BY ".implode(' , ', $param_orderby);

	$sproc_params = array("IN given_ids MEDIUMTEXT");

	create_stoproc("GET_MY_SCHEDULE", implode(", ", $sproc_params), $sql);
}

/** initializes the database with stored procedures
  * 
  * this function uses the other declared functions to initialize the database with stored procedures
  */
function init_db()
{
	global $pagehead, $pagecontent, $pagefoot;
	csp_getCourses();
	csp_getSchedule();
	csp_getMySchedule();

	echo $pagehead.$pagecontent.$pagefoot;
}

init_db(); // initialize the database with stored procedures.
