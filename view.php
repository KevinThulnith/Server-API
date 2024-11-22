<?php
require_once 'header.php';
header('Access-Control-Allow-Method: GET');

require_once 'functions.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") echo  getCustomerList();
else {
  header("HTTP/1.0 405 Method Not Allowed");
  retsponseJson(405, $requestMethod . ' Method Not Allowed');
}
