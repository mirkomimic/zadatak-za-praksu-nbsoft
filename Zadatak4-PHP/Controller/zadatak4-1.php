<?php

use App\Model\Order;
use App\Model\Response;
use App\Model\Paginator;
use App\Resources\OrderResource;
use App\Database\DB;

require_once '../vendor/autoload.php';

$conn = DB::connectDB();

if (empty($_GET)) {
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ordersCollection = OrderResource::collection($conn, Order::getAllOrders($conn));

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($ordersCollection);
    $response->send();
  } else {
    $response = new Response();
    $response->set_httpStatusCode(405);
    $response->set_success(false);
    $response->set_message("Request method not allowed");
    $response->send();
    exit();
  }
}
if (isset($_GET['page'])) {
  $page = $_GET['page'];
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $ordersCollection = OrderResource::collection($conn, Order::getAllOrders($conn));

    $ordersCollection = Paginator::paginate($ordersCollection, $page, 5);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($ordersCollection);
    $response->send();
  }
}
