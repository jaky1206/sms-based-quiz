<?php
// if the form was submitted
if($_POST){
 
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
 
    // set product property values
    $user->msisdn = $_POST['msisdn'];
 
    // create the product
    //echo $user->create() ? "true" : "false";
    $user->create();

    // read one question
    $results=$user->readOne();
    
    // output in json format
    echo $results;
}
?>