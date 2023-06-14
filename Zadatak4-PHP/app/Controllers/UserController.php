<?php

namespace App\Controllers;

use App\Database\DB;

class UserController
{

  public static function store($conn, $firstname, $lastname, $phone, $email)
  {
    $query = "INSERT INTO users VALUES(null, '$firstname', '$lastname', '$phone', '$email', default, default)";
    return $conn->query($query);
  }
}
