<?php
require_once "../db.php";
require_once "../Model/Product.php";
require_once "../Model/Response.php";
require_once "../Model/User.php";

$conn = DB::connectDB();


if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1)
{
  $response = new Response();
  $response->set_httpStatusCode(401); // za autorizaciju
  $response->set_success(false);
  $response->set_message("Authorization token cannot be blank or must be set");
  $response->send();
  exit;
}
$accesstoken = $_SERVER['HTTP_AUTHORIZATION'];
// var_dump($accesstoken);

$query = "SELECT tblsessions.userId, tblsessions.accessexpiry
          FROM users, tblsessions
          WHERE tblsessions.userId = users.id
          AND tblsessions.accesstoken ='$accesstoken'";
$result = $conn->query($query);
// var_dump($result);
$rowCount = mysqli_num_rows($result);
if ($rowCount == 0)
{
  $response = new Response();
  $response->set_httpStatusCode(401);
  $response->set_success(false);
  $response->set_message("Access token not valid");
  $response->send();
  exit;
}
$row = $result->fetch_assoc();
$userid = $row['userId'];
$accessexpiry = $row['accessexpiry'];

if (strtotime($accessexpiry) < time())
{
  $response = new Response();
  $response->set_httpStatusCode(401);
  $response->set_success(false);
  $response->set_message("Access token expired");
  $response->send();
  exit;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  // create product
  if (isset($_GET['product']))
  {

    if ($_SERVER['CONTENT_TYPE'] !== "application/json")
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      $response->set_message("Content type header not set to JSON");
      $response->send();
      exit();
    }


    $rawPostData = file_get_contents('php://input');
    if (!$jsonData = json_decode($rawPostData))
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      $response->set_message("Request body is not valid JSON");
      $response->send();
      exit();
    }
    if (!isset($jsonData->name) || !isset($jsonData->price))
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      if (!isset($jsonData->name))
        $response->set_message("Name field is mandatory and must be provided");
      if (!isset($jsonData->price))
        $response->set_message("Price field is mandatory and must be provided");
      $response->send();
      exit();
    }

    Product::store($conn, $jsonData->name, $jsonData->price);
    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_message("Product Added");
    $response->send();
  }


  // register user
  if (isset($_GET['user']))
  {
    if ($_SERVER['CONTENT_TYPE'] !== "application/json")
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      $response->set_message("Content type header not set to JSON");
      $response->send();
      exit();
    }

    $rawPostData = file_get_contents('php://input');
    if (!$jsonData = json_decode($rawPostData))
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      $response->set_message("Request body is not valid JSON");
      $response->send();
      exit();
    }

    if (!isset($jsonData->firstname) || !isset($jsonData->lastname) || !isset($jsonData->phone) || !isset($jsonData->email))
    {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      if (!isset($jsonData->firstname))
        $response->set_message("Firstname field is mandatory and must be provided");
      if (!isset($jsonData->lastname))
        $response->set_message("Lastname field is mandatory and must be provided");
      if (!isset($jsonData->phone))
        $response->set_message("Phone field is mandatory and must be provided");
      if (!isset($jsonData->email))
        $response->set_message("Email field is mandatory and must be provided");
      $response->send();
      exit();
    }

    User::store($conn, $jsonData->firstname, $jsonData->lastname, $jsonData->phone, $jsonData->email);
    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_message("User registered!");
    $response->send();
  }
}