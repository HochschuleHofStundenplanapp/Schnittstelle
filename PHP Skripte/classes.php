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

/* Vorlesung */
class Course {

    var $course;
    var $year;
    var $labels;
    var $semester;
    
    function __construct($course, $year ,$de, $en) {
        $this->course=$course;
        $this->year=$year;
        if(trim($en)==false){
            $en = $de;
        }
        $this->labels=array("de"=>$de, "en"=>$en);
        $this->semester=array();
    }
    
    function addSemester($semester){
        $this->semester=$semester;
    }
}

/* Stundenplan */
class MSchedule {
    var $label; 
    var $docent; 
    var $type; 
    var $group; 
    var $starttime; 
    var $endtime; 
    var $startdate; 
    var $enddate; 
    var $day; 
    var $room;
    var $changes;
	var $comment;
    
    function __construct($label, $docent, $type, $group, $starttime, $endtime, $startdate, $enddate, $day, $room, $comment) {        
        $this->label = $label;
        $this->docent = $docent;
        $this->type=$type;
        $this->group=$group;
        $this->starttime=$starttime;
        $this->endtime=$endtime;
        $this->startdate=$startdate;
        $this->enddate=$enddate;
        $this->day=$day;
        $this->room=$room;
		$this->comment=$comment;
    }
    
    function setChanges($changes) {
        $this->changes=$changes;
    }
}

/* Stundenplanänderungen */
class MChanges {
    var $comment; 
    var $reason; 
    var $day;
    var $time; 
    var $date;         
    var $room; 
    function __construct($comment, $reason, $time, $date, $room, $day) {
        $this->comment=$comment;
        $this->reason=$reason;
        $this->time=$time;
        $this->date=$date;
        $this->room=$room;
        $this->day=$day;
    }

}
