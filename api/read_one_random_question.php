<?php
// include core configuration
include_once '../config/core.php';
 
// include database connection
include_once '../config/database.php';
 
// product object
include_once '../objects/question.php';
 
// class instance
$database = new Database();
$db = $database->getConnection();
$question = new Question($db);
 
// read one question
$question->id=$_POST['question_id'];
$results=$question->readOneRandom();
 
// output in json format
echo $results;
?>