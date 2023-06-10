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

  public static function collection($conn, $orders)
  {
    $array['orders'] = [];

    if (isset($orders['orders']))
    {
      foreach ($orders['orders'] as $order)
      {
        $array['orders'][] = [
          'id' => $order->id,
          'userId' => $order->userId,
          'value' => $order->value,
          'dateCreate' => $order->dateCreate,
          'dateEdit' => $order->dateEdit,
          'items' => Product::getProductsByOrderId($conn, $order)
        ];
      }
      $array['meta']['current_page'] = $orders['current_page'];
      $array['meta']['rows_returned'] = $orders['rows_returned'];
      $array['meta']['total_rows'] = $orders['total_rows'];
      $array['meta']['total_pages'] = $orders['total_pages'];
      $array['meta']['has_next_page'] = $orders['has_next_page'];
      $array['meta']['has_previous_page'] = $orders['has_previous_page'];

      $array['links']['next_page'] = $orders['links']['next_page'];
      $array['links']['prev_page'] = $orders['links']['prev_page'];
    }
    else
    {

      foreach ($orders as $order)
      {
        $array['orders'][] = [
          'id' => $order->id,
          'userId' => $order->userId,
          'value' => $order->value,
          'dateCreate' => $order->dateCreate,
          'dateEdit' => $order->dateEdit,
          'items' => Product::getProductsByOrderId($conn, $order)
        ];
      }
    }

    return $array;
  }
}
