<?php

use App\Models\Order;
use App\Models\Response;
use App\Utilities\Paginator;
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
    // try method chaining
    // https://stackoverflow.com/questions/3724112/php-method-chaining-or-fluent-interface
    $ordersCollection = OrderResource::collection($conn, Order::getAllOrders($conn));

    $ordersCollection = Paginator::paginate($ordersCollection, $page, 5);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($ordersCollection);
    $response->send();
  }
}
