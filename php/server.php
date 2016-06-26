<?php
//include './mensa.php';        //Mensa deaktiviert!
include './classes.php';

const __VERSIONNUMBER = 2;

$server = new SoapServer(
        null, array(                    //Parameter	Bedeutung                       Priorität
    'uri' => "http://localhost",        //uri           Namespace des SOAP-Service	notwendig wenn WSDL nicht genutzt wird            
    'encoding' => 'UTF-8',              //encoding	Zeichensatz für SOAP-Request	optional
    'soap_version' => SOAP_1_2          //soap_version	Eingesetzte Protokollversion	optional
        )
);

function addGeneralInfos($nameOfContent, $arrContent){
    return array(
        "version" => __VERSIONNUMBER,
        $nameOfContent => $arrContent
            );
}

/**
 * 
 * @return type Speiesplan, der aus der XML-Datei erzeugt wird.
 */
/*
 * Mensa deaktiviert!
function getMenu(){
    return addGeneralInfos("menu", readMenuXML());
}
 * 
 */

/** 
 * @return type Array aller Studiengänge
 */
function getCourses(){
    require 'connect_db.php';
    $ws_ss="";
    $year=  date('Y');
    $month = date('n');
    if(3 < $month && $month < 9){  //Sommersemester        
        $ws_ss="SS";
    }else{                         //Wintersemester        
        $ws_ss="WS";        
        if($month < 4){
            $year = $year-1;       //Wenn Wintersemester und neues Jahr hat begonnen nimm altes Jahr.
        }
    }    
    $sql = "SELECT DISTINCT sg.Bezeichnung, sg.Bezeichnung_en, sg.STGNR, sp.Fachsemester
        FROM Studiengaenge AS sg, Stundenplan_WWW AS sp
        WHERE sg.STGNR=sp.STGNR AND sp.WS_SS=:ws_ss AND sp.Jahr=:year                 
        ORDER BY sg.Bezeichnung";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ws_ss', $ws_ss);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    
    $result = array();
    $arrSemester = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {      
        if(!array_key_exists($row['STGNR'], $arrSemester)){
            $result[]=new Course($row["STGNR"], $row["Bezeichnung"], $row["Bezeichnung_en"]);
        }
        $arrSemester[$row['STGNR']][]=$row["Fachsemester"];
    }
    foreach ($result as $course) {
        $semester = $arrSemester[$course->course];
        sort($semester ,SORT_NUMERIC );
        $course->addSemester($semester);
    }
    $pdo = null;
    return addGeneralInfos("courses", $result);
}

/**
 * 
 * @param type $stgnr =course(Studiengangnummer/STGNR)
 * @param type $semester =semester
 * @return type Array über alle Vorlesungen eines Studienganges in einem Semester. Die Einträge sind nach Wochentag und Startzeitpunkt sortiert.
 */
function getSchedule($stgnr, $semester, $id){
    require 'connect_db.php';
    $param_select = array(
        "Stundenplan_WWW.id",
        "Stundenplan_WWW.Bezeichnung label",
        "IF (Stundenplan_WWW.Anzeigen_int=0 , Stundenplan_WWW.InternetName, '') docent",
        "Stundenplan_WWW.VArt type",
        "Stundenplan_WWW.Gruppe 'group'",
        "DATE_FORMAT(Stundenplan_WWW.AnfDatum, '%H:%i') starttime",
        "DATE_FORMAT(Stundenplan_WWW.Enddatum, '%H:%i') endtime",
        "DATE_FORMAT(Stundenplan_WWW.AnfDatum, '%d.%m.%Y') startdate",
        "DATE_FORMAT(Stundenplan_WWW.Enddatum, '%d.%m.%Y') enddate",
        "Stundenplan_WWW.Tag_lang day",
        "Stundenplan_WWW.RaumNr room",
        "Stundenplan_WWW.SplusName splusname");
    $param_where = array("(Studiengaenge.STGNR = :stgnr)","(Stundenplan_WWW.Fachsemester = :semester)");
    $param_orderby=array("Stundenplan_WWW.Tag_Nr", "starttime");
    
    if(!empty($id)){
        array_push($param_where, "Stundenplan_WWW.id IN (".implode(",",$id).")");
    }
    $sql = "SELECT ".implode(' , ', $param_select).
            " FROM Stundenplan_WWW INNER JOIN Studiengaenge ON Studiengaenge.STGNR = Stundenplan_WWW.STGNR "
            . " WHERE ".implode(' AND ', $param_where).
            " ORDER BY ".implode(' , ', $param_orderby);
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':stgnr', $stgnr);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    $result = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
        if($row['starttime']!= null && $row['endtime']!=null){
            $result[] = $row;
        }
    }
    $pdo = null;
    return addGeneralInfos("schedule", $result);
}

function getMergedSchedule($stgnr, $semester, $id) {
    require 'connect_db.php';
 $param_select = array(
        "Stundenplan_WWW.id",
        "Stundenplan_WWW.Bezeichnung label",
        "IF (Stundenplan_WWW.Anzeigen_int=0 , Stundenplan_WWW.InternetName, '') docent",
        "Stundenplan_WWW.VArt type",
        "Stundenplan_WWW.Gruppe 'group'",
        "DATE_FORMAT(Stundenplan_WWW.AnfDatum, '%H:%i') starttime",
        "DATE_FORMAT(Stundenplan_WWW.Enddatum, '%H:%i') endtime",
        "DATE_FORMAT(Stundenplan_WWW.AnfDatum, '%d.%m.%Y') startdate",
        "DATE_FORMAT(Stundenplan_WWW.Enddatum, '%d.%m.%Y') enddate",
        "Stundenplan_WWW.Tag_lang day",
        "Stundenplan_WWW.RaumNr room");
    $param_where = array("(Studiengaenge.STGNR = :stgnr)","(Stundenplan_WWW.Fachsemester = :semester)");
    $param_orderby=array("Stundenplan_WWW.Tag_Nr", "starttime");     
    if(!empty($id)){
        array_push($param_where, "Stundenplan_WWW.id IN (".implode(",",$id).")");
    }
    $sql = "SELECT ".implode(' , ', $param_select).
            " FROM Stundenplan_WWW INNER JOIN Studiengaenge ON Studiengaenge.STGNR = Stundenplan_WWW.STGNR "
            . " WHERE ".implode(' AND ', $param_where).
            " ORDER BY ".implode(' , ', $param_orderby); 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':stgnr', $stgnr);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    $arrMSchedule = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
        if($row['starttime'] != null && $row['endtime'] != null){
            $arrMSchedule[$row['id']] = new MSchedule($row['label'], $row['docent'], $row['type'], $row['group'], $row['starttime'], $row['endtime'], $row['startdate'], $row['enddate'], $row['day'], $row['room']);
        }
    }
    $stmt=null;
    $param_select=null;
    $param_where=null;
    $param_orderby=null;
    
    $param_select = array(
        "s.id",
        "v.Kommentar",
        "v.Ausfallgrund",
        "s.VArt type",
        "s.Gruppe 'group'",
        "DATE_FORMAT(v.Ersatzdatum, '%H:%i') ersatzzeit",
        "DATE_FORMAT(v.Ersatzdatum, '%d.%m.%Y') ersatzdatum",
        "v.Raum",
        "v.Ersatztag");
    $param_where = array("(s.STGNR=:stgnr)","(s.Fachsemester=:semester)","(WEEKOFYEAR(v.Ausfalldatum)=WEEKOFYEAR(NOW()) OR WEEKOFYEAR(v.Ersatzdatum)=WEEKOFYEAR(NOW()))","s.STGNR=v.STGNR");    
    
    $sqlChanges = "SELECT ".implode(" , ", $param_select)
            ." FROM stundenplan_www as s INNER JOIN Verlegungen_WWW as v ON SUBSTRING_INDEX(s.SplusName, '$', '1')=SUBSTRING_INDEX(v.SplusVerlegungsname,'$','1') "
            . "WHERE ".implode(" AND ", $param_where);
    /*    "SELECT s.id, 
        v.Kommentar,         
        v.Ausfallgrund, 
        DATE_FORMAT(v.Ersatzdatum, '%H:%i') ersatzzeit, 
        DATE_FORMAT(v.Ersatzdatum, '%d.%m.%Y') ersatzdatum, 
        v.Raum,
        v.Ersatztag
        FROM stundenplan_www as s, verlegungen_www as v 
        WHERE 
        (s.STGNR=:stgnr) AND 
        (s.Fachsemester=:semester) AND
        (WEEKOFYEAR(v.Ausfalldatum)=WEEKOFYEAR(NOW()) OR WEEKOFYEAR(v.Ersatzdatum)=WEEKOFYEAR(NOW()))AND 
        s.STGNR=v.STGNR AND 
        SUBSTRING_INDEX(s.SplusName, '$',  1)=SUBSTRING_INDEX(v.SplusVerlegungsname,'$','1')"; */
    $stmt = $pdo->prepare($sqlChanges);
    $stmt->bindParam(':stgnr', $stgnr);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    $result = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
        if(array_key_exists($row['id'], $arrMSchedule)){
            $arrMSchedule[$row['id']]->setChanges(new MChanges($row['Kommentar'], $row['Ausfallgrund'], $row['ersatzzeit'], $row['ersatzdatum'], $row['Raum'], $row['Ersatztag']));            
        }
    }
    foreach ($arrMSchedule as $mSchedule) {
        $result[] = $mSchedule;
    }
    $pdo = null;

    return addGeneralInfos("mschedule", $result);    
}

/**
 * 
 * @param type $stgnr =course(Studiengangnummer/STGNR)
 * @param type $semester =semester
 * @return type Array über alle Änderungen eines Studienganges in einem Semester. Die Einträge sind nach Ausfalltag und Ausfallzeitpunkt sortiert.
 */
function getChanges($stgnr, $semester, $id) {
    require 'connect_db.php';
    
     $param_select = array(
         "v.id",
        "v.Bezeichnung bezeichnung",
        "IF (v.Anzeigen_int=0, v.InternetName, '') dozent",
        "v.Tag_lang ausfalltag",
        "v.Kommentar kommentar",
        "v.Gruppe gruppe",
        "DATE_FORMAT(v.Ausfalldatum, '%H:%i') ausfallzeit",
        "DATE_FORMAT(v.Ausfalldatum, '%d.%m.%Y') ausfalldatum",
        "v.RaumNr ausfallraum",
        "v.Ausfallgrund ausfallgrund",
        "DATE_FORMAT(v.Ersatzdatum, '%H:%i') ersatzzeit",
        "DATE_FORMAT(v.Ersatzdatum, '%d.%m.%Y') ersatzdatum",
         "v.Raum ersatzraum",
         "v.Ersatztag ersatztag",
         "v.SplusVerlegungsname splusname");
    $param_where = array("(v.STGNR = :stgnr)","(v.Fachsemester = :semester)","(DATE(v.Ausfalldatum)>=NOW() OR DATE(v.Ersatzdatum)>=NOW())");
    $param_orderby=array("ausfalldatum", "ausfallzeit");
    if(!empty($id)){
        array_push($param_where, "s.id IN (".implode(",",$id).")");
    }
    
    $sql = "SELECT ".implode(", ", $param_select)
            ." FROM stundenplan_www as s INNER JOIN Verlegungen_WWW as v ON SUBSTRING_INDEX(s.SplusName, '$', '1')=SUBSTRING_INDEX(v.SplusVerlegungsname,'$','1')"
            . " WHERE ".implode(" AND ", $param_where)
            ." ORDER BY ".implode(", ",$param_orderby);
/*    $sql1 = "SELECT "
            . "IF (Verlegungen_WWW.Anzeigen_int=0, Verlegungen_WWW.InternetName, '') dozent, "
            . "Verlegungen_WWW.Bezeichnung bezeichnung, "
            . "Verlegungen_WWW.Tag_lang ausfalltag, "
            . "Verlegungen_WWW.Kommentar kommentar, "
            . "Verlegungen_WWW.Gruppe gruppe, "
            . "DATE_FORMAT(Verlegungen_WWW.Ausfalldatum, '%H:%i') ausfallzeit, "
            . "DATE_FORMAT(Verlegungen_WWW.Ausfalldatum, '%d.%m.%Y') ausfalldatum, "
            . "Verlegungen_WWW.RaumNr ausfallraum, "
            . "Verlegungen_WWW.Ausfallgrund ausfallgrund, "
            . "DATE_FORMAT(Verlegungen_WWW.Ersatzdatum, '%H:%i') ersatzzeit, "
            . "DATE_FORMAT(Verlegungen_WWW.Ersatzdatum, '%d.%m.%Y') ersatzdatum, "
            . "Verlegungen_WWW.Raum ersatzraum, "
            . "Verlegungen_WWW.Ersatztag ersatztag, "
            . "Verlegungen_WWW.SplusVerlegungsname splusname "
            . "FROM Verlegungen_WWW "
            . "INNER JOIN Studiengaenge "
            . "ON Studiengaenge.STGNR = Verlegungen_WWW.STGNR "
            . "WHERE (Studiengaenge.STGNR = :stgnr) "
            . "AND (Verlegungen_WWW.Fachsemester = :semester) "
            . "AND (DATE(Verlegungen_WWW.Ausfalldatum)>=NOW() OR DATE(Verlegungen_WWW.Ersatzdatum)>=NOW()) "
            . "ORDER BY ausfalldatum, ausfallzeit"; */

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':stgnr', $stgnr);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    $result = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
        if($row['ausfallzeit']!= null){
        $result[] = array(
            "id"=>$row['id'],
            "label"=>$row['bezeichnung'], 
            "docent" => $row['dozent'],             
            "comment"=>$row['kommentar'], 
            "reason" => $row['ausfallgrund'],
            "group" => $row['gruppe'],
            "splusname" => $row['splusname'],
            "original" => array(
                "day" => $row['ausfalltag'], 
                "time" => $row['ausfallzeit'], 
                "date" => $row['ausfalldatum'], 
                "room" => $row['ausfallraum']), 
            "alternative"=> ($row['ersatztag']!=null && $row['ersatzzeit']!= null) ? array(
                "day" => $row['ersatztag'], 
                "time" => $row['ersatzzeit'], 
                "date" => $row['ersatzdatum'], 
                "room" => $row['ersatzraum']): null,            
            );
        }
    }
    // Datenbankverbindung schließen
    $pdo = null;
    return addGeneralInfos("changes", $result);
}

$server->addFunction("getMenu");
$server->addFunction("getSchedule");
$server->addFunction("getMergedSchedule");
$server->addFunction("getCourses");
$server->addFunction("getChanges");

$server->handle();
