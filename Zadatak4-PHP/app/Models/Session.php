<?php

namespace App\Models;

class Session
{

  public static function checkToken($conn, $token)
  {
    $query = "SELECT tblsessions.userId, tblsessions.accessexpiry
          FROM users, tblsessions
          WHERE tblsessions.userId = users.id
          AND tblsessions.accesstoken ='$token'";
    return $conn->query($query);
  }

  public static function getUserByEmail($conn, $email)
  {
    $query = "SELECT * FROM users WHERE email='$email'";
    return $conn->query($query);
  }

  public static function login($conn, $userId, $token, $accessexpiry)
  {
    $query = "INSERT INTO tblsessions (userId, accesstoken, accessexpiry) VALUES ($userId, '$token', DATE_ADD(NOW(), INTERVAL $accessexpiry SECOND))";
    return $conn->query($query);
  }
}
