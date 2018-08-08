<?php
// include core configuration
include_once '../config/core.php';
 
// include database connection
include_once '../config/database.php';
 
// product object
include_once '../objects/user.php';
 
// class instance
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
 
// read one question
$user->msisdn=$_POST['msisdn'];
$results=$user->readOne();
 
// output in json format
echo $results;
?>