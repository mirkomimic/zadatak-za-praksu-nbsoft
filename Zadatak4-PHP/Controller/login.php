<?php

require_once "../db.php";
require_once "../Model/Response.php";
require_once "../Model/Session.php";

$conn = DB::connectDB();


if ($_SERVER['REQUEST_METHOD'] !== "POST")
{
  $response = new Response();
  $response->set_httpStatusCode(405);
  $response->set_success(false);
  $response->set_message("Only POST method is allowed");
  $response->send();
  exit();
}

sleep(1);

if ($_SERVER['CONTENT_TYPE'] !== 'application/json')
{
  $response = new Response();
  $response->set_httpStatusCode(400);
  $response->set_success(false);
  $response->set_message("Content Type header not set to JSON");
  $response->send();
  exit;
}


$rawPostData = file_get_contents('php://input');

if (!$jsonData = json_decode($rawPostData))
{
  $response = new Response();
  $response->set_httpStatusCode(400);
  $response->set_success(false);
  $response->set_message("Request body is not valid JSON");
  $response->send();
  exit;
}

if (!isset($jsonData->email))
{
  $response = new Response();
  $response->set_httpStatusCode(400);
  $response->set_success(false);
  $response->set_message("Missing email");
  $response->send();
  exit;
}
$email = $jsonData->email;

$result = Session::getUserByEmail($conn, $email);
$rowCount = mysqli_num_rows($result);

if ($rowCount == 0)
{
  $response = new Response();
  $response->set_httpStatusCode(409);
  $response->set_success(false);
  $response->set_message("Email is not correct");
  $response->send();
  exit;
}

$row = $result->fetch_assoc();
$id = $row['id'];

$accesstoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)));
$access_expiry = 2800;

Session::login($conn, $id, $accesstoken, $access_expiry);

$returnData = [];
$returnData['accesstoken'] = $accesstoken;
$response = new Response();
$response->set_httpStatusCode(201); // za kreiranje 201
$response->set_success(true);
$response->set_message("User logged in, access token created");
$response->set_data($returnData);
$response->send();
exit;
