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
}
