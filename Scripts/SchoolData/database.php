<?php
  $mysqli = new mysqli('localhost', 'root', 'Tianpei', 'cse530');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>