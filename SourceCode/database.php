<?php
/**
 * Created by PhpStorm.
 * User: cse530 team
 * Date: 16/4/22
 * Time: 下午4:14
 */
// Content of database.php

$mysqli = new mysqli('localhost', 'root', 'cse530', 'cse530');

if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>