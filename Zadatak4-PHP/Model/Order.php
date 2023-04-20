<?php

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


  public static function getAllOrders($conn)
  {
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);


    $orders = [];
    while ($row = $result->fetch_assoc())
    {

      $order = new Order($row['id'], $row['userId'], $row['value'], $row['dateCreate'], $row['dateEdit']);

      $orders[] = $order;
    }

    return $orders;
  }

  public static function getOrderProducts($conn, $orderId)
  {
    $query = 'SELECT * FROM order_item WHERE orderId=' . $orderId;
    $result = $conn->query($query);
    $orderItems = [];
    while ($row = $result->fetch_assoc())
    {
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
}
