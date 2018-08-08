<?php
$g_base_url = "http://localhost/sms_based_quiz_demo";
$g_environment = "PRODUCTION";
if (strtoupper($g_environment) == "DEVELOPMENT") {
    // show error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
// set your default time-zone
date_default_timezone_set('Asia/Dhaka');
?>