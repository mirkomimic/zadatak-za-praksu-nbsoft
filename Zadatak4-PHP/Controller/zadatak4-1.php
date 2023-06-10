<?php

require_once '../db.php';
require_once '../Model/Order.php';
require_once '../Model/Product.php';
require_once '../Model/Response.php';
require_once '../Model/OrderItem.php';
require_once "../Resources/OrderResource.php";


$conn = DB::connectDB();

if (empty($_GET))
{
  if ($_SERVER['REQUEST_METHOD'] === 'GET')
  {
    $ordersCollection = OrderResource::collection($conn, Order::getAllOrders($conn));

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($ordersCollection);
    $response->send();
  }
  else
  {
    $response = new Response();
    $response->set_httpStatusCode(405);
    $response->set_success(false);
    $response->set_message("Request method not allowed");
    $response->send();
    exit();
  }
}
if (isset($_GET['page']))
{
  $page = $_GET['page'];
  // var_dump($page);
  if ($_SERVER['REQUEST_METHOD'] === 'GET')
  {
    $ordersCollection = OrderResource::collection($conn, Order::paginate($conn, $page, 5));

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($ordersCollection);
    $response->send();
  }
}
