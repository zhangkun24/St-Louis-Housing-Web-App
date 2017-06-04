<?php
// this script is used to get data for all matched houses. it severs house_list_function.js

session_start();
header("Content-Type: application/json");

/* uncomment the next few lines till to the first return statement to realize actual house list, also remember to
 * change the testArray in the last line to houseArr
 * /
 *
 */

// get house from
$houseArr = $_SESSION["results"];
if(sizeof($houseArr) == 0){
    echo json_encode(array(
    "success" => true,
    "message" => "No available house was found, try again",
    ));
    return;
}

$testArr = array();
$testArr[0] = array('zillow_id' => '2947203',
                 'bedrooms' => '2',
                 'bathrooms' => '3',
                 'value' => '4',
                 'size' => '5',
                 'zest_value'=> '6',
                 'address' => 'address',
                 'zipcode' =>'63101',
                 'city' => 'city',
                 'state' => 'state',
                 'zillow_link' => 'link'
                 );

echo json_encode(array(
    "success" => true,
    "message" => "That is great",
    "houseArray" => $houseArr
));

?>