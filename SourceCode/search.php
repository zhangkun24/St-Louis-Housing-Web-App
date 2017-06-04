<?php
require 'database.php';
session_start();
$_SESSION['user']='wangwei';
//get the search value from home page
$search_value=$_GET['search'];
//get the search option from home page
$search_option=$_GET['option'];
//no search value provided-> go back to home page
if($search_value==''){
    header('Location:index.html');
    exit;
}
//search value is provided
else{
    //if search option is zip code
    if($search_option=='zipcode'){
        $stmt=$mysqli->prepare('select *from house where zipcode=?');
        //error handler
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s',$search_value);
        $stmt->execute();
        $query_results=$stmt->get_result();
        $results=array();
        $stmt->close();
        //pass the result to results array
        $index=0;
        while($row=$query_results->fetch_assoc()){
            $results[$index]=array();
            $results[$index]['zipcode']=$row['zipcode'];
            $results[$index]['zillow_id']=$row['zillow_id'];
            $results[$index]['address']=$row['address'];
            $results[$index]['size']=$row['size'];
            $results[$index]['bedrooms']=$row['bedrooms'];
            $results[$index]['bathrooms']=$row['bathrooms'];
            $results[$index]['city']=$row['city'];
            $results[$index]['state']=$row['state'];
            $results[$index]['value']=$row['value'];
            $results[$index]['zest_value']=$row['zest_value'];
            $index++;
        }
        $_SESSION['results']=$results;
    }
    //default(no search option) is address
    else{
        $stmt=$mysqli->prepare('select *from house');
        //error handler
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $results=array();
        $stmt->execute();
        $query_results=$stmt->get_result();
        $stmt->close();
        $index=0;
        while($row=$query_results->fetch_assoc()){
            $target=$row['address'];
            //find the match address
            if(stripos($target,$search_value)!==false || stripos($search_value,$target)!==false){
             $results[$index]=array();
                $results[$index]['zipcode']=$row['zipcode'];
                $results[$index]['zillow_id']=$row['zillow_id'];
                $results[$index]['address']=$row['address'];
                $results[$index]['size']=$row['size'];
                $results[$index]['bedrooms']=$row['bedrooms'];
                $results[$index]['bathrooms']=$row['bathrooms'];
                $results[$index]['city']=$row['city'];
                $results[$index]['state']=$row['state'];
                $results[$index]['value']=$row['value'];
                $results[$index]['zest_value']=$row['zest_value'];
                $index++;
            }
        }
        $_SESSION['results']=$results;
    }
    //success query and redirect to house result display page
    /**
    if(count($_SESSION['results'])==0){
        $_SESSION['results']='error';
    }
     **/
    header('Location:house_list_display.php');
}
?>
