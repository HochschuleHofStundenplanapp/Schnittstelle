<?php
class Course {

    var $course;
    var $labels;
    var $semester;
    
    function __construct($course, $de, $en) {
        $this->course=$course;
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
    
    function __construct($label, $docent, $type, $group, $starttime, $endtime, $startdate, $enddate, $day, $room) {        
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
    }
    
    function setChanges($changes) {
        $this->changes=$changes;
    }
}

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