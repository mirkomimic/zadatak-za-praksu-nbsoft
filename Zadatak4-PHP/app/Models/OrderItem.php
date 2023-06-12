<?php

namespace App\Models;

class OrderItem
{
  private $id;
  private $orderId;
  private $value;
  public $productId;

  public function __construct($id, $orderId, $productId, $value)
  {
    $this->id = $id;
    $this->orderId = $orderId;
    $this->productId = $productId;
    $this->value = $value;
  }

  public static function createOrderItem($conn, $productId, $orderId, $value)
  {
    $query = "INSERT INTO order_item VALUES (null, $orderId, $value, $productId)";
    return $conn->query($query);
  }
}
