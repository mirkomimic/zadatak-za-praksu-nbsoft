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
}
