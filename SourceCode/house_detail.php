<?php
// this script is trying to collect all data in different tables for a house and the district the house locates in
// I am trying to use an array, "infoArray" to store all the data from different tables.
// this script servers house_list_function.js

//session_start();
header("Content-Type: application/json");
require "database.php";

//the biggest info array
$infoArray = array();

// get the posted value
$zillow_id = htmlentities($_POST['zillow_id']);
$zipcode  = htmlentities($_POST['zipcode']);


// connect to database to get house info
$house_connect = $mysqli->prepare("select * from house where zillow_id = ?");
if(!$house_connect){
    $get_house_message = sprintf("Query prepare failed: %s\n", $mysqli->error);
    echo json_encode(array(
       "success" => false,
       "message" => $get_house_message
    ));
    exit;
}

$house_connect->bind_param("s", $zillow_id);
$house_connect->execute();
$house_result = $house_connect->get_result();
$house_array = array();
$j = 0;

while($row = $house_result->fetch_assoc()){
    $house_array[$j] = $row;
    $j++;
}
$house_connect->close();



// connect to the database to get district_has_race info
$district_has_race_connect = $mysqli->prepare("select * from districtHasRace where zipcode = ?");
if(!$district_has_race_connect){
    $get_pop_message = sprintf("Query prepare failed: %s\n", $mysqli->error);
    echo json_encode(array(
       "success" => false,
       "message" => "connect error"
    ));
    exit;
}

$district_has_race_connect->bind_param('s', $zipcode);
$district_has_race_connect->execute();
$district_has_race_result = $district_has_race_connect->get_result();
$district_has_race_array = array();
$i = 0;

while($row = $district_has_race_result->fetch_assoc()){
    $district_has_race_array[$i] = $row;
    $i++;
}
$district_has_race_connect->close();

// connect to the database to get the demographic info from district table
$stmt=$mysqli->prepare('select *from district_A where zipcode=?');
$stmt->bind_param('s',$zipcode);
$stmt->execute();
$query_results=$stmt->get_result();
$district_array=array();
$index=0;
while($row=$query_results->fetch_assoc()){
    $district_array[$index++]=$row;
}
$stmt->close();

// connect to the database to get offender info
//$offender_connect = $mysqli->prepare("select * from off")

// connect to the database to get school info
$school_connect = $mysqli->prepare("select * from schools");
//$school_connect->bind_param('s', $zipcode);
$school_connect->execute();
$school_connect_result = $school_connect->get_result();
$school_array =array();
$school_index = 0;
while($row = $school_connect_result->fetch_assoc()){
    $school_array[$school_index] = $row;
    $school_index++;
}
$school_connect->close();


// connect to the database to get restaurant info
$restaurant_connect = $mysqli->prepare("select name,latitude,longitude,style from restaurant");
if(!$restaurant_connect){
    $get_error_message = sprintf("Query prepare failed: %s\n", $mysqli->error);
    echo json_encode(array(
       "success" => false,
       "message" => "connect error"
    ));
    exit;
}
$restaurant_array = array();
$restaurant_connect->execute();
$restaurant_connect_result = $restaurant_connect->get_result();
$restaurant_index = 0;
while($row = $restaurant_connect_result->fetch_assoc()){
    $restaurant_array[$restaurant_index]= $row;
    $restaurant_array[$restaurant_index]['name'] = json_encode($restaurant_array[$restaurant_index]['name']);
    $restaurant_index++;
}/**/
$restaurant_connect->close();

//offender
$stmt=$mysqli->prepare("select lat,lng from offender");
$stmt->execute();
$offender_array=array();
$index=0;
$query_results=$stmt->get_result();
while($row=$query_results->fetch_assoc()){
    $offender_array[$index++]=$row;
}
$stmt->close();


//form the info array that used to to pass all data 
$infoArray[0] = $house_array;    // house info be the first element in the info array
$infoArray[1] = $district_has_race_array;  //race info
$infoArray[2] = $district_array; //demographic info
$infoArray[3] = $school_array; // school info
$infoArray[4] = $restaurant_array;
$infoArray[5] = $offender_array;


echo json_encode(array(
    "success" => true,
    "message" => $zipcode,
    "infoArray" => $infoArray
));

?>
