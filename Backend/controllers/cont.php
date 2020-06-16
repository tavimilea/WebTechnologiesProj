<?php
include '../views/pag.php';
include '../model/FilterConfigurator.php';
include '../model/FilterDataObj.php';
if(isset($_POST['filter'])){
    $cookie_name=$_POST['filter'];
    setcookie($cookie_name."/".uniqid(),"ok",time()+(86400*30),"/");
    header("Refresh:0");
}

foreach($_COOKIE as $cookie_name=>$cookie_value){
$name=substr($cookie_name, 0, strpos($cookie_name, '/'));
echo "<label>".$name."</label>";
echo "<select value=".$name."></select>";}
$filterOne = new FilterDataObj(true, 2, 1 , "1970", "iyear");
$filterTwo = new FilterDataObj(true, 1, null, "2000", "iyear");
$filterThree = new FilterDataObj(false, 3, null, "2010", "iyear");
$filtersArray = array();
$filtersArray[2] = $filterOne;
$filtersArray[1] = $filterTwo;
$filtersArray[3] = $filterThree;
var_dump(FilterConfigurator::getInstance()::configureFilters($filtersArray)[1]);
?>