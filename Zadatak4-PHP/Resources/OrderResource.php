<?php


class OrderResource
{
  public $order = [];
  public function __construct($conn, $order)
  {
    return $this->order = [
      'id' => $order->id,
      'userId' => $order->userId,
      'value' => $order->value,
      'dateCreate' => $order->dateCreate,
      'dateEdit' => $order->dateEdit,
      'items' => Product::getProductsByOrderId($conn, $order)
    ];
  }
}
