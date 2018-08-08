<?php
// include configuration file
include 'config/core.php';
// include utility file
include 'libs/php/functions.php';
 
// include the head template
include_once "layout_head.php";

$msg = strtoupper(urldecode($_GET['text']));
$msisdn = urldecode($_GET['mdn']);

$url_list = array(
   'read_one_user' => $g_base_url.'/api/read_one_user.php',
   'read_one_question' => $g_base_url.'/api/read_one_question.php',
   'read_one_random_question' => $g_base_url.'/api/read_one_random_question.php',
   'create_user' => $g_base_url.'/api/create_user.php',
   'update_user' => $g_base_url.'/api/update_user.php',
   'create_activity_log' => $g_base_url.'/api/create_activity_log.php'
);
$reply = "";
$user=null;
$question=null;
$result_flag = '0';
$message_option = "";

// log activity
$result = json_decode(api_call_post($url_list["create_activity_log"],array('msisdn' => $msisdn,'text_msg' => $msg)));

$result = json_decode(api_call_post($url_list["read_one_user"],array('msisdn' => $msisdn)));
$does_user_exists = ($result == 0) ? FALSE : TRUE;
$is_user_registered = ($result[0]->user_status == '0') ? FALSE : TRUE;

if ($does_user_exists) {
    $user = $result[0];
    $result = json_decode(api_call_post($url_list["read_one_question"],array('question_id' => ($user->last_question_id == '0') ? 1 : (int)$user->last_question_id))); 
    $question=$result[0];
}

if(strlen($msg)>0) {
    switch ($msg) {
        case "START QUIZ":
            if(!$does_user_exists){
                $result = json_decode(api_call_post($url_list["create_user"],array('msisdn' => $msisdn)));
                $user = $result[0];
                $result = json_decode(api_call_post($url_list["read_one_question"],array('question_id' => ($user->last_question_id == '0') ? 1 : (int)$user->last_question_id))); 
                $question=$result[0];
                $reply = "Thanks. You are registered.";
            }
            else{
                if(!$is_user_registered){
                    $result = json_decode(api_call_post($url_list["update_user"],array(
                        'user_status' => '1',
                        'last_question_id' => $user->last_question_id,
                        'user_score' => $user->user_score,
                        'msisdn' => $msisdn
                    )));
                    $user = $result[0];
                    $reply = "Thanks. You are registered.";
                }
                else {
                    $reply = "You are already registered.";
                }
            }
            if($question->question_id != $user->last_question_id){
                $result = json_decode(api_call_post($url_list["update_user"],array(
                        'user_status' => $user->user_status,
                        'last_question_id' => $question->question_id,
                        'user_score' => $user->user_score,
                        'msisdn' => $msisdn
                    )));
                $user = $result[0];
            }
            $reply .=' (Points:'.$user->user_score.'). '.$question->full_question.'.';
            break;
        case "STOP QUIZ":
            if ($is_user_registered) {
                $result = json_decode(api_call_post($url_list["update_user"],array(
                        'user_status' => '0',
                        'last_question_id' => $user->last_question_id,
                        'user_score' => $user->user_score,
                        'msisdn' => $msisdn
                    )));
                $user = $result[0];
                $reply = "Thanks. You are unregistered.";
            }
            else {
                $reply = "You have already unregistered.";
            }
            break;
        case "QUIZ A":
        case "QUIZ B":
            //var_dump($question);
            //echo "<br />";
            $message_option = ($msg == "QUIZ A") ? "A" : "B";
            if($does_user_exists && $is_user_registered){
                if($question->answer == $message_option){
                    $user->user_score = (int)$user->user_score + 1;
                    $result_flag = '1';
                    $reply = "Correct.";
                }
                else {
                    $result_flag = '0';
                    $reply = "Incorrect.";
                }
                $result = json_decode(api_call_post($url_list["read_one_random_question"],array('question_id' => $user->last_question_id))); 
                $question=$result[0];
                $result = json_decode(api_call_post($url_list["update_user"],array(
                        'user_status' => $user->user_status,
                        'last_question_id' => $question->question_id,
                        'user_score' => $user->user_score,
                        'msisdn' => $msisdn
                    )));
                $user = $result[0];
                $reply .=' (Points:'.$user->user_score.'). '.$question->full_question.'.';
            }
            else {
                $reply = "You are not registered.";
            }
            break;
        default:
            $reply = "Invalid Keyword.";
    }
}
else {
    $reply = "Invalid Keyword.";
}

//echo $reply;
call_api_using_curl($msisdn,$reply);

// page footer
include_once "layout_foot.php";
?>