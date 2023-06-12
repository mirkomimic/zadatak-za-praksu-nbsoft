<?php

namespace App\Resources;

use App\Models\Product;

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
      'products' => Product::getProductsByOrderId($conn, $order)
    ];
  }

  public static function collection($conn, $orders)
  {
    $array['items'] = [];

    if (isset($orders['orders'])) {
      foreach ($orders['orders'] as $order) {
        $array['items'][] = [
          'id' => $order->id,
          'userId' => $order->userId,
          'value' => $order->value,
          'dateCreate' => $order->dateCreate,
          'dateEdit' => $order->dateEdit,
          'products' => Product::getProductsByOrderId($conn, $order)
        ];
      }
      $array['meta']['current_page'] = $orders['current_page'];
      $array['meta']['rows_returned'] = $orders['rows_returned'];
      $array['meta']['total_rows'] = $orders['total_rows'];
      $array['meta']['total_pages'] = $orders['total_pages'];
      $array['meta']['has_next_page'] = $orders['has_next_page'];
      $array['meta']['has_previous_page'] = $orders['has_previous_page'];

      $array['links']['next_page'] = $orders['links']['next_page'] ?? null;
      $array['links']['prev_page'] = $orders['links']['prev_page'] ?? null;
    } else {

      foreach ($orders as $order) {
        $array['items'][] = [
          'id' => $order->id,
          'userId' => $order->userId,
          'value' => $order->value,
          'dateCreate' => $order->dateCreate,
          'dateEdit' => $order->dateEdit,
          'products' => Product::getProductsByOrderId($conn, $order)
        ];
      }
    }

    return $array;
  }
}
