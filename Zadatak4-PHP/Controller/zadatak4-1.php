<?php

require_once '../db.php';
require_once '../Model/Order.php';
require_once '../Model/Product.php';
require_once '../Model/Response.php';
require_once '../Model/OrderItem.php';

$conn = DB::connectDB();


$orders = Order::getAllOrders($conn);
if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
  $response = [];
  $itemsResponse = [];
  foreach ($orders as $order)
  {

    $orderItems = Order::getOrderProducts($conn, $order->id);
    foreach ($orderItems as $orderItem)
    {
      $items = Order::getProduct($conn, $orderItem->productId);
      $itemsResponse[] = $items;
    }

    $orderResponse = [
      'id' => $order->id,
      'userId' => $order->userId,
      'value' => $order->value,
      'dateCreate' => $order->dateCreate,
      'dateEdit' => $order->dateEdit,
      'items' => $itemsResponse,
    ];


    $response[] = $orderResponse;
  }
  // header('Content-Type: application/json');
  // echo json_encode($response);

  $returnData['orders'] = $response;

  $response = new Response();
  $response->set_httpStatusCode(200);
  $response->set_success(true);
  $response->set_data($returnData);
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
