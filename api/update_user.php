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
 
    // new values
    $user->user_status=$_POST['user_status'];
    $user->last_question_id=$_POST['last_question_id'];
    $user->user_score=$_POST['user_score'];
    $user->msisdn=$_POST['msisdn'];
 
    // update the product
    //echo $product->update() ? "true" : "false";
    $user->update();

    // read one question
    $results=$user->readOne();
    
    // output in json format
    echo $results;
}
?>