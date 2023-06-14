<?php

namespace App\Utilities;

use App\Database\Db;
use Exception;
use InvalidArgumentException;

class Query
{
  private static $query;

  public static function select(string $field = "*")
  {
    // https://stackoverflow.com/questions/125268/chaining-static-methods-in-php
    self::$query .= "SELECT $field FROM ";
    return new self;
  }

  public function table(string $table)
  {
    self::$query .= "$table ";
    return new self;
  }

  public function where(string $field, string $operator, $value)
  {
    if (!in_array($operator, ['=', '>', '<', '<=', '>=', 'LIKE'])) {
      throw new InvalidArgumentException("Operator $operator not allowed.");
    }
    self::$query .= "WHERE $field $operator $value ";
    return new self;
  }

  public function join(string $table, string $field1, string $operator, string $field2)
  {
    if (!in_array($operator, ['='])) {
      throw new InvalidArgumentException("Operator $operator not allowed.");
    }
    self::$query .= "JOIN $table ON $field1 $operator $field2 ";
    return new self;
  }

  public function get()
  {
    try {
      $conn = DB::connectDB();
      $result = $conn->query(self::$query);

      $array = [];
      while ($row = $result->fetch_object()) {
        $array[] = $row;
      }

      self::$query = "";
      return $array;
    } catch (Exception $ex) {
      echo $ex->getMessage();
    }
  }
}
