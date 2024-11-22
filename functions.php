<?php
error_reporting(0); // !disable error messages

// TODO: connect with DB
$conn = mysqli_connect("localhost", "root", "", "kans");

// TODO: check the connection  
if (mysqli_connect_errno()) {
  echo "faild to connect with database!" . $conn->connect_errno;
  die("retry");
}

// TODO: return response json
function retsponseJson(int $code, String $response)
{
  $data = [
    'status' => $code,
    'message' => $response
  ];
  echo json_encode($data);
}


// TODO:send users list via json
function getCustomerList()
{
  global $conn;
  $query_run = mysqli_query($conn, "SELECT user_id, name, address, dob, is_active FROM user WHERE is_customer = 1;");
  if ($query_run) {
    if (mysqli_num_rows($query_run) > 0) {
      $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
      $data = [
        'status' => 200,
        'message' => 'Customer List, Fetched Successfully',
        'data' => $res,
      ];
      header("HTTP/1.0 200 Customer List Fetched Successfully");
      return json_encode($data);
    } else {
      header("HTTP/1.0 404 No Customer Found");
      retsponseJson(404, "No Customer Found");
    }
  } else {
    header("HTTP/1.0 500 Internal Server Error");
    retsponseJson(500, "Internal Server Error");
  }
}

// TODO: return error message
function error422(string $message)
{
  header("HTTP/1.0 422 Unprocessable Entity");
  retsponseJson(422, $message);
  exit();
}

// TODO: create new customer on Sql DB
function storeCustomer($customerInput)
{
  global $conn;

  $name = mysqli_real_escape_string($conn, $customerInput['name']);
  $email = mysqli_real_escape_string($conn, $customerInput['email']);
  $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

  if (empty(trim($name))) return error422("Enter name to proceed");
  elseif (empty(trim($email))) return error422("Enter email to proceed");
  elseif (empty(trim($phone))) return error422("Enter mobile to proceed");
  else {
    $result = mysqli_query($conn, "INSERT INTO `user` (`name`,  `email`, `mobile`) VALUES ('$name', '$email', '$phone') ");
    if ($result) {
      header("HTTP/1.0 201 Created");
      retsponseJson(201, "Customer Created Successfully");
    } else {
      header("HTTP/1.0 500 Internal Server Error");
      retsponseJson(500, "Internal Server Error");
    }
  }
}

// TODO: return specific customer data via json
function getCustomer($customerParams)
{
  global $conn;

  if ($customerParams['id'] == null) return error422('Enter Valid Id');

  $customerId = mysqli_real_escape_string($conn, $customerParams['id']);
  $query = "SELECT * FROM user WHERE user_id='$customerId' AND is_customer = 1 LIMIT 1";
  $result = mysqli_query($conn, $query);
  if ($result) {
    if (mysqli_num_rows($result) == 1) {
      $res = mysqli_fetch_assoc($result);
      $data = [
        'status' => 200,
        'message' => 'Customer Fetched Successfully',
        'data' => $res
      ];
      header("HTTP/1.0 200 OK");
      return json_encode($data);
    } else {
      header("HTTP/1.0 400 Not found");
      retsponseJson(404, "No Customer Found");
    }
  } else {
    header("HTTP/1.0 500 Internal Server Error");
    retsponseJson(500, "Internal Server Error");
  }
}

// TODO: update customer on Sql DB
function updateCustomer($customerInput, $customerParams)
{
  global $conn;

  if (!isset($customerParams['id']) || $customerParams['id'] == null) return error422('Valide id not detected');

  $customerId = mysqli_real_escape_string($conn, $customerParams['id']);
  $name = mysqli_real_escape_string($conn, $customerInput['name']);
  $email = mysqli_real_escape_string($conn, $customerInput['email']);
  $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

  if (empty(trim($name))) return error422("Enter name to proceed");
  elseif (empty(trim($email))) return error422("Enter email to proceed");
  elseif (empty(trim($phone))) return error422("Enter mobile to proceed");
  else {
    $result = mysqli_query($conn, "UPDATE `user` SET `name` = '$name', `email` = '$email', `mobile` = '$phone' WHERE user_id = '$customerId' AND is_customer = 1");
    if ($result) {
      header("HTTP/1.0 200 Updated");
      retsponseJson(200, "Customer Updated Successfully");
    } else {
      header("HTTP/1.0 500 Internal Server Error");
      retsponseJson(500, "Internal Server Error");
    }
  }
}

// TODO: delete customer on Sql DB
function deleteCustomer($customerParams)
{
  global $conn;

  if (!isset($customerParams['id']) || $customerParams['id'] == null) return error422('Valide id not detected');

  $customerId = mysqli_real_escape_string($conn, $customerParams['id']);

  $result = mysqli_query($conn, "DELETE FROM `user` WHERE user_id = '$customerId' AND is_customer = 1");

  if ($result) {
    // !204 dosen't return json code to client
    header("HTTP/1.0 200 DELETED");
    retsponseJson(200, "Customer Deleted Successfully");
  } else {
    header("HTTP/1.0 404 Not found");
    retsponseJson(404, "No Customer Found");
  }
}
