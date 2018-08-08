<?php
// if the form was submitted
if($_POST){
 
    // include core configuration
    include_once '../config/core.php';
 
    // include database connection
    include_once '../config/database.php';
 
    // product object
    include_once '../objects/activity_log.php';
 
    // class instance
    $database = new Database();
    $db = $database->getConnection();
    $activity_log = new ActivityLog($db);
 
    // set product property values
    $activity_log->msisdn = $_POST['msisdn'];
    $activity_log->text_msg = $_POST['text_msg'];
 
    // create the product
    echo $activity_log->create() ? "true" : "false";
}
?>