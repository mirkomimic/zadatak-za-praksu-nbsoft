<?php

namespace App\Controllers;

use App\Database\DB;
use App\Models\Product;

class ProductController
{

  public static function store($name, $price)
  {
    $conn = DB::connectDB();
    $query = "INSERT INTO products VALUES (null, '$name', $price)";
    return $conn->query($query);
  }

  public static function getProducts()
  {
    $conn = DB::connectDB();
    $productIds = [];
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $productIds[] = $row['id'];
    }
    return $productIds;
  }

  public static function getProductValue($productId)
  {
    $conn = DB::connectDB();
    $productValue = 0;
    $query = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $productValue += $row['price'];
    }
    return $productValue;
  }

  // public static function getProductsByOrderId($order)
  // {
  //   $itemsResponse = [];

  //   $orderItems = OrderController::getOrderProducts($order->id);

  //   foreach ($orderItems as $orderItem) {
  //     $items = self::getProduct($orderItem->productId);
  //     $itemsResponse[] = $items;
  //   }

  //   return $itemsResponse;
  // }

  public static function getProduct($productId)
  {
    $conn = DB::connectDB();
    $query = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $product = new Product($row['id'], $row['name'], $row['price']);

    return $product;
  }
}
