<?php

namespace App\Models;

use App\Database\DB;
use Exception;

class Order
{
  public $id;
  public $userId;
  public $value;
  public $dateCreate;
  public $dateEdit;

  public function __construct($id, $userId, $value, $dateCreate, $dateEdit)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->value = $value;
    $this->dateCreate = $dateCreate;
    $this->dateEdit = $dateEdit;
  }

  public static function getAllOrders()
  {
    $conn = DB::connectDB();
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);

    $orders = [];
    while ($row = $result->fetch_assoc()) {

      $order = new Order($row['id'], $row['userId'], $row['value'], $row['dateCreate'], $row['dateEdit']);

      $orders[] = $order;
    }

    return $orders;
  }
  // public static function getAllOrders($conn)
  // {
  //   $query = "SELECT * FROM orders";
  //   $result = $conn->query($query);

  //   $orders = [];
  //   while ($row = $result->fetch_assoc()) {

  //     $order = new Order($row['id'], $row['userId'], $row['value'], $row['dateCreate'], $row['dateEdit']);

  //     $orders[] = $order;
  //   }

  //   return $orders;
  // }

  public static function getOrderProducts($conn, $orderId)
  {
    $query = 'SELECT * FROM order_item WHERE orderId=' . $orderId;
    $result = $conn->query($query);
    $orderItems = [];
    while ($row = $result->fetch_assoc()) {
      $orderItem = new OrderItem($row['id'], $row['orderId'], $row['productId'], $row['value']);
      $orderItems[] = $orderItem;
    }
    return $orderItems;
  }

  public static function getProduct($conn, $productId)
  {
    $query = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $product = new Product($row['id'], $row['name'], $row['price']);

    return $product;
  }

  public static function updateOrderTotalValue($conn, $orderId, $value)
  {
    $query = "UPDATE orders SET value=$value WHERE id=$orderId";
    return $conn->query($query);
  }

  public static function createOrder($conn, $jsonData, $userid)
  {
    $conn->begin_transaction();

    try {
      $query = "INSERT INTO orders (userId, value, dateCreate, dateEdit) VALUES ($userid, 0, now(), now())";
      $conn->query($query);
      $orderId = $conn->insert_id;

      $totalValue = 0;

      $productsIds = Product::getProducts($conn);
      foreach ($jsonData as $id) {
        if (in_array($id->id, $productsIds)) {
          $orderedItemValue = Product::getProductValue($conn, $id->id);
          $totalValue += $orderedItemValue;

          OrderItem::createOrderItem($conn, $id->id, $orderId, $orderedItemValue);
        }
      }
      Order::updateOrderTotalValue($conn, $orderId, $totalValue);

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

  public static function getOrderById($conn, $id)
  {
    $query = "SELECT * FROM orders WHERE id=$id";
    $result = $conn->query($query);
    $row = $result->fetch_object();

    $order = new Order($row->id, $row->userId, $row->value, $row->dateCreate, $row->dateEdit);

    return $order;
  }
}
