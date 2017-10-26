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
	require_once 'connect_db.php';

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

/** initializes the database with stored procedures
  * 
  * this function uses the other declared functions to initialize the database with stored procedures
  */
function init_db()
{
	global $pagehead, $pagecontent, $pagefoot;
	csp_getCourses();

	echo $pagehead.$pagecontent.$pagefoot;
}

init_db(); // initialize the database with stored procedures.
