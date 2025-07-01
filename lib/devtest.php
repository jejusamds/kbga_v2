<?php

exit;
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$from_name = "kbga";
$from_email = "kblsm917@gmail.com";

$to_name = "devtest";
$to_email = "jejusamds@gmail.com";

$subject = "hello";
$content = "this is the smtp mail test..";

$result = send_mail($from_name, $from_email, $to_name, $to_email, $subject, $content);

var_dump($result);