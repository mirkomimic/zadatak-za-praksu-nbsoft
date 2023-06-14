<?php

namespace App\Models;

class User
{
  public $id;
  public $firstname;
  public $lastname;
  public $phone;
  public $email;
  public $dateCreate;
  public $dateEdit;

  public function __construct($id, $firstname, $lastname, $phone, $email)
  {
    $this->id = $id;
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->phone = $phone;
    $this->email = $email;
    // $this->dateCreate = $dateCreate;
    // $this->dateEdit = $dateEdit;
  }
}
