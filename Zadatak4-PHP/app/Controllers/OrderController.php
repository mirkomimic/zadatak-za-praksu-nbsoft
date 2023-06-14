<?php

namespace App\Controllers;

use Exception;
use App\Database\DB;
use App\Models\Order;
use App\Models\Product;
use App\Http\Response;
use App\Models\OrderItem;
use App\Resources\OrderResource;
use App\Utilities\Paginator;
use App\Utilities\Query;

class OrderController
{

  public static function index(int $page = 1)
  {
    $orders = Query::select()->table('orders')->get();
    $orders = OrderResource::collection($orders);
    $orders = Paginator::paginate($orders, $page, 5);
    return $orders;
  }

  public static function products($orderId)
  {
    $products = Query::select("products.*")
      ->table("products")
      ->join("order_item", "products.id", "=", "order_item.productId")
      ->where("order_item.orderId", "==", $orderId)
      ->get();

    return $products;
  }

  // public static function getOrderProducts($orderId)
  // {
  //   $orderItems = Query::select()->table("order_item")->where("orderId", "=", $orderId)->get();
  //   return $orderItems;
  // }
  // public static function getOrderProducts($orderId)
  // {
  //   $conn = DB::connectDB();
  //   $query = "SELECT * FROM order_item WHERE orderId=" . $orderId;
  //   $result = $conn->query($query);
  //   $orderItems = [];
  //   while ($row = $result->fetch_assoc()) {
  //     $orderItem = new OrderItem($row['id'], $row['orderId'], $row['productId'], $row['value']);
  //     $orderItems[] = $orderItem;
  //   }
  //   var_dump($orderItems);
  //   return $orderItems;
  // }

  public static function updateOrderTotalValue($orderId, $value)
  {
    $conn = DB::connectDB();
    $query = "UPDATE orders SET value=$value WHERE id=$orderId";
    return $conn->query($query);
  }

  public static function createOrder($jsonData, $userid)
  {
    $conn = DB::connectDB();
    $conn->begin_transaction();

    try {
      $query = "INSERT INTO orders (userId, value, dateCreate, dateEdit) VALUES ($userid, 0, now(), now())";
      $conn->query($query);
      $orderId = $conn->insert_id;

      $totalValue = 0;

      $productsIds = ProductController::getProducts($conn);
      foreach ($jsonData as $id) {
        if (in_array($id->id, $productsIds)) {
          $orderedItemValue = ProductController::getProductValue($conn, $id->id);
          $totalValue += $orderedItemValue;

          OrderItemController::createOrderItem($conn, $id->id, $orderId, $orderedItemValue);
        }
      }
      self::updateOrderTotalValue($conn, $orderId, $totalValue);

      $conn->commit();

      return $orderId;
    } catch (Exception $ex) {
      $conn->rollback();

      $response = new Response();
      $response->set_httpStatusCode(500);
      $response->set_success(false);
      $response->set_message("Error creating order!" . " " . $ex->getMessage());
      $response->send();
      exit;
    }
  }

  public static function getOrderById($id)
  {
    $conn = DB::connectDB();
    $query = "SELECT * FROM orders WHERE id=$id";
    $result = $conn->query($query);
    $row = $result->fetch_object();

    $order = new Order($row->id, $row->userId, $row->value, $row->dateCreate, $row->dateEdit);

    return $order;
  }
}
