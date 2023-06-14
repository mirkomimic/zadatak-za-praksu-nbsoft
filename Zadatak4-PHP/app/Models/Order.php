<?php

namespace App\Models;

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
}
