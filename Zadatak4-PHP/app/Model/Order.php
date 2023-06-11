<?php

namespace App\Model;

use Exception;

class Order
{
  const TABLE = 'orders';
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


  public static function getAllOrders($conn)
  {
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);


    $orders = [];
    while ($row = $result->fetch_assoc()) {

      $order = new Order($row['id'], $row['userId'], $row['value'], $row['dateCreate'], $row['dateEdit']);

      $orders[] = $order;
    }

    return $orders;
  }

  public static function paginate($conn, int $page, $perPage): array
  {
    $query = "SELECT count(id) as numOfOrders FROM orders";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $ordersCount = intval($row['numOfOrders']);
    $numOfPages = ceil($ordersCount / $perPage);

    if ($numOfPages == 0) {
      $numOfPages = 1;
    }

    $offset = ($page == 1 ? 0 : $perPage * ($page - 1));
    $query = "SELECT * FROM orders LIMIT $perPage offset $offset";
    // $query = "SELECT * FROM orders";
    $result2 = $conn->query($query);
    $rowCount = $result2->num_rows;

    $orders = [];
    while ($row = $result2->fetch_assoc()) {

      $order = new Order($row['id'], $row['userId'], $row['value'], $row['dateCreate'], $row['dateEdit']);

      $orders[] = $order;
    }

    $data = [];
    $data['current_page'] = $page;
    $data['rows_returned'] = $rowCount;
    $data['total_rows'] = $ordersCount;
    $data['total_pages'] = $numOfPages;
    $data['has_next_page'] = ($page < $numOfPages) ? true : false;
    $data['has_previous_page'] = ($page > 1) ? true : false;
    $data['orders'] = $orders;

    if ($data['has_next_page']) {
      $page2 = $page + 1;
      $data['links']['next_page'] = 'http://localhost/MirkoXAMPP/zadatak-za-praksu-NBSoft/Zadatak4-PHP/orders?page=' . $page2;
    }
    if ($data['has_previous_page']) {
      $page2 = $page - 1;
      $data['links']['prev_page'] = 'http://localhost/MirkoXAMPP/zadatak-za-praksu-NBSoft/Zadatak4-PHP/orders?page=' . $page2;
    }

    return $data;
  }

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
