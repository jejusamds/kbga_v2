<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$from_name = "test";
$from_email = "test@test.com";

$to_name = "test";
$to_email = "hoon@hd-group.co.kr";

$subject = "hello";
$content = "world~!";

$result = send_mail($from_name, $from_email, $to_name, $to_email, $subject, $content);

var_dump($result);