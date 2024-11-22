<?php
require_once 'header.php';
header('Access-Control-Allow-Method: DELETE');

require_once 'functions.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "DELETE") echo deleteCustomer($_GET);
else {
  header("HTTP/1.0 405 Method Not Allowed");
  retsponseJson(405, $requestMethod . ' Method Not Allowed');
}
