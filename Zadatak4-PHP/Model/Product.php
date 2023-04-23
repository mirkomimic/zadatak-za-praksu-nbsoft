<?php

class Product
{

  public $id;
  public $name;
  public $price;

  public function __construct($id, $name, $price)
  {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
  }

  public static function store($conn, $name, $price)
  {
    $query = "INSERT INTO products VALUES (null, '$name', $price)";
    return $conn->query($query);
  }

  public static function getProducts($conn)
  {
    $productIds = [];
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc())
    {
      $productIds[] = $row['id'];
    }
    return $productIds;
  }

  public static function getProductValue($conn, $productId)
  {
    $productValue = 0;
    $query = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc())
    {
      $productValue += $row['price'];
    }
    return $productValue;
  }

  public static function getProductsByOrderId($conn, $order)
  {
    $itemsResponse = [];

    $orderItems = Order::getOrderProducts($conn, $order->id);

    foreach ($orderItems as $orderItem)
    {
      $items = Order::getProduct($conn, $orderItem->productId);
      $itemsResponse[] = $items;
    }

    return $itemsResponse;
  }
}
