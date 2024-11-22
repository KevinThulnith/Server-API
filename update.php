<?php
require_once 'header.php';
header('Access-Control-Allow-Method: PUT');

require_once 'functions.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "PUT") {
  $inputData = json_decode(file_get_contents("php://input"), true);
  echo updateCustomer($inputData, $_GET);
} else {
  header("HTTP/1.0 405 Method Not Allowed");
  retsponseJson(405, $requestMethod . ' Method Not Allowed');
}
