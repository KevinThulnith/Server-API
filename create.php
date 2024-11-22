<?php
require_once 'header.php';
header('Access-Control-Allow-Method: POST');

require_once 'functions.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "POST") {
  $inputData = json_decode(file_get_contents("php://input"), true);
  if (empty($inputData)) {
    $storeCustomer = storeCustomer($_POST);
  } else {
    $storeCustomer = storeCustomer($inputData);
  }
  echo $storeCustomer;
} else {
  header("HTTP/1.0 405 Method Not Allowed");
  retsponseJson(405, $requestMethod . ' Method Not Allowed');
}
