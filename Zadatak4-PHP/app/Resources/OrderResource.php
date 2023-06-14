<?php

namespace App\Resources;

use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Models\Product;

class OrderResource
{
  public $order = [];
  public function __construct($order)
  {
    return $this->order = [
      'id' => $order->id,
      'userId' => $order->userId,
      'value' => $order->value,
      'dateCreate' => $order->dateCreate,
      'dateEdit' => $order->dateEdit,
      'products' => OrderController::products($order->id)
    ];
  }

  public static function collection($orders)
  {
    $array['items'] = [];

    foreach ($orders as $order) {
      $array['items'][] = [
        'id' => $order->id,
        'userId' => $order->userId,
        'value' => $order->value,
        'dateCreate' => $order->dateCreate,
        'dateEdit' => $order->dateEdit,
        'products' => OrderController::products($order->id)
      ];
    }

    return $array;
  }
}
