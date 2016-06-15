<?php
const __FILE = "mensa.xml";
const __URL = "https://www.studentenwerk-oberfranken.de/?eID=bwrkSpeiseplanRss&tx_bwrkspeiseplan_pi2%5Bbar%5D=340&tx_bwrkspeiseplan_pi2%5Bdate%5D=";

function readMenuXML(){     
    
    $xml = getXML();    
    if(!file_exists('./mensa.xml')){
        getJSON(null);
        return;
    }
    $weekdays = parseWeekdays($xml->channel[0]->item[0]->children('mensa')[0]->speiseplan[0]->tag);    
    return $weekdays;
}

function getXML(){        
    if(!file_exists(__FILE) || filemtime(__FILE) < strtotime("last Saturday")){
        $date = new DateTime(date("Y-m-d"));    
        if($date->format("N")==6){
            $date->modify('+1 day');
        }
        $strDate = $date->format('Y-m-d');

        $xml=simplexml_load_file(__URL.$strDate);
         file_put_contents(__FILE, $xml->asXML());    
    }else{
        $xml =simplexml_load_file(__FILE);
    }
    return $xml;
}


function parseWeekdays($xmlWeekdays){
    $weekdays=[];
     foreach ($xmlWeekdays as $xmlWeekday){
        $day = $xmlWeekday->attributes()['wochentag']->__toString();
        $date = $xmlWeekday->attributes()['datum']->__toString();
        $categories = parseCategories($xmlWeekday->bereich[0]->kategorie);
        $weekdays[]=new Weekday($day, $date,$categories);
    }    
    return $weekdays;
}

function parseCategories($xmlCategories){
    $categories=[];
    foreach ($xmlCategories as $xmlCategory){
        $cName = $xmlCategory->attributes()['name']->__toString();
        $meals = parseMeal($xmlCategory->gericht);        
        $categories[]=new Category($cName, $meals);   
    }
    return $categories;
}

function parseMeal($xmlMeals){
    $meals=[];
    foreach ($xmlMeals as $xmlMeal){
        $mName = $xmlMeal->attributes()['name']->__toString();
        $tarifs = parsePrice($xmlMeal->gerichtPreise->preis);
        $meals[] = new Meal($mName, $tarifs);
    }
    return $meals;
}

function parsePrice($xmlPrices){
    $prices=[];
    foreach ($xmlPrices as $xmlPrice){
        $group = $xmlPrice->attributes()['gruppe']->__toString();
        $groupId = $xmlPrice->attributes()['gruppenId']->__toString();
        $price = $xmlPrice->__toString();
        $prices[] = new Price($group, $groupId, $price);                    
    }
    return $prices;
} 


class Weekday{
    var $day;
    var $date;
    var $categories;
    
    public function __construct($day,$date, $categories) {
        $this->day = $day;
        $this->date = $date;
        $this->categories = $categories;
    }
}

class Category{
    var $name;
    var $meals;
    
    public function __construct($name, $meals) {
        $this->name =$name;
        $this->meals=$meals;
    }
}

class Meal{    
    var $name;
    var $prices;       
    public function __construct($name, $prices) {
        $this->name = $name;
       $this->prices = $prices;
    }
}

class Price {
    var $group;
    var $groupId;
    var $price;
    
    public function __construct($group,$groupId,$price) {
        $this->group=$group;
        $this->groupId=$groupId;
        $this->price =$this->checkPriceFormat($price);
//        $this->price =$price;
    }
    
    private function checkPriceFormat($strPrice) {         
        $arrPrice = explode(".", $strPrice);
        if($arrPrice[1] == null){
            $result = $result=$strPrice.",00";
        }else if(strlen($arrPrice[1])==1){
                $result=$arrPrice[0].",".$arrPrice[1]."0";
        }           
        return $result;
    }
}